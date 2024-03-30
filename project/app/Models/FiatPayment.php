<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiatPayment extends Model
{
    protected $fillable = ['sum', 'sum_in_currency', 'currency', 'course'];
}
