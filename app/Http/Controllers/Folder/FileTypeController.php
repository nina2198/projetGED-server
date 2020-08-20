<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use App\Models\Folder\FileType;
use Illuminate\Http\Request;
use App\Models\APIError;

class FileTypeController extends Controller
{
    // Retourner tout les types de fichier avec pagination
    public function index(Request $req)
    {
        $data = FileType::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }
   // Rechercher un type de fichier par son id
    public function find($id){
         $file_type = new FileType();
         if(!$file_type = FileType::find($id)) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("FILE_TYPE_NOT_FOUND");
            $apiError->setMessage("Ce type de fichier d'id " . $id . "n'existe pas");
            return response()->json($apiError, 404);
         }
         return response()->json($file_type);
    }

    // Rechercher une occurence d'un attribut du fichier
    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = FileType::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    // Créer un type de fichier
    public function create(Request $req)
    {
        $data = $req->only(['name', 'description', 'max_size', 'folder_type_id']);
        $this->validate($data, [
            'name' => 'required',
            'description' => 'required',
            'folder_type_id' => 'required'
        ]);

        $file_type = new FileType();
        $file_type->name = $data['name'];
        $file_type->description = $data['description'];
        $file_type->folder_type_id = $data['folder_type_id'];
        if(isset($data['max_size']))
            $file_type->max_size = $data['max_size'];
        $file_type->save();
        
        return response()->json($file_type);
    }
    
    // Faire la mise à jour d'un type de fichier
    public function update(Request $req, $id)
    {
        if (!$file_type = FileType::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("FILE_TYPE_NOT_FOUND");
            $apiError->setMessage("Le type de fichier d'id " . $id . "n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->only(['name', 'description', 'max_size', 'folder_type_id']);
        if(isset($data['name'])) $file_type->name = $data['name'];
        if(isset($data['description'])) $file_type->description = $data['description'];
        if(isset($data['max_size'])) $file_type->max_size = $data['max_size'];
        if(isset($data['folder_type_id'])) $file_type->folder_type_id = $data['folder_type_id'];
        $file_type->update();

        return response()->json($file_type);
    }

    // Supprimer un type de fichier
    public function destroy($id)
    {
        if (!$file_type = FileType::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FILE_TYPE_NOT_FOUND");
            $apiError->setMessage("Ce type de fichier d'id " . $id . "n'existe pas");
            return response()->json($apiError, 404);
        }
        $file_type->delete();

        return response()->json(null);
    }
}
