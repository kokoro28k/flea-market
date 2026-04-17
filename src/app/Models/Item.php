<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Category;
use App\Models\User;

class Item extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'name',
        'brand',
        'description',
        'condition',
        'price',
        'image_path',
        'status',
    ];

    protected $casts = [
    'status' => 'integer',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function purchase(){
        return $this -> hasOne(Purchase::class);
    }

    public function comments(){
        return $this -> hasMany(Comment::class);
    }

    public function likes(){
        return $this -> hasMany(Like::class);
    }

    public function categories(){
        return $this -> belongsToMany(Category::class,'category_item');
    }
}
