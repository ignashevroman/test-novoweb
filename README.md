# Запуск
```bash
cp .env.example .env
```
Указать настройки БД в `.env`, а также `EXTERNAL_API_KEY` - ключ от внешнего API.

Установить зависимости и выполнить миграции
```bash
composer instal
vendor/bin/sail up
vendor/bin/sail artisan migrate
```

# О проекте
Проект "заказать продвижение instagram". Список услуг получаем из внешнего API. На главной странице поле для ввода ссылки
на профиль instagram. При отправке формы происходит парсинг профиля и редирект на следующий шаг - создание заказа.
Для указаного профиля выбираем услугу, количество подписчиков и отправляем форму. После обработки отправляемся на 
страницу заказа.

## Получение список услуг с API
Для работы с внешним API сделал клиент `App\Services\ExternalApi\Client`. Его можно получить из DI контейнера.

Для получения списка услуг из внешнего API создана команда 
```bash
artisan external-api:update-services
```
После получения услуги сохраняются в базу данных. Если какая-либо услуга изменилась - она будет обновлена в БД.

Список услуг из БД можно получить с помощью запроса `GET /api/services`, в ответе json.

## Парсинг Instagram
`App\Services\InstaParser\Parser` - класс парсера. В нем можно описать абстрактные запросы, которые не зависят от какого
бы то ни было контекста (то есть не затрагивают функционал фреймворка, модели и тд - простые запросы)

`App\Services\InstaParser\InstaParser` - основной класс, который использует предыдущий для получения инфы из инсты. 
В методах этого класса уже используются инструменты фреймворка и содержится "бизнес-логика". 

Для парсинга стоит докрутить возможность использования прокси. 

Результат парсинга URL кэшируется в `redis` на неделю, чтобы избежать лишних запросов. Кроме того, URL перед парсингом 
очищается - приводится к минимально необходимой форме `protocol://host/path` - отсекаются GET аргументы. Таким образом 
ссылки `https://www.instagram.com/zuck` и `https://www.instagram.com/zuck?hl=ru` обрабатываются как одна, соответственно
количество лишних запросов к инсте также уменьшается.

## Создание заказа
При создании заказа валидация проверяет наличие услуги, профиля и в допустимом ли диапазоне находится число подписчиков 
для заказываемоей услуги.

Заказ создается в статусе `fresh` (также существуют `processing` и `completed`).

Событие создания обрабатывается листенером `SendCreatedOrderToExternalApi`, который обрабатывается в очереди на rabbitmq.
Листенер отправляет заказ во внешнее API на выполнение. При успешной отправке заказ переводится в статус `processing`.
Процесс отправки логируется в `storage/logs/external_api.log`. На выполнение есть 5 попыток с промежутком 5 минут.

## Обновление статуса заказа
Обновление статуса заказов происходит на `cron` каждые 5 минут, вызывается команда `external-api:update-orders-statuses`.
