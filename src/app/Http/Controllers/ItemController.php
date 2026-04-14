<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query ('tab','recommend');

        /*マイリストタブ*/
        if ($tab === 'mylist'){
            //　未ログインなら空を返す
            if (!Auth::check()) {
                $items = collect();
            } else {
                $query = Item::whereIn('id',Auth::user()->likes->pluck('item_id'));

                if ($request->filled('keyword')) {
                    $query->where('name', 'like', '%' . $request->keyword . '%');
                }

                $items = $query->latest()->get();
            }
        }
        /*おすすめタブ*/
        else {
            $query = Item::query();

            if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
            }

            if (Auth::check())
            { 
                $query->where('user_id','<>',Auth::id());
            }

            $items = $query->latest()->get();
        }


        return view('items.list',compact('items','tab'));
    }
    
    public function show($item_id)
    {
        $item = Item::with(['categories', 'comments.user', 'likes'])
                ->findOrFail($item_id);

        $isLiked = false;

        if (Auth::check()) {
            $isLiked = $item->likes->contains('user_id', Auth::id());
        }

        $likeCount = $item->likes->count();
        $commentCount = $item->comments->count();

        //  商品状態
        $conditions = [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];

        return view('items.detail', compact(
            'item',
            'isLiked',
            'likeCount',
            'commentCount',
            'conditions'
        ));

    }

    public function create()
    {
        $categories = Category::all();
        return view('items.sell',compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {

        $validated = $request->validated();

        $path = $request->file('image_path')->store('images', 'public');

        $item = Item::create([
            'user_id' => Auth::id(), 
            'image_path' => $path,
            'condition' => $validated['condition'],
            'name' => $validated['name'],
            'brand' => $validated['brand'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'status' => 0,
        ]);

        $item->categories()->attach($validated['category_id']);

        return redirect()->route('items.index');  
    }

  

    public function commentStore(CommentRequest $request,$item_id)
    {
        $validated = $request->validated();

        Comment::create([
            'item_id' => $item_id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
        ]);

        return back();  
    }
}

