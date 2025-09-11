<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
       protected $fillable = [
        'name',
        'email',
        'designation',
        'phone',
        'address',
        'doctor_name',
        'prescription_file',
        'total_amount',
        'discount',
        'net_amount',
        'payment_mode',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}
