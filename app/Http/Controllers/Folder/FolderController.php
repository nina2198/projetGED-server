<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folder\Folder;
use App\Models\Folder\File;
use App\Models\Folder\FolderType;
use App\Models\Person\User;
use Carbon\Carbon;
use App\Models\APIError;
use Illuminate\Support\Str;
use App\Helpers\Helper;
use App\Models\Activity\ActivitySchema;
use App\Models\Activity\ActivityInstance;
use App\Models\Activity\Activity;

class FolderController extends Controller
{
    // Retourner tout les dossiers avec pagination
    public function index(Request $req)
    {
        $data = Folder::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    // Retourner tout les dossiers d'un utilisateur donne
    public function getUserFolders(Request $req, $id)
    {
        $data = Folder::whereUserId($id)->orderBy('created_at', 'desc')->get();
        return response()->json($data);
    }

    // Rechercher une occurence d'un attribut du dossier
    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = Folder::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    // Créer un dossier
    public function create(Request $req)
    {
        $data = $req->only(['description', 'user_id', 'folder_type_id']);
        $this->validate($data, [
            'description' => 'required',
            'user_id' => 'required:exists:users:id',
            'folder_type_id' => 'required:exists:folder_types:id',
        ]);

        $folder = new Folder();
        $folder->description = $data['description'];
        $folder->user_id = $data['user_id'];
        $folder->folder_type_id = $data['folder_type_id'];
        $folder->track_id = $this->generateFolderTrackId();
        $folder_type = $this->getFolderType($data['folder_type_id']);
        $folder->name = $folder_type->slug;
        $folder->slug = $folder->name;
        $folder->save();
        $folder->name = $folder->name . '_GED_0.0.' . $folder->id;
        $folder->slug = $folder->name;
        $folder->update();
        $this->initialiserInstance($folder->id);
        $user = User::find($folder->user_id);
        Helper::send_track_id_to_user($user, $folder->track_id);

        return response()->json($folder);
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

    public function getFolderById($id){
        $folder =  Folder::find($id);
        return $folder;
    }

    // Generation du track-id
    public function generateFolderTrackId() {
        $rand = -1;
        do {
            $rand = random_int(1000 , 9999);
        } while($folder = Folder::whereTrackId($rand)->first());
        return $rand;
    }

    // Generation du nom du dossier
    public function getFolderType($folder_type_id) {
        if(!$folder_type = FolderType::find($folder_type_id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("FOLDER_TYPE_NOT_FOUND");
            $apiError->setMessage("Le type de dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        return $folder_type;
    }

    // Rechercher un dossier par son id
    public function find($id) {
        if(!$folder = Folder::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("FOLDER_NOT_FOUND");
            $apiError->setMessage("Le dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        return response()->json($folder);
    }

    // Rechercher un dossier par son track-id
    public function findByTrackId($track_id) {
        if(!$folder = Folder::whereTrackId($track_id)->first()) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("FOLDER_NOT_FOUND");
            $apiError->setMessage("Le dossier de track_id $track_id n'existe pas");
            return response()->json($apiError, 404);
        }

        return response()->json($folder);
    }

    // Faire la mise à jour d'un dossier
    public function update(Request $req, $id)
    {
        if (!$folder = Folder::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_NOT_FOUND");
            $apiError->setMessage("le dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $data = $req->only(['description', 'status']);
        if (isset($data['description'])) 
            $folder->description = $data['description'];
        if (isset($data['status'])) {
            $this->validate($data, [
                'status' => 'in:ACCEPTED,PENDING,REJECTED,ARCHIVED'
            ]);
            if($data['status'] == 'ARCHIVED')
                $folder->archiving_date = Carbon::now();
            $folder->status = $data['status'];
        }
        $folder->update();

        return response()->json($folder);
    }

    // Supprimer un dossier
    public function destroy($id)
    {
        if (!$folder = Folder::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_NOT_FOUND");
            $apiError->setMessage("Le dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $folder->delete();

        return response()->json(null);
    }

    public function getAcceptedFolders($user_id) {
        $data = Folder::select('folders.*', 'folder_types.id as folder_type_id', 'folder_types.name as folder_type_name', 'folder_types.file_number')
            ->join('folder_types', 'folder_types.id', '=', 'folders.folder_type_id')
            ->where(['folders.user_id' => $user_id, 'folders.status' => 'ACCEPTED'])
            ->orderBy('folders.created_at', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getPendingFolders($user_id) {
        $data = Folder::select('folders.*', 'folder_types.id as folder_type_id', 'folder_types.name as folder_type_name', 'folder_types.file_number')
            ->join('folder_types', 'folder_types.id', '=', 'folders.folder_type_id')
            ->where(['folders.user_id' => $user_id, 'folders.status' => 'PENDING'])
            ->orderBy('folders.created_at', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getRejectedFolders($user_id) {
        $data = Folder::select('folders.*', 'folder_types.id as folder_type_id', 'folder_types.name as folder_type_name', 'folder_types.file_number')
            ->join('folder_types', 'folder_types.id', '=', 'folders.folder_type_id')
            ->where(['folders.user_id' => $user_id, 'folders.status' => 'REJECTED'])
            ->orderBy('folders.created_at', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getArchivedFolders($user_id) {
        $data = Folder::select('folders.*', 'folder_types.id as folder_type_id', 'folder_types.name as folder_type_name', 'folder_types.file_number')
            ->join('folder_types', 'folder_types.id', '=', 'folders.folder_type_id')
            ->where(['folders.user_id' => $user_id, 'folders.status' => 'ARCHIVED'])
            ->orderBy('folders.created_at', 'desc')
            ->get();
        return response()->json($data);
    }

    //trouver tous les fichiers d'un dossier
    public function findFiles($id){
        if (!$folder = Folder::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_NOT_FOUND");
            $apiError->setMessage("Le dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
         $files = $folder->files;
         $folder->folderType;
         foreach($files as $file)
            $file->filetype;
         return response()->json($folder);

    }
    //trouver tout les dossiers archivés
    public function findTreatedFolders(){
        $folders = Folder::where('status','ARCHIVED')->get();
        return response()->json($folders);
    }
}
