# Установка приложения

### Требования

 - Docker
 - Git

Приложение максимально автоматизировано. Для запуска необходимо клонировать код приложения. После чего находясь в корне проекта запустить docker compose

```
docker compose -f docker-compose.yml up
```

В результате будет запущено 5 контейнеров callmedia-app, callmedia-nginx,  callmedia-rmq, callmedia-mysql, callmedia-clickhouse.


# Просморт статистики

В браузере посетить 

```
http://localhost
```

т.к. producer отправляет сообщения в с интервалами (10; 100) сек. данные будут появляться постепенно

# Healthcheck

есть возможность проверить состояние app контейнера (запущен или нет php-fpm)

```
docker inspect callmedia-app | jq '.[].State.Health'
```

В контейнер добавлен скрипт который раз в 10 секунд проверят запущен fpm или нет