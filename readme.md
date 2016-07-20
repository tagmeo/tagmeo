# Tagmeo

[![Build Status](https://travis-ci.org/tagmeo/tagmeo.svg?branch=master)](https://travis-ci.org/tagmeo/tagmeo) [![Latest Stable Version](https://poser.pugx.org/tagmeo/tagmeo/v/stable)](https://packagist.org/packages/tagmeo/tagmeo) [![Latest Unstable Version](https://poser.pugx.org/tagmeo/tagmeo/v/unstable)](https://packagist.org/packages/tagmeo/tagmeo) [![License](https://poser.pugx.org/tagmeo/tagmeo/license)](https://packagist.org/packages/tagmeo/tagmeo) [![composer.lock](https://poser.pugx.org/tagmeo/tagmeo/composerlock)](https://packagist.org/packages/tagmeo/tagmeo)

A modern WordPress stack with the latest development tools, easier configuration, enhanced security, and an improved folder structure.

- [Installation](#-installation)
    - [Server Requirements](#server-requirements)
    - [Installing Tagmeo](#installing-tagmeo)
        - [Via Tagmeo Installer](#via-tagmeo-installer)
        - [Via Composer Create-Project](#via-composer-create-project)
        - [Via Git Clone](#via-git-clone)
- [Folder Structure](#-folder-structure)
- [Environment](#-environment)
- [Assets](#-assets)
    - [Gulp](#gulp)
    - [NPM](#npm)
- [WordPress](#-wordpress)
    - [Plugins](#plugins)
    - [MU Plugins](#must-use-mu-plugins)
    - [Themes](#themes)
- [Resources](#-resources)
    - [SCSS](#scss)
    - [Asset Loader](#asset-loader)
        - [Parameters](#parameters)
        - [Examples](#examples)
- [Console Application](#-tagmeo-console)
- [Vagrant](#-vagrant)
- [Laravel Valet](#-laravel-valet)

## [#](#-installation) Installation

### [](#server-requirements)Server Requirements

Tagmeo has a few system requirements. Of course, all of these requirements are satisfied by the [Vagrant](#-vagrant) virtual machine that's included, so it's highly recommended that you use this for your local development environment.

If you're not using Vagrant, you will need to make sure your server meets the following requirements:

- MySQL >= 5.5.9
- Node >= 5.9
- NPM >= 3.7
- PHP >= 5.6

You'll also need the following PHP extensions:

- Mbstring
- OpenSSL
- PDO
- Tokenizer

### [](#installing-tagmeo)Installing Tagmeo

Tagmeo uses [Composer](http://getcomposer.org) to manage its dependencies. So, before using Tagmeo, make sure you have Composer installed on your machine.

#### [](#via-tagmeo-installer)Via Tagmeo Installer

First, download the Tagmeo installer using Composer:

```bash
composer global require tagmeo/installer
```

Make sure to place the `~/.composer/vendor/bin` directory (or the equivalent for your OS) in your `PATH` so the `tagmeo` executable can be located by your system.

Once installed, the `tagmeo new` command will create a fresh Tagmeo installation in the directory you specify. For example, `tagmeo new blog` will create a directory named `blog` containing a fresh installation with all of the dependencies already installed. This method of installation is much faster than installing via Composer:

```bash
tagmeo new blog
cd blog
php tagmeo setup
```

The last command will walk you through the setup process to create your environment file, generate WordPress authentication keys and salts, install NPM packages, run Gulp, and provision your virtual machine.

#### [](#via-composer)Via Composer

You can install Tagmeo by issuing the Composer `create-project` command:

```bash
composer create-project tagmeo/tagmeo blog
cd blog
php tagmeo setup
```

#### [](#via-git)Via Git

If you prefer, you can install Tagmeo by cloning the repository:

```bash
git clone https://github.com/tagmeo/tagmeo blog
cd blog
composer install
php tagmeo setup
```

## [#](#-folder-structure) Folder Structure

The following `tree` output shows the most commonly used folders and files:

    ├── elixir.json                         # Asset loader
    ├── gulpfile.js                         # Task automator
    ├── app                                 # Application framework
    ├── config
    │   └──  application.php                # Primary configuration
    ├── public                              # Virtual host document root
    │   ├── assets                          # Public assets
    │   │   └── rev-manifest.json           # Cache busting
    │   ├── cms                             # WordPress core
    │   ├── mu-plugins                      # WordPress must-use plugins
    │   ├── plugins                         # WordPress plugins
    │   ├── themes                          # WordPress themes
    │   └── uploads                         # WordPress uploads
    ├── resources
    │   └── assets                          # Local assets
    ├── vendor                              # Composer dependencies
    └── tagmeo                              # Console application

## [#](#-environment) Environment

The `.env` file controls your environment settings. For security reasons, this file is not stored in the repository, but you can look at the `.env.example` file as a reference. The following variables can be set:

    AUTH_KEY
    AUTH_SALT
    DB_CHARSET
    DB_COLLATE
    DB_HOST
    DB_NAME
    DB_PASS
    DB_PREFIX
    DB_USER
    DISABLE_CRON
    DISABLE_FILE_EDIT
    DISABLE_UPDATER
    LOGGED_IN_KEY
    LOGGED_IN_SALT
    NONCE_KEY
    NONCE_SALT
    SECURE_AUTH_KEY
    SECURE_AUTH_SALT
    WP_ENV
    WP_HOME
    WP_SITEURL

The following variables are required:

- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `WP_HOME`
- `WP_SITEURL`

Valid input for the `WP_ENV` variable are:

- `development`
- `staging`
- `production`

## [#](#-assets) Assets

The `elixir.json` file controls what local or remote scripts or stylesheets are loaded, along with the local and distribution asset folders.

### [](#gulp)Gulp

The `gulpfile.js` file handles all of the assets for the project and places them in the `public/assets` folder, along with things like copying files, minification, cache busting, and BrowserSync.

### [](#npm)NPM

The `package.json` file is where dependencies for `npm` are saved.

## [#](#-wordpress) WordPress

WordPress is installed in the `public/cms` directory.

### [](#plugins)Plugins

Installed plugins reside in `public/plugins`.

### [](#mu-plugins)Must-Use (MU) Plugins

All must-use plugins are in `public/mu-plugins`.

### [](#themes)Themes

The themes are located in the `public/themes` directory. The default theme is called `tagmeo`, but can be renamed based on the project.

## [#](#-resources) Resources

The `resources` directory holds all of the local assets (images, fonts, scripts, stylesheets, etc.) that `gulp` uses to make the `app.css` and `app.js` file in the public distribution folder.

### [](#scss)SCSS

A default SCSS structure has been setup to speed up the styling process. The `app.scss` file should not contain any CSS, instead, it's where we import partials and vendor files.

The `modules` directory really won't be touched either, besides setting up variables for the theme. No code should reside in these files either.

Finally, the `partials` directory is where the meat of the styling goes, and it's divided up based on the element and/or layout we're dealing with.

### [](#asset-loader)Asset Loader

All of the CSS and JavaScript assets are setup in the `elixir.json` file. Here's an example of the configuration options available to you:

```javascript
"assets": {
  "[handle]": {
    "file": "[file]",
    "dependsOn": "[dependsOn]",
    "version": "[version]",
    "inFooter": "[inFooter]",
    "media": "[media]"
  }
}
```

#### [](#parameters)Parameters

| Paremeter     | Type                | Required | Description  |
|:------------- |:------------------- |:--------:|:------------ |
| `[handle]`    | *(string)*          | Yes      | Name of the script or stylesheet.
| `[file]`      | *(string)*          | Yes      | Path to the script or stylesheet relative to the `public/assets` directory. The asset loader knows what type of file you're loading, so you don't have to put that in the path. If you want to specify a path outside of the `public/assets` directory, then use the a relative path from the `asset` or web root to the file. To load an external script or stylesheet, enter the full URL.
| `[dependsOn]` | *(array)*           | No       | An array of registered handles that the script or stylesheet depends on.
| `[version]`   | *(string\|boolean)* | No       | String specifying the script or stylesheet version number, if it has one. Default is `false`.
| `[inFooter]`  | *(boolean)*         | No       | Whether to enqueue the script before `</head>` or before `</body>`. Default is `true`.
| `[media]`     | *(string)*          | No       | String specifying the media for which the stylesheet has been defined, e.g. (`all`, `screen`, `handheld`, `print`). Default is `all`.

#### [](#examples)Examples

```javascript
"assets": {
  "tagmeo-css": {
    "file": "app.css"
  },
  "akismet": {
    "file": "./plugins/akismet/js/akismet.min.js"
  },
  "authorizer": {
    "file": "../plugins/authorizer/js/authorizer.min.js"
  },
  "bootstrap": {
    "file": "bootstrap.css",
    "version": "3.3.6",
    "media": "screen"
  },
  "jquery": {
    "file": "jquery.js",
    "version": "2.2.3",
    "inFooter": true
  },
  "tagmeo-js": {
    "file": "app.js"
  },
  "tagmeo-customizer": {
    "file": "customizer.js",
    "dependsOn": [
      "jquery",
      "customize-preview"
    ]
  },
  "html5shiv": {
    "file": "//cdn.example.com/js/html5shiv.min.js",
    "version": "3.7.3",
    "dependsOn": [
      "jquery"
    ]
  }
}
```

## [#](#-vagrant) Vagrant

If you choose to use [Vagrant](https://www.vagrantup.com), please make sure it and [VirtualBox](https://www.virtualbox.org) are installed on your machine.

The default root directory for your project is:

```bash
/vagrant
```

The following plugins, while not required, are recommended:

- `vagrant-hostmanager`
- `vagrant-auto_network`
- `vagrant-cachier`

You can run the following command to see what plugins you have installed:

```bash
vagrant plugin list
```

And the following command to install a plugin:

```bash
vagrant plugin install <plugin_name>
```

## [#](#-laravel-valet) Laravel Valet

Alternatively, you can use [Valet](https://laravel.com/docs/master/valet) to test your site locally. Once you've finished [installing Valet](https://laravel.com/docs/master/valet#installation), you can load the custom driver:

```bash
cd ~/.valet/Drivers/
git clone https://github.com/tagmeo/valet .
valet restart
```