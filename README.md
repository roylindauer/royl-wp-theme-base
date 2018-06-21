[![Build Status](https://travis-ci.org/roylindauer/royl-wp-theme-base.svg?branch=master)](https://travis-ci.org/roylindauer/royl-wp-theme-base)

# ROYL WP Theme Base

A WordPress rapid development theme framework. 

# Development

To develop on this project you will need the following:

- Latest [Docker][1]
- Latest [NodeJS][2]
- Latest [Composer][3]

Install development dependencies:

```
npm install
composer install
```

## Docker Development Environment 

This project uses chriszarate/wordpress:4.7.2 (https://hub.docker.com/r/chriszarate/wordpress/). It's a pretty great WordPress development docker setup. You will need to add a hosts record on your computer in order to view the site. 

```
127.0.0.1 project.dev
```

You'll be able to view the site at http://project.dev. 

The WordPress u/p is: project:project

### Docker Commands

Build the development environment for the first time by running `docker-compose up` from the project root. 

After it is built you can simply call `docker-compose start` when you want to bring it back up.

Stop the environment with `docker-compose stop` or `ctrl+c` if you are not running it in daemon mode.


[1]:http://docker.com/
[2]:https://nodejs.org/en/
[3]:http://getcomposer.org/

