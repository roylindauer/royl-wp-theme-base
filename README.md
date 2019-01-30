# ROYL WP Theme Base

A WordPress rapid development theme framework. 

## Development

To develop on this project you will need the following:

- Latest [Docker][1]

Install development dependencies:

```
docker run --rm --interactive --tty --volume $PWD:/app composer install
```

## Docker Development Environment 

This project uses chriszarate/wordpress (https://hub.docker.com/r/chriszarate/wordpress/). It's a pretty great WordPress development docker setup. You will need to add a hosts record on your computer in order to view the site. 

```
127.0.0.1 project.test
```

You'll be able to view the site at http://project.test. 

[1]:http://docker.com/
[2]:https://nodejs.org/en/
[3]:http://getcomposer.org/

