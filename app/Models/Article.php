<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'title', 'summary', 'tags'];
    protected $casts = ['tags' => 'array'];

    // Add method to get the formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    public function getTagsAttribute($value)
    {
        if ($value === null) {
            return [];
        }

        return json_decode($value, true);
    }
}
