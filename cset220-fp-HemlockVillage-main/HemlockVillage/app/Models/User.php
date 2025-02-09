<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Helpers\ModelHelper;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "first_name",
        "last_name",
        'email',
        "date_of_birth",
        "phone_number",
        'password',
        "role_id",
        "approved"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public static function getRoleId($id)
    {
        return ModelHelper::getRow(User::class, "id", $id)->role_id ?? null;
    }

    public static function getId($email)
    {
        return ModelHelper::getId(User::class, "email", $email);
    }
}
