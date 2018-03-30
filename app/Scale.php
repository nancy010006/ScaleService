<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scale extends Model
{
    protected $fillable = [
        'name','dimension','level','author'
    ];
}