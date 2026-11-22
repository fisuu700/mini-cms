<?php

use App\Models\Post;
use App\Models\User;

beforeEach(function () {
    $this->withoutVite();
});

it('shows only published posts to guests', function () {
    $publishedPost = Post::factory()->published()->create(['title' => 'Article publié']);
    $draftPost = Post::factory()->create(['title' => 'Brouillon', 'is_published' => false]);

    $this->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Article publié')
        ->assertDontSee('Brouillon');
});

it('shows published posts and own drafts to author', function () {
    $author = User::factory()->create();
    $otherAuthor = User::factory()->create();

    $publishedPost = Post::factory()->published()->create(['title' => 'Article publié']);
    $ownDraft = Post::factory()->create(['title' => 'Mon brouillon', 'is_published' => false, 'user_id' => $author->id]);
    $otherDraft = Post::factory()->create(['title' => 'Brouillon autre', 'is_published' => false, 'user_id' => $otherAuthor->id]);

    $this->actingAs($author)
        ->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Article publié')
        ->assertSee('Mon brouillon')
        ->assertDontSee('Brouillon autre');
});

it('shows all posts to admin', function () {
    $admin = User::factory()->admin()->create();
    $author = User::factory()->create();

    $publishedPost = Post::factory()->published()->create(['title' => 'Article publié']);
    $ownDraft = Post::factory()->create(['title' => 'Mon brouillon', 'is_published' => false, 'user_id' => $author->id]);

    $this->actingAs($admin)
        ->get(route('posts.index'))
        ->assertOk()
        ->assertSee('Article publié')
        ->assertSee('Mon brouillon');
});

it('allows guest to view published post', function () {
    $post = Post::factory()->published()->create(['title' => 'Article publié', 'body' => 'Contenu publié.']);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Contenu publié.');
});

it('forbids guest from viewing draft', function () {
    $post = Post::factory()->create(['title' => 'Brouillon', 'body' => 'Contenu brouillon.', 'is_published' => false]);

    $this->get(route('posts.show', $post))
        ->assertForbidden();
});

it('allows author to view own draft', function () {
    $author = User::factory()->create();
    $post = Post::factory()->create(['title' => 'Mon brouillon', 'body' => 'Contenu brouillon.', 'is_published' => false, 'user_id' => $author->id]);

    $this->actingAs($author)
        ->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Contenu brouillon.');
});

it('forbids author from viewing other author draft', function () {
    $author = User::factory()->create();
    $otherAuthor = User::factory()->create();
    $post = Post::factory()->create(['title' => 'Brouillon autre', 'body' => 'Contenu brouillon.', 'is_published' => false, 'user_id' => $otherAuthor->id]);

    $this->actingAs($author)
        ->get(route('posts.show', $post))
        ->assertForbidden();
});

it('allows admin to view any draft', function () {
    $admin = User::factory()->admin()->create();
    $author = User::factory()->create();
    $post = Post::factory()->create(['title' => 'Brouillon', 'body' => 'Contenu brouillon.', 'is_published' => false, 'user_id' => $author->id]);

    $this->actingAs($admin)
        ->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('Contenu brouillon.');
});

it('allows authenticated user to access create form', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('posts.create'))
        ->assertOk();
});

it('redirects guest from create form to login', function () {
    $this->get(route('posts.create'))
        ->assertRedirect(route('login'));
});

it('allows authenticated user to store a post', function () {
    $author = User::factory()->create();

    $this->actingAs($author)
        ->post(route('posts.store'), [
            'title' => 'Nouvel article',
            'slug' => 'nouvel-article',
            'body' => 'Contenu assez long pour passer la validation.',
            'is_published' => '1',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'slug' => 'nouvel-article',
        'user_id' => $author->id,
    ]);
});

it('redirects guest from storing a post to login', function () {
    $this->post(route('posts.store'), [
        'title' => 'Nouvel article',
        'slug' => 'nouvel-article',
        'body' => 'Contenu assez long pour passer la validation.',
    ])->assertRedirect(route('login'));
});

it('checks view policy for guest on published post', function () {
    $post = Post::factory()->published()->create();
    expect(Gate::forUser(null)->allows('view', $post))->toBeTrue();
});

it('checks view policy for guest on draft', function () {
    $post = Post::factory()->create(['is_published' => false]);
    expect(Gate::forUser(null)->allows('view', $post))->toBeFalse();
});

it('checks view policy for author on own draft', function () {
    $author = User::factory()->create();
    $post = Post::factory()->create(['is_published' => false, 'user_id' => $author->id]);
    expect(Gate::forUser($author)->allows('view', $post))->toBeTrue();
});

it('checks view policy for author on other draft', function () {
    $author = User::factory()->create();
    $otherAuthor = User::factory()->create();
    $post = Post::factory()->create(['is_published' => false, 'user_id' => $otherAuthor->id]);
    expect(Gate::forUser($author)->allows('view', $post))->toBeFalse();
});

it('checks create policy for guest', function () {
    expect(Gate::forUser(null)->allows('create', Post::class))->toBeFalse();
});

it('checks create policy for author', function () {
    $author = User::factory()->create();
    expect(Gate::forUser($author)->allows('create', Post::class))->toBeTrue();
});

it('checks create policy for admin', function () {
    $admin = User::factory()->admin()->create();
    expect(Gate::forUser($admin)->allows('create', Post::class))->toBeTrue();
});
