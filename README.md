## About Application
I code this application for demonstration of how to integrate PayPal REST API payment with Laravel. The main packages are "league/omnipay" and "omnipay/paypal". Current fully tested with Laravel 7.0.

## Installation and Configuration
1. Clone this repository.
2. Copy .env.example to .env
3. Run "composer install" to install package.
4. Run "php artisan key:generate" to generate application key.
5. Create database and config database name, database user and password in .env file.
6. Migrate database using "php artisan migrate"
7. Sign up for PayPal developer. You will have two development user one as a buyer and one as a seller.
8. Edit PAYPAL_SANDBOX_CLIENT_ID and PAYPAL_SANDBOX_SECRET in .env file. These two parameters you got from PayPal.
9. Run the application using "php artisan serve"
10. Enjoy testing.

## Contributing



## Code of Conduct

In order to ensure that the open source community is welcoming to all, please review and abide by the [Code of Conduct]().

## Security Vulnerabilities

If you discover a security vulnerability within application, please send an e-mail to Prasit Gebsaap via [prasit.gebsaap@gmail.com](mailto:rasit.gebsaap@gmail.com). All security vulnerabilities will be promptly addressed.

## License

The application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
