<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $table = 'actions';

    protected $fillable = ['name'];

    public $timestamps = false;

    // public function regions()
    // {
    //     return $this->belongsToMany('App\Models\Region');
    // }
    public function name()
    {
        return $this->name;
    }
}
