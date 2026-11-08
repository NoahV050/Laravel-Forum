<?php

namespace Tests\Feature;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitors_can_browse_threads_and_topics_but_cannot_see_reply_form(): void
    {
        $user = User::factory()->create();
        $thread = Thread::create(['title' => 'Laravel', 'description' => 'Laravel vragen', 'user_id' => $user->id]);
        $topic = Topic::create(['thread_id' => $thread->id, 'user_id' => $user->id, 'title' => 'Hoe werkt routing?', 'body' => 'Mijn volledige vraag.']);

        $this->get('/')->assertOk()->assertSee('Laravel');
        $this->get(route('threads.show', $thread))->assertOk()->assertSee('Hoe werkt routing?');
        $this->get(route('topics.show', [$thread, $topic]))
            ->assertOk()
            ->assertSee('Mijn volledige vraag.')
            ->assertDontSee('Plaats een reactie');
    }

    public function test_registered_users_can_create_topics_and_replies_and_edit_only_their_own_content(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $thread = Thread::create(['title' => 'PHP', 'description' => 'PHP vragen', 'user_id' => $author->id]);
        $topic = Topic::create(['thread_id' => $thread->id, 'user_id' => $otherUser->id, 'title' => 'Bestaand', 'body' => 'Oude tekst']);

        $this->actingAs($author)
            ->post(route('topics.store', $thread), ['title' => 'Nieuwe vraag', 'body' => 'Een vraag over PHP'])
            ->assertRedirect();
        $createdTopic = Topic::where('title', 'Nieuwe vraag')->firstOrFail();

        $this->post(route('replies.store', [$thread, $topic]), ['body' => 'Mijn antwoord'])
            ->assertRedirect();
        $reply = Reply::where('body', 'Mijn antwoord')->firstOrFail();
        $this->put(route('replies.update', [$thread, $topic, $reply]), ['body' => 'Aangepast antwoord'])->assertRedirect();
        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => 'Aangepast antwoord']);
        $this->put(route('topics.update', [$thread, $topic]), ['title' => 'Niet van mij', 'body' => 'Nieuwe tekst'])->assertForbidden();
        $this->delete(route('replies.destroy', [$thread, $topic, $reply]))->assertForbidden();
        $this->assertDatabaseHas('topics', ['id' => $createdTopic->id, 'user_id' => $author->id]);
    }

    public function test_admin_can_manage_users_and_delete_content_while_user_list_hides_passwords(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $thread = Thread::create(['title' => 'Vue', 'description' => 'Vue vragen', 'user_id' => $admin->id]);
        $topic = Topic::create(['thread_id' => $thread->id, 'user_id' => $user->id, 'title' => 'Vraag', 'body' => 'Bericht']);

        $this->actingAs($user)->get(route('users.index'))->assertForbidden();
        $this->actingAs($admin)->get(route('users.index'))
            ->assertOk()
            ->assertSee($user->username)
            ->assertDontSee($user->password);
        $this->put(route('users.update', $user), [
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'role' => 'admin',
        ])->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'role' => 'admin']);
        $this->delete(route('topics.destroy', [$thread, $topic]))->assertRedirect(route('threads.show', $thread));
        $this->assertDatabaseMissing('topics', ['id' => $topic->id]);
    }

    public function test_registration_never_accepts_an_admin_role(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Nieuw',
            'username' => 'nieuw',
            'email' => 'nieuw@example.test',
            'password' => 'SecretPass123',
            'password_confirmation' => 'SecretPass123',
            'role' => 'admin',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ['email' => 'nieuw@example.test', 'role' => 'user']);
    }

    public function test_users_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create(['password' => 'SecretPass123']);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'SecretPass123',
        ])->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_only_admins_can_create_threads(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['role' => 'admin']);
        $attributes = ['title' => 'Laravel', 'description' => 'Laravel en PHP'];

        $this->actingAs($user)->post(route('threads.store'), $attributes)->assertForbidden();
        $this->actingAs($admin)->post(route('threads.store'), $attributes)->assertRedirect();
        $this->assertDatabaseHas('threads', ['title' => 'Laravel', 'user_id' => $admin->id]);
    }
}
