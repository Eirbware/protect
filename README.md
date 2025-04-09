# Protect

Ce projet permet de proposer pour tous les sites web d'Eirbware d'avoir une API
**simple** pour permettre de connecter une application web au cas de
l'ENSEIRB-MATMECA

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

