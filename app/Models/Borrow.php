<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    public function equipment(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Equipments::class);
    }

    //protected $table = 'post';
    protected $fillable = [
        'first_name_borrower', 'surname_borrower', 'equipment_id', 'start_date', 'end_date', 'email_borrower', 'status', 'check_contract_borrower', 'need_explanation', 'reason', 'handled_by', 'registered_by',
    ];
}
