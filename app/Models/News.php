<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property string guid
 * @property string title
 * @property string description
 * @property string author
 * @property string image_link
 * @property Carbon publication_datetime
 */
class News extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'guid',
        'title',
        'description',
        'author',
        'image_link',
        'created_at'
    ];

    protected $casts = [
      'publication_datetime' => 'datetime'
    ];
}
