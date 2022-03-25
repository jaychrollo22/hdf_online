<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseNurseNotifier extends Model
{
    protected $connection = 'mysql';
    protected $table = 'case_nurse_notifier';
}
