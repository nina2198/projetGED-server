<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Models\Service\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection\stdClass;
use App\Models\APIError;
use Illuminate\Pagination\Paginator;

/**
 * @author Ulrich Bertrand
 * return all the activity of all Service
 */
class ActivitiesController extends Controller
{
    public function index(Request $req)
    {
        $activity = Activity::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($activity); 
    }
   
    public function create(Request $req)
    {
        // si description et service present dans la requete
        $data = $req->only(['description', 'service_id']);
        $this->validate($data, [
            'description' => 'required',
            'service_id' => 'required|integer'
        ]);
        //recuperation des variable de requete
        $activity = new Activity();
        $activity->service_id = $data['service_id'];
        $activity->description = $data['description'];
        $activity->save(); 
        return response()->json($activity) ;
    }
    
    public function find($id) 
    {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_NOT_FOUND");
            $apiError->setMessage("L'acticite d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($activity);
    }

    public function find_service($id) 
    {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_DON'T_EXIST");
            $apiError->setMessage("L'acticite d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        $service = Service::find($activity->id)->where('id', '=', $activity->id)
        ->pluck('name') ;
        return response()->json($service);
    }

    public function update(Request $req, $id)
    {
         if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_DON'T_EXIST");
            $apiError->setMessage("L'acticite d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->only('description');
        if (isset($data['description'])) 
            $activity->description = $data['description'];
        $activity->update();
        return response()->json($activity);
    }

/**
 * THE ODERS METHODS WE CAN USE 
 */

    /**
     * @author Ulrich Bertrand
     * Get the instances for the activity
     */
    public function activities_instances(Request $req, $id)
    {
        //activitiesInstances : function define in the  model activity 
        $activityInstances = Activity::simplePaginate($req->has('limit') ? $req->limit : 15)->find($id)->activitiesInstances;
                              
        return response()->json($activityInstances);
    }

    /**
     * @author Ulrich Bertrand
     * Get the service for the activity
     */
    public function service(Request $req, $id)
    {
        //service : the method defiine in the  model activity 
        $service = Activity::find($id)->service;
                              
        return response()->json($service);
    }

    /**
     * @author Ulrich Bertrand
     * search the activity where description name as
     */
    public function search(Request $req)
    {
        $limit = $req->limit ;
        $search = $req->search ;
        $page = $req->page ;

        $activities = Activity::where('description', 'LIKE', '%'.$search.'%')->paginate($limit) ;
        return response()->json($activities);
    }

    public function join(Request $req){
        $activities = Activity::select('activities.*')
            ->join('services', 'activities.service_id', '=', 'services.id')
            ->get();

        return response()->json($activities);
    }

}