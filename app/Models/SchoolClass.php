<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function students(){
        return $this->hasMany(User::class,"school_class_id","id");
    }

    public function class_teacher(){
        return $this->belongsTo(User::class,"class_teacher_id","id");
    }
}
