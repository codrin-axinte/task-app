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

```shell
php artisan scout:index App\\Models\\Task
```
