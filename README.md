# [MT Project]

> ### RESTful API based on PHP8.1 and Laravel 9

This API is also served via a Lambda function on Aws, serving data from an RDS database. Api token for this will be shared  by email. Logging is not implemented for the lambda function, but CloudWatch would be a great option here.

For the lambda deployment: [serverless](https://www.serverless.com/framework/docs/getting-started) package. Configuration of the stack in serverless.yml

    npm install -g serverless   

RDS was provisioned from the console.

Lambda endpoint: [https://zefvilch42.execute-api.us-east-1.amazonaws.com/api/v1/invoices](https://zefvilch42.execute-api.us-east-1.amazonaws.com/api/v1/invoices)

----------

# Getting started

## Local Installation


Alternative installation is possible without local dependencies relying on [Docker](#docker).

Clone the repository

    git clone git@github.com:chrisgala/MTproject.git

Switch to the repo folder

    cd MTproject

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration change in the api token field in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations along with the seeders to populate the database (**Set the database connection in .env before migrating**)

    php artisan migrate --seed

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

**TL;DR command list**

    git clone git@github.com:chrisgala/MTproject.git
    cd MTproject
    composer install
    cp .env.example .env
    php artisan key:generate

**Make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)

    php artisan migrate:fresh --seed
    php artisan serve

## Docker

To install with [Docker](https://www.docker.com), run following commands:

```
git clone git@github.com:chrisgala/MTproject.git
cd MTproject
cp .env.example .env
vendor/bin/sail up //It might take a while the first time it pulls dependencies
vendor/bin/sail php artisan key:generate
vendor/bin/sail php artisan migrate:fresh --seed
```

The api can be accessed at [http://localhost/api/v1/invoices](http://localhost/api/v1/invoices).

## API Specification
The API and the backend directories are versioned. (V1)
| **query parameter** 	| **value format**              |
|----------	|------------------	|
| date_from      	| YYYY-MM-DD     	|
| date_to      	| YYYY-MM-DD     	|
| invoice_id | string

#### Sample endpoints:
    http://localhost/api/v1/invoices?date_from=2020-11-11&date_to=2020-12-11
    http://localhost/api/v1/invoices?date_from=2020-11-11
    http://localhost/api/v1/invoices?invoice_id=2c92a0ab751b54bc01751e9d11b24503


Results are paginated. The response contains information about the results, the pages, next and previous links so that it can be easily consumed by an external service.

----------

# Code overview


## Folders

- `app` - Contains all the Eloquent models
- `app/Http/Controllers/Api` - Contains the api controller
- `app/Http/Middleware` - Contains the auth and logging middleware
- `app/Http/Resources/Api` - Contains all the api response resources and collections
- `config` - Contains all the application configuration files
- `database/migrations` - Contains all the database migrations
- `database/seeds` - Contains the database seeder
- `routes` - Contains all the api routes defined in api.php file

## Environment variables

- `.env` - Environment variables can be set in this file

----------

# Testing API

Run the laravel development server

    php artisan serve

Or start the docker containers

    /vendor/bin/sail/up

The api can now be accessed at

    http://localhost:8000/api/v1/invoices
    http://localhost/api/v1/invoices //docker


Request headers

| **Required** 	| **Key**              	| **Value**            	|
|----------	|------------------	|------------------	|
| Yes      	| Content-Type     	| application/json 	|

----------

# Authentication

This applications uses a predefined api token to handle authentication. The value is saved in an environment variable. Every request is passed through a simple middleware that checks the token and forwards the request on success. The token is passed as a query parameter in the 'api_token' key.

----------

# Logging

Api requests are logged to /logs/api.log file

----------
