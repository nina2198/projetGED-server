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
        $data = $req->only(['name', 'description', 'service_id']);
        $this->validate($data, [
            'name' => 'required|unique:activities',
            'description' => 'required',
            'service_id' => 'required:exist:activities:id'
        ]);
        //recuperation des variable de requete
        $activity = new Activity();
        $activity->name = $data['name'];
        $activity->description = $data['description'];
        $activity->service_id = $data['service_id'];  
        $activity->save(); 
        return response()->json($activity) ;
    }

    public function update(Request $req, $id)
    {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_NOT_FOUND");
            $apiError->setMessage("L'activite d'id " . $id . " n'existe pas");
            return response()->json($apiError, 404);
        }

        $data = $req->only(['name', 'description', 'service_id']);
        if (isset($data['name'])) 
            $activity->name = $data['name'];
        if (isset($data['description'])) 
            $activity->description = $data['description'];
        if (isset($data['service_id'])) 
            $activity->service_id = $data['service_id'];
        $activity->update();
        return response()->json($activity);
    }

    public function find($id) 
    {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_NOT_FOUND");
            $apiError->setMessage("L'activite d'id " . $id . " n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($activity);
    }

    public function service($id) 
    {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_NOT_FOUND");
            $apiError->setMessage("L'activite d'id " . $id . " n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($activity->service);
    }

    public function activitiesInstances(Request $req, $id)
    {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_NOT_FOUND");
            $apiError->setMessage("L'activite d'id " . $id . " n'existe pas");
            return response()->json($apiError, 404);
        }            
        return response()->json($activity->activityInstances);
    }

    public function schemas(Request $req, $id)
    {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_NOT_FOUND");
            $apiError->setMessage("L'activite d'id " . $id . " n'existe pas");
            return response()->json($apiError, 404);
        }                 
        return response()->json($activity->schemas);
    }
}