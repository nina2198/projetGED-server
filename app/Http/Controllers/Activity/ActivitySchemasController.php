<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Activity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Schema\Schema;

class ActivitySchemasController extends Controller
{
    public function create(Request $req)
    {  
      // si name et service present dans la requete
      $data = $req->only(['order']);
      $this->validate($data,[
          'order' => 'required|integer'
      ]);
      $order = $data['order'];

     

     while($order != 0){
      $activity_id = Request('activity'.$order) ;         
       echo ' id: '.$activity_id ;
          $order = $order -1;
       }
      return response()->json($activity_id) ;

    }
}
