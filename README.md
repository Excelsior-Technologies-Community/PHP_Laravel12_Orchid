# PHP_Laravel12_Orchid


## Project Description

PHP_Laravel12_Orchid is a web-based project built using Laravel 12 and the Orchid Platform.
The main purpose of this project is to create a ready-made admin panel that allows administrators to manage application data easily without writing frontend code.

Orchid provides a powerful dashboard system with built-in components such as forms, tables, charts, layouts, and user management, all written in PHP.

## Project Purpose

The purpose of this project is to:

- Build a secure admin dashboard using Laravel 12

- Reduce development time for admin panels

- Demonstrate how Orchid integrates with Laravel

- Manage users and application data efficiently

- Follow clean and scalable Laravel architecture


## Technologies Used

- PHP

- Laravel 12

- Orchid Platform

- MySQL

- Composer


## Key Features

- Secure admin login

- User management

- Forms, tables, charts

- Clean Laravel architecture

## Use Case

- Admin panels

- CMS dashboards

- Backend management systems


---


# Project Setup 

---

## STEP 1: Create New Laravel 12 Project

### Run Command :

```
composer create-project laravel/laravel PHP_Laravel12_Orchid "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Orchid

```

##### Explanation:

Creates a fresh Laravel 12 application.

Laravel 12 is required for modern PHP and Orchid compatibility.



## STEP 2: Generate App Key

### Run:

```
php artisan key:generate

```

#### Explanation:

Generates APP_KEY used for encryption and sessions.



## STEP 3: Database Configuration

### Open .env file and update database credentials:

```
APP_NAME=LaravelOrchid
APP_URL=http://localhost


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_orchid
DB_USERNAME=root
DB_PASSWORD=


```

### Create database:

```
laravel12_orchid

```


### Then Run:

```
php artisan migrate

```


##### Explanation:

Creates default Laravel tables in MySQL.




## STEP 4: Install Orchid Package

### Run:

```
composer require orchid/platform

```

##### Explanation:

Installs Orchid admin panel package.




## STEP 5: Run Orchid Install Script

## Run:

```
php artisan orchid:install

```

#### What happens now:

- Orchid configuration published

- config/platform.php created

- Orchid tables migrated

- Admin UI installed

- User model prepared for Orchid



## STEP 6: Create Admin User

## Run:

```
php artisan orchid:admin demo demo123@gmail.com Demo@123

```

Change the name/email/password to your values.

#### Explanation:

Creates admin user with full Orchid permissions.



## STEP 7: Serve the Application

### Start Laravel development server:

```
php artisan serve

```

### Now visit:

```
http://localhost:8000/admin

```

You should see the Orchid login screen.

Log in with the admin credentials you created.


## So you can see this type Output:


### Laravel Orchid Login Page:


<img width="1815" height="961" alt="Screenshot 2026-02-09 101356" src="https://github.com/user-attachments/assets/863f5712-c2a3-4b26-b7e1-a88c3f95259d" />


### Get Strated Page:


<img width="1833" height="966" alt="Screenshot 2026-02-09 101428" src="https://github.com/user-attachments/assets/11474ccc-bba0-44fb-8bf0-76ddce876d39" />


### Sample Screen Page:


<img width="1829" height="965" alt="Screenshot 2026-02-09 101446" src="https://github.com/user-attachments/assets/130a0eca-7c37-4555-bdd5-6c10de939ca8" />


### Form Elements Page:


<img width="1825" height="973" alt="Screenshot 2026-02-09 101513" src="https://github.com/user-attachments/assets/3d80ceca-9e19-4c75-bb3e-26adf62b31f4" />


### Layouts Overview Page:


<img width="1811" height="959" alt="Screenshot 2026-02-09 101530" src="https://github.com/user-attachments/assets/4587c9dd-c93c-4ef7-8103-8c8e585cbc99" />


### Grid System Page:


<img width="1826" height="966" alt="Screenshot 2026-02-09 101539" src="https://github.com/user-attachments/assets/550e1180-24b6-4b21-8034-6990b1302ea6" />


### Charts Page:


<img width="1822" height="967" alt="Screenshot 2026-02-09 101549" src="https://github.com/user-attachments/assets/897caa96-c468-4b81-b640-b85d2d70bd4a" />


### Cards Page:


<img width="1823" height="963" alt="Screenshot 2026-02-09 101612" src="https://github.com/user-attachments/assets/f4e21480-9d9b-4a93-9698-a3c72c1c883b" />


### Users Page:


<img width="1825" height="969" alt="Screenshot 2026-02-09 101624" src="https://github.com/user-attachments/assets/aca7ca49-f947-434f-8b04-283b3c67589c" />



### User Profile Page:


<img width="1830" height="966" alt="Screenshot 2026-02-09 110355" src="https://github.com/user-attachments/assets/c2795316-5391-490b-893b-0bbb065ce912" />




---


# Project Folder Structure:

```

PHP_Laravel12_Orchid/
├── app/
│   ├── Models/
│   │   └── User.php              ← Orchid-ready User model
│   ├── Orchid/
│   │   ├── Screens/              ← Orchid admin screens
│   │   └── PlatformProvider.php
│   └── Http/
├── config/
│   └── platform.php              ← Orchid config
├── database/
│   └── migrations/
├── routes/
│   ├── web.php
│   └── platform.php              ← Orchid admin routes
├── resources/
├── vendor/
├── .env
├── artisan
└── composer.json

```
