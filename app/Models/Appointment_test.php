<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment_test extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'time', 'date','driving_school_id','driving_student_id' , 'timestampes'];
    public function school(){
        return $this->belongsTo(Driving_school::class);
    }
    /*public function students(){
        return $this->hasMany(Driving_student::class);
    }*/

}
