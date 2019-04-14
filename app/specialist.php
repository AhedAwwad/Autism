<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class specialist extends Authenticatable
{   
	public    $timestamps    = false;
	public    $remember_token =false;
	protected $primaryKey    = 'sp_id';
    public $table = "specialists";
    protected $fillable = [
        'name', 'sp_user_name', 'password',
    ];
    
     protected $hidden = [
        'password', 'remember_token',
    ];

}
