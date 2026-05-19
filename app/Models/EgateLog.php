<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EgateLog extends Model
{
    use HasFactory;

    protected $table = 'egate_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'student_number',
        'lrn',
        'last_name',
        'first_name',
        'middle_name',
        'sex',
        'department',
        'course',
        'year_level',
        'grade_level',
        'status',
        'image',
        'logged_at',
        'gate_name',
        'ip_address',
        'remarks',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return $this->image;
        }

        $initials = collect([$this->first_name, $this->last_name])
            ->filter()
            ->map(fn (string $part) => strtoupper(mb_substr($part, 0, 1)))
            ->implode('');

        $initials = $initials ?: 'EG';
        $palette = ['#0f766e', '#1d4ed8', '#7c3aed', '#c2410c', '#be123c'];
        $index = abs(crc32($this->student_number ?: $this->id)) % count($palette);
        $background = $palette[$index];

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128">
  <rect width="128" height="128" rx="28" fill="{$background}" />
  <text x="50%" y="54%" text-anchor="middle" dominant-baseline="middle" font-family="Arial, sans-serif" font-size="42" font-weight="700" fill="#ffffff">{$initials}</text>
</svg>
SVG;

        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }
}
