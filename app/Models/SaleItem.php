<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $table = 'sales_items';
    protected $fillable = [
        'sale_id',
        'medicine_name',
        'base_price',
        'sale_price',
        'quantity',
        'line_total',
        'salt_name',
        'brand_name',
        'type',
        'company',
        'margin',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
