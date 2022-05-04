# Snow - P6 - Projet Snowtricks

Built by a developper Student, Snowtricks project is a community website to promote

snowboarding. As a user you can learn and share different snowboarding tricks with

the community. You also have the possibility to comment those figures with other users.

## Technologies
- Html/CSS
- PHP 8
- Symfony 5.4

## Installation

Copy the link on GitHub and clone it on your local repository
https://github.com/Fossette7/snow

Open your terminal and run: composer install

Create database: php bin/console doctrine:database:create

Open file .env and write username and password for 

DATABASE_URL: DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7.34&charset=utf8"

Fill the database with fixtures: php bin/console make:migration php bin/console doctrine:migration:migrate php bin/console doctrine:fixtures:load

**Choix de la palette de couleur**
> - [ ] theme 1 [palette verte](https://colorhunt.co/palette/3e8e7e7cd1b8fabb51faedc6).
> - [x] theme 2 [palette bleu](https://colorhunt.co/palette/22577e5584ac95d1ccf6f2d4).
  
  <br/><br/>
Librairie utilisée
> - css
>   - [Bootstrap 5.1](https://getbootstrap.com/docs/5.1/getting-started/introduction/)
> - php
>   - [Symfony 5.4](https://symfony.com/download)
