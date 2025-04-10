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

Pour l'instant, seule la protection de données est possible. Pour ajouter des
des données protégées, définissez les comme suivant dans le fichier
`auth-config.php` :

```php
$PROTECTED_DATA = [
    "message" =>"Je suis protégé !",
    "video" => "https://www.youtube.com/watch?v=xvFZjo5PgG0",
    "theme_du_wei" => "wei.eirb.fr"
];
```

### `protect.login`

Redirige l'utilisateur vers [connect.eirb.fr](https://connect.eirb.fr), afin
qu'il puisse se connecter.

La redirection et la gestion de l'authentification est gérée automatiquement
par la librairie.

### `protect.getData`

Retourne les données de l'utilisateur connecté. Signature :

```ts
async protect.getData() -> Promise<userData, Error>
```

Un exemple d'utilisation se trouve dans le fichier `src/index.html` de ce
projet.

### `protect.logout`

Redirige l'utilisateur de façon à le déconnecter.

## Lancer une démo ou un environnement de développement

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
make demo
```

