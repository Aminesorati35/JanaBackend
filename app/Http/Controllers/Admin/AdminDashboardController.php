<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu_item;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(){
        $TotalReservation = Reservation::count();
        $TotalPendingReservation = Reservation::where('status','pending')->count();
        $TotalCancelledReservation = Reservation::where('status','cancelled')->count();
        $TotalConfirmedReservation = Reservation::where('status','confirmed')->count();
        $TotalMenu_Items = Menu_item::count();
        return response()->json([
            "TotalReservation"=>$TotalReservation,
            "TotalPendingReservation"=>$TotalPendingReservation,
            "TotalCancelledReservation"=>$TotalCancelledReservation,
            "TotalConfirmedReservation"=>$TotalConfirmedReservation,
            "TotalMenu_Items"=>$TotalMenu_Items

        ]);
    }
}
