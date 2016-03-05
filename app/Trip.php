<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;


class Trip extends Moloquent
{
    //
    protected $collection = 'trips';

    protected $fillable = [
        'title', 'description', 'userId',
    ];
}
