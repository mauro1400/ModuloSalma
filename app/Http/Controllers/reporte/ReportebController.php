<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Reporteb;
use Illuminate\Http\Request;

class ReportebController extends Controller
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
            $reporteb = Reporteb::where('descripcion', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $reporteb = Reporteb::latest()->paginate($perPage);
        }

        return view('reporte.reporteb.index', compact('reporteb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('reporte.reporteb.create');
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
        
        Reporteb::create($requestData);

        return redirect('reporte/reporteb')->with('flash_message', 'Reporteb added!');
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
        $reporteb = Reporteb::findOrFail($id);

        return view('reporte.reporteb.show', compact('reporteb'));
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
        $reporteb = Reporteb::findOrFail($id);

        return view('reporte.reporteb.edit', compact('reporteb'));
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
        
        $reporteb = Reporteb::findOrFail($id);
        $reporteb->update($requestData);

        return redirect('reporte/reporteb')->with('flash_message', 'Reporteb updated!');
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
        Reporteb::destroy($id);

        return redirect('reporte/reporteb')->with('flash_message', 'Reporteb deleted!');
    }
}
