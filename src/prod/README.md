# Démo protect

Ceci est le site par défaut d'Eirbware, il permet de mettre en ligne un site
statique en utilisant [protect](https://github.com/Eirbware/protect).

## Lancer le site

Afin de lancer le site, faites :

1. Mettre à jour le port du `docker-compose.yml`, le **port par défaut** sur le serveur d'Eirbware **ne doit pas être modifié**.
2. Complétez le fichier `nginx/php/auth-config.php`
3. Exécutez la commande suivante :

```sh
docker compose up -d
```
