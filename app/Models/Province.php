<?php

namespace App\Models;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name'];
    // اگر ستون‌های دیگری هم داری، اضافه کن
}
