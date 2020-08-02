<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection\stdClass;
use App\Models\Activity\ActivityInstance;
use App\Models\Activity\Activity;
use App\Models\Person\User;


class ActivityInstancesController extends Controller
{
        public function index(Request $req)
        {
            $activity_instance = ActivityInstance::simplePaginate($req->has('limit') ? $req->limit : 15);
            return response()->json($activity_instance); 
        }


        public function find($id)
        {
            if(!$activityInstance = ActivityInstance::find($id)) {
                $apiError = new APIError;
                $apiError->setStatus("404");
                $apiError->setCode("ACTIVITY_DON'T_EXIST");
                $apiError->setMessage("AUCUNE INSTANCE D'ACTIVITE EXISTENTE POUR CETTE id $id");
                return response()->json($apiError, 404);
            }
            return response()->json($activityInstance);
        }
    
    

}
