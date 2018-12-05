<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
