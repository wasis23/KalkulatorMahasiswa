<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beasiswa extends Model
{
    use HasFactory;

    protected $table = 'beasiswas';

    protected $fillable = [
        'nama_beasiswa',
    ];

    /**
     * Get the rules for the scholarship.
     */
    public function rules()
    {
        return $this->hasMany(BeasiswaRule::class, 'beasiswa_id');
    }
}
