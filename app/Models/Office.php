<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    public $fillable = ['office_name','office_abbrev','office_description','office_head','office_type','status'];
    use HasFactory;

    /**
     * Define an inverse one-to-many relationship with the RequestedDocument model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function requestedDocument()
    {
        return $this->belongsTo(RequestedDocument::class, 'recieved_offices');
    }

    public function requestedDocuments()
    {
        return $this->hasMany(RequestedDocument::class, 'requestor', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'office_id');
    }

   

}
