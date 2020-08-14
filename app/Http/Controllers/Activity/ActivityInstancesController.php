<?php

namespace App\Http\Controllers\Activity;

use AdminServices;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Activity\ActivityInstance;
use App\Http\Controllers\Activity\ActivitySchemasController ;
use App\Http\Controllers\Activity\ActivityController ;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivitySchema;
use App\Models\APIError;
use App\Models\Folder\Folder;
use App\Models\Person\User;
use App\Models\Schema\Schema;
use App\Models\Service\Service;

/**
     *  @author Ulrich Bertrand 
     * gestion automatique des activités dans tous les services
     */

class ActivityInstancesController extends Controller
{

    public function index(Request $req)
    {
        $activity_instance = ActivityInstance::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($activity_instance); 
    }

    public function find($id)
    {
        if(!$activityInstance = ActivityInstance::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_INSTANCE_NOT_FOUND");
            $apiError->setMessage("L'instance d'activite d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($activityInstance);
    }
    /**
     * return the id of the folder where track_id correspond
     */
    public function getFolderByTrackId($track_id){
        if(!$folder=Folder::whereTrackId($track_id)->first()) {
            $apiError = new APIError;
            $apiError->setStatus("401");
            $apiError->setCode("FOLDER_NOT_FOUND");
            $apiError->setMessage("Le dossier de track_id $track_id n'existe pas");
            return response()->json($apiError, 401);
        }
        return $folder;
    }

    public function getFolderById($id){
        $folder =  Folder::find($id);
        return $folder;
    }
    /**
     * A UTILISER POUR DEMARRER LE PROCESSUS DE TRAITEMENT D'UN DOSSIER DANS L'APPLICATION
     * DE FACON AUTOMATIQUE LORSQUON LE SOUMET POUR SUIVRE UN SCHEMA DONNE DE TRAITEMENT
     * @author Ulrich Bertrand
     * par defaut le traitement de l'activité est assigné à chaque admin du service correspondant
     */
    public function initialiserInstance($folder_id)
    {
        // On recupere le dossier pour lequel on veut effectuer un premier traitement dans un service precis
        $folder = $this->getFolderById($folder_id); 
        // On recupere le type du dossier correspondant
        $folder_type = $folder->folderType;
        // On recupere le schema du type de dossier
        $schema = $folder_type->schema;
        // On recupere la premiere activite du schema
        $activity_schema = ActivitySchema::whereSchemaId($schema->id)->orderBy('activity_order', 'asc')->first();
        $activity = Activity::find($activity_schema->activity_id);
        // On recupere le service dans lequel la premier activite sera effectue
        $service = $activity->service;

        // On creer l'instance d'activite correspondante avec l'id du dossier, 
        // l'id de la premiere activite a suivre par le dossier, l'id du service
        // et l'id de l'administrateur du service qui se chargera d'executer le premiere activite
        $activity_instance = new ActivityInstance();
        $activity_instance->folder_id = $folder->id;
        $activity_instance->activity_id = $activity->id;
        $activity_instance->service_id = $service->id;
        $activity_instance->user_id = $service->admin_id;
        $activity_instance->save();

        return response()->json($activity_instance) ;
    }
     /**
   * A n'utiliser que dans le fonctionnement interne de l'application
   */
    public function getFolderProgressionPourcentage(Request $req) {
        $data = $req->only(['track_id', 'user_id']);
        $this->validate($data, [
            'track_id' => 'required:exist:folders:track_id',
            'user_id' => 'required:exist:users:id'
        ]);
        $user = User::find($data['user_id']);
        $folder = $this->getFolderByTrackId($data['track_id']);

        if($folder->user_id != $user->id) {
            $apiError = new APIError;
            $apiError->setStatus("401");
            $apiError->setCode("UNAUTHORIZED_ACCESS_TO_FOLDER");
            $apiError->setMessage("L'access a ce dossier est interdit pour cet utilisateur");
            return response()->json($apiError, 401);
        }

        // On recupere le type du dossier correspondant
        $folder_type = $folder->folderType;
        // On recupere le schema du type de dossier
        $schema = $folder_type->schema;
        //recuper l'instance la plus courante en cours de traitement du dossier
        $activity_instance = $folder->activityInstances()->orderBy('created_at','DESC')->first();

        $activity = $activity_instance->activity ;
        $activity_schema = ActivitySchema::whereActivityIdAndSchemaId($activity->id, $schema->id)->first();

        if($activity_instance->status =='FINISH' && $schema->nb_activities == $activity_schema->activity_order){
            $result = [
                'data' => 100,
                'status' => 'FINISH',
                'service' => $activity->service
            ];
            return response()->json($result, 200);
        }
        if($activity_instance->status !='FINISH' && $schema->nb_activities == $activity_schema->activity_order){
            $result = [
                'data' => (($activity_schema->activity_order-1)/$schema->nb_activities)*100,
                'status' => $activity_instance->status,
                'service' => $activity->service
            ];
            return response()->json($result, 200);
        }
        if($schema->nb_activities > $activity_schema->activity_order){
            $result = [
                'data' => (($activity_schema->activity_order-1)/$schema->nb_activities)*100,
                'status' => $activity_instance->status
            ];
            return response()->json($result, 200);
        }
    }

    public function onApproveFolder($current_activity_instance_id) {
        // Chercher l'activite de l'instance d'activite courante
        $current_activity_instance = ActivityInstance::find($current_activity_instance_id);
        $current_activity = $current_activity_instance->activity;
        
        // Determiner le schema de l'activite courrante
        $current_folder = $current_activity_instance->folder;
        $current_folder_type = $current_folder->folderType;
        $current_schema = $current_folder_type->schema;

        // Determiner la table qui contient l'ordre de l'activite courrante dans le schema
        $current_activity_schema = ActivitySchema::whereActivityIdAndSchemaId($current_activity->id, $current_schema->id)->first();
        // Si l'activite courrante est la derniere activite du schema, on archive le dossier et on arrete les transactions
        if($current_schema->nb_activities == $current_activity_schema->activity_order) {
            $current_activity_instance->status = 'FINISH';
            $current_folder->status = 'ARCHIVED';
            $current_activity_instance->update();
            $current_folder->update();
            return response()->json(['data' => true]);
        }

        // Si l'activite courrante n'est pas la derniere activite du schema
        // Creer la prochaine instance d'activite pour la prochaine activite
        $next_activity_schema = ActivitySchema::whereSchemaIdAndActivityOrder($current_schema->id, $current_activity_schema->activity_order + 1)->first();
        // Determiner la prochaine activite
        $next_activity = Activity::find($next_activity_schema->activity_id);
        // On dertermine le service de l'activite suivante
        $next_service = $next_activity->service;

        // Changer le status de l'activite courrante
        $current_activity_instance->status = 'FINISH';

        // Creer l'instance d'activite suivante
        $next_activity_instance = new ActivityInstance();
        $next_activity_instance->folder_id = $current_folder->id;
        $next_activity_instance->service_id = $next_service->id; 
        $next_activity_instance->user_id = $next_service->admin_id;
        $next_activity_instance->activity_id = $next_activity->id;
        // Marquer l'activite precedente comme terminee
        $current_activity_instance->update();
        $next_activity_instance->save();
        return response()->json($next_activity_instance, 200);
    }

    public function onRejectFolder($activity_instance_id) {
        $activity_instance = ActivityInstance::find($activity_instance_id);
        if( $activity_instance->status == 'ARCHIVED')
            return response()->json(['data' => false], 200); 
        $activity_instance->status = 'REJECTED';
        $folder = $activity_instance->folder;
        $folder->status = 'REJECTED';
        $folder->update();
        $activity_instance->update();
        return response()->json(['data' => true], 200);
    }

    /*
     * Get the activity  for this instance
     */
    public function activity(Request $req, $id)
    {
        $activity = ActivityInstance::find($id)->activity;              
        return response()->json($activity);
    }
    /**
    * Get the user  who is work or traited in this instance
    */
    public function user(Request $req, $id)
    {
        //user : function define in the  model ActivityInstance
        $user = ActivityInstance::find($id)->user;
                              
        return response()->json($user);
    }

    /**
     * FUNCTIONS OF THE SUPER ADMIN OF THE CAN SEE All 
     */

     public function numbetFoldersTraited ($superAdmin_id){
        $admin = User::find($superAdmin_id)
                ->where([
                    'users.job' => 'SUPERADMIN',
                    'users.id' => $superAdmin_id
                    ])->first() ;
        if($admin){
           return  Folder::select(DB::raw('count(*) as number'))
                            ->where(['folders.status' => 'ARCHIVED'])
                            ->get() ;
        }
     }

     public function numbetFoldersPending ($superAdmin_id){
        $admin = User::find($superAdmin_id)
                ->where([
                    'users.job' => 'SUPERADMIN',
                    'users.id' => $superAdmin_id
                    ])->first() ;
        if($admin){
           return  Folder::select(DB::raw('count(*) as number'))
                            ->where(['folders.status' => 'PENDING'])
                            ->get() ;
        }
     }
     public function numbetFoldersRejected ($superAdmin_id){
        $admin = User::find($superAdmin_id)
                ->where([
                    'users.job' => 'SUPERADMIN',
                    'users.id' => $superAdmin_id
                    ])->first() ;
        if($admin){
           return  Folder::select(DB::raw('count(*) as number'))
                            ->where(['folders.status' => 'REJECTED'])
                            ->get() ;
        }
     }

     public function numbetFoldersAccepted ($superAdmin_id){
        $admin = User::find($superAdmin_id)
                ->where([
                    'users.job' => 'SUPERADMIN',
                    'users.id' => $superAdmin_id
                    ])->first() ;
        if($admin){
           return  Folder::select(DB::raw('count(*) as number'))
                            ->where(['folders.status' => 'ACCEPTED'])
                            ->get() ;
        }
     }
/************************************************************ */

/**
 * Obtent the number of folders pour les users beheviors
 */


 
}
