# Movies
Site for self-hosted movie collection.

## TODO
- news.php
  - scan multiple directories
  - find new files
  - parse filename to identify film
  - fetch meta-data from imdb, such as poster
- movies.php
  - grid of posters
  - filter & sortable
    - genre
    - rating (G,PG,PG13,R)
    - score (self, network, imdb, rotten tomatoes)
    - duration
    - year released
    - watched/new
- watch.php
  - html5 video
  - direct download
  - VLC embed
  - youtube trailer
  - list of meta-data
- login.php
  - LDAP authentication
  - fallback to local authentication
  - user security groups
    - general
    - unrestricted
    - admin
  - favorite home page

## Requirements
Might work elsewhere, but designed and tested on:  
- Ubuntu 14.04  
  - GNU bash, version 4.3.11(1)  
- Apache 2.4.7  
  - host directory must AllowOverride using .htaccess
    - Apache mod rewrite required to block certain folders (`sudo a2enmod rewrite`)
    - files use both `require all denied` and `deny from all` for backwards compatibility with Apache 2.2
    - these dependencies are automatically configured when using the `/_resources/_setup/install.bash` script
- PHP 5.5.9  
- OpenSSL 1.0.1f  
- Git 2.6.2
- MySQL  Ver 14.14 Distrib 5.5.46, for debian-linux-gnu (x86_64) using readline 6.3
