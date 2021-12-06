# Projet bail facile

## Installation

> ***Préambule :*** J'ai du réaliser ce projet sous le pc Windows brouette avec lequel nous avions parlé sur meets. 
J'ai donc du faire quelques concessions. Par exemple, je n'ai pas pu utiliser docker et j'ai du virtualiser Ubuntu sur Winwows et faire tourner le project en local, pas idéal...

###  1)  Installer php en local avec  ses extensions, mysql (ou faire tourner avec docker) ainsi que composer
### 2) Récupérer les sources sur git

    git clone https://github.com/peterk-mtl/test-laravel-bail-facile.git .
### 3) Créer des fichiers d'environnement
Copier le .env.exemple vers .env

    cp .env.exemple .env

Le remplir avec les valeurs suivantes :

    APP_NAME=Laravel
    APP_ENV=local
    APP_KEY=base64:vrekm+GQqO2q0VZ2cAdy2jxfaEDBiwyQ+vEYBcdiloM=
    APP_DEBUG=true
    APP_URL=http://localhost

    LOG_CHANNEL=stack
    LOG_DEPRECATIONS_CHANNEL=null
    LOG_LEVEL=debug

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=test_laravel_pk
    DB_USERNAME=peter
    DB_PASSWORD=863186Aa!

    BROADCAST_DRIVER=log
    CACHE_DRIVER=file
    FILESYSTEM_DRIVER=local
    QUEUE_CONNECTION=sync
    SESSION_DRIVER=file
    SESSION_LIFETIME=120

    MEMCACHED_HOST=127.0.0.1

    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    MAIL_MAILER=smtp
    MAIL_HOST=mailhog
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=null
    MAIL_FROM_NAME="${APP_NAME}"

    AWS_ACCESS_KEY_ID=
    AWS_SECRET_ACCESS_KEY=
    AWS_DEFAULT_REGION=us-east-1
    AWS_BUCKET=
    AWS_USE_PATH_STYLE_ENDPOINT=false

    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=mt1

    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

    L5_SWAGGER_CONST_HOST="http://127.0.0.1:8000/api"

### 4) Makefile

Afin de faciliter l'installation en local, j'ai créé un fichier Makefile pour la simplifier.

Il faut lancer la commande suivante : 
    
    make install
La commande va lancer un `composer install`, puis créer la base de données. Elle demandera d'entrer l'utilisateur et le mot de passe :

    The following steps will create the database:

    Enter your mysql user: peter
    Enter password:
    Database sucessfully created!

Les migrations, seeders et factories sont ensuite lancées :

    The following steps will run migrations:

    php artisan migrate
    Migration table created successfully.
    Migrating: 2014_10_12_000000_create_users_table
    Migrated:  2014_10_12_000000_create_users_table (408.75ms)
    Migrating: 2019_08_19_000000_create_failed_jobs_table
    Migrated:  2019_08_19_000000_create_failed_jobs_table (416.37ms)
    Migrating: 2019_12_14_000001_create_personal_access_tokens_table
    Migrated:  2019_12_14_000001_create_personal_access_tokens_table (669.18ms)
    Migrating: 2021_12_02_095210_create_document_formats_table
    Migrated:  2021_12_02_095210_create_document_formats_table (250.62ms)
    Migrating: 2021_12_02_095310_create_document_types_table
    Migrated:  2021_12_02_095310_create_document_types_table (1,211.24ms)
    Migrating: 2021_12_02_100047_create_documents_table
    Migrated:  2021_12_02_100047_create_documents_table (1,518.57ms)
    Migrating: 2021_12_06_110518_create_cache_table
    Migrated:  2021_12_06_110518_create_cache_table (1,334.00ms)


    The following steps will run seeders:

    php artisan db:seed
    Seeding: Database\Seeders\DocumentFormatSeeder
    Seeded:  Database\Seeders\DocumentFormatSeeder (181.90ms)
    Seeding: Database\Seeders\DocumentTypeSeeder
    Seeded:  Database\Seeders\DocumentTypeSeeder (620.56ms)
    Seeding: Database\Seeders\DocumentSeeder
    Seeded:  Database\Seeders\DocumentSeeder (1,957.38ms)
    Database seeding completed successfully.

Enfin, le serveur de développement Laravel se déploiera et le site sera accessible à l'adresse  [http://127.0.0.1:8000](http://127.0.0.1:8000) :

    php artisan serve
    Starting Laravel development server: http://127.0.0.1:8000
    [Mon Dec  6 22:23:45 2021] PHP 8.0.13 Development Server (http://127.0.0.1:8000) started

### 5) Configurer les tests

Pour les tests, j'utilise sqlite. Il faut créer un fichier .env.testing contenant les informations suivantes :

    DB_CONNECTION=sqlite
    CACHE_DRIVER=array

### 6) Lancer les tests

Lancer les commandes suivantes :

    php artisan config:cache --env=testing
    php artisan migrate --database=sqlite
    php artisan test --testsuite=Feature --stop-on-failure

## A propos des choix techniques

- J'ai créé trois modèles

