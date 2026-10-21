<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'description',
        'amount',
        'status',
        'inputer_id',
        'checker_id',
        'approver_id',
        'rejection_note',
        'submitted_at',
        'checked_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'submitted_at' => 'datetime',
            'checked_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function inputer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inputer_id');
    }

    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Scope query untuk eager loading relasi pengguna dalam workflow alur transaksi
     * (checker, approver, inputer) guna memuat data relasi secara efisien dan mencegah N+1 problem.
     */
    public function scopeWithWorkflowUsers($query)
    {
        return $query->with(['checker', 'approver', 'inputer']);
    }
}
