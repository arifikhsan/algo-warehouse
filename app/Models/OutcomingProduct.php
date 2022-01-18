<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutcomingProduct extends Model
{
    use HasFactory;
    protected $fillable = ['distributor_id', 'going_at'];
}
