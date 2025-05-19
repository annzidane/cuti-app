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

    protected $appends = [
        'total_leave_this_year',
        'remaining_leave_quota',
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
    
    public function getTotalLeaveThisYearAttribute()
    {
        $year = now()->year;

        return $this->leaves()
            ->whereYear('start_date', $year)
            ->sum('total_days');
    }

    public function getRemainingLeaveQuotaAttribute()
    {
        $maxQuota = 12;
        return max(0, $maxQuota - $this->total_leave_this_year);
    }
}
