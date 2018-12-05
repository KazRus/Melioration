<?php

namespace App\Models;

use App\Http\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Comment extends Model
{
    protected $table = 'comment';
    protected $primaryKey = 'comment_id';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
