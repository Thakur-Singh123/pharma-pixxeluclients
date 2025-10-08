<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';
       protected $fillable = [
        'user_id',
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
        'status',
        'approved_by',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
