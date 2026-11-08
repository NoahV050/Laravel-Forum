<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThreadController extends Controller
{
    public function index(): View
    {
        $threads = Thread::query()->with('user')->withCount('topics')->latest()->get();

        return view('threads.index', compact('threads'));
    }

    public function show(Thread $thread): View
    {
        $thread->load(['user', 'topics.user'])->loadCount('topics');
        $thread->topics->loadCount('replies');

        return view('threads.show', compact('thread'));
    }

    public function create(): View
    {
        return view('threads.form', ['thread' => new Thread, 'heading' => 'Nieuw forumonderdeel']);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        $thread = $request->user()->threads()->create($validated);

        return redirect()->route('threads.show', $thread)->with('status', 'Forumonderdeel aangemaakt.');
    }

    public function edit(Thread $thread): View
    {
        return view('threads.form', ['thread' => $thread, 'heading' => 'Forumonderdeel bewerken']);
    }

    public function update(Request $request, Thread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        $thread->update($validated);

        return redirect()->route('threads.show', $thread)->with('status', 'Forumonderdeel bijgewerkt.');
    }

    public function destroy(Thread $thread): RedirectResponse
    {
        $thread->delete();

        return redirect()->route('home')->with('status', 'Forumonderdeel en bijbehorende berichten verwijderd.');
    }
}
