<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Models\service\Service;
use Illuminate\Http\Request;
use App\APIError;

class ServiceController extends Controller
{
    /**
    * Display a list of service from database
    * @author NGOMSEU
    * @email ngomseuromaric@gmail.com
    * @param  \Illuminate\Http\Request  $req
    * @return \Illuminate\Http\Response
    */
   public function index (Request $req)
   {
       $data = Service::simplePaginate($req->has('limit') ? $req->limit : 15);
       return response()->json($data);
   }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Create a service on database
     * @author NGOMSEU
     * @email ngomseuromaric@gmail.com
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('photo');

        $this->validate($data, [
            'ordination_date' => 'required',
            'ordination_place' => 'required',
            'ordination_godfather' => 'required',
            'career' => 'required',
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
        $service = Service::find($id);
        if (!$service) {
            abort(404, "No service found with id $id");
        }

        $this->validate($data, [
            'name' => 'required',
        ]);

        if (null !== $data['name']) $service->name = $data['name'];

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
            abort(404, "No service found with id $id");
        }

        $service->delete();      
        return response()->json();
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
            'q' => 'present',
            'field' => 'present'
        ]);

        $data = Service::where($req->field, 'like', "%$req->q%")
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
}
