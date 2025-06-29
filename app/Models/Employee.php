<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'phone',
        'dni',
        'address',
        'companies_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
