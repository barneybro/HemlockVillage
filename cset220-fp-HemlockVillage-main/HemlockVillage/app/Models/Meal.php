<?php

namespace App\Models;

use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = [
        "patient_id",
        "meal_date",
        "breakfast",
        "lunch",
        "dinner"
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the row associated with a patient and meal date
     */
    public static function getRow($patientId, $date)
    {
        return ModelHelper::getRowWithConditions(Meal::class, [
            [ "patient_id", "=", $patientId ],
            [ "meal_date", "=", $date ]
        ]);
    }

    /**
     * Get all the meals of a patient
     */
    public static function getRows($patientId)
    {
        return ModelHelper::getRowWithConditions(Meal::class, [
            [ "patient_id", "=", $patientId ]
        ]);
    }
}
