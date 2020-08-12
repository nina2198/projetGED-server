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
    /**
     * return the id of the folder where track_id correspond
     */
    public static function getIdFolder($track_id){
        $folder =  Folder::select('folders.*')->where(['folders.track_id' => $track_id])->first() ;
        return $folder->id ;
    }

    /**
     * A UTILISER POUR DEMARRER LE PROCESSUS DE TRAITEMENT D'UN DOSSIER DANS L'APPLICATION
     * DE FACON AUTOMATIQUE LORSQUON LE SOUMET POUR SUIVRE UN SCHEMA DONNE DE TRAITEMENT
     * @author Ulrich Bertrand
     * par defaut le traitement de l'activité est assigné à chaque admin du service correspondant
     */
    public static function initialiserInstance($schema_id, $track_id)
    {
            //Obtend the id of folder in the table 
        $folder_id = ActivityInstancesController::getIdFolder($track_id); 
            //recupere dans la table activity_schema la premiere activité a effectuer.
        $activity_id = ActivitySchema::select('*')
        ->join('schemas', 'activity_schemas.schema_id', '=', 'schemas.id')
        ->where([
            'schemas.id' => +$schema_id,
            'activity_schemas.activity_order' => 1
        ])->first()->activity_id;

        $service_id = Activity::find($activity_id)->service_id ;
        $user_id = Service::select('admin_id')->
                join('admin_services','admin_services.service_id','=', 'services.id')
                ->where(['admin_services.service_id' =>$service_id])->first()->admin_id ; 

        $activity_instance = new ActivityInstance();
        $activity_instance->folder_id = $folder_id ;
        $activity_instance->activity_id = $activity_id ;
        $activity_instance->user_id = $user_id ;
        $activity_instance->service_id = $service_id ;
        $activity_instance->save() ;
        return response()->json($activity_instance) ;
    }

    public function index(Request $req) //list of all the traitments in the system
    {
        $activity_instance = ActivityInstance::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($activity_instance); 
    }

    public function find($id) //find the instance activity by the id
    {
        if(!$activityInstance = ActivityInstance::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_DON'T_EXIST");
            $apiError->setMessage("AUCUNE INSTANCE D'ACTIVITE EXISTENTE POUR CETTE id $id");
            return response()->json($apiError, 404);
        }
        return response()->json($activityInstance);
    }

    public function create(Request $req){}

    /**
     * @return activity_instance that the state status of this @param id take in paramater is
     */
    public static function update($id, $status)
    {
        if(!$activityInstance = ActivityInstance::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_INSTANCES_DON'T_EXIST");
            $apiError->setMessage("L'instance d'activité d'id ". $id ."n'existe pas");
            return response()->json($apiError, 404);
        }
            $activityInstance->status = $status;
            $activityInstance->update();
     return  response()->json($activityInstance) ;
    }

    /**
     * set the status of the curent id with @param id 
     * and if status=='ENDING' create the new instance for the next activity can do 
     */
    public static function getChangeStaus($id, $status){
        if($activityInstance = ActivityInstance::find($id))
        {
            if($status =='ENDING'){ //si au niveau de l'instance actuelle le dossier est validé
                $data = ActivitySchemasController::getActivityOrderAndServiceNumber($id) ;
                if($data){
                    $folder_id = $data->folder_id ;
                    if($data->service_number > $data->activity_order){
                        $schema_id = $data->schema_id ;
                        $activity_id = ActivitySchemasController:: getFutureActivity($schema_id, $data->activity_order);
                        $service = ActivitySchemasController:: getAdminServiceId($activity_id->activity_id) ; 
                        
                        $service_id = $service->service_id ;
                        $user_id = $service->user_id ;

                        $activity_instance = new ActivityInstance();
                        $activity_instance->folder_id = $folder_id;
                        $activity_instance->activity_id = $activity_id->activity_id;
                        $activity_instance->user_id = $user_id ;
                        $activity_instance->service_id = $service_id ;

                        $activity_instance->save();
                        return response()->json($activity_instance) ;
                    }
                    else if($data->service_number = $data->activity_order){
                        //dernier activité traité le dossier doit etre archivé 
                        $folder = Folder::find($folder_id) ;
                        $folder->status = 'ARCHIVED' ;
                        $folder->update() ;
                        return response()->json($folder) ;
                    }
                } 
            }
            else{
                return response()->json(ActivityInstancesController:: update($id, $status));
            }
        }
    }

    /**
     * use this function to set the status of instance activity to set automaticaly creer the 
     * new instance 
     */
    public function edit(Request $req, $id){
        if(!$activityInstance = ActivityInstance::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_INSTANCES_DON'T_EXIST");
            $apiError->setMessage("L'instance d'id ". $id ."n'existe pas");
            return response()->json($apiError, 404);
        }
        $data = $req->only('status');
        $activityInstance->status = $data['status'];
        $activityInstance->update();
        $status = $activityInstance['status']; 
        return  $this-> getChangeStaus($id, $status);
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
