#!/usr/bin/env bash

php bin/console migrations:migrate

exec apache2-foreground
