<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'mobile',
        'address',
        'image',
        'status',
        'user_type',
        'phone',
        'territory',
        'city',
        'state',
        'joining_date',
        'employee_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password'          => 'hashed',
        ];
    }

    public function mrs()
    {
        return $this->belongsToMany(User::class, 'manager_mr', 'manager_id', 'mr_id');
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'manager_mr', 'mr_id', 'manager_id');
    }


    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_mr_assignments', 'mr_id', 'doctor_id'              
        );
    }

    public function patients()
    {
        return $this->hasMany(Patient::class, 'mr_id');
    }
    
    public function interest(){
        return $this->hasMany(VisitPlanInterest::class, 'mr_id');
    }

    public function attendance(){
        return $this->hasMany(MRAttendance::class, 'user_id');
    }

}
