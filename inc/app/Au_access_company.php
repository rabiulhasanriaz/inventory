<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Au_access_company extends Model
{
     //
     protected $fillable =
     [
        'access_company_id',
        'code',
        'permission_name',
        'permission_title',
        'status',
     ];
     protected $table = 'au_access_companies';
     protected $primaryKey = 'access_company_id';
     public $incrementing = true;
     public $timestamps = false;
 
}
