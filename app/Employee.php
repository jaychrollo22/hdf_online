<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $connection = 'mysql_hr';

    public function companies()
    {
        return $this->belongsToMany('App\Company')->withTimestamps();
    }

    public function departments()
    {
        return $this->belongsToMany('App\Department')->withTimestamps();
    }
}
