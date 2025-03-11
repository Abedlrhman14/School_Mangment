<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $primaryKey = 'subject_id';
    public $incrementing = false ;
    protected $keyType = 'string' ;
    protected $fillable = ['subject_id','name'];

    public function teachers(){
        return $this->belongsToMany(User::class,'teacher_subjects','subject_id','teacher_id');
    }

    public function tasks(){
        return $this->hasMany(Task::class,'subject_id');
    }

}
