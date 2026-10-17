<x-layout :title="$post->title">
    <article class="max-w-2xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>
        <p class="text-sm text-gray-500 mb-6">Publié le {{ $post->created_at->format('d/m/Y') }}</p>

        <div class="prose max-w-none text-gray-700">
            {!! nl2br(e($post->content)) !!}
        </div>

        <div class="mt-8">
            <a href="{{ route('posts.index') }}" class="text-blue-600 hover:underline">&larr; Retour aux articles</a>
        </div>
    </article>
</x-layout>
