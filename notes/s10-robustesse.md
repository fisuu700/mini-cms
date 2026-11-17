# Session 10 - Robustesse

TagAdminTest contient 5 tests verts : oui
/posts/categorie/inconnue affiche Catégorie introuvable avec le statut 404 : oui
php artisan test est vert : oui

1. Pourquoi le message de messages() l'emporte-t-il sur celui de lang/fr/validation.php ?
Parce que Laravel cherche d'abord le message le plus précis : la clé `slug.unique` de `messages()` vise cette règle pour ce champ, alors que `validation.unique` est un message générique. Le premier trouvé gagne, et une clé absente ne casse rien : elle renvoie simplement à la source suivante.

2. Pourquoi phpunit.xml fixe-t-il APP_LOCALE ?
Pour que les tests produisent toujours les mêmes textes, quel que soit le `.env` du poste ou d'un clone du projet : les tests qui comparent un message français restent verts partout, comme ils sont déjà isolés de `SITE_READ_ONLY` et de la base SQLite en mémoire.

3. Pourquoi CategoryController n'a-t-il besoin d'aucun try/catch ?
Parce que Laravel gère les erreurs à sa place : une validation échouée est interceptée par la Form Request (redirection avec les erreurs) et une erreur comme `abort(404)` ou `abort(503)` est transformée par le gestionnaire d'exceptions en page du dossier `errors`. Le contrôleur ne garde que le chemin normal : enregistrer puis rediriger.
