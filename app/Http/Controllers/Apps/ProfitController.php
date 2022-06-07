<?php

namespace App\Http\Controllers\Apps;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Profit;
// use Barryvdh\DomPDF\PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Exports\ProfitsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ProfitController extends Controller
{
    public function index()
    {
        return Inertia::render('Apps/Profits/Index');
    }

    public function filter(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        //get data profts by range date
        $profits = Profit::with('transaction')
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->get();

        //get total profit by range date
        $total = Profit::with('transaction')
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->sum('total');

        return Inertia::render('Apps/Profits/Index',
        [
            'profits' => $profits,
            'total' => (int)$total
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new ProfitsExport($request->start_date, $request->end_date),
        'profits: '.$request->start_date.' - '.$request->end_date.'.xlsx');
    }

    public function pdf(Request $request)
    {
        $tanggal_awal = Carbon::parse($request->start_date)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->end_date)->endOfDay();

        //get data profts by range date
        $profits = Profit::with('transaction')
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->get();

        //get total profit by range date
        $total = Profit::with('transaction')
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->sum('total');

        $pdf = PDF::loadView('exports.profits', compact('profits', 'total'));

        return $pdf->download('profits '.$tanggal_awal.' - '.$tanggal_akhir.'.pdf');
    }
}
