<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $casts = [
        'preferences' => 'array',
    ];

    protected $fillable = ['user_id', 'preferences'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
