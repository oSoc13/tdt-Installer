# tdt-Installer

This is an installer for the Datatank.

## Requirements

* A web server
* PHP >= 5.3
* A database server (MySQL is recommended)
* Curl
* Git (in your PATH)
* Composer (in your PATH)

## Installation instructions

To get started, clone this repository to your machine.
After that, the installer's dependencies can be installed using Composer. Your copy of the installer will include a composer.json file, so just run a `composer update` to install all dependencies.

To start the installer, move the directory containing the installer to the directory on your web server where you wish to install the Datatank. Then point your browser to the index.php file of the installer.

If something goes wrong during the installation, it will be written to the installer log file in settings/installer.log.

## Authors

* [Benjamin Mestdagh](https://github.com/benjaminmestdagh "Benjamin Mestdagh")
* [Nicolas Dierck] (https://github.com/nicolasdierck "Nicolas Dierck")

## Copyright

2013 by OKFN Belgium