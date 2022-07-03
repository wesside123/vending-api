# Vending API

A simple REST service which will support a beverage vending machine that is tested via HTTP.

## Requirements

This is a LAMP installation. An Apache webserver that has PHP 7.1.32 and a MySQL 5.7.26 database is required. 

## Installation

Place the files on your webserver and edit the ```configVending.php``` to match your MySQL credentials.

```bash
define("DB_HOST", "DB_HOST_HERE");
define("DB_USERNAME", "USERNAME_HERE");
define("DB_PASSWORD", "PASSWORD_HERE");
define("DB_DATABASE_NAME", "DB_NAME_HERE");
```
## Usage

With your favorite API client the following HTTP requests can be made:

### Requests

#### Coin accepted:

```PUT http://simple-rest-api-php:8888/index.php/```

Request Body:

```
{
“coin”: 1 
}
```

Response Code:

```204 OK```

Response Header:

```X-Coins: $[# of coins accepted]```

#### Coins returned:

```DELETE http://simple-rest-api-php:8888/index.php/```

Response Code:

```204 OK```

Response Header:

```X-Coins: $[# of coins to be returned]```

#### Get an array of integers of remaining item quantities:

```GET http://simple-rest-api-php:8888/index.php/inventory```

Response Code:

```200 OK```

Response Body:

```[#,#,#]```

#### Get remaining item quantity (an integer) where ```id=#``` is a zero-based indexed list of three options:

```GET http://simple-rest-api-php:8888/index.php/inventory?id=#```

Response Code:

```200 OK```

Response Body:

```[#]```

#### Vend an item:

```PUT http://simple-rest-api-php:8888/index.php/inventory?id=#```

Response Code:

```200 OK```

Response Headers:

```X-Coins: $[# of coins to be returned] X-Inventory-R emaining: $[item quantity]```

Response Body:

```
{
“quantity”: $[number of items vended]
}
```

#### Attempt to vend an item out of stock:

```PUT http://simple-rest-api-php:8888/index.php/inventory?id=#```

Response Code:

```404 OK```

Response Headers:

```X-Coins: $[# of coins accepted]```

#### Attempt to vend with insufficient coins:

```PUT http://simple-rest-api-php:8888/index.php/inventory?id=#```

Response Code:

```403 OK```

Response Headers:

```X-Coins: $[0|1]```