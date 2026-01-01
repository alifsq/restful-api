<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    //
    protected $table = "adresses";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable =[
        "street",
        "city",
        "province",
        "country",
        "postal_code"
    ];
    public function contact(){
        return $this->belongsTo(Contact::class,'contact_id','id');
    }
}
