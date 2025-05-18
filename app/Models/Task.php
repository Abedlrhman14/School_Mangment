<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =['teacher_id','title','description','file_path','book_path','class_id'];

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }
    public function subject() {
        return $this->belongsTo(Subject::class,'subject_id');

    }
    public function class(){
        return $this->belongsTo(Classes::class,'class_id');
    }
}
