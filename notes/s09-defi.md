# Session 09 - Défi

Le bandeau s'affiche sur toutes les pages quand SITE_READ_ONLY=true : oui
Le bandeau disparaît quand SITE_READ_ONLY=false : oui
La page Article introuvable propose au plus trois articles publiés, sans brouillon : oui
Aucune LazyLoadingViolationException sur /posts/999 : oui
Les deux nouveaux tests passent et php artisan test est vert : oui

Pourquoi le bandeau est-il dans le layout et pas dans chaque vue ?
Parce que le layout est le seul fichier commun à toutes les pages : un seul bloc de code suffit pour informer tout le site, et une page ajoutée plus tard en hérite automatiquement.

Pourquoi le test du bandeau n'a-t-il pas besoin de modifier .env ?
Parce que PHPUnit charge sa propre configuration (phpunit.xml impose SITE_READ_ONLY=false) et que le test change la valeur en mémoire avec `config(['site.read_only' => true])` : aucun fichier n'est touché, donc le test reste rapide et reproductible.
