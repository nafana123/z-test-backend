## Установка и запуск

1. Клонировать репозиторий:
git clone https://github.com/nafana123/z-test-backend.git

2. Запустить проект через Docker:
docker-compose up --build -d

3. Применить миграции:
php bin/console doctrine:migrations:migrate

4. Импортировать тестовые данные:
php bin/console app:import-tenders

5. Приложение будет доступно по адресу:
http://127.0.0.1:8000

## Подключение к БД

База данных будет доступна по адресу:

```
DATABASE_URL="mysql://root:root@127.0.0.1:3306/zebra_db?serverVersion=8.0.32&charset=utf8mb4"
```
## Генерация JWT-ключей

Перед запуском приложения необходимо сгенерировать ключи для JWT (используется для авторизации пользователей):

1. Введите команду:
php bin/console lexik:jwt:generate-keypair

Эта команда создаст приватный и публичный ключи, которые будут использоваться для создания и проверки JWT токенов.

3. Убедитесь, что в вашем `.env` файле указаны пути к ключам. Пример:

```dotenv
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=your_jwt_passphrase
```

## Аутентификация

Перед использованием API необходимо зарегистрироваться и авторизоваться:

1. Регистрация:

```
POST /api/auth/register
```

Тело запроса:

```json
{
  "email": "test@mail.ru",
  "login": "test",
  "password": "test123"
}
```

2. Авторизация:

```
POST /api/auth/login
```

Тело запроса:

```json
{
  "email": "test@mail.ru",
  "password": "test123"
}
```

Ответ:

```json
{
  "token": "<ваш JWT токен>"
}
```

Полученный токен необходимо указывать в заголовке `Authorization` для всех защищённых методов:

```
Authorization: Bearer <ваш JWT токен>
```

## API

* `GET /api/tenders` — получить список тендеров (доступно только авторизованным пользователям)

    * Поддерживает фильтрацию по:

        * `name`: название тендера
        * `date`: дата обновления (формат `Y-m-d`)

* `GET /api/tenders/{id}` — получить конкретный тендер по ID (доступно только авторизованным пользователям)

* `POST /api/tenders/add` — создать тендер (доступно только авторизованным пользователям)

Тело запроса:
```json
{
  "externalCode": "ABC-123",
  "number": "2024-001",
  "status": "active",
  "title": "Закупка оборудования",
  "updatedAt": "2024-06-01"
}
```