<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Perlindungan Integritas Log Audit (Append-Only / Anti-Manipulasi):
     * Mencegah data log audit diedit atau dihapus oleh siapapun di tingkat model/aplikasi.
     */
    protected static function booted()
    {
        static::updating(function ($log) {
            throw new \RuntimeException('Keamanan Audit: Data Log Audit bersifat permanen (Immutable) dan dilarang untuk diedit.');
        });

        static::deleting(function ($log) {
            throw new \RuntimeException('Keamanan Audit: Data Log Audit bersifat Append-Only dan dilarang untuk dihapus demi integritas kepatuhan audit.');
        });
    }

    /**
     * Helper praktis untuk mencatat log audit dari mana saja.
     */
    public static function record(string $action, string $description): self
    {
        $user = Auth::user();

        return self::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name ?? 'Guest/Sistem',
            'user_role'   => $user?->roles->first()?->name ?? 'None',
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip() ?? '127.0.0.1',
            'user_agent'  => request()->userAgent() ?? 'CLI/Browser',
        ]);
    }
}
