<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Reportea;
use Illuminate\Http\Request;

class ReporteaController extends Controller
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
            $reportea = Reportea::where('descripcion', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $reportea = Reportea::latest()->paginate($perPage);
        }

        return view('reporte.reportea.index', compact('reportea'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('reporte.reportea.create');
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
        
        Reportea::create($requestData);

        return redirect('reporte/reportea')->with('flash_message', 'Reportea added!');
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
        $reportea = Reportea::findOrFail($id);

        return view('reporte.reportea.show', compact('reportea'));
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
        $reportea = Reportea::findOrFail($id);

        return view('reporte.reportea.edit', compact('reportea'));
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
        
        $reportea = Reportea::findOrFail($id);
        $reportea->update($requestData);

        return redirect('reporte/reportea')->with('flash_message', 'Reportea updated!');
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
        Reportea::destroy($id);

        return redirect('reporte/reportea')->with('flash_message', 'Reportea deleted!');
    }
}
