# joke-sender

## Installation
   Requirements:
   * PHP 7.2.*
   * Composer
   
```
$ cd app
$ composer install
```
## Start server

In app/.env file set username and password from google mail.
For example

```
GMAIL_USERNAME=user # if your email user@gmail.com
GMAIL_PASSWORD=pass
```
Allow Google account access for insecure apps

Run server

```
$ symfony server:start
``` 

Go to http://localhost:8000

## Jokes log

After sending the joke to the user's mail, it is written to a file in the temporary files directory of your PHP.
You can get the directory path as follows
```
$ php -a
Interactive shell

php > echo sys_get_temp_dir();
```

## Testing
All tests are stored in a directory app/tests.
Tests run
```
$ ./bin/phpunit
```

Tests run with coverage
```
$ ./bin/phpunit  --coverage-html ./tests/coverage_report/
```
