<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = ['name', 'teacher_id','grade_id'];

    public function students(){
        return $this->belongsToMany(User::class,'class_student','class_id','student_id',);
    }
    public function tasks(){
        return $this->hasMany(Task::class,'class_id');
    }
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function grade(){
        return $this->belongsTo(Grade::class);
       }
}
