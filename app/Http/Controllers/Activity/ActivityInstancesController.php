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
    /**
     *  @author Ulrich Bertrand
     */
    public function index(Request $req)
    {
        $activity_instance = ActivityInstance::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($activity_instance); 
    }

    /**
     *  @author Ulrich Bertrand
     */ 
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
    /**
     * 
     * 
     * 
    */

    /**
     * @author Ulrich Bertrand
     * Get the activity  for this instance
     */
    public function activity(Request $req, $id)
    {
        //activity : function define in the  model ActivityInstance 
        $activity = ActivityInstance::find($id)->activity;
                              
        return response()->json($activity);
    }

    /**
     * @author Ulrich Bertrand
    * Get the user  who is work or traited in this instance
    */
    public function user(Request $req, $id)
    {
        //user : function define in the  model ActivityInstance
        $user = ActivityInstance::find($id)->user;
                              
        return response()->json($user);
    }



}
