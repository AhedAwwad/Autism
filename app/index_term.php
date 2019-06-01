<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class index_term extends Model
{
    public    $timestamps    = false;
    protected  $table ="index_terms";
    protected $fillable=array('id','file_id','word_id','freq','tf','tfidf');
}
