<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    protected $connection = 'mysql_hdf_visitor';
    protected $table = 'cases';

    protected $guarded = [];
}
