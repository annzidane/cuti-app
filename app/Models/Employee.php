<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;

class Employee extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'gender',
        'dob'
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
