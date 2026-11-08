<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TopicController extends Controller
{
    public function show(Thread $thread, Topic $topic): View
    {
        abort_unless($topic->thread_id === $thread->id, 404);
        $topic->load(['user', 'replies.user']);

        return view('topics.show', compact('thread', 'topic'));
    }

    public function store(Request $request, Thread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:20000'],
        ]);
        $topic = $thread->topics()->create([...$validated, 'user_id' => $request->user()->id]);

        return redirect()->route('topics.show', [$thread, $topic])->with('status', 'Bericht geplaatst.');
    }

    public function edit(Thread $thread, Topic $topic): View
    {
        $this->ensureThreadContainsTopic($thread, $topic);
        $this->authorizeOwnerOrAdmin($topic->user_id);

        return view('topics.form', compact('thread', 'topic'));
    }

    public function update(Request $request, Thread $thread, Topic $topic): RedirectResponse
    {
        $this->ensureThreadContainsTopic($thread, $topic);
        $this->authorizeOwnerOrAdmin($topic->user_id);
        $topic->update($request->validate([
            'title' => ['required', 'string', 'max:160'],
            'body' => ['required', 'string', 'max:20000'],
        ]));

        return redirect()->route('topics.show', [$thread, $topic])->with('status', 'Bericht bijgewerkt.');
    }

    public function destroy(Thread $thread, Topic $topic): RedirectResponse
    {
        $this->ensureThreadContainsTopic($thread, $topic);
        abort_unless(request()->user()->isAdmin(), 403);
        $topic->delete();

        return redirect()->route('threads.show', $thread)->with('status', 'Bericht en reacties verwijderd.');
    }

    private function ensureThreadContainsTopic(Thread $thread, Topic $topic): void
    {
        abort_unless($topic->thread_id === $thread->id, 404);
    }

    private function authorizeOwnerOrAdmin(int $ownerId): void
    {
        abort_unless(request()->user()->isAdmin() || request()->user()->id === $ownerId, 403);
    }
}
