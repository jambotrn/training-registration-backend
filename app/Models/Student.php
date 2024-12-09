<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone', 'email'];


    // public function trainings()
    // {
    //     return $this->belongsToMany(TrainingSchedule::class, 'student_trainings');
    // }
    public function trainings()
{
    return $this->belongsToMany(TrainingSchedule::class, 'student_trainings', 'student_id', 'schedule_id')
                ->withPivot('status')
                ->withTimestamps();
}
}
