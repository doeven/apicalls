## About GhostClan

Ghost Clan is a simple API service built on Laravel, to help you with user authentication and backend data manipulations for a community MLM platform. This gives you the flexibility to use any front-end technology of your choice, be it Angular, React or Vue Js.

### Features

- [Simple API Documentation](http://www.xxxxxxxx.com/ghostclan/docs).
- You can implement both Session Authentications for SPAs (React, Vue etc) or Token Based Authentication for apps that are not session based (Android, iOS etc)
- Database agnostic since it's based on Laravel [schema migrations](https://laravel.com/docs/migrations).
- Email Sending can run with Laravel Queues and stored in the Job Table for background processing.

**Table of Contents**

[TOCM]

#### How to Setup
Make sure to upload the codebase to your domain for api calls (eg. api.example.com).

Run migrations to setup the database structure using the code below

`php artisan migrate`

After which you can run a Database Seed to populate the DB with some default settings and test user accounts using the code below.

`php artisan db:seed`

You should get 4 user to test authentication. Usernames and passwords which include.

| Email      | Username | Password | Role
| ------- | -----:| ------- | -----:|
| admin@admin.com | admin | adminadmin | Admin
| joseph@admin.com | mark | adminadmin | User
| john@admin.com |    john | adminadmin | User
| luke@admin.com |    luke | adminadmin | User

** *Note:* ** Users can login using Email/Username and Password combination during API calls


#### .env File (Should be in the Root Folder)

    APP_NAME=LaravelAppName
	APP_ENV=local
	APP_KEY=base64:GPE6i6T8dKDCnEZOig5JlmtronePWiVecVroifG3VQQ=
	APP_DEBUG=true
	APP_URL=http://127.0.0.1:8000
	APP_LICENSE_KEY=NjA5MzkyNjFlN2NhMTRmOTQ1NTZkYTU5

	LOG_CHANNEL=stack
	LOG_LEVEL=debug

	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=dbname
	DB_USERNAME=dbUsername
	DB_PASSWORD=dbUserPasskey

	BROADCAST_DRIVER=log
	CACHE_DRIVER=file
	QUEUE_CONNECTION=database
	SESSION_DRIVER=database
	SESSION_LIFETIME=120

	MEMCACHED_HOST=127.0.0.1

	REDIS_HOST=127.0.0.1
	REDIS_PASSWORD=null
	REDIS_PORT=6379

	MAIL_MAILER=smtp
	MAIL_HOST=smtp.mailtrap.io
	MAIL_PORT=465
	MAIL_USERNAME=username
	MAIL_PASSWORD=password
	MAIL_ENCRYPTION=tls
	MAIL_FROM_ADDRESS=info@laravel-app.com
	MAIL_FROM_NAME="${APP_NAME}"

	MAILGUN_DOMAIN=example.com
	MAILGUN_SECRET=secret_key

	AWS_ACCESS_KEY_ID=
	AWS_SECRET_ACCESS_KEY=
	AWS_DEFAULT_REGION=us-east-1
	AWS_BUCKET=

	PUSHER_APP_ID=
	PUSHER_APP_KEY=
	PUSHER_APP_SECRET=
	PUSHER_APP_CLUSTER=mt1

	MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
	MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"


	SANCTUM_STATEFUL_DOMAINS=127.0.0.1:5000,127.0.0.1:5001,127.0.0.1:5002,localhost:5000,localhost:5001,localhost:5002,localhost:3000
	SESSION_DOMAIN=localhost
	CURRENT_SANCTUM_STATEFUL_DOMAINS=http://127.0.0.1:5000,http://127.0.0.1:5001,http://127.0.0.1:5002,http://localhost:5000,http://localhost:5001,http://localhost:5002

    
The above .env setting should pretty much work for a 127.0.0.1 development after you must have linked to database and ran migrations. If you are using your custom domain in production (for instance https://example.com), the last part of your .env file should look something like below, and this is assuming your laravel app is running on the api subdomain (api.example.com), while the front end runs on the app subdomain (app.example.com).
Make sure to match your domains accordingly.

	SANCTUM_STATEFUL_DOMAINS=app.example.com
	SESSION_DOMAIN=api.example.com
	CURRENT_SANCTUM_STATEFUL_DOMAINS=https://app.example.com
Sometimes, you might want to **MAIL_MAILER** to `mailgun` if you are sending emails from mailgun. 

Your front-end SPA should run on 127.0.0.1 and port 5000, 5001, 5002 using the default .env file in development.


#### .htaccess
	<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

### App License Key
Make sure to get a valid license key for your domain from the developer. The valid key should be placed in the APP_LICENSE_KEY section within the .env file.
    `APP_LICENSE_KEY=yourDeveloperGeneratedKey`
Keys are valid per subdomain (i.e the specific subdomain the api calls would go to, eg. https://api.example.com)

**Note:** The license key above works for the stated development domain (http://127.0.0.1). You'd have to reach out to the developer if you wish to purchase a License for your url.
