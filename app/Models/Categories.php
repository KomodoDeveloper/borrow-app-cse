<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Categories extends Model
{
    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Equipments::class);
    }

    //protected $table = 'post';
    protected $fillable = [
        'name', 'description',
    ];
}
