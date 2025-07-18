<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['nom','prenom','email','phone','number_of_people','reservation_date','reservation_time','message','status'];
}
