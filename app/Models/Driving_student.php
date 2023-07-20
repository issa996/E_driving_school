<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driving_student extends Authenticatable
{
    use HasApiTokens,HasFactory,Notifiable;
    protected $fillable = ['id','full_name','mobile_number','email','password','student_address','is_passed','driving_school_id','status','is_registed_with_school','nationality_number','born_date','gender','appointment_test_id'];
    protected $hidden = ['password'];

    public function driving_school()
    {
        return $this->belongsTo(Driving_school::class);
    }
    public function tests(){
        return  $this->belongsToMany(Test::class,'driving_student_test')->withPivot('is_passed');
    }
    /*public function appointment_test(){
        $this->belongsTo(Appointment_test::class);
    }*/
}
