<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sliders extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'status',
        'release_start',
        'release_finish',
        'order',
        'row_1_text',
        'row_1_color',
        'row_1_css',
        'row_2_text',
        'row_2_color',
        'row_2_css',
        'button_text',
        'button_url',
        'button_target',
        'button_color',
        'button_css'
    ];
}