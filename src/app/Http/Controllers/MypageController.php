<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $page = $request->query('page','selling');

        $items = Item::where('user_id',$user->id)->get();
        $purchases = Purchase::where('user_id',$user->id)->get();

        return view('profiles.profile',compact('user','page','items','purchases'));
    }    
    
}
