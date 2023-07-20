<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'time', 'date','driving_school_id' , 'timestampes'];
    public function school(){
        return $this->hasMany(Driving_school::class);
    }

}
