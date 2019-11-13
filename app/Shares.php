<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shares extends Model
{
    protected $table = 'shares';

    protected $fillable = ['photo_id', 'user_from', 'user_to'];
}
