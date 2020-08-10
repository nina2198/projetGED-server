<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Models\service\Service;
use Illuminate\Http\Request;
use App\Models\APIError;

class ServiceController extends Controller
{
    
   public function index (Request $req)
   {
       $data = Service::simplePaginate($req->has('limit') ? $req->limit : 15);
       return response()->json($data);
   }

    
    public function store(){ }


    public function create(Request $req)
    {
        $data = $req->only('name');

        $this->validate($data, [
            'name' => 'required',
        ]);

            $Service = new Service();
            $Service->name = $data['name'];
            $Service->save();
       
        return response()->json($Service);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\service\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\service\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update a service on database
     * @author NGOMSEU
     * @email ngomseuromaric@gmail.com
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
         if(!$service = Service::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("SERVICE_DON'T_EXIST");
            $apiError->setMessage("Le Nom de ce Service n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->only('name');
        if (isset($data['name'])) 
            $service->name = $data['name'];
        $service->update();
        return response()->json($service);
    }

    
 /**
     * Remove a service from database
     * @author NGOMSEU
     * @email ngomseuromaric@gmail.com
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$service = Service::find($id)) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("ASSIGNMENT_NOT_FOUND");
            $unauthorized->setMessage("Service id not found in database.");

            return response()->json($unauthorized, 404);
        }

        $service->delete();      
        return response()->json($service);
    }

    /**
     * Search a service from database
     * @author NGOMSEU
     * @email ngomseuromaricl@gmail.com
     * @param  \Illuminate\Http\Request  $req
     * @return \Illuminate\Http\Response
     */
    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'name' => 'present',
        ]);

        $data = Service::where('name', 'like', "%$req->name%")
            ->get();

        return response()->json($data);
    }

    /**
     * find a spacific assignement 
     * @author NGOMSEU
     */
    public function find($id){
        $service = Service::find($id);
        if($service == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("ASSIGNMENT_NOT_FOUND");
            $unauthorized->setMessage("Service id not found in database.");

            return response()->json($unauthorized, 404);
        }
        return response()->json($service);
    }

    /**
     * @author NGOMSEU
     * Get all the activity for the service
     */
    public function activities(Request $req, $id)
    {
        //activitiesInstances : function define in the  model activity 
        $activities = Service::simplePaginate($req->has('limit') ? $req->limit : 15)->find($id)->activities;
                              
        return response()->json($activities);
    }

     /**
     * @author NGOMSEU
     * Get all the users for the service
     */
    public function users(Request $req, $id)
    {
        //activitiesInstances : function define in the  model activity 
        $users = Service::simplePaginate($req->has('limit') ? $req->limit : 15)->find($id)->users;
                              
        return response()->json($users);
    }

}
