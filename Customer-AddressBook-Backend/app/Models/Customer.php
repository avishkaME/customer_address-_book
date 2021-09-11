<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = ['name','nic'];

    protected $with = ['address','telephones'];

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function telephones()
    {
        return $this->hasMany(Telephone::class);
    }
}
