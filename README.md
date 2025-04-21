# Protect

Ce projet permet de proposer pour tous les sites web d'Eirbware d'avoir une API
**simple** pour permettre de connecter une application web au cas de
l'ENSEIRB-MATMECA

## Utilisation

La connexion au CAS a toujours été pénible, nous avons fait en sorte que ça soit
le plus simple possible.

Vous pouvez:

1. Protéger des **données** derrière un mur d'authentification
2. Protéger des **redirections** derrière un mur d'authentification

### Configuration

Toute la configuration se fait dans le fichier `auth-config.php`.
En production, ce fichier se trouve dans le dossier `nginx/php`.

#### Données protégées

Pour ajouter des données protégées, définissez les comme suivant dans le
fichier `auth-config.php` :

```php
$PROTECTED_DATA = [
    "message" =>"Je suis protégé !",
    "video" => "https://www.youtube.com/watch?v=xvFZjo5PgG0",
    "theme_du_wei" => "wei.eirb.fr"
];
```

#### Liens protégés

Pour ajouter des liens protégés, définissez les comme suivant dans le
fichier `auth-config.php` :

```php
$PROTECTED_LINKS = [
    "video" => "https://www.youtube.com/watch?v=xvFZjo5PgG0",
    "super_documentation" => "https://docs.eirb.fr",
];
```

### Fonctions de la librairie js

#### `protect.login`

Redirige l'utilisateur vers [connect.eirb.fr](https://connect.eirb.fr), afin
qu'il puisse se connecter. Signature :

```ts
protect.login() -> void
```

La redirection et la gestion de l'authentification est gérée automatiquement
par la librairie.

#### `protect.getData`

Retourne les données de l'utilisateur connecté. Signature :

```ts
async protect.getData() -> Promise<userData, Error>
```

Un exemple d'utilisation se trouve dans le fichier `src/index.html` de ce
projet.

#### `protect.getData`

Redirige vers une url protégée définie 

```ts
protect.redirect(redirectId: string) -> void
```

#### `protect.logout`

Redirige l'utilisateur de façon à le déconnecter. Signature :

```ts
protect.logout() -> void
```

## Lancer l'environnement de développement

1. Créer un fichier `auth-config.php` (en utilisant `auth-config.example.php`)

Vous devrez aussi renseigner les champs suivants :

```
"server_url" => "<server_url>",
"client_id" => "<clientId>",
"client_secret" => "<clientSecret>",
"redirect_url" => "http://localhost:8080/protect/login.php"
```

Si vous n'avez pas de quoi les remplir, [contactez Eirbware](telegram.eirb.fr)

2. Exécutez :

```sh
make dev
```

## Compiler l'environnement de production

1. Exécutez :

```sh
make prod
```

Cela va créer le dossier `demo`, dans lequel une démo (presque) complète sera
générée. Le but de cette version est d'être exécutée en mode `rootless`.

2. Éditer le fichier `demo/docker-compose.yml` et entrez le port a utiliser

Exemple :

```
-      - PORT:80
+      - 8080:80
```

3. Éditez le fichier `demo/nginx/php/auth-config.php`, vous devrez aussi renseigner les champs suivants :

```
"client_id" => "<clientId>",
"client_secret" => "<clientSecret>",
```

Si vous n'avez pas de quoi les remplir, [contactez Eirbware](telegram.eirb.fr)
