<?php

namespace App\Http\Controllers\Apps;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Exports\SalesExport;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SaleController extends Controller
{
    public function index()
    {
        return Inertia::render('Apps/Sales/Index');
    }

    public function filter(Request $request)
    {
        $this->validate($request, [
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        // dd($request->all());
        $tanggal_awal = Carbon::parse($request->start_date)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->end_date)->endOfDay();

        //get data sales by range date
        $sales = Transactions::with(['cashier', 'customer'])
        ->whereDate('created_at', '>=', $tanggal_awal)
        ->whereDate('created_at', '<=', $tanggal_akhir)
        ->get();

        //get total sales by range date
        $total = Transactions::whereDate('created_at', '>=', $tanggal_awal)
        ->whereDate('created_at', '<=', $tanggal_akhir)
        ->sum('grand_total');

        return Inertia::render('Apps/Sales/Index', [
            'sales' => $sales,
            'total' => (int) $total
        ]);
    }

    public function export(Request $request)
    {
        // dd($request->all());
        $tanggal_awal = Carbon::parse($request->start_date)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->end_date)->endOfDay();

        return Excel::download(new SalesExport($tanggal_awal, $tanggal_akhir), 'sales: '.$tanggal_awal.' - '.$tanggal_akhir.'.xlsx');
    }

    public function pdf(Request $request)
    {
        $tanggal_awal = Carbon::parse($request->start_date)->startOfDay();
        $tanggal_akhir = Carbon::parse($request->end_date)->endOfDay();

        //get data sales by range date
        $sales = Transactions::with(['cashier', 'customer'])
        ->whereDate('created_at', '>=', $tanggal_awal)
        ->whereDate('created_at', '<=', $tanggal_akhir)
        ->get();

        $total = Transactions::whereDate('created_at', '>=', $tanggal_awal)
        ->whereDate('created_at', '<=', $tanggal_akhir)
        ->sum('grand_total');

        $pdf = PDF::loadView('exports.sales', compact('sales', 'total'));

        return $pdf->download('sales '.$tanggal_awal.' - '.$tanggal_akhir.'.pdf');
    }
}
