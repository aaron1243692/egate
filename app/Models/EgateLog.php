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
        'rfid',
        'name',
        'role',
        'email',
        'contact',
        'sex',
        'department',
        'course',
        'school_level',
        'grade_level',
        'image',
    ];

    protected $appends = [
        'first_name',
        'middle_name',
        'last_name',
        'year_level',
    ];

    public function getFirstNameAttribute(): string
    {
        if (array_key_exists('first_name', $this->attributes)) {
            return (string) ($this->attributes['first_name'] ?? '');
        }

        $parts = $this->nameParts();

        if (count($parts) <= 1) {
            return $parts[0] ?? '';
        }

        return $parts[0];
    }

    public function getMiddleNameAttribute(): string
    {
        if (array_key_exists('middle_name', $this->attributes)) {
            return (string) ($this->attributes['middle_name'] ?? '');
        }

        $parts = $this->nameParts();

        return count($parts) > 2 ? implode(' ', array_slice($parts, 1, -1)) : '';
    }

    public function getLastNameAttribute(): string
    {
        if (array_key_exists('last_name', $this->attributes)) {
            return (string) ($this->attributes['last_name'] ?? '');
        }

        $parts = $this->nameParts();

        return count($parts) > 1 ? end($parts) : '';
    }

    public function getYearLevelAttribute(): string
    {
        return (string) ($this->attributes['year_level'] ?? $this->attributes['school_level'] ?? $this->attributes['grade_level'] ?? '');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return $this->image;
        }

        $initials = collect([$this->first_name, $this->last_name ?: $this->name])
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

    private function nameParts(): array
    {
        $name = trim((string) ($this->attributes['name'] ?? ''));

        if ($name === '') {
            return [];
        }

        if (str_contains($name, ',')) {
            return array_values(array_filter(
                array_map('trim', explode(',', $name)),
                fn (string $part) => $part !== ''
            ));
        }

        return preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    }
}
