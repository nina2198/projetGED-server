<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use App\Models\Folder\FolderType;
use Illuminate\Http\Request;
use App\Models\APIError;

class FolderTypeController extends Controller
{
    // Retourner tout les type de dossier avec pagination
    public function index(Request $req)
    {
        $data = FolderType::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }
   // Rechercher un type de dossier par son id
    public function find($id){
         $folder_type = new FolderType();
         if(!$folder_type = FolderType::find($id)) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("NOT_FOUND");
            $apiError->setMessage("ce type de dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
         }
         return response()->json($folder_type);
    }

    // Rechercher une occurence d'un attribut du dossier
    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = FolderType::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    // Créer un type de dossier
    public function create(Request $req)
    {
        $data = $req->only(['name', 'description', 'max_file_size', 'file_number']);
        $this->validate($data, [
            'name' => 'required',
            'description' => 'required'
        ]);

        $folder_type = new FolderType();
        $folder_type->name = $data['name'];
        $folder_type->description = $data['description'];
        if(isset($data['max_file_size']))
            $folder_type->max_file_size = $data['max_file_size'];
        if(isset($data['file_number']))

            $folder_type->file_number = $data['file_number'];
        $folder_type->save();

        return response()->json($folder_type);
    }
    
    // Faire la mise à jour d'un dossier
    public function update(Request $req, $id)
    {
        if (!$folder_type = FolderType::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_TYPE_NOT_FOUND");
            $apiError->setMessage("le type de dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->only(['name', 'description', 'max_file_size', 'file_number']);
        if(isset($data['name'])) $folder_type->name = $data['name'];
        if(isset($data['description'])) $folder_type->description = $data['description'];
        if(isset($data['max_file_size'])) $folder_type->max_file_size = $data['max_file_size'];
        if(isset($data['file_number'])) $folder_type->file_number = $data['file_number'];
        $folder_type->update();

        return response()->json($folder_type);
    }

    // Supprimer un type de dossier
    public function destroy($id)
    {
        if (!$folder_type = FolderType::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_TYPE_NOT_FOUND");
            $apiError->setMessage("le type de dossier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $folder_type->delete();

        return response()->json(null);
    }
}
