<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class word extends Model
{
    public    $timestamps    = false;
    protected  $table ="words";
    protected $fillable=array('id','word','count','idf');
}
