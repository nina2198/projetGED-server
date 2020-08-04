<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folder\Folder;
use App\Models\Folder\FolderType;
use App\Models\Persons\User;
use Carbon\Carbon;
use App\Models\APIError;

class FolderController extends Controller
{
    // Retourner tout les dossiers avec pagination
    public function index(Request $req)
    {
        $data = Folder::simplePaginate($req->has('limit') ? $req->limit : 15);
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
        $folder->name = $folder_type->name;
        $folder->save();
        $folder->name = $folder->name . '_GED_0.0.' . $folder->id;
        $folder->update();

        return response()->json($folder);
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

    //trouver tous les fichiers d'un dossier
    public function findFiles($id){
        if (!$folder = Folder::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_NOT_FOUND");
            $apiError->setMessage("Le dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

         $files = File::where('folder_id',$id)->get();
         return response()->json($folder);

    }
    //trouver tout les dossiers archivés
    public function findTreatedFolders(){
        $folders = Folder::where('status','ARCHIVED')->get();
        return response()->json($folders);
    }
}
