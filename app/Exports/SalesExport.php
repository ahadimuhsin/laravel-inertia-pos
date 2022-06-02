<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Transactions;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesExport implements FromView
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    public function view() :View
    {
        $tanggal_awal = Carbon::parse($this->start_date)->startOfDay();
        $tanggal_akhir = Carbon::parse($this->end_date)->endOfDay();

        //get data sales by range date
        $sales = Transactions::with(['cashier', 'customer'])
        ->whereDate('created_at', '>=', $tanggal_awal)
        ->whereDate('created_at', '<=', $tanggal_akhir)
        ->get();

        //get total sales by range date
        $total = Transactions::whereDate('created_at', '>=', $tanggal_awal)
        ->whereDate('created_at', '<=', $tanggal_akhir)
        ->sum('grand_total');
        
        return view('exports.sales', [
            'sales' => $sales,
            'total' => $total
        ]);
    }
}
