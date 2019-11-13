<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    protected $table = 'photos';

    protected $fillable = ['url', 'name', 'owner_id'];

    public function getUrlAttribute($value)
    {
        return 'http://192.168.20.1/' . $value;
    }
}
