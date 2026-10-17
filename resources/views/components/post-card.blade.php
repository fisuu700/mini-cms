@props(['post'])

<article class="bg-white p-6 rounded-lg shadow-md border border-gray-200 flex flex-col justify-between">
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">
            <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600">
                {{ $post->title }}
            </a>
        </h2>
        <p class="text-xs text-gray-500 mb-4">
            Publié le {{ $post->created_at->format('d/m/Y') }}
        </p>
        <p class="text-gray-700 text-sm mb-4">
            {{ Str::limit($post->content, 100) }}
        </p>
    </div>

    <div>
        <a href="{{ route('posts.show', $post) }}" class="text-sm font-medium text-indigo-600 hover:underline">
            Lire l'article &rarr;
        </a>
    </div>
</article>
