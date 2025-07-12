<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Materials;
use App\Models\SchoolLevel;


class Section extends Model
{
    use HasFactory;

    protected $table = 'sectionsmat';
    protected $foreignKeys = [
        'level_id' => 'level_id',
    ];

    protected $fillable = [
        'id',
        'name',
        'level_id',
    ];
    public function level()
    {
        return $this->belongsTo(SchoolLevel::class, $this->foreignKeys['level_id'], 'id');
    }
    public function materials()
    {
        return $this->hasMany(Materials::class, 'section_id', 'id');
    }

}
