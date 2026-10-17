<x-layout title="Liste des articles">
    <x-page-header title="Les articles" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-6">
        @forelse ($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <p class="text-gray-500">Aucun article pour le moment.</p>
        @endforelse
    </div>

    <div class="my-6">
        {{ $posts->links() }}
    </div>
</x-layout>
