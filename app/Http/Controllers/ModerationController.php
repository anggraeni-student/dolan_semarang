<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ReportController;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModerationController extends Controller
{
    /** Daftar laporan untuk admin (filter status) */
    public function index(Request $request): View
    {
        abort_unless($request->user()?->is_admin, 403);

        $status = $request->query('status', 'open');
        if (!in_array($status, ['open', 'actioned', 'warned', 'dismissed', 'all'], true)) {
            $status = 'open';
        }

        $reports = Report::with(['reporter', 'reportable'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->get();

        $counts = collect(Report::STATUSES)
            ->mapWithKeys(fn ($label, $key) => [$key => Report::where('status', $key)->count()])
            ->all();

        return view('moderation.index', [
            'reports' => $reports,
            'status' => $status,
            'counts' => $counts,
            'openCount' => Report::where('status', 'open')->count(),
        ]);
    }

    /** Hapus konten langsung dari halaman biasa (tombol inline admin) */
    public function destroy(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(ReportController::TYPES)),
            'id' => 'required|integer',
            'back' => 'nullable|url',
        ]);

        $class = ReportController::TYPES[$validated['type']];
        $model = $class::find($validated['id']);

        if (!$model) {
            return back()->with('error', 'Konten sudah tidak ada.');
        }

        $summary = $this->summarize($model);
        $this->deleteContent($class, $model, $request->user()->id);

        // kembali ke halaman asal (hanya URL se-domain, anti open-redirect)
        $back = $validated['back'];
        if ($back && parse_url($back, PHP_URL_HOST) === $request->getHost()) {
            return redirect($back)->with('success', "Konten \"{$summary}\" sudah dihapus.");
        }

        return redirect()->route('moderation.index')->with('success', "Konten \"{$summary}\" sudah dihapus.");
    }

    /** Tindak lanjut laporan: hapus konten / hapus + peringatan / abaikan */
    public function action(Request $request, Report $report): RedirectResponse
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'do' => 'required|in:delete,warn,dismiss',
            'warning' => 'nullable|string|max:1000',
        ]);

        if ($validated['do'] === 'dismiss') {
            $report->update([
                'status' => 'dismissed',
                'handled_by' => $request->user()->id,
                'handled_at' => now(),
            ]);

            return back()->with('success', 'Laporan diabaikan (dianggap tidak melanggar).');
        }

        $content = $report->reportable;
        if (!$content) {
            return back()->with('error', 'Konten yang dilaporkan sudah tidak ada — tidak ada yang perlu dihapus.');
        }

        $class = $content::class;
        $authorId = $content->authorId();
        $summary = $this->summarize($content);

        $this->deleteContent($class, $content, $request->user()->id);

        if ($validated['do'] === 'warn' && $authorId) {
            $text = trim($validated['warning'] ?? '') ?: $this->defaultWarning($report, $summary);
            $this->sendWarning($authorId, $request->user()->id, $text);

            $report->update([
                'status' => 'warned',
                'handled_by' => $request->user()->id,
                'handled_at' => now(),
            ]);

            return back()->with('success', "Konten \"{$summary}\" dihapus & peringatan terkirim lewat chat ke penulisnya.");
        }

        return back()->with('success', "Konten \"{$summary}\" dihapus.");
    }

    /** Hapus konten + tandai semua laporan terkait sebagai "ditindak" */
    private function deleteContent(string $class, Model $model, int $adminId): void
    {
        Report::where('reportable_type', $class)
            ->where('reportable_id', $model->id)
            ->where('status', '!=', 'dismissed')
            ->update(['status' => 'actioned', 'handled_by' => $adminId, 'handled_at' => now()]);

        $model->delete();
    }

    /** Kirim peringatan resmi ke user lewat chat (buat utas bila belum ada) */
    private function sendWarning(int $userId, int $adminId, string $text): void
    {
        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => $userId],
            ['last_message_at' => now()],
        );

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $adminId,
            'body' => $text,
            'is_warning' => true,
        ]);

        $conversation->update(['last_message_at' => now()]);
    }

    private function defaultWarning(Report $report, string $summary): string
    {
        return "Konten \"{$summary}\" kamu telah dihapus oleh moderator karena melanggar panduan komunitas "
            . "(kategori laporan: {$report->reasonLabel}). Mohon jaga konten selanjutnya tetap sesuai panduan, ya!";
    }

    private function summarize(Model $model): string
    {
        return match (class_basename($model)) {
            'Review' => \Illuminate\Support\Str::limit($model->body, 40),
            'Thread' => $model->title,
            'ThreadReply' => \Illuminate\Support\Str::limit($model->body, 40),
            'Destination' => $model->name,
            default => class_basename($model) . ' #' . $model->id,
        };
    }
}
