# Pelican

## Introduction 🎉

Welcome to the Pelican documentation, it is an application that allows you to deploy a platform to manage different types of events.

Welcome to the Pelican documentation, it is an application that allows you to deploy a platform to manage different types of events. 😉

<br />

## Getting started 👓

This project works with Symfony version 4. Moreover, it uses the WebPack Encore allowing a fast and real time compilation. 

<br />

### Requirements 🔧

* Symfony 4.4.*
* NPM and Yarn
* Composer
* Wamp server (To use the MySQL Database)

## Installation 📦

<br />

### Symfony

To start, you need to install Symfony 4.4.*, to do so, go to <a href="https://symfony.com/download">symfony.com/download</a> to download symfony.

Once installed, you can access the symfony command prompt by launching the terminal of your operating system.

<br />

### npm and Yarn

To install NPM and Yarn, you need to go to <a href="https://www.npmjs.com/get-npm">npmjs.com/get-npm</a> and click on "Download Node.js and npm".

And finally to install Yarn to be able to use the WebPack Encore you just have to type the command. More infos on <a href="https://classic.yarnpkg.com/en/docs/install/#windows-stable">yarnpkg.com</a>.

```
 npm install --global yarn
```

<br />

### Composer

To install Composer go to the download page of Composer <a href="https://getcomposer.org/download/">getcomposer.org</a> then install it.

<br />

### Wamp Server

Concerning the installation of wamp I let you visit the associated documentation page <a href="https://www.wampserver.com/">wampserver.com</a>.

Once installed, open a terminal window at the project location run these commands

```
composer install
```

This command allows you to install all the dependencies of the project concerning Composer.

```
yarn install
```

This command allows you to install all the dependencies of the project concerning Yarn.

<br />

## Setup 🔨

First of all, you have to configure the .env file so that it corresponds to your database. To do this you need to follow this link <a href="https://symfony.com/doc/current/configuration.html#configuring-environment-variables-in-env-files">documentation .env</a>.

Once the server is set up, the database that will host Pelican must be created. To do so, you only need one symfony command.

```
php bin/console doctrine:database:create
```

If the database has no tables, use this command in addition.

```
php bin/console doctrine:schema:update --force
```

To use a dataset for demonstration purposes, you can load the set up fixtures with this command.

```
php bin/console doctrine:fixtures:load
```

<br />

## Start Pelican 💥

Nothing could be simpler, to run Pelican, you just have to launch two commands on two terminals at the same time.

The first one allows to start the server

```
symfony server:start
```

The second one allows the compilation of the project

```
yarn encore dev --watch
```

<br />

## First connection 🤩

If you have used the provided dataset, the admin user is already configured, just login as 

Login
> admin@admin

Password
> admin

Once done, you can reach the /admin route to access the administration panel.

Now, if you have not used this dataset, you must first register on your base, to do so go to Pelican and then to the route /register.

Once done, go to your PhpMyAdmin to change the role of your User, in the user table look for your user then add the role ROLE_ADMIN to the roles array.

```
["ROLE_ADMIN"]
```

<br />

**After that your Pelican platform is ready to be used.
Well done !** 👍