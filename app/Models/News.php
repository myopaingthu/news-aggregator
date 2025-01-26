<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'data_source_id',
        'author',
        'title',
        'category',
        'slug',
        'description',
        'content',
        'url',
        'image_url',
        'published_at'
    ];

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function dataSource()
    {
        return $this->belongsTo(DataSource::class);
    }

    /**
     * Scope to filter news articles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['date'])) {
            $query->whereDate('published_at', $filters['date']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['source'])) {
            $query->where('source_id', $filters['source']);
        }

        if (!empty($filters['categories'])) {
            $query->whereIn('category', $filters['categories']);
        }

        if (!empty($filters['sources'])) {
            $query->whereIn('source_id', $filters['sources']);
        }

        if (!empty($filters['authors'])) {
            $query->whereIn('author', $filters['authors']);
        }

        return $query;
    }
}
