<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;


class ProfileController extends Controller
{
    public function edit()
    {
       $user = Auth::user();
       $address = $user->address;
       
    return view('profiles.profile-edit',compact('user','address'));

    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
       
        $validated = $request->validated();
    
        
        if ($request->hasFile('image')){
            $path = $request->file('image')->store('user_images','public');
        }else{
            $path = $user->image;
        }

        $user->update([
            'image' => $path,
            'name' => $validated['name'],
        ]);

        $user->address()->updateOrCreate([
            'user_id' => $user->id],
            ['postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ]);

        $user->profile_completed = true;
        $user->save();

        return redirect('/');
    }
}