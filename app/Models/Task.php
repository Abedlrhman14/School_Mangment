<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable =['teacher_id','title','description','file_path'];

    public function teacher(){
        return $this->belongsTo(User::class,'teacher_id');
    }
}
