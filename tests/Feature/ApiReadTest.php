<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;

it('lists only published posts with the resource shape', function () {
    Post::factory()->published()->count(2)->create();
    Post::factory()->create(['title' => 'Brouillon cache']);

    $this->getJson('/api/posts')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => ['*' => ['id', 'title', 'slug', 'excerpt', 'published_at', 'author', 'category', 'url']],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
        ]);
});

it('filters the posts by category slug', function () {
    $category = Category::factory()->create();
    Post::factory()->published()->create(['category_id' => $category->id, 'title' => 'Dans la categorie']);
    Post::factory()->published()->create(['title' => 'Hors categorie']);

    $this->getJson('/api/posts?category='.$category->slug)
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Dans la categorie')
        ->assertJsonPath('data.0.category.slug', $category->slug);
});

it('rejects an unknown category slug in French', function () {
    $this->getJson('/api/posts?category=inexistante')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['category'])
        ->assertJsonPath('errors.category.0', 'La valeur sélectionnée pour le champ catégorie est invalide.');
});

it('lists the categories with their published post count', function () {
    $category = Category::factory()->create();
    Post::factory()->published()->count(2)->create(['category_id' => $category->id]);
    Post::factory()->create(['category_id' => $category->id]);

    $this->getJson('/api/categories')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', $category->slug)
        ->assertJsonPath('data.0.posts_count', 2);
});

it('returns a JSON 404 for an unknown category', function () {
    $this->getJson('/api/categories/999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});

it('lists the tags with their published post count', function () {
    $tag = Tag::factory()->create();
    $post1 = Post::factory()->published()->create();
    $post2 = Post::factory()->published()->create();
    $draft = Post::factory()->create();

    $tag->posts()->attach([$post1->id, $post2->id, $draft->id]);

    $this->getJson('/api/tags')
        ->assertOk()
        ->assertJsonStructure([
            'data' => ['*' => ['id', 'name', 'slug', 'posts_count']],
        ])
        ->assertJsonPath('data.0.slug', $tag->slug)
        ->assertJsonPath('data.0.posts_count', 2);
});

it('filters the posts by tag slug', function () {
    $tag = Tag::factory()->create();
    $taggedPost = Post::factory()->published()->create(['title' => 'Article avec tag']);
    $otherPost = Post::factory()->published()->create(['title' => 'Article sans tag']);

    $taggedPost->tags()->attach($tag->id);

    $this->getJson('/api/posts?tag='.$tag->slug)
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $taggedPost->id)
        ->assertJsonPath('data.0.title', 'Article avec tag');
});

it('returns a JSON 404 for an unknown tag', function () {
    $this->getJson('/api/tags/999999')
        ->assertNotFound()
        ->assertJsonStructure(['message']);
});

