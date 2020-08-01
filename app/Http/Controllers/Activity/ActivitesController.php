<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Service\Service;
use App\Models\Activity\Activites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection\stdClass;

class ActivitesController extends Controller
{
    /**
     * Donne la liste des activites possibles d'un service
     */
    public function index()
    {
        $idserv = /*sessionScope('idService')*/ 2 ;

        $activite = DB::table('activites')->where('idService', $idserv)
                    ->sharedLock()
                    ->get();
      
        return $activite; 
    }

    /**
     * permet de creer une nouvelle activites.
     */

    public function create()
    {
        /*description et idService fournit par la variable de session recuperée 
        *dans la page de creation d'activités par 
        * l'administrateur systeme en decrivant ce qui est attendu de l'utilisateur
        */
        $descrip = /*request('description')*/ 'test de description';
        $idserv = /*sessionScope('idService')*/ 1 ;
        
        //creation d'une nouvelle activité
        $id=  DB::table('activites')->timestamps()->insertGetId(
            ['idService' =>$idserv , 'description' =>$descrip ]
             );
        //return the new ID from the activity create
        return $id;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity\Activites  $activites
     * @return \Illuminate\Http\Response
     */
    public function show(Activites $activites)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity\Activites  $activites
     * @return \Illuminate\Http\Response
     */
    public function edit(Activites $activites)
    {
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity\Activites  $activites
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activites $activites)
    {
        $description = /*request('description')*/ 'description a changer';
        $idActivity = /*request('idActivite')*/ 1 ;
        DB::table('activites')
            ->where('idActivite', $idActivity)
            ->update(['description' => $description, 'idService'=>1 ]) ;
    }

    //changer l'activité pour le service: a utiliser lors de creation d'activite pour services
    public function ChangeForService(Request $request, Activites $activites)
    {
        $service_id = /**request('service') */ 3; 
        $idActivity = /*request('idActivite')*/ 1 ;
        DB::table('activites')
            ->where('idActivite', $idActivity)
            ->update(['idService' => $service_id]) ;
            return back();
    }

    public function destroy()
    {
        //Une Activite creer ne peut plus jamais etre detruite
        //trouver plutot un moyen de le rendre obsolete dans la base de donnee
    }
}
