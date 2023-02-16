# SureClaims

## Local Development Setup

### Backend Application
1. Install composer dependencies.
2. Copy the `.env.example` file to `.env` and updated the entries to match your local development environment. Take note of the `LIMIT_UUID_LENGTH_32` setting. If you are using MySQL, earlier versions limit the database usernames up to 32 characters max. Set `LIMIT_UUID_LENGTH_32` to `true` if this is the case. If you are using MariaDB, you can omit this setting.
3. Run the database migrations for the system connection. 
    ```
    php artisan migrate --database=system
    ```
4. Seed the `global_lookups` table
    ```
    php artisan db:seed --database=system --class=GlobalLookupsSeeder
    ```
5. Run the `sureclaims:make:customer` command to generate a Hostname + Website + Customer pairing.  This also creates a new tenant database for and run all tenant migrations for the new database if all goes well. 
    ```
    php artisan sureclaims:make:customer sureclaims.test
    ```
6. See the followup section for setting up the development environment.

#### Development Configurations

#####  Via Artisan Serve
The **Sureclaims Frontend** application is configured to connect to http://localhost:10000 by default. Just run the artisan command `serve` with the corresponding `--port` option.
```
php artisan serve --port=10000
```
##### Vagrant 
*TODO: Steps for setting up the backend application using Vagrant*

##### Laragon
*TODO: Steps for setting up the development environment using Laragon*

##### WAMP/XAMPP
*TODO: Steps for setting up the development environment using WAMP/XAMPP*

##### WTServer/WinNMP
*TODO: Steps for setting up the development environment using WTServer/WinNMP*

### Frontend Application

1. Install all npm dependencies 
    ```
    yarn install
    ```
1. Serve the frontend application.
    ```
    npm run dev
    ```
1. Log in to the system using `demo@sureclaims.co` as the username and `P@ssw0rd` as the password.

### Issues

*Address commonly encountered installation issues here...*

## QA/Testing Setup

This is a guide to access our test server [**spmc.sureclaims.io**](http://spmc.sureclaims.io).

1. Add this text to your C:\Windows\System32\drivers\etc\hosts file.
    ```
    52.77.245.190  spmc.sureclaims.io
    ```

2. Visit [spmc.sureclaims.io](http://spmc.sureclaims.io) in your browser.

3. Credentials:
    ```    
    Username: demo@somc.gov.ph
    Password: P@ssw0rd
    ```

## Production Setup

*Describe how to deploy the application in a production environment here ...*

