<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $dates = ['start', 'end'];
    
    use HasFactory;
    public $fillable = ['title','start','time','end','classname','url','event_notes'];
}
