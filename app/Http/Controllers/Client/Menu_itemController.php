<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Menu_item;
use Illuminate\Http\Request;

class Menu_itemController extends Controller
{
    public function index(){
        $Menu_items = Menu_item::orderBy('created_at',"desc")->paginate(20);
        $categories = Menu_item::whereNotNull('category')->select('category')->distinct()->pluck('category');
        return response()->json([
            'Menu_items'=>$Menu_items,
            'Categories'=>$categories
        ]);
    }
    public function getMenuByCategory(Request $request){
        $menu_items = Menu_item::where('category',$request->category)->get();
        return $menu_items;
    }
}
