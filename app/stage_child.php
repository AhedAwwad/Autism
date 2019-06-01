<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class stage_child extends Model
{
    
	public    $timestamps    = false;
	public    $remember_token =false;
	protected $primaryKey    = 'st_ch_id';
    public $table = "stage_children";
    protected $fillable = [
        'notes'
    ];
    
}
