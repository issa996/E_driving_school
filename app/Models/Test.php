<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $fillable = ['name','driving_school_id'];

    public function questions(){
        return $this->hasMany(Question::class);
    }
    public function school(){
        return $this->belongsTo(Driving_school::class);
    }
    public function students(){
        return $this->belongsToMany(Driving_student::class)->withPivot('is_passed');
    }
    public static function boot()
{
    parent::boot();

    static::deleting(function ($test) {
        $students = $test->students;

        if ($students->isNotEmpty()) {
            $test->students()->detach();
            $defaultTest = static::find(1);
            //$defaultTest->students()->sync($students->pluck('id')->toArray());
        }
        
    });
    self::deleting(function($test) { // before delete() method call this
        $test->questions()->each(function($question) {
           $question->delete(); // <-- direct deletion
        });
        // do the rest of the cleanup...
   });
    

}

}
