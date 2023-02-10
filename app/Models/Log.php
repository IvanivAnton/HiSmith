<?php

namespace App\Models;

/**
 * @property int id
 * @property string method
 * @property string url
 * @property string response_code
 * @property array response_body
 * @property int response_timing_ms
 * @property Carbon request_datetime
 */
class Log extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = [
        'request_datetime',
        'method',
        'url',
        'response_code',
        'response_body',
        'response_timing_ms',
    ];

    protected $casts = [
        'response_body' => 'array',
        'request_datetime' => 'datetime'
    ];
}
