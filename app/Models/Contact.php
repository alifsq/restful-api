<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contacts";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone"
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function address(){
        return $this->hasMany(Address::class,'contact_id','id');
    }

}
