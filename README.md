# Google Photos Slideshow

Automatically backup your Google Photos to a self-hosted device (Raspberry PI is perfect). Then display them as a rolling, randomised slideshow on any screen with an HDMI connection or web browser.

## Overview

This software is a [Laravel package](https://laravel.com/docs/10.x/packages) providing the following functionality:

* Console commands to maintain a local copy of Google Photos files on a daily schedule
* Web endpoints for setting up OAuth access to the Google API
* Web based Slideshow App and supporting API to display and control a rolling, randomised display of your photos

I made this software to solve several problems:

1. Keep an automated, local backup of Google photos
2. Display a running _randomised_ slideshow of all my images on a TV or photoframe without having to do anything to instigate it
3. Display a slideshow on multiple devices around the house without having to maintain multiple copies
4. Filter out less important images with minimal effort

The backup/indexer tool is only interested in photos you have placed in a Google Photos Album. The intention is that you have to denote the image as "special" enough to copy down. That way random snaps or screebgrabs that are uploaded don't corrupt your slideshows.

There are a couple of ways to view the photos. The simplest is to utilise my [Slideshow](https://github.com/dmlogic/photo-slideshow) tool and plug your PI directly into the HDMI port of your TV. This way you can switch the HDMI input and the slideshow will always be there instantly. This tool uses a Python script to render the image and has no controls or user interface.

If you want more control (pause, rewind etc), or you want to display on a device some distance from the PI, you can view the Web Interface. This is the preferred method for a Smart TV, most of which have a simple web browser with full screen mode. Whilst it takes a few extra clicks to get it running, it avoids the need for me to maintain multiple TV apps with multiple content stores.

## Installation

As a package, this requires a host Laravel app. I _really_ hate dealing with [laravel/laravel](https://github.com/laravel/laravel) as a container for my code, so there is a build script to quickly consume this package from a functioning Laravel install based on the latest available skeleton. This keeps the codebase clean and makes life massively easier at upgrade time.

1. Create suitable host hardware and OS. A Raspberry PI with a large storage card is perfect
2. Clone this repo
3. Copy `.env.template` to `.env` and adjust as required - particularly the full path to photo storage (which should really be totally separate from this code)
4. Create an application in your Google Account and register it for [OAuth access](https://developers.google.com/photos/library/guides/authorization) to your Google account and download credentials to `credentials.json` in this folder
5. Run `./build.sh`
6. `cd` into `build` and run `php artisan serve`
7. In your browser visit `http://127.0.0.1:8000/google-oauth/start` and complete the oauth process
8. Setup a CRON command to index daily. e.g. `0 1 * * * cd /full/path/to/project/build && php artisan photos:index`

### Accessing via a Web Interface

@todo - instructions for raspi webserver and recommended config

## Upgrading

* Copy `build/storage/app/oauth.access` somewhere safe
* Copy `database/database.sqlite` somewhere safe if using the path in the `build` folder
* If your photo storage is not outside of the `build` folder, copy it somewhere safe
* Delete the `build` folder
* Update the package files to the latest version. Likely with a `composer update` if you've cloned this repo
* Run `./build.sh`
* Copy back `oauth.access` to `build/storage/app/`
* Copy back `database/database.sqlite` and your photos if necessary
* Re-start the webserver if you installed one

## License

This code is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
