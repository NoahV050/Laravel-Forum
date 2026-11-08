<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReplyController extends Controller
{
    public function store(Request $request, Thread $thread, Topic $topic): RedirectResponse
    {
        $this->ensureTopicBelongsToThread($thread, $topic);
        $validated = $request->validate(['body' => ['required', 'string', 'max:20000']]);
        $topic->replies()->create([...$validated, 'user_id' => $request->user()->id]);

        return redirect()->route('topics.show', [$thread, $topic])->with('status', 'Reactie geplaatst.');
    }

    public function edit(Thread $thread, Topic $topic, Reply $reply): View
    {
        $this->ensureReplyBelongsToTopic($thread, $topic, $reply);
        $this->authorizeOwnerOrAdmin($reply);

        return view('replies.form', compact('thread', 'topic', 'reply'));
    }

    public function update(Request $request, Thread $thread, Topic $topic, Reply $reply): RedirectResponse
    {
        $this->ensureReplyBelongsToTopic($thread, $topic, $reply);
        $this->authorizeOwnerOrAdmin($reply);
        $reply->update($request->validate(['body' => ['required', 'string', 'max:20000']]));

        return redirect()->route('topics.show', [$thread, $topic])->with('status', 'Reactie bijgewerkt.');
    }

    public function destroy(Thread $thread, Topic $topic, Reply $reply): RedirectResponse
    {
        $this->ensureReplyBelongsToTopic($thread, $topic, $reply);
        abort_unless(request()->user()->isAdmin(), 403);
        $reply->delete();

        return redirect()->route('topics.show', [$thread, $topic])->with('status', 'Reactie verwijderd.');
    }

    private function ensureTopicBelongsToThread(Thread $thread, Topic $topic): void
    {
        abort_unless($topic->thread_id === $thread->id, 404);
    }

    private function ensureReplyBelongsToTopic(Thread $thread, Topic $topic, Reply $reply): void
    {
        $this->ensureTopicBelongsToThread($thread, $topic);
        abort_unless($reply->topic_id === $topic->id, 404);
    }

    private function authorizeOwnerOrAdmin(Reply $reply): void
    {
        abort_unless(request()->user()->isAdmin() || request()->user()->id === $reply->user_id, 403);
    }
}
