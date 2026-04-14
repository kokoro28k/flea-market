<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\User;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'building',
    ];

    public function purchases(){
        return $this->hasMany(Purchase::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
