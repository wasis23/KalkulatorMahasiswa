<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodis';

    protected $fillable = [
        'nama_prodi',
        'jenjang',
        'ukt',
        'dpi',
        'seragam',
        'atribut',
        'pkl',
        'ta',
        'wisuda'
    ];

    protected $casts = [
        'ukt' => 'integer',
        'dpi' => 'integer',
        'seragam' => 'integer',
        'atribut' => 'integer',
        'pkl' => 'integer',
        'ta' => 'integer',
        'wisuda' => 'integer',
    ];
}
