# ROYL WP Theme Base

A WordPress rapid development theme framework. 

## Development

To develop on this project you will need the following:

- [Docker](http://docker.com/)
- [Node](https://nodejs.org/en/)

**Refer to https://github.com/chriszarate/docker-compose-wordpress for more information on the Docker setup.**

### Install Dependencies

- Home Brew - https://docs.brew.sh/Installation
- Docker - https://docs.docker.com/docker-for-mac/install/
- NodeJS - https://nodejs.org/en/download/

- Add hosts entry to `/etc/hosts` for local development
  ```shell
  127.0.0.1 localhost project.test
  ```
### Start Development

- Start Docker
  ```shell 
  docker-compose up -d
  ```

- Install WordPress
  ```shell
  docker-compose run --rm wp-cli install-wp
  ```

- Visit http://project.test/ in your browser

### WP-CLI

```shell
docker-compose run --rm wp-cli wp [command]
```

Import to and export from the WordPress database:

```shell
docker-compose run --rm wp-cli wp db import - < dump.sql
docker-compose run --rm wp-cli wp db export - > dump.sql
```

### Running tests (PHPUnit)

```shell
docker-compose run --rm wp-cli wp scaffold plugin-tests my-plugin
```
