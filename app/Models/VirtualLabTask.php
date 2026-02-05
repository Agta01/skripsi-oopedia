<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualLabTask extends Model
{
    protected $fillable = [
        'material_id',
        'title',
        'slug',
        'description',
        'template_code',
        'solution_code',
        'test_cases',
        'difficulty',
        'created_by'
    ];

    protected $casts = [
        'test_cases' => 'array',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
