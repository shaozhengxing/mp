#!/usr/bin/env bash
# 这里最好写成可接受参数的形式
# start web
# start queue
# start job

export PATH=/home/php/bin:/home/php/sbin:/home/tengine/sbin:$PATH
if [ "$1" = "staging" ]; then
    unlink /data/.env
    cd /data/ && ln -s .env.stg .env
fi
php-fpm && nginx
