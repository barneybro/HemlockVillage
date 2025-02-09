<?php

namespace App\Models;

use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;

class PrescriptionStatus extends Model
{
    protected $fillable = [
        "appointment_id",
        "prescription_date",
        "morning",
        "afternoon",
        "night"
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the row associated with the prescription id
     */
    public static function getRow($prescriptionId)
    {
        return ModelHelper::getrow(PrescriptionStatus::class, "prescription_id", $prescriptionId);
    }

}
