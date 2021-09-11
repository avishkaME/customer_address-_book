<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telephone extends Model
{
    use HasFactory;

    protected $table = 'telephones';

    protected $fillable = ['telephone','customer_id'];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
