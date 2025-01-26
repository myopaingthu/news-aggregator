<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    protected $casts = [
        'last_fetched_at' => 'datetime',
    ];

    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }
}
