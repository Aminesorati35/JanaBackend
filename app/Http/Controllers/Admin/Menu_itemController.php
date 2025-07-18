<?php

namespace App\Http\Controllers\Admin;

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
    public function store(Request $request){
        $request->validate([
            'name'=>"required|string|max:255",
            'description'=>"required|string|max:500",
            'price'=>'required|decimal:0,2|min:0',
            'category'=>'required|string|max:255',
            'image'=>'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('images/menu','public');
        }
        else{
            return response()->json(['error' => 'Image upload failed.'], 400);
        }
        $menu = Menu_item::create([
            'name'=>strtolower($request->name),
            'description'=>$request->description,
            'price'=>$request->price,
            'category'=>strtolower($request->category),
            'image'=>$imagePath
        ]);
        return response()->json([
            'message'=>'Menu Created successfully',
            'menu' => $menu,
            'image_url' => asset('storage/' . $imagePath)
        ],201);
    }
    public function show(Menu_item $menu_item ){
            return $menu_item;
    }
    public function update(Request $request, Menu_item $menu_item)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:500',
        'price' => 'required|numeric|min:0',
        'category' => 'required|string|max:255',
        'image' => $request->hasFile('image')
            ? 'image|mimes:jpeg,png,jpg,webp|max:2048'
            : '',
    ]);

    $updateData = [
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'category' => $request->category,
    ];

    // Only update image if new one is provided
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images/menu', 'public');
        $updateData['image'] = $imagePath;
    }

    $menu_item->update($updateData);

    return response()->json([
        'message' => 'Menu item updated successfully',
        'menu_item' => $menu_item
    ]);
}
    public function destroy(Menu_item $menu_item){
        $menu_item->delete();
        return ['message'=>'Menu Deleted successfully'];
    }
    public function MenuClients(){
        $Menu_items = Menu_item::orderBy('created_at',"desc")->paginate(20);
        $categories = Menu_item::whereNotNull('category')->select('category')->distinct()->pluck('category');
        return response()->json([
            'Menu_items'=>$Menu_items,
            'Categories'=>$categories
        ]);
    }

}
