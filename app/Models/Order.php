<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'notes',
        'total_price',
        'status',
        'user_id',
        'order_type',
        'table_id',
        'maps_link',
        'tracking_token',
        'payment_method',
        'payment_status',
        'midtrans_order_id',
        'snap_token',
        'paid_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
