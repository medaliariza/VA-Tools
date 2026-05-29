# Railway Deployment

## Required Services

1. Create a Railway web service from this repository.
2. Add a Railway MySQL database service.
3. Reference the MySQL service variables from the web service variables.

## Required Variables

Set these in the Railway web service variables:

```env
APP_NAME="VA Tools"
APP_ENV=production
APP_KEY=base64:REPLACE_WITH_YOUR_KEY
APP_DEBUG=false
APP_URL=https://your-railway-domain.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail-address@gmail.com
MAIL_PASSWORD=your-gmail-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail-address@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Railway JSON variable import:

```json
{
  "DB_CONNECTION": "mysql",
  "DB_HOST": "${{MySQL.MYSQLHOST}}",
  "DB_PORT": "${{MySQL.MYSQLPORT}}",
  "DB_DATABASE": "${{MySQL.MYSQLDATABASE}}",
  "DB_USERNAME": "${{MySQL.MYSQLUSER}}",
  "DB_PASSWORD": "${{MySQL.MYSQLPASSWORD}}"
}
```

Generate `APP_KEY` locally with:

```bash
php artisan key:generate --show
```

## Deploy Behavior

Railway uses `railway.toml`.

- Build installs Composer dependencies, installs Node dependencies, and builds Vite assets.
- Pre-deploy runs migrations, storage linking, and Laravel cache warmup.
- Start command runs Laravel on Railway's assigned `$PORT`.

## After First Deploy

1. Open the Railway domain.
2. Register an account.
3. Check Gmail OTP delivery.
4. If email fails, check Railway logs for `Authentication OTP email could not be sent`.
