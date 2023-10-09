<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    public $fillable = ['notification_from_id','notification_from_name','notification_to_id','notification_message','notification_status'];
}
