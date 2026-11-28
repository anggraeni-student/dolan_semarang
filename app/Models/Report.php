<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    public const REASONS = [
        'spam' => 'Spam / promosi',
        'ofensif' => 'Kasar / ofensif',
        'tidak-relevan' => 'Tidak relevan',
        'data-salah' => 'Data tidak akurat',
        'lainnya' => 'Lainnya',
    ];

    public const STATUSES = [
        'open' => 'Menunggu',
        'actioned' => 'Ditindak (konten dihapus)',
        'warned' => 'Ditindak + peringatan',
        'dismissed' => 'Diabaikan',
    ];

    protected $fillable = [
        'reporter_id', 'reportable_type', 'reportable_id',
        'reason', 'note', 'status', 'handled_by', 'handled_at',
    ];

    protected $casts = ['handled_at' => 'datetime'];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /** Label alasan yang enak dibaca */
    public function getReasonLabelAttribute(): string
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }

    /** Deskripsi singkat konten yang dilaporkan (aman dipanggil walau konten sudah dihapus) */
    public function getTargetSummaryAttribute(): string
    {
        $target = $this->reportable;
        if (!$target) {
            return 'Konten sudah dihapus.';
        }

        return match (class_basename($target)) {
            'Review' => 'Review: ' . \Illuminate\Support\Str::limit($target->body, 90),
            'Thread' => 'Thread: ' . $target->title,
            'ThreadReply' => 'Balasan: ' . \Illuminate\Support\Str::limit($target->body, 90),
            'Destination' => 'Destinasi: ' . $target->name,
            default => class_basename($target),
        };
    }

    /** User penulis konten yang dilaporkan (target peringatan) */
    public function getTargetAuthorAttribute(): ?User
    {
        $target = $this->reportable;
        if (!$target) {
            return null;
        }

        $authorId = match (class_basename($target)) {
            'Destination' => $target->created_by,
            default => $target->user_id,
        };

        return $authorId ? User::find($authorId) : null;
    }
}
