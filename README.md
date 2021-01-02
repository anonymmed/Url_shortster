# URL Shortster

URL Shortster is a RESTful API written with PHP (Symfony 5 framework) to to help users shorteners the provided URLs.

## Installation

Use the package manager [composer](https://getcomposer.org/download/) to install the app. Go to the home directory where you'll find composer.json file.

```bash
composer install
```

## Usage
To execute the API services run the folowing command.
```bash
symfony serve
```
You'll be able to access to the API on the folowing default url:

#### GET https://127.0.0.1:8000/api/v1 => api home
#### POST https://127.0.0.1:8000/api/v1/createNewUrl : 
##### Note: shortCode is optional, if not provided a random shortCode will be generated. This will create a shortened URL for you.

body => {"originalUrl" : "xxx", "shortCode": "xxxx"}
#### Or 
body => {"originalUrl" : "xxx"}


#### GET https://127.0.0.1:8000/api/v1/{shortCode}/stats:
##### {shortCode} is your url shortCode. This will get you the URL stats

#### GET https://127.0.0.1:8000/{shortCode}
##### {shortCode} is your url shortCode. This will redirect you to the original url.


## Test
To execute the Test Cases run the folowing command.
```bash
php bin/phpunit
```

## License
[MIT](https://choosealicense.com/licenses/mit/)
