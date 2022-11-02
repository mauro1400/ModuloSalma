<?php

namespace App\Http\Controllers\reporte;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Models\Reportec;
use Illuminate\Http\Request;

class ReportecController extends Controller
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
            $reportec = Reportec::where('descripcion', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $reportec = Reportec::latest()->paginate($perPage);
        }

        return view('reporte.reportec.index', compact('reportec'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('reporte.reportec.create');
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
        
        Reportec::create($requestData);

        return redirect('reporte/reportec')->with('flash_message', 'Reportec added!');
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
        $reportec = Reportec::findOrFail($id);

        return view('reporte.reportec.show', compact('reportec'));
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
        $reportec = Reportec::findOrFail($id);

        return view('reporte.reportec.edit', compact('reportec'));
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
        
        $reportec = Reportec::findOrFail($id);
        $reportec->update($requestData);

        return redirect('reporte/reportec')->with('flash_message', 'Reportec updated!');
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
        Reportec::destroy($id);

        return redirect('reporte/reportec')->with('flash_message', 'Reportec deleted!');
    }
}
