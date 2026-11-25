# Session 14 - Défi

Connexion enregistre le jeton toute seule : oui
Créer un article répond 201 sans copier le jeton : oui
Le fichier exporté ne contient aucun jeton : oui

Le dépôt est public. Que risquez-vous si le fichier exporté contient un jeton, et comment l'annuler ?
N'importe qui peut agir en votre nom tant que le jeton n'est pas révoqué ; par défaut, il n'expire jamais. Pour l'annuler, on peut appeler /api/logout avec ce jeton, ou supprimer sa ligne dans la table personal_access_tokens.

Pourquoi les écritures de l'API passent-elles par les Form Requests et PostPolicy plutôt que par des règles écrites dans Api\PostController ?
Une règle pour deux portes : un changement de la Form Request ou de la Policy s'applique au site et à l'API en même temps, évitant ainsi toute duplication et garantissant que les deux portes ne se contredisent jamais.
