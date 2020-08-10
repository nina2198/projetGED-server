<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Activity\ActivitiesController;
use App\Models\Activity\ActivitySchema;
use App\Models\Activity\Activity;
use App\Models\Activity\ActivityInstance;
use App\Models\Folder\Folder;
use Illuminate\Http\Request;
use App\Models\Schema\Schema;

class ActivitySchemasController extends Controller
{
  public function create(Request $req)
  {
    // si name et service present dans la requete
    $data = $req->only(['order']);
    $this->validate($data, [
      'order' => 'required|integer'
    ]);
    $order = $data['order'];



    while ($order != 0) {
      $activity_id = Request('activity' . $order);
      echo ' id: ' . $activity_id;
      $order = $order - 1;
    }
    return response()->json($activity_id);
  }

  public static function getFolderProgressionPourcentage($folder_id){
    $data = ActivityInstance::select('activity_instances.id')
            ->join('folders', 'activity_instances.folder_id', '=', 'folders.id')
            ->where(['folders.id' => $folder_id])
            ->orderBy('activity_instances.created_at', 'DESC')
            ->first();
    $activity_instance = $data->id;
    $data = ActivitySchemasController::getActivityOrderAndServiceNumber($activity_instance);
    return ($data->activity_order / $data->service_number)*100 ;
  }

  //poucentage de progression d'un dossier
  public function getFolderPoucentage($activity_instance_id)
  {
    $data = ActivitySchema::select('schemas.service_number', 'activity_schemas.activity_order')
      ->join('schemas', 'schemas.id', '=', 'activity_schemas.schema_id')
      ->join('activities', 'activities.id', '=', 'activity_schemas.activity_id')
      ->join('activity_instances', 'activity_instances.activity_id', '=', 'activities.id')
      ->where(['activity_instances.id' => $activity_instance_id])
      ->first();

    $result = ($data->activity_order / $data->service_number) * 100;
    return response()->json($result);
  }

  public static function getActivityOrderAndServiceNumber($activity_instance_id)
  {
    $data = ActivitySchema::select('schemas.service_number', 'activity_schemas.activity_order', 'schemas.id as schema_id', 'activity_instances.folder_id')
      ->join('schemas', 'schemas.id', '=', 'activity_schemas.schema_id')
      ->join('activities', 'activities.id', '=', 'activity_schemas.activity_id')
      ->join('activity_instances', 'activity_instances.activity_id', '=', 'activities.id')
      ->where(['activity_instances.id' => $activity_instance_id])
      ->first();
    return ($data);
  }

  /**
   * @return l'id de la prochaine activité a effectuer dans le schema
   */
  public static function getFutureActivity($schema_id, $activity_order)
  {
    $data = ActivitySchema::select('activity_schemas.activity_id as activity_id')
      ->join('schemas', 'schemas.id', '=', 'activity_schemas.schema_id')
      ->where([
        'schemas.id' => $schema_id,
        'activity_schemas.activity_order' => $activity_order+1
      ])
      ->first();
    return $data;
  }

  /**
   * @param $activity_id l'id de l'activite suivante
   * @return l'id de l'administrateur  
   */
  public static function getAdminServiceId($activity_id)
  {
    $data = Activity::select('admin_services.admin_id as user_id', 'admin_services.service_id as service_id') // id le l'admin du service
      ->join('services', 'activities.service_id', '=', 'services.id')
      ->join('admin_services', 'admin_services.service_id' ,'=', 'services.id')
      ->where([
        'activities.id' => $activity_id
      ])->first();

    return $data;
  }

}
