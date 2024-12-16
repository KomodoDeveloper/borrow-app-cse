<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchiveBorrow extends Model
{
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Equipments::class);
    }

    public function a_equipment(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Equipments::class);
    }

    //protected $table = 'post';
    protected $fillable = [
        'a_first_name_borrower', 'a_surname_borrower', 'a_equipment_id', 'a_email_borrower', 'a_start_date', 'a_end_date', 'a_status', 'origin_id',
    ];
}
