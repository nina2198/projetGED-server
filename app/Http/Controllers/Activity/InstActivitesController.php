<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection\stdClass;
use APP\Models\Activity\InstActivites;

class InstActivitesController extends Controller
{
    //liste de toutes les activites à gerer dans un service par l'admin
    public function index(){

        $service_id = /** sessionScope('idService')  */ 1 ;
        $user_id = /** sessionScope('id') */ 1 ;

      $list =  DB::table('inst_activites')
            ->where('id',$service_id)
            ->join('users', 'id', '=', 'idUser')
            ->select('inst_activites.*')
            ->get();
            ;
        return $list;
    }

    /**
     * liste des activite en attente: le plus ancien d'abord
     */
    public function index_waiting(){

        $service_id = /** sessionScope('idService')  */ 1 ;
        $user_id = /** sessionScope('id') */ 1 ;

      $list_w =  DB::table('inst_activites')->orderBy('idinstActivite')
            ->where('id',$service_id)
            ->join('users', 'id', '=', 'idUser')
            ->select('inst_activites.*')
            ->where('status', 'WAITING')
            ->get();
            ;
        return $list_w;
    } 

    /**
     * liste des activite en suspendues: la plus ancienne d'abord
     */
    public function index_hanging(){

        $service_id = /** sessionScope('idService')  */ 1 ;
        $user_id = /** sessionScope('id') */ 1 ;

      $list_h =  DB::table('inst_activites')->orderBy('idinstActivite')
            ->where('id',$service_id)
            ->join('users', 'id', '=', 'idUser')
            ->select('inst_activites.*')
            ->where('status', 'HANGING')
            ->get();
            ;
        return $list_h;
    } 
    
     /**
     * liste des activite en execution: la plus ancienne d'abord
     */
    public function index_execution(){

        $service_id = /** sessionScope('idService')  */ 1 ;
        $user_id = /** sessionScope('id') */ 1 ;

        $list_e =  DB::table('inst_activites')->orderBy('idinstActivite')
            ->where('id',$service_id)
            ->join('users', 'id', '=', 'idUser')
            ->select('inst_activites.*')
            ->where('status', 'EXECUTION')
            ->get();
            ;
        return $list_e;
    }
    
     /**
     * liste des activite en suspendues: la plus ancienne d'abord
     */
    public function index_ending(){

        $service_id = /** sessionScope('idService')  */ 1 ;
        $user_id = /** sessionScope('id') */ 1 ;

      $list_end =  DB::table('inst_activites')->orderBy('idinstActivite')
            ->where('id',$service_id)
            ->join('users', 'id', '=', 'idUser')
            ->select('inst_activites.*')
            ->where('status', 'ENDING')
            ->get();
            ;
        return $list_end;
    } 
    
    /**
     * creation d'une instance d'activite 
     */
    public function create()
    {
        $activite_id = /**requeste('ActivitéSelection') */ 1;
        $user_id = /** sessionScope('id')*/ 2;

        $id = DB::table('inst_activites')->insertGetId(
            [
                'idActivite' => $activite_id,
                'idUser' => $user_id,
                //par defeaut status est en attente lors de la premiere relation
            ]);
        dd($id);
    }

}
