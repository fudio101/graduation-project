<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Bill;
use Carbon\Carbon;
use App\Models\ElectricManager;

class BillController extends Controller
{
    public function billing(Request $request)
    {
        $date  = $request->date;
        $rooms = Room::with('roomType', 'services')->whereHas('contracts', function ($query) use ($date) {
            $query->where('status', 1);
        })->get();
        foreach ($rooms as $room) {
            $room_money          = $room->roomType->rental_price;
            $room_services       = $room->services;
            $room_services_money = 0;
            foreach ($room_services as $service) {
                $service_quantity     = $service->pivot->quantity;
                $room_services_money += $service->price * $service_quantity;
            }
            $room_electric = ElectricManager::select('id', 'status', 'step', 'quantity')->where('house_id', $room->house_id)->first();
            $room_electric_money = 0;
            if ($room_electric->status == 1) {
                
            }
            dd($room_electric);
            $bill = Bill::create([
                'room_id'     => $room->id,
                'total_money' => 0,
                'month'       => date('Y-m', strtotime($date)),
                'status'      => 0,
                'start_date'  => date('Y-m-01', strtotime($date)),
                'end_date'    => date('Y-m-t', strtotime($date)),
            ]);
        }

        return redirect()->back();
    }

    public function billing_room()
    {
        $rooms = Room::whereHas('contracts', function ($query) {
            $query->where('status', 1);
        })->get();

        return view('bill.billing_room', compact('rooms'));
    }
}
