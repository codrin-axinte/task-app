# Task App

A simple shakespearean task app.

## Installation

```shell
composer install
```

```shell
php artisan migrate --seed 
```

```shell
npm install && npm run dev
```

### Search Index

If searching is not giving an error, then the index might be missing. Run this:

Add this to your .env
```.dotenv

SCOUT_DRIVER=database
```

```shell
php artisan scout:index App\\Models\\Task
```
