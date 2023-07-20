<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['id','body','image','test_id'];

    public function test(){
        return $this->belongsTo(Test::class);
    }
    public function answers(){
        return $this->hasMany(Answer::class);
    }
    public static function boot() {
        parent::boot();
        self::deleting(function($question) { // before delete() method call this
             $question->answers()->each(function($answer) {
                $answer->delete(); // <-- direct deletion
             });
             // do the rest of the cleanup...
        });
    }
}
