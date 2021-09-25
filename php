#!/usr/bin/env bash
docker run -it --rm --name calculator-php -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.3-cli-alpine php ${@}
