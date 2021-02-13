<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function class_teacher(){
        return $this->hasMany(User::class,"id","school_class_id");
    }
}
