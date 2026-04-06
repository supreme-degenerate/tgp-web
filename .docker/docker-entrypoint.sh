#!/usr/bin/env bash

php bin/console migrations:execute

exec apache2-foreground
