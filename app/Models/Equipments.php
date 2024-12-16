<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Equipments extends Model
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Categories::class);
    }

    public function borrows(): HasMany
    {
        return $this->hasMany(\App\Models\Borrow::class);
    }

    public function aborrows(): HasMany
    {
        return $this->hasMany(\App\Models\ArchiveBorrow::class);
    }

    //protected $table = 'post';
    protected $fillable = [
        'name', 'description', 'image', 'seriallNumber', 'ci_number', 'availability', 'internal', 'is_out_of_service', 'product_year', 'purchase_date', 'expiration_date',
    ];
}
