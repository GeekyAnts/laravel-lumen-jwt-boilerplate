# A Boilerplate for yor Web & API server built using PHP Tech


## What is needed to setup?

1. PHP >=7.1 with extension requirements given as in Laravel's documentation.
2. Node & NPM LTS Stable Release.
3. Composer Stable Release.
4. Any Database of your choice, I have used MySQL.

## How you can install? 

```sh
# Installs all the necessary packages required to run the app
> composer install;

# Creates your dotEnv file
> cp -fR .env.example .env;

# Gives permission to these directories
> chmod -fR 777 bootstrap/ storage/;

# Generates app secret
> php artisan key:generate;

# Generates jwt secret
> php artisan jwt:secret;

# Creates the required tables into your database
# Note: Please do remember to create your database before you run this command!
> php artisan migrate;

# Installs all the npm packages
> npm install;
```

## How to run the app?

```sh
# You can either run it on localhost or you can have the virtualhost configuration 
# in a server of your choice (We prefer nginx / apache)
php artisan serve
```

## How can you see Web routes?

```sh
# Lists all the web routes defined for your web-app
php artisan route:list
```

## How can you see API routes?

```sh
# Lists all the api routes defined for your web-app
php artisan api:rotue
```

## What this repo contains?

1. E-Mail Verification/Confirmation for new users.
2. JWT Setup for your APIs.
3. Transform Request Middleware for your boolean inputs in requests.
4. Basic Contracts to Repositories binding sample for Auth Logic.

## License

MIT License GeekyAnts 2018
