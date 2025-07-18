<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu_item extends Model
{
    protected $fillable = ['name','description','price','category','image'];
}
