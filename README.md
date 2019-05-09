<h1 align="center">LARAvel + CENTrifugo = Laracent</h1>
<h2 align="center">Centrifugo broadcast driver for Laravel 5</h2>

<p align="center">
<a href="https://travis-ci.org/AlexHnydiuk/laracent"><img src="https://travis-ci.org/AlexHnydiuk/laracent.svg?branch=master" alt="Build Status"></a>
<a href="https://github.com/AlexHnydiuk/laracent/releases"><img src="https://img.shields.io/github/release/AlexHnydiuk/laracent.svg?style=flat-square" alt="Latest Version"></a>
<a href="https://scrutinizer-ci.com/g/AlexHnydiuk/laracent"><img src="https://img.shields.io/scrutinizer/g/AlexHnydiuk/laracent.svg?style=flat-square" alt="Quality Score"></a>
<a href="https://github.styleci.io/repos/171129986"><img src="https://github.styleci.io/repos/171129986/shield?branch=master" alt="StyleCI"></a>
<a href="https://packagist.org/packages/alex-hnydiuk/laracent"><img src="https://img.shields.io/packagist/dt/alex-hnydiuk/laracent.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://github.com/AlexHnydiuk/laracent/blob/master/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="Software License"></a>
</p>


## Introduction
Centrifugo broadcaster for laravel >= 5.7 is fork of [centrifuge-broadcaster](https://github.com/LaraComponents/centrifuge-broadcaster), based on:
- [LaraComponents/centrifuge-broadcaster](https://github.com/LaraComponents/centrifuge-broadcaster)
- [centrifugal/phpcent](https://github.com/centrifugal/phpcent)

## Changenotes
- predis/predis removed from dependencies
- Removed all code, related to Redis
- Updated public functions code in accordance with new version of Centrifugo API  

## Requirements

- PHP 7.2.12+ or newer
- Laravel 5.7 or newer
- Centrifugo Server 2.1.0 or newer (see [here](https://github.com/centrifugal/centrifugo))

## Dependencies

- guzzlehttp/guzzle

## Installation

Require this package with composer:

```bash
composer require alex-hnydiuk/laracent
```

Open your config/app.php and add the following to the providers array:

```php
'providers' => [
    // ...
    AlexHnydiuk\Laracent\LaracentServiceProvider::class,

    // And uncomment BroadcastServiceProvider
    App\Providers\BroadcastServiceProvider::class,
],
```

Open your config/broadcasting.php and add new connection like this:

```php
    'centrifugo' => [
        'driver' => 'centrifugo',
        'secret'  => env('CENTRIFUGO_SECRET'),
        'apikey'  => env('CENTRIFUGO_APIKEY'),
        'url'     => env('CENTRIFUGO_URL', 'http://localhost:8000'), // centrifugo api url
        'verify'  => env('CENTRIFUGO_VERIFY', false), // Verify host ssl if centrifugo uses this
        'ssl_key' => env('CENTRIFUGO_SSL_KEY', null), // Self-Signed SSl Key for Host (require verify=true)
    ],
```

Also you should add these two lines to your .env file:

```
CENTRIFUGO_SECRET=very-long-secret-key-from-centrifugo-config
CENTRIFUGO_APIKEY=long-secret-apikey-from-centrifugo-config
```

These lines are optional:
```
CENTRIFUGO_URL=http://localhost:8000
CENTRIFUGO_SSL_KEY=/etc/ssl/some.pem
CENTRIFUGO_VERIFY=false
```

Don't forget to change `BROADCAST_DRIVER` setting in .env file!

```
BROADCAST_DRIVER=centrifugo
```

## Basic Usage

To configure Centrifugo server, read [official documentation](https://centrifugal.github.io/centrifugo/)

For broadcasting events, see [official documentation of laravel](https://laravel.com/docs/5.7/broadcasting)

A simple client usage example:

```php
<?php

namespace App\Http\Controllers;

use AlexHnydiuk\Laracent\Laracent;

class ExampleController extends Controller
{
    public function home(Laracent $centrifugo)
    {
        // Send message into channel
        $centrifugo->publish('channel-name', [
            'key' => 'value'
        ]);

        // Generate connection token
        $token = $centrifugo->generateConnectionToken('user id', 'timestamp', 'info');

        // Generate private channel token
        $apiSign = $centrifuge->generatePrivateChannelToken('client', 'channel', 'timestamp', 'info');

        // ...
    }
}
```

### Available methods

| Name | Description |
|------|-------------|
| publish(string $channel, array $data) | Send message into channel. |
| broadcast(array $channels, array $data) | Send message into multiple channel. |
| presence(string $channel) | Get channel presence information (all clients currently subscribed on this channel). |
| presence_stats(string $channel) | Get channel presence information in short form (number of clients).|
| history(string $channel) | Get channel history information (list of last messages sent into channel). |
| history_remove(string $channel) | Remove channel history information.
| unsubscribe(string $channel,  string $user) | Unsubscribe user from channel. |
| disconnect(string $user_id) | Disconnect user by it's ID. |
| channels() | Get channels information (list of currently active channels). |
| info() | Get stats information about running server nodes. |
| generateConnectionToken(string $userId, int $exp, array $info)  | Generate connection token. |
| generatePrivateChannelToken(string $client, string $channel, int $exp, array $info) | Generate private channel token. |

## License

The MIT License (MIT). Please see [License File](https://github.com/LaraComponents/centrifuge-broadcaster/blob/master/LICENSE) for more information.
