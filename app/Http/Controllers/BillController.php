<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Bill;
use App\Models\ServiceBill;
use App\Models\ElectricBill;
use App\Models\WaterBill;
use Carbon\Carbon;
use App\Models\ElectricManager;
use App\Models\WaterManager;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function billing(Request $request)
    {
        DB::beginTransaction();
        try {
            $date  = $request->date;
            $rooms = Room::with('roomType', 'services')->whereHas('contracts', function ($query) use ($date) {
                $query->where('status', 1);
            })->get();

            foreach ($rooms as $room) {
                  // Bill
                $bill = Bill::create([
                    'room_id'     => $room->id,
                    'total_money' => 0,
                    'month'       => date('Y-m', strtotime($date)),
                    'status'      => 0,
                    'start_date'  => date('Y-m-01', strtotime($date)),
                    'end_date'    => date('Y-m-t', strtotime($date)),
                ]);

                $type_room_money = $room->roomType->rental_price;

                // Service Bill
                $room_services       = $room->services;
                $room_services_money = 0;
                foreach ($room_services as $service) {
                    $service_quantity     = $service->pivot->quantity;
                    $room_services_money += $service->price * $service_quantity;
                    ServiceBill::create([
                        'bill_id'    => $bill->id,
                        'service_id' => $service->id,
                        'quantity'   => $service_quantity,
                        'price'      => $service->price,
                    ]);
                }

                // Electric Bill
                $room_electric       = ElectricManager::select('id', 'status', 'step', 'quantity')->where('house_id', $room->house_id)->first();
                $room_electric_money = 0;
                if ($room_electric->status->value == 0) {
                    $room_electric_money    = $room_electric->quantity * $room->electric_record;
                } else {
                    $step_electric       = $room_electric->step;
                    $electric_record     = $room->electric_record;
                    $tier                = $this->calculateTier($step_electric);
                    $room_electric_money = $this->calculateElectricityBill($electric_record, $tier);
                }
                ElectricBill ::create([
                    'bill_id'  => $bill->id,
                    'number'   => $room->electric_record,
                    'type'     => $room_electric->status->value,
                    'costs'    => $room_electric_money,
                    'quantity' => $room_electric->quantity,
                    'step'     => $room_electric->step,
                ]);

                // Water Bill
                $room_water       = WaterManager::select('id', 'status', 'step', 'quantity')->where('house_id', $room->house_id)->first();
                $room_water_money = 0;
                if ($room_water->status->value == 0) {
                    $room_water_money = $room_water->quantity * $room->water_record;
                } else {
                    $step_water       = $room_water->step;
                    $water_record     = $room->water_record;
                    $tier             = $this->calculateTier($step_water);
                    $room_water_money = $this->calculateElectricityBill($water_record, $tier);
                }
                WaterBill::create([
                    'bill_id'  => $bill->id,
                    'number'   => $room->water_record,
                    'type'     => $room_water->status->value,
                    'costs'    => $room_water_money,
                    'quantity' => $room_water->quantity,
                    'step'     => $room_water->step,
                ]);

                $total_money_room =  $type_room_money + $room_services_money + $room_electric_money + $room_water_money;
                $bill = $bill->update(['total_money' => $total_money_room]); 
            }
            DB::commit();
            return redirect()->back();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e; 
        }
    }

    public function billing_room()
    {
        $rooms = Room::whereHas('contracts', function ($query) {
            $query->where('status', 1);
        })->get();

        return view('bill.billing_room', compact('rooms'));
    }

        // Calculate tier for Step
    public function calculateTier($step_electric)
    {
        $tiers        = [];
        $previousTier = 0;
        foreach ($step_electric as $limit => $rate) {
            $tiers[] = [
                'limit' => (int) $limit,
                'rate'  => (int) $rate,
                'range' => (int) $limit - $previousTier,
            ];
            $previousTier = (int) $limit;
        }

        return $tiers;
    }

        // Calculate tier for Step
    function calculateElectricityBill($number, $tiers) {
        $totalCost = 0;
        foreach ($tiers as $tier) {
            if ($number > $tier['range']) {
                $totalCost += $tier['range'] * $tier['rate'];
                $number    -= $tier['range'];
            } else {
                $totalCost += $number * $tier['rate'];
                break;
            }
        }
        return $totalCost;
    }
}
