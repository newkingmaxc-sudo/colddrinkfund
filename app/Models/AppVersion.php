<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    use HasFactory;

    protected $table = 'app_versions';

    protected $fillable = [
        'version_code',
        'version_name',
        'apk_url',
        'apk_size',
        'is_forced',
        'title',
        'changelog',
        'min_supported_version',
        'is_active',
        'download_count',
    ];

    protected $casts = [
        'version_code' => 'integer',
        'is_forced' => 'boolean',
        'is_active' => 'boolean',
        'download_count' => 'integer',
        'min_supported_version' => 'integer',
    ];

    public static function getLatest()
    {
        return self::whereRaw('is_active IS TRUE')
            ->orderByDesc('version_code')
            ->first();
    }
}
