<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use App\Http\Requests\AddressRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{

    public function buy($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $payment_method = $user->payment_method;
        $address_id = $user->address->id;

        return view('purchases.purchase', ['item'=> $item,'user'=> $user,'payment_method'=> $payment_method,'item_id'=> $item_id,
        'address_id' => $address_id]);
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $validated = $request->validated();

        $payment_method = $validated['payment_method'];
        $address_id = $validated['address_id'];
        
        $item = Item::findOrFail($item_id);
      
        //すでに購入済みかを確認 
        if ($item->status !== 0) {
            return redirect()->back();
        }
        
        session([
            'payment_method' => $payment_method,
            'address_id' => $address_id,
        ]);

         // 決済処理stripeに接続
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = Session::create([
            'ui_mode' => 'hosted_page',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name, ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success',['item_id' => $item_id]),
            'cancel_url' => route('items.buy',['item_id' => $item_id])      
        ]);

        return redirect($session->url);
    }

    public function success($item_id)
    {
        $payment_method = session('payment_method');
        $address_id = session('address_id');
        
        DB::beginTransaction();

        try {
            $item = Item::findOrFail($item_id);

            if($item->status === 1){
                return redirect()->route('items.index');
            }

            $item->status = 1;
            $item->save();

            Purchase::create([
                'user_id' => auth()->id(),
                'item_id' => $item_id,
                'payment_method' => $payment_method,
                'address_id' => $address_id,
            ]);

            $item->refresh();
            
            DB::commit();

            return redirect()->route('items.index');
    
            } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('items.index');
            }
    }      

    public function edit($item_id)
    {
        $user = Auth::user();

        $address = $user->address;

        return view('profiles.address',compact('item_id','address'));
    }

    public function update(AddressRequest $request,$item_id)
    {   
        $user = Auth::user();

        $address = $user->address;

        $address->update($request->validated());

        return redirect()->route('items.buy',['item_id' => $item_id]);
    }

    public function calculate(Request $request,$item_id)
    {
        $item = Item::findOrFail($item_id);   
        $user = Auth::user();

        $payment_method = $request->input('payment_method');
        $address_id = $user->address->id;

        return view('purchases.purchase',compact('item','user','payment_method','item_id','address_id'));

    }
} 