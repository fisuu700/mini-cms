# Session 03 - Routes, contrôleurs et layout

## Défi

URL produite par route('about') :
http://localhost:8000/a-propos

URL produite par route('posts.show', 'routes-et-controleurs') :
http://localhost:8000/posts/routes-et-controleurs

URL produite par route('posts.archive', 2025) :
http://localhost:8000/posts/archive/2025

Pourquoi /posts/archive/abc et /posts/article-inconnu répondent toutes deux 404, et quelle ligne de code en est responsable dans chaque cas ?
- Pour /posts/archive/abc : C'est la contrainte ->whereNumber('year') dans le fichier routes/web.php qui rejète la route car "abc" n'est pas un nombre.
- Pour /posts/article-inconnu : La route correspond au motif, mais la méthode show() du PostController exécute if (! array_key_exists($slug, $posts)) { abort(404); } ce qui déclenche explicitement une erreur 404.

Que se passe-t-il si une vue appelle route('posts.show') sans paramètre ?
Laravel lève une exception indiquant qu'il manque un paramètre requis pour la route ([Missing required parameter for [Route: posts.show]]).
