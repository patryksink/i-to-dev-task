# IToDevTask

## Two tasks

This project consist of two taks:

- Symofony framework
- [Anagram](#Anagram)

## Table of content

- [Setup](#Setup)
- [Command](#Command)
- [Anagram](#Anagram)
- [Mailer](#Mailer)

## Setup

Download

- [composer](https://getcomposer.org/) - PHP package manager!
- [node.js](https://nodejs.org/) - Npm package manager!
- [docker](https://www.docker.com/) - Docker platform

### Run commands:

#### Package setup:

```sh
composer i
npm i
npm run build
```

#### Docker setup:

```sh
docker compose up
```

> **NOTE:**
> Only mysql container needed so command is optional
>

#### Database

```sh
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Command

Command ca be run using code below

```sh
php bin/console user:mark:inactive
```

## Anagram

Anagram location path

```sh
relative path: App\Additional\Anagram
absolut path: src\Additional\Anagram
```

## Mailer

Had some proplems with [MailerInterface](#https://symfony.com/doc/current/mailer.html)
Mailer class object behaved like it should.
What cannot be told about MailerInterface which seem like have not read .env because could established proper smtp
Transport object for MailerInterface.
> **NOTE:**
> Code is written like in documentation using MailerInterface for sanity purposes.
>