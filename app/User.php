<?php

namespace App;

//use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class User extends Moloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'tripIds',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function addTrip($tripId)
    {
        $tripIds = $this->tripIds;
        $tripIds[] = $tripId;
        $this->tripIds = $tripIds;
        $this->save();
    }
}
