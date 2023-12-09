<p align="center">
    <a href="#" ><img src="https://raw.githubusercontent.com/starmoozie/keuangankudewe/dev/images/report.png" alt="Report"></a>
</p>

## About App

-   Aplikasi keuangan sederhana

## Fitur

-   Pencatatan keluar masuknya uang

## Default Menu

-   Permission Menu
    -   Permission
    -   Menu
    -   Route
-   User Management
    -   Role
    -   User
-   Master
    -   Transaction Category
    -   Bank
-   Transaction
    -   Income
    -   Expense
    -   Report

## Install

-   `composer install`
-   `php artisan starmoozie:install`
-   `php artisan migrate --seed`
-   `php artisan db:seed --class=RouteSeeder`
-   `php artisan db:seed --class=MenuSeeder`

## Default User

-   Email `starmoozie@gmail.com`
-   Password `password`
