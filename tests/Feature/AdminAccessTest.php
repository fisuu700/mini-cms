<?php

use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

beforeEach(function () {
    $this->withoutVite();
});

it('redirects a guest to the login page for admin tags', function () {
    $this->get(route('admin.tags.index'))
        ->assertRedirect(route('login'));
});

it('forbids an author from accessing admin tags', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.tags.index'))
        ->assertForbidden();
});

it('allows an admin to access admin tags', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get(route('admin.tags.index'))
        ->assertOk();
});

it('forbids an author from creating a tag', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.tags.create'))
        ->assertForbidden();
});

it('forbids an author from storing a tag', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('admin.tags.store'), ['name' => 'Test', 'slug' => 'test'])
        ->assertForbidden();
});

it('forbids an author from editing a tag', function () {
    $tag = Tag::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get(route('admin.tags.edit', $tag))
        ->assertForbidden();
});

it('forbids an author from updating a tag', function () {
    $tag = Tag::factory()->create();

    $this->actingAs(User::factory()->create())
        ->put(route('admin.tags.update', $tag), ['name' => 'Test', 'slug' => 'test'])
        ->assertForbidden();
});

it('forbids an author from deleting a tag', function () {
    $tag = Tag::factory()->create();

    $this->actingAs(User::factory()->create())
        ->delete(route('admin.tags.destroy', $tag))
        ->assertForbidden();
});

it('allows an admin to manage tags', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->post(route('admin.tags.store'), ['name' => 'Laravel', 'slug' => 'laravel'])
        ->assertRedirect(route('admin.tags.index'));

    $this->assertDatabaseHas('tags', ['slug' => 'laravel']);
});

it('checks manage-tags gate for guest', function () {
    expect(Gate::forUser(null)->allows('manage-tags'))->toBeFalse();
});

it('checks manage-tags gate for author', function () {
    $author = User::factory()->create();
    expect(Gate::forUser($author)->allows('manage-tags'))->toBeFalse();
});

it('checks manage-tags gate for admin', function () {
    $admin = User::factory()->admin()->create();
    expect(Gate::forUser($admin)->allows('manage-tags'))->toBeTrue();
});

it('checks manage-categories gate for guest', function () {
    expect(Gate::forUser(null)->allows('manage-categories'))->toBeFalse();
});

it('checks manage-categories gate for author', function () {
    $author = User::factory()->create();
    expect(Gate::forUser($author)->allows('manage-categories'))->toBeFalse();
});

it('checks manage-categories gate for admin', function () {
    $admin = User::factory()->admin()->create();
    expect(Gate::forUser($admin)->allows('manage-categories'))->toBeTrue();
});
