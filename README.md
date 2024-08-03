# Laravel Simple Ticket System with Admin and Agent

Welcome to the Simple Ticket System project built with Laravel and Filament! This README will guide you through setting up and using the ticket system, which includes features for both admins and agents.

## Table of Contents

1. [Project Overview](#project-overview)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Usage](#usage)

## Project Overview

The Laravel Simple Ticket System is a basic ticket management application designed to streamline support requests. It features distinct roles for admins and agents, allowing for efficient handling and tracking of tickets. Built using Laravel for backend logic and Filament for the admin panel, this system offers a clean and user-friendly interface.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm (for managing frontend assets)
- MySQL or another supported database

## Installation

To set up the ticket system application, follow these steps:

1. Clone the repository: `git clone https://github.com/qaisnezami/ticketsystem.git`
2. Configure environment variables: Run `cd ticketsystem && cp .env.example .env` ,
3. Install composer: `composer install`
4. Install NPM: `npm install`
5. Generate application key: `php artisan key:generate`
6. Run migrations: `php artisan migrate` (This command sets up the database tables based on defined migrations)
7. (Optional) Seed the database: `php artisan db:seed` (This command populates the database with sample data, if available)
8. Run npm dev `npm run dev`,
9. Link Storage `php artisan storage:link`
10. Run Application `php artisan serve`,



## Usage
The application should be available at `http://127.0.0.1:8000/admin/login`,
You can login with these credentials
Admin Email : `admin@me.com`
Admin Password : `password`

Agent Email : `agent@me.com`
Agent Password : `password`





