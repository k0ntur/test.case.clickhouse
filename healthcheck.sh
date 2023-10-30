#!/bin/bash
is_running=`supervisorctl status php-fpm | grep RUNNING`
if [[ ! $is_running ]]
then
    echo 1
else
    echo 0
fi