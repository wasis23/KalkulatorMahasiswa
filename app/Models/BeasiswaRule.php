<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeasiswaRule extends Model
{
    use HasFactory;

    protected $table = 'beasiswa_rules';

    protected $fillable = [
        'beasiswa_id',
        'komponen_biaya',
        'jenis_potongan',
        'nilai_potongan',
    ];

    protected $casts = [
        'beasiswa_id' => 'integer',
        'nilai_potongan' => 'integer',
    ];

    /**
     * Get the scholarship that owns the rule.
     */
    public function beasiswa()
    {
        return $this->belongsTo(Beasiswa::class, 'beasiswa_id');
    }
}
