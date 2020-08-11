<?php

namespace App\Http\Controllers\Schema;

use App\Http\Controllers\Controller;
use App\Models\APIError;
use App\Models\Schema\Schema;
use Illuminate\Http\Request;

class SchemasController extends Controller
{
    /**
     * @author Ulrich Bertrand
     * return all the schema of all Service
     */
    public function index(Request $req)
    {
        $schema = Schema::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($schema); 
    }

    /**
     * @author Ulrich Bertrand
     * Create the new schema.
     * OK
     */
    public function create(Request $req)
    {
        // si name et service present dans la requete
        $data = $req->only(['name', 'service_number']);
        $this->validate($data, [
            'name' => 'required',
            'service_number' => 'required|integer'
        ]);
        //recuperation des variable de requete
        $name = $data['name'];
        $service_number = $data['service_number'];

        $schema = new Schema();
        $schema->name = $name ;
        $schema->service_number = $service_number;

        $schema->save(); 
        return response()->json($schema) ;
    }

    /**
     * @author Ulrich Bertrand
     * Find the activity
     */
    public function find($id) 
    {
        if(!$schema = Schema::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_DON'T_EXIST");
            $apiError->setMessage("L'acticite d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($schema);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Schema\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function show(Schema $schema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Schema\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function edit(Schema $schema)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schema\Schema  $schema
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schema $schema)
    {
        //
    }
}
