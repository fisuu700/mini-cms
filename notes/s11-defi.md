# Session 11 - Défi

/mes-articles ne montre que mes articles, brouillons compris : oui
Un visiteur qui ouvre /mes-articles arrive sur /connexion : oui
Le cookie remember_web_ apparaît quand la case est cochée : oui
Les deux nouveaux tests passent et php artisan test est vert : oui

## Pourquoi partir de `$request->user()->posts()` plutôt que de `Post::where('user_id', $request->user()->id)` ?

La relation `posts()` porte déjà la bonne condition : elle ne vise que les articles de l'utilisateur de la session, et aucun champ de formulaire ni paramètre d'adresse ne peut la diriger vers un autre utilisateur. La requête est sûre par construction, alors qu'un `where` écrit à la main devrait répéter cette condition à chaque endroit.

## Pourquoi la case remember ne doit-elle pas être dans le tableau passé à `Auth::attempt()` ?

`Auth::attempt()` ajoute chaque clé du tableau à la recherche de l'utilisateur, sauf le mot de passe : il chercherait une colonne `remember` dans la table `users`. Comme elle n'existe pas, la page afficherait une erreur SQL (`no such column: remember`) au lieu de connecter l'utilisateur. Le second argument de `attempt()` est justement là pour ça : `$request->boolean('remember')`.
