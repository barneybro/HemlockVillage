<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\ModelHelper;

class Appointment extends Model
{
    protected $fillable = [
        "patient_id",
        "date_scheduled",
        "appointment_date",
        "doctor_id",
        "status",
        "comment",
        "morning",
        "afternoon",
        "night"
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, "patient_id");
    }

    public function doctor()
    {
        return $this->belongsTo(Employee::class, "doctor_id");
    }

    public function prescriptions()
    {
        return $this->hasMany(PrescriptionStatus::class);
    }

    public static function getId($patientID, $appointmentDate)
    {
        return ModelHelper::getIdWithConditions(Appointment::class, [
            [ "patient_id", "=", $patientID ],
            [ "appointment_date", "=", $appointmentDate ]
        ]);
    }

    /**
     * Get all the appointments of a patient
     */
    public static function getPatientAppointments($patientId)
    {
        return ModelHelper::getRowsWithConditions(Appointment::class, [
            ["patient_id", "=", $patientId]
        ]);
    }

    /**
     * Get the appointment for a patient on a specific date
     */
    public static function getPatientAppointment($patientId, $appointmentDate)
    {
        return ModelHelper::getRowWithConditions(Appointment::class, [
            ["patient_id", "=", $patientId],
            [ "appointment_date", "=", $appointmentDate ]
        ]);
    }

    /**
     * Get all the appointments of a doctor
     */
    public static function getDoctorAppointments($doctorId)
    {
        return ModelHelper::getRowsWithConditions(Appointment::class, [
            ["doctor_id", "=", $doctorId]
        ]);
    }
}
