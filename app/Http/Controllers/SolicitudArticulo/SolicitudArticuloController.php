<?php

namespace App\Http\Controllers\SolicitudArticulo;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\SolicitudArticulo;
use Illuminate\Http\Request;

class SolicitudArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $solicitudarticulo = SolicitudArticulo::where('descripcion', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $solicitudarticulo = SolicitudArticulo::latest()->paginate($perPage);
        }

        return view('SolicitudArticulo.solicitud-articulo.index', compact('solicitudarticulo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('SolicitudArticulo.solicitud-articulo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        
        $requestData = $request->all();
        
        SolicitudArticulo::create($requestData);

        return redirect('SolicitudArticulo/solicitud-articulo')->with('flash_message', 'SolicitudArticulo added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $solicitudarticulo = SolicitudArticulo::findOrFail($id);

        return view('SolicitudArticulo.solicitud-articulo.show', compact('solicitudarticulo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $solicitudarticulo = SolicitudArticulo::findOrFail($id);

        return view('SolicitudArticulo.solicitud-articulo.edit', compact('solicitudarticulo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $requestData = $request->all();
        
        $solicitudarticulo = SolicitudArticulo::findOrFail($id);
        $solicitudarticulo->update($requestData);

        return redirect('SolicitudArticulo/solicitud-articulo')->with('flash_message', 'SolicitudArticulo updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        SolicitudArticulo::destroy($id);

        return redirect('SolicitudArticulo/solicitud-articulo')->with('flash_message', 'SolicitudArticulo deleted!');
    }
}
