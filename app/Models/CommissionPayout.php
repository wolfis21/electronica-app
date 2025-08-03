<?php

// app/Models/CommissionPayout.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CommissionPayout extends Model
{
    protected $guarded = []; // Permite asignación masiva
    public function user() { return $this->belongsTo(User::class); }
    public function payer() { return $this->belongsTo(User::class, 'paid_by_user_id'); }
}