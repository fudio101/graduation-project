<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Contract;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function __invoke($contract)
    {
        $contract = Contract::find($contract)->with('room')->first();
        // dd($contract->room->house->province->name);
        
        $now = Carbon::now();
        $date = $this->getDayVN($now);

        $pdf = Pdf::loadView('contract_pdf', ['contract' => $contract, 'date' => $date]);
        
        // return $pdf->download('contract.pdf');
        return $pdf->stream('contract.pdf');
    }

    public function getDayVN($time)
    {
         $daysOfWeek = [
            'Chủ Nhật',
            'Thứ Hai',
            'Thứ Ba',
            'Thứ Tư',
            'Thứ Năm',
            'Thứ Sáu',
            'Thứ Bảy'
        ];
        $date = [];
        $date['year'] = $time->year;
        $date['month'] = $time->month;
        $date['day'] = $time->day;
        $date['day_name'] = $daysOfWeek[$time->dayOfWeek];

        return $date;
    }

}
