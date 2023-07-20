<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traffic_Regulation extends Model
{
    use HasFactory;
    protected $fillable = ['title','regulation'];
}
