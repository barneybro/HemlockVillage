<?php

namespace App\Models;

use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;

class Roster extends Model
{
    protected $fillable = [
        "date_assigned",
        "supervisor_id",
        "doctor_id",
        "caregiver_one_id",
        "caregiver_two_id",
        "caregiver_three_id",
        "caregiver_four_id"
    ];

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, "supervisor_id");
    }

    public function doctor()
    {
        return $this->belongsTo(Employee::class, "doctor_id");
    }

    public function caregiverOne()
    {
        return $this->belongsTo(Employee::class, 'caregiver_one_id');
    }

    public function caregiverTwo()
    {
        return $this->belongsTo(Employee::class, 'caregiver_two_id');
    }

    public function caregiverThree()
    {
        return $this->belongsTo(Employee::class, 'caregiver_three_id');
    }

    public function caregiverFour()
    {
        return $this->belongsTo(Employee::class, 'caregiver_four_id');
    }
    /**
     * Get the roster for a specific date
     */
    public static function getRosterByDate($date)
    {
        return ModelHelper::getRow(Roster::class, "date_assigned", $date);
    }
}
