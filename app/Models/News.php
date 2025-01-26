<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id', 'data_source_id', 'author', 'title', 'category', 
        'slug', 'description', 'content', 'url', 'image_url', 'published_at'
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }
}
