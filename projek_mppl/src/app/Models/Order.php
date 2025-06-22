<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'table_id',
        'order_date',
        'status',
        'proof_of_payment',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    protected static function booted()
    {
        static::saving(function ($order) {
            $total = 0;

            foreach ($order->order_items as $item) {
                // ambil harga dari menu
                $menu = Menu::find($item->menu_id);
                $item->price = $menu->price ?? 0;
                $item->save();

                $total += $item->quantity * $item->price;
            }

            $order->total = $total;
        });
    }
}
