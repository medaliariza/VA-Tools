# Railway Deployment

This app is configured for Railway with `railway.toml` and scripts in `railway/`.

## App service

Create a Railway project from the GitHub repo and add a MySQL database service.

Use these variables on the app service:

```env
APP_NAME="VA Tools"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-railway-domain
PORT=8080
RAILPACK_PHP_ROOT_DIR=/app/public

DB_CONNECTION=mysql
DB_URL=${{MySQL.MYSQL_URL}}
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}
RAILPACK_PHP_EXTENSIONS=pdo_mysql

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

LOG_CHANNEL=stderr
LOG_STDERR_FORMATTER=\Monolog\Formatter\JsonFormatter

MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-address@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-address@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

The app service uses:

- Build command: `npm run build`
- Pre-deploy command: `chmod +x ./railway/init-app.sh && sh ./railway/init-app.sh`

Set the Public Networking target port to `8080`.

Use `MYSQL_URL` / `MYSQLHOST` for the app service. Do not use `MYSQL_PUBLIC_URL` unless you are connecting from outside Railway.

If Railway serves a PHP info page, confirm `RAILPACK_PHP_ROOT_DIR=/app/public` exists on the app service and redeploy. The root `Caddyfile` also points FrankenPHP to Laravel's `public` directory.

## Optional services

For queues, create another service from the same repo and set the custom start command:

```bash
chmod +x ./railway/run-worker.sh && sh ./railway/run-worker.sh
```

For scheduled tasks, create another service from the same repo and set the custom start command:

```bash
chmod +x ./railway/run-cron.sh && sh ./railway/run-cron.sh
```

Use the same environment variables on each service.
