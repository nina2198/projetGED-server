<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Service\Service;
use Illuminate\Http\Request;
use App\Models\APIError;
use App\Models\Activity\ActivityInstance;


 /**
 * Update a service on database
 * @author NGOMSEU
 * @email ngomseuromaric@gmail.com
 * @param  \Illuminate\Http\Request  $request
 * @param  $id
 * @return \Illuminate\Http\Response
 */
class ServiceController extends Controller
{
    
   public function index (Request $req)
   {
       $data = Service::simplePaginate($req->has('limit') ? $req->limit : 15);
       return response()->json($data);
   }

   public function all()
   {
       $data = Service::all();
       return response()->json($data);
   }

    public function create(Request $req)
    {
        $data = $req->only('name', 'admin_id', 'building');
        $this->validate($data, [
            'name' => 'required',
            'admin_id' => 'required:exist:users:id'
        ]);
        $Service = new Service();
        $Service->name = $data['name'];
        $Service->admin_id = $data['admin_id'];
        if(isset($data['building']))
            $Service->building = $data['building'];
        $Service->save();
        return response()->json($Service);
    }
   
    public function update(Request $req, $id)
    {
         if(!$service = Service::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("SERVICE_NOT_FOUND");
            $apiError->setMessage('Service id ' .$id . ' not found in database.');
            return response()->json($apiError, 404);
        }
        $data = $req->only(['name', 'admin_id', 'building']);
        if (isset($data['name'])) 
            $service->name = $data['name'];
        if (isset($data['admin_id'])) 
            $service->admin_id = $data['admin_id'];
        if (isset($data['building'])) 
            $service->building = $data['building'];
        $service->update();
        return response()->json($service);
    }

    public function destroy($id)
    {
        if (!$service = Service::find($id)) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SERVICE_NOT_FOUND");
            $unauthorized->setMessage('Service id ' .$id . ' not found in database.');
            return response()->json($unauthorized, 404);
        }
        $service->delete();      
        return response()->json(null);
    }

    public function search(Request $req)
    {
        $this->validate($req->all(), [
            'q' => 'present',
            'field' => 'present'
        ]);
        $data = Service::where($req->field, 'like', "%$req->q%")->simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($data);
    }

    public function find($id){
        if(!$service = Service::find($id)){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SERVICE_NOT_FOUND");
            $unauthorized->setMessage('Service id ' .$id . ' not found in database.');
            return response()->json($unauthorized, 404);
        }
        return response()->json($service);
    }

    public function activities(Request $req, $id)
    {
        if(!$service = Service::find($id)){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SERVICE_NOT_FOUND");
            $unauthorized->setMessage('Service id ' .$id . ' not found in database.');
            return response()->json($unauthorized, 404);
        }
        $activities = Service::find($id)->activities;
        return response()->json($activities);
    }

    public function users(Request $req, $id)
    {
        if(!$service = Service::find($id)){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("SERVICE_NOT_FOUND");
            $unauthorized->setMessage('Service id ' .$id . ' not found in database.');
            return response()->json($unauthorized, 404);
        }
        $users = Service::find($id)->users;
        return response()->json($users);
    }

    //donne le service de l'administrateur
    public function serviceByAdmin($admin_id)
    {
        $service = Service::whereAdminId($admin_id)->first();
        return response()->json($service, 200);
    }

    public function listFoldersRejecteced($service_id, $admin_id)
    {
        $service = Service::whereId($service_id)->first();
        //recupere toutes les instances d'activites ou le service est ou a ete concerne
        $activity_instance = $service->activityInstances()->where(['status' => 'REJECTED']);

        return response()->json($activity_instance, 200);
    }

    public function listFoldersPending($service_id)
    {
        $folder = ActivityInstance::select('folders.*')
                    ->join('services', 'services.id', '=', 'activity_instance.service_id')
                    ->join('folders', 'folders.id', '=', 'activity_instance.folder_id')
                    ->where([
                        'activity_instance.service_id' => $service_id, 
                        'folders.status' => 'PENNDING'])->get();   
        return response()->json($folder, 200);
    }

    public function listFoldersRejected($service_id, $admin_id)
    {
        $activity_instance = ActivityInstance::select('*')
                ->where(['service_id' => $service_id, 'status' => 'REJECTED'])
                ->get();   
                      
        return response()->json($activity_instance, 200);
    }

    public function listFoldersFinish($service_id, $admin_id)
    {
        $activity_instance = ActivityInstance::select('*')
                ->where([
                    'service_id' => $service_id,
                    'status' => 'FINISH'])
                ->get();   
                      
        return response()->json($activity_instance, 200);
    }
}
