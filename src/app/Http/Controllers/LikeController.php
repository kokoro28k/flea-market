<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class LikeController extends Controller
{
    public function store(Item $item)
    {
        $user = Auth::user();

        if (!$item->likes()->where('user_id', $user->id)->exists()) {
            $item->likes()->create([
                'user_id' => $user->id,
            ]);
        }

        return back();
    }

    public function destroy(Item $item)
    {
        $user = Auth::user();

        $item->likes()->where('user_id', $user->id)->delete();

        return back();
    }
}
