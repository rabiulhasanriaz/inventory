<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sds_reason_com extends Model
{
    //
    protected $fillable =
    [
      'src_company_id',
      'src_reason_id',
      'src_create_date',
    ];
    protected $table = 'sds_reason_com';
    protected $primaryKey = 'src_slid';
    public $incrementing = true;
    public $timestamps = false;
}
