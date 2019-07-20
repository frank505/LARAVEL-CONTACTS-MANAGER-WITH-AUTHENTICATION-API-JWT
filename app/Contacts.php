<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','phonenumber','image_file'
        //'lastname','email'
    ];

}
