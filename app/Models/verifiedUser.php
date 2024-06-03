<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verifiedUser extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
}
