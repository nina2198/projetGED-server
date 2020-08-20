<?php

namespace App\Http\Controllers\Folder;

use App\Http\Controllers\Controller;
use App\Models\Folder\File;
use App\Models\Folder\Folder;
use App\Models\Folder\FolderType;
use App\Models\Folder\FileType;
use Illuminate\Http\Request;
use App\Models\APIError;

class FileController extends Controller
{
    // Retourner tout les dossiers avec pagination
    public function index(Request $req)
    {
        $data = File::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    // Rechercher une occurence d'un attribut du dossier
    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = File::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    // Créer un dossier
    public function create(Request $req)
    {
        $data = $req->only(['name', 'file_size', 'file_type_id', 'path', 'folder_id']);
        $this->validate($data, [
            'name' => 'required',
            'file_size' => 'required',
            'file_type_id' => 'required:exists:file_types:id',
            'folder_id' => 'required:exists:folders:id',
            'path' =>  'required|file'
        ]);
        
        $file_type = FileType::find($data['file_type_id']);

        $rules = null;
        if($file_type->file_type == 'PHOTO') {
            $rules = array_merge(['file'], ['mimes:jpg,png,jpeg']);
        } else if($file_type->file_type == 'PDF') {
            $rules = array_merge(['file'], ['mimes:pdf']);
        }
        $rules = array_unique($rules);
        $location = $this->computeFolderPath((int)$data['folder_id']);

        if ($file = $req->file('path')) {
            $filePath = $this->uploadSingleFile($req, 'path', $location, $rules);
            $data['path'] = $filePath['saved_file_path'];
        }

        $file = new File();
        $file->name = $data['name'];
        $file->file_size = $data['file_size'];
        $file->file_type_id = $data['file_type_id'];
        $file->folder_id = $data['folder_id'];
        if(isset($data['path'])) $file->path = $data['path'];
        $file->save();
        $file->path = url($file->path);

        return response()->json($file);
    }

    public function computeFolderPath($folder_id) {
        if (!$folder = Folder::find($folder_id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_NOT_FOUND");
            $apiError->setMessage("Le dossier d'id $folder_id n'existe pas");
            return response()->json($apiError, 404);
        }
        if (!$folder_type = FolderType::find($folder->folder_type_id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FOLDER_TYPE_NOT_FOUND");
            $apiError->setMessage("le type de dossier d'id $folder->folder_type_id n'existe pas");
            return response()->json($apiError, 404);
        }
        return $folder_type->slug . '\\' . $folder->slug;
    }

    // Rechercher un dossier par son id
    public function find($id) {
        if(!$file = File::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("FILE_NOT_FOUND");
            $apiError->setMessage("Le fichier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        return response()->json($file);
    }

    // Faire la mise à jour d'un dossier
    public function update(Request $req, $id)
    {
        if (!$file = File::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FILE_NOT_FOUND");
            $apiError->setMessage("le fichier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $data = $req->only(['description']);
        if (isset($data['description'])) 
            $file->description = $data['description'];
        $file->update();

        return response()->json($file);
    }

    // Supprimer un dossier
    public function destroy($id)
    {
        if (!$file = File::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode(" FILE_NOT_FOUND");
            $apiError->setMessage("le fichier d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $file->delete();

        return response()->json(null);
    }
}
