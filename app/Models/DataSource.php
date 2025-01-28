<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'last_fetched_at' => 'datetime',
    ];

    /**
     * Get the sources associated with the data source.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    /**
     * Get the news associated with the data source.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }
}
