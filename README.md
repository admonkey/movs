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

## User Roles and Access
1. general
  - view unrestricted movies
  - create user scores & user tags
2. unrestricted
  - view restricted movies
3. admin
  - create sources, movies, movie extras, tags, movie tags, roles, & user roles

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

## Installation
[SQL script][1] for creating database and user

[bash script][2] to setup database with [DDL][4] (see [ERD][5])

    ./_resources/SQL/ddl.sql.bash

read and edit [`_resources/credentials.inc.php`][6] or create a `_resources/credentials_local.inc.php`

(optional) interactive [bash script][3] for Apache virtual host
with self-signed SSL certificate, including hosts entry:

    ./_resources/_setup/install.bash

[1]:_resources/SQL/create_db_user.sql
[2]:_resources/SQL/ddl.sql.bash
[3]:_resources/_setup/install.bash
[4]:_resources/SQL/ddl.sql
[5]:_resources/SQL/erd.png
[6]:_resources/credentials.inc.php
