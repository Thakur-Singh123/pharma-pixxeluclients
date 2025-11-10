<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManagerPurchaseManager extends Model
{
    use HasFactory;

    protected $fillable = ['manager_id', 'purchase_manager_id'];
}
