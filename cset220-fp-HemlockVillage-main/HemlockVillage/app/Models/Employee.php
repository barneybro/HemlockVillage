<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\ModelHelper;

class Employee extends Model
{
    protected $fillable = [
        "user_id",
        "salary"
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, "doctor_id");
    }

    public function rosters()
    {
        return $this->hasMany(Roster::class);
    }

    public static function getId($userID)
    {
        return ModelHelper::getId(Employee::class, "user_id", $userID);
    }

  

}
