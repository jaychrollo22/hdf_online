<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class CaseCloseContact extends Model 
{
    protected $connection = 'mysql_hdf_visitor';
    
    protected $guarded = [];

    use SoftDeletes;

}
