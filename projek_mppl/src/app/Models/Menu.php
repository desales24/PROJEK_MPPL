<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'available'];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
