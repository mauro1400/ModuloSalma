<?php

namespace App\Http\Controllers\SolicitudArticulo;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\SolicitudArticulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $solicitudarticulo = DB::table('subarticle_requests')
                            ->join('requests','subarticle_requests.request_id','=','requests.id')   
                            ->join('subarticles','subarticle_requests.subarticle_id','=','subarticles.id')
                            ->select('subarticles.description','subarticles.unit','requests.nro_solicitud','subarticle_requests.*')
                            ->simplePaginate(100);    
        return view('SolicitudArticulo.solicitud-articulo.index',['solicitudarticulo'=>$solicitudarticulo]);
    }

    public function buscarSolicitud(Request $request)
    {
        $keyword = trim($request->get('search'));
        $perPage = 25;
        $solicitudarticulo = DB::table('subarticle_requests')
                            ->join('requests','subarticle_requests.request_id','=','requests.id')   
                            ->join('subarticles','subarticle_requests.subarticle_id','=','subarticles.id')
                            ->select('subarticles.description','subarticles.unit','requests.nro_solicitud','subarticle_requests.*')
                            ->where('nro_solicitud', 'LIKE', "$keyword")->simplePaginate($perPage);    
        return view('SolicitudArticulo.solicitud-articulo.index',['solicitudarticulo'=>$solicitudarticulo]);
    }

    public function edit($id)
    {
        $solicitudarticulo = SolicitudArticulo::findOrFail($id);

        return view('SolicitudArticulo.solicitud-articulo.edit', ['solicitudarticulo'=>$solicitudarticulo]);
    }
    
    public function estado($id){
        $solicitudarticulo = SolicitudArticulo::findOrFail($id);
        if($solicitudarticulo->estado == "0"):
        $solicitudarticulo->estado = "1";
        else:
            $solicitudarticulo->estado= "0";
        endif;
        return view('SolicitudArticulo.solicitud-articulo.index',['solicitudarticulo'=>$solicitudarticulo]);
    }
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        $solicitudarticulo = SolicitudArticulo::findOrFail($id);
        $solicitudarticulo->update($requestData);

        return redirect('SolicitudArticulo/solicitud-articulo');
    }

}
