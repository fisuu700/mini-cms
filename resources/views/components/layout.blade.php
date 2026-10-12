<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Mini-CMS' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <nav class="bg-white border-b border-gray-200 p-4 shadow-sm">
        <div class="container mx-auto flex space-x-6">
            <a href="{{ route('home') }}" class="text-lg font-bold">Mini-CMS</a>
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
            <a href="{{ route('posts.index') }}" class="text-gray-600 hover:text-gray-900">Articles</a>
            <a href="{{ route('time') }}" class="text-gray-600 hover:text-gray-900">Heure</a>
            <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900">À propos</a>
        </div>
    </nav>

    <main class="container mx-auto mt-6 px-4">
        {{ $slot }}
    </main>
</body>
</html>
