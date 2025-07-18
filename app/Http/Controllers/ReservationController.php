<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
       $perPage = $request->per_page ?? 10;
       $query = Reservation::query()->orderBy('created_at', 'desc');
       if($request->has('status')&&$request->status!=="all"){
        $query->where('status',$request->status);
       }
       if($request->has('reservation_date')&&$request->reservation_date!==""){
        $query->where('reservation_date',$request->reservation_date);
       }
       if($request->has('email')&&$request->email!==""){
        $query->where('email','LIKE',$request->email."%");
       }
       if ($request->has('nom_complet') && $request->nom_complet !== "") {
    // Split full name by spaces
    $nameParts = explode(' ', $request->nom_complet);

    $query->where(function($q) use ($nameParts) {
        foreach ($nameParts as $part) {
            $q->where(function($q2) use ($part) {
                // Check if either 'nom' or 'prenom' contains the part (case insensitive)
                $q2->where('nom', 'LIKE', "%{$part}%")
                   ->orWhere('prenom', 'LIKE', "%{$part}%");
            });
        }
    });
}


       $reservations = $query->paginate($perPage);
       return $reservations;
    }



    public function show($id){
        $reservation = Reservation::findOrFail($id);
        if($reservation->status==='cancelled'){
            return response()->json(['status'=>$reservation->status,'nom'=>$reservation->nom,'prenom'=>$reservation->prenom]);
        }
        return $reservation;
    }
    public function store(Request $request){
        $request->validate([
            "nom"=>"required|string|max:255",
            "prenom"=>"required|string|max:255",
            "email"=>"required|email",
            "phone"=>"required|max:255",
            "number_of_people"=>"required|integer",
            "reservation_date"=>"required|date",
            "reservation_time"=>"required|date_format:H:i",
            "message"=>"max:500",
        ]);
        $existing = Reservation::where('reservation_date',$request->reservation_date)->where('reservation_time',$request->reservation_time)->exists();
        if($existing){
            return response()->json([
            'message' => 'Désolé, une réservation existe déjà à ce moment-là.',
        ], 409);
        }
        $data = Reservation::create([
            'nom'=>strtolower($request->nom),
            'prenom'=>strtolower($request->prenom),
            'email'=>$request->email,
            'phone'=>$request->phone,
            'number_of_people'=>$request->number_of_people,
            'reservation_date'=>$request->reservation_date,
            'reservation_time'=>$request->reservation_time,
            'message'=>$request->message,
        ]);
        return response()->json([
            'message'=>'Votre réservation a été effectuée.',
            'reservation' => $data
        ]);


    }
    public function destroy($id){
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return response()->json([
            'message' => 'La réservation de ' . ucfirst($reservation->nom) . ' ' . ucfirst($reservation->prenom) . ' a été supprimée avec succès.',
        ]);
    }
    public function cancelReservation(Request $request ){
        $reservation = Reservation::findOrFail($request->id);
        if($reservation->status!=='confirmed' && $reservation->status!=='cancelled'){
            $reservation->status="cancelled";
            $reservation->save();
            return response()->json(['message' => 'Reservation cancelled successfully','reservation'=>$reservation]);
        }else{
            return response()->json([
            'message' => 'La réservation ne peut pas être annulée car elle est déjà confirmée ou annulée.'
        ], 409);
        }
    }
    public function changeReservationStatus ( Request $request, $id){
        $reservation = Reservation::findOrFail($id);
        $reservation->status=$request->status;
        $reservation->save();
        return response()->json([
            'message'=>'Le statut de la réservation de '.ucfirst($reservation->nom)." ".ucfirst($reservation->prenom)." a été mis à jour avec succès",
            'status'=>$reservation->status
        ]);
    }
}
