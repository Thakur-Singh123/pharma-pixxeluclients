<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'purchase_manager_id',
        'vendor_id',
        'order_date',
        'notes',
        'subtotal',
        'discount_total',
        'grand_total',
        'status',
        'manager_id',
        'is_delivered'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function purchaseManager()
    {
        return $this->belongsTo(User::class, 'purchase_manager_id');
    }
}
