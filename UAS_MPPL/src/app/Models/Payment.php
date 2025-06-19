<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    protected static function booted()
    {
        // Saat membuat Payment baru
        static::creating(function ($payment) {
            if ($payment->order_id && !$payment->amount) {
                $payment->amount = Order::find($payment->order_id)?->total;
            }
        });

        // Saat mengedit dan mengganti order_id
        static::updating(function ($payment) {
            if ($payment->isDirty('order_id')) {
                $payment->amount = Order::find($payment->order_id)?->total;
            }
        });
    }
}
