<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArchiveBorrow extends Model
{
    public function equipment() {
        return $this->belongsTo('App\Models\Equipments');
    }

    public function a_equipment() {
        return $this->belongsTo('App\Models\Equipments');
    }

    //protected $table = 'post';
    protected $fillable = [
        'a_first_name_borrower', 'a_surname_borrower', 'a_equipment_id', 'a_email_borrower','a_start_date','a_end_date', 'a_status', 'origin_id'
    ];
}
