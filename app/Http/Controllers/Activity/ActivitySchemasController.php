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
use Illuminate\Support\Facades\DB;

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

  //poucentage de progression d'un dossier a partir de la derniere instance cree
  public function getFolderPoucentage($activity_instance_id)
  {
    $data = ActivitySchema::select('schemas.service_number', 'activity_schemas.activity_order', 'activity_instances.status')
      ->join('schemas', 'schemas.id', '=', 'activity_schemas.schema_id')
      ->join('activities', 'activities.id', '=', 'activity_schemas.activity_id')
      ->join('activity_instances', 'activity_instances.activity_id', '=', 'activities.id')
      ->where(['activity_instances.id' => $activity_instance_id])
      ->first();
      if($data->status!='ENDING' && $data->activity_order==1) return 0;
      else if($data->activity_order>1 && $data->status!='ENDING') return (($data->activity_order-1) / $data->service_number)*100 ;
      else if ($data->activity_order>1 && $data->status=='ENDING') return (  $data->activity_order/ $data->service_number)*100 ;
  }

}
