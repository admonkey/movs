# Movies (IN DEVELOPMENT)
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


-----------
Development
-----------


## Testing

There's [a test script][8] written in bash that will create a test database and user account,
if one doesn't already exist. Then it will drop all tables and recreate them fresh
including stored procedures. Then the [PHPUnit][7] test suite will be run.

    ./test

The verbose flag will show more detailed output.

    ./test -v

## Notes

### Switching Database Management Systems

The database object connects using [PDO for PHP][10]
with a wrapper function called `MoviesController::executeQuery`
that relies on [PDOStatement::rowCount][11] which has documented that:

> some databases may return the number of rows returned by that statement. However, this behaviour is not guaranteed for all databases and should not be relied on for portable applications.

So while this currently works with
MySQL Ver 14.14 Distrib 5.5.49, for debian-linux-gnu (x86_64) using readline 6.3
recognize that this may be an incompatibility risk when migrating to another DBMS.

[1]:_resources/SQL/create_db_user.sql
[2]:_resources/SQL/ddl.sql.bash
[3]:_resources/_setup/install.bash
[4]:_resources/SQL/ddl.sql
[5]:_resources/SQL/erd.png
[6]:_resources/credentials.inc.php
[7]:https://phpunit.de/
[8]:test
[9]:http://php.net/manual/en/pdostatement.rowcount.php
[10]:http://php.net/manual/en/book.pdo.php
[11]:http://php.net/manual/en/pdostatement.rowcount.php
