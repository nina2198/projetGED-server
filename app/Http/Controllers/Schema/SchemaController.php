<?php

namespace App\Http\Controllers\schema;

use App\Http\Controllers\Controller;
use App\Models\Schema\Schema;
use Illuminate\Http\Request;
use App\Models\APIError;


class SchemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $data = Schema::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);      
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        
        $data = $req->all();

        $this->validate($data, [
            'name' => 'required|unique:schemas',
            'description' => 'required',
            'service_number' => 'required'
        ]);
         $schema = new Schema();
         $schema->name = $data['name'];
         $schema->description = $data['description'];
         $schema->service_number = $data['service_number'];

         return response()->json($schema);
         
    }


    public function find($id)
    {
        $schema = Schema::find($id);
        if (!$schema) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("SCHEMA_NOT_FOUND");
            return response()->json($apiError, 404);
        }
        return response()->json($schema);
    }

   
    public function show(AppModelsSchemaSchema $appModelsSchemaSchema)
    {
        //
    }

    public function edit(AppModelsSchemaSchema $appModelsSchemaSchema)
    {
        //
    }

    
    public function update(Request $req, $id)
    {
        $schema = Schema::find($id);
        if (!$schema) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("CHATDISCUSSION_NOT_FOUND");
            return response()->json($apiError, 404);
        }
        
        $data = $req->all();
        $this->validate($data, [
            'name' => 'required|unique:schemas',
            'description' => 'required',
            'service_number' => 'required'
        ]);

        // en cours 
        $schema = new Schema();
        $schema->name = $data['name'];
        $schema->description = $data['description'];
        $schema->service_number = $data['service_number'];

        return response()->json($schema);
    }

    
    public function destroy($id)
    {
        $schema = Schema::find($id);
        if (!$schema) {
            $apiError = new APIError();
            $apiError->setStatus("404");
            $apiError->setCode("SCHEMA_NOT_FOUND");
            $apiError->setMessage("le schema d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $schema->delete();      
        return response()->json(null);
    }

    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);

        $data = Schema::where($req->field, 'like', "%$req->q%")
            ->simplePaginate($req->has('limit') ? $req->limit : 15);

        return response()->json($data);
    }

   

}
