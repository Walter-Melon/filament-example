# Installation

-   Pull repository
-   Copy `.env.example` to `.env`
-   Install sail with composer

```sh
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

-   Run dev server

```sh
./vendor/bin/sail up
```

-   Migrate

```sh
./vendor/bin/sail artisan migrate --seed
```

-   Login with
    -   Mail: admin@example.com
    -   Password: password

# Recreate error

-   Open "Groups" --> any group --> click on "Associate" in items relation
-   The relation is named group but it's trying to fetch the itemGroups
