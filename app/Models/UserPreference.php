<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'preferences' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'preferences'];

    /**
     * Get the user that owns the preference.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
