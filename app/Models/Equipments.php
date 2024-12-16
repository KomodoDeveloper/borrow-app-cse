<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipments extends Model
{
    public function categories()
    {
        return $this->belongsToMany(\App\Models\Categories::class);
    }

    public function borrows()
    {
        return $this->hasMany(\App\Models\Borrow::class);
    }

    public function aborrows()
    {
        return $this->hasMany(\App\Models\ArchiveBorrow::class);
    }

    //protected $table = 'post';
    protected $fillable = [
        'name', 'description', 'image', 'seriallNumber', 'ci_number', 'availability', 'internal', 'is_out_of_service', 'product_year', 'purchase_date', 'expiration_date',
    ];
}
