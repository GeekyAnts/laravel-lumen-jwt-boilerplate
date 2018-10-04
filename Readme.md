# A Sample Boilerplate for yor Web & API server built in PHP Tech Stack:


# What is needed to setup?

1. PHP >=7.1 with extension requirements given as in Laravel's documentation.
2. Node & NPM LTS Stable Release.
3. Composer Stable Release.
4. Any Database of your choice, I have used MySQL.

# How you can install? 

```sh
> composer install;
> cp -fR .env.example .env;
> chmod -fR 777 bootstrap/ storage/;
> php artisan key:generate;
> php artisan jwt:secret;
> php artisan migrate;
> npm install;
```

# How to run the app?

```sh
# You can either run it no localhost or you can have the virtualhost configuration 
# in a server of your choice (We prefer nginx / apache)
php artisan serve
```

# How can you see Web routes?

```sh
php artisan route:list
```

# How can you see API routes?

```sh
php artisan api:rotue
```

# What this repo contains?

1. E-Mail Confirmation for new users.
2. JWT Setup for your APIs.
3. Transform Request Middleware for your boolean inputs in requests.
4. Basic Contracts to Repositories binding sample for Auth Logic.
