<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Blog extends Model
{
    protected $table = 'blog';
    protected $primaryKey = 'blog_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
