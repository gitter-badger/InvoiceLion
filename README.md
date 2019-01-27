# <img src="./web/img/invoicelion_icon.png" alt="logo" height="48" /> InvoiceLion

InvoiceLion is free invoicing software for entrepreneurs.

## As a service

We run a production instance of this software on [InvoiceLion.com](https://www.invoicelion.com) and you can use it for FREE.
We promise to never make a profit from this service, but we may ask for a (optional) donation in order to pay for our servers.

## Download and install

If you want to have full control over your invoicing system, then you can install this software on your own server.

## Requirements

- PHP 7 with `php-mysql`, `php-mbstring` and `php-dom` extensions enabled
- MariaDB 10.1 (or MySQL 5.6) or higher

## Installation

- unzip the source code
- run `composer install` to install dependencies
- create the database using `create_db.sql` (adjust the database credentials)
- initiate the database using `db_structure.sql`
- copy `config/config.php.template` to `config/config.php` and fill in the database credentials

## Running

Point the `DocumentRoot` of Apache (or `root` of Nginx) to the `web` folder of the project.

## Support

Please use the software, report bugs and ask for features using the [Github issues](https://github.com/Usecue/InvoiceLion/issues)!

NB: No costs and no guarentees. Please be kind to each other and help each other out!
