<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CryptoPayment extends Model
{
    protected $fillable = ['sum', 'sum_in_currency', 'currency', 'course', 'purchase_date', 'sale_date'];
}
