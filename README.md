# Instructions

## Setup

- Clone repository code
- Copy `.env.example` to `.env`
- Import database
- Run `php artisan serve`

## Usage

To reads RSS feed and import currency rates from `bank.lv` containing last 4 workdays.
- Run `php artisan currency:import`


To read `X` number of days from `bank.lv` and imports currency history.
- Run `php artisan currency:import:date X`
