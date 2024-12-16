<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    public function equipments() {
        return $this->belongsToMany(\App\Models\Equipments::class);
    }

    //protected $table = 'post';
    protected $fillable = [
        'name', 'description'
    ];
}
