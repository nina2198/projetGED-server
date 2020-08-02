<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity\Activity;
use App\Models\Service\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection\stdClass;
use App\Models\APIError;


class ActivitiesController extends Controller
{
    /**
     * Donne la liste des activites possibles d'un service
     * OK
     */
    public function index(Request $req)
    {
        $activity = Activity::simplePaginate($req->has('limit') ? $req->limit : 15);
        return response()->json($activity); 
    }
    /**
     * permet de creer une nouvelle activites.
     * OK
     */
    public function create(Request $req)
    {
        // si description et service present dans la requete
        $data = $req->only(['description', 'service_id']);
        $this->validate($data, [
            'description' => 'required',
            'service_id' => 'required|integer'
        ]);
        //recuperation des variable de requete
        $service_id = $data['service_id'];
        $description = $data['description'];

        $activity = new Activity();
        $activity->service_id = $service_id ;
        $activity->description = $description;

        $activity->save(); 
        return response()->json($activity) ;
        
    }
    
    //rechercher une activité à partir de son id   OK
    public function find($id) {
        if(!$activity = Activity::find($id)) {
            $apiError = new APIError;
            $apiError->setStatus("404");
            $apiError->setCode("ACTIVITY_DON'T_EXIST");
            $apiError->setMessage("L'acticite d'id $id n'existe pas");
            return response()->json($apiError, 404);
        }
        return response()->json($activity);
    }
    /**
     * changer la description d'une activité
     * OK
     */
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

}