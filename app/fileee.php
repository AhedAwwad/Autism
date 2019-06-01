<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class fileee extends Model
{
    public    $timestamps    = false;
    protected  $table ="fileees";
    protected $fillable=array('id','title','num_of_word');
}
