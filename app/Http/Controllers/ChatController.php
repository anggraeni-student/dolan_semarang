<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    /** Kotak chat: user ↔ tim admin. Admin memilih utas via ?c={id}. */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->is_admin) {
            $conversations = ChatConversation::with('user')
                ->withCount(['messages'])
                ->orderByDesc('last_message_at')
                ->get();

            $active = $conversations->firstWhere('id', (int) $request->query('c')) ?? $conversations->first();
        } else {
            // utas pribadi dibuat otomatis saat pertama kali dibuka
            $active = ChatConversation::firstOrCreate(
                ['user_id' => $user->id],
                ['last_message_at' => now()],
            )->load('user');

            $conversations = collect([$active]);
        }

        if ($active) {
            $active->markReadFor($user);
            $active->load(['messages' => fn ($q) => $q->oldest(), 'messages.sender', 'user']);
        }

        return view('chat.index', [
            'conversations' => $conversations,
            'active' => $active,
            'unreadCounts' => $conversations
                ->mapWithKeys(fn ($c) => [$c->id => $c->unreadCountFor($user)])
                ->all(),
        ]);
    }

    /** Kirim pesan ke utas */
    public function send(Request $request, ChatConversation $conversation): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        // admin boleh membalas semua utas; user hanya utasnya sendiri
        abort_unless($user->is_admin || $conversation->user_id === $user->id, 403);

        $validated = $request->validate([
            'body' => 'required|string|min:1|max:2000',
        ], [
            'body.required' => 'Tulis pesanmu dulu ya.',
            'body.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        // peringatan resmi hanya boleh dikirim admin
        $isWarning = $user->is_admin && $request->boolean('is_warning');

        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $validated['body'],
            'is_warning' => $isWarning,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return redirect()
            ->route('chat.index', $user->is_admin ? ['c' => $conversation->id] : [])
            ->with('success', $isWarning ? 'Peringatan resmi terkirim.' : 'Pesan terkirim.');
    }
}
