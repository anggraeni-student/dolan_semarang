<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Report;
use App\Models\Review;
use App\Models\Thread;
use App\Models\ThreadReply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /** Tipe konten yang bisa dilaporkan */
    public const TYPES = [
        'review' => Review::class,
        'thread' => Thread::class,
        'reply' => ThreadReply::class,
        'destination' => Destination::class,
    ];

    /** Simpan laporan dari user */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(self::TYPES)),
            'id' => 'required|integer',
            'reason' => 'required|in:' . implode(',', array_keys(Report::REASONS)),
            'note' => 'nullable|string|max:500',
        ], [
            'type.in' => 'Tipe laporan tidak dikenal.',
            'reason.in' => 'Pilih alasan laporan.',
        ]);

        $class = self::TYPES[$validated['type']];
        $target = $class::find($validated['id']);

        if (!$target) {
            return back()->with('error', 'Konten yang mau dilaporkan sudah tidak ada.');
        }

        // tidak bisa melaporkan konten sendiri
        if ($target->authorId() === $request->user()->id) {
            return back()->with('error', 'Kamu tidak bisa melaporkan konten milikmu sendiri.');
        }

        // satu laporan per user per konten
        $already = Report::where('reporter_id', $request->user()->id)
            ->where('reportable_type', $class)
            ->where('reportable_id', $target->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Kamu sudah melaporkan konten ini. Tim moderator sedang meninjaunya.');
        }

        Report::create([
            'reporter_id' => $request->user()->id,
            'reportable_type' => $class,
            'reportable_id' => $target->id,
            'reason' => $validated['reason'],
            'note' => $validated['note'] ?? null,
            'status' => 'open',
        ]);

        return back()->with('success', 'Laporanmu terkirim. Terima kasih sudah menjaga Dolan Semarang tetap sehat!');
    }
}
