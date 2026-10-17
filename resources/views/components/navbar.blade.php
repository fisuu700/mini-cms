<header class="bg-white border-b border-gray-200">
    <nav class="max-w-5xl mx-auto px-4 py-3 flex flex-wrap items-center gap-2" aria-label="Navigation principale">
        <a href="{{ route('posts.index') }}" class="font-bold text-lg text-indigo-600 mr-4">Mini-CMS</a>

        <x-nav-link to="posts.index" match="posts.*">Accueil</x-nav-link>
        <x-nav-link to="posts.index" match="posts.*">Articles</x-nav-link>
    </nav>
</header>
