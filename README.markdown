PHP Last.fm API
===============

A PHP (5.3+) client library for the [Last.fm API][lastfm-api].

Quick start
-----------

The main entry point of the library is the `Lastfm\Client` class. Once you have
a Client instance, you can easily access all the API services and call their
methods.

Let's try to find a track using the `search` method of the `track` service:

```php
<?php

$client = new Lastfm\Client('MyApiKey');

$tracks = $client->getTrackService()->search(array(
    'track' => 'No cars go'
));

foreach ($tracks as $track) {
    echo $track['name'];
}

```

### The API Key

All the API methods require you to specify an `api_key` parameter. Luckily, the
client is smart enough to add it automatically to all your API calls. To turn
this functionality on, you simply need to configure the client's API key by
either providing it as first argument of the constructor or setting it after
using the `->setApiKey()` method.

```php
<?php

$client = new Lastfm\Client('MyApiKeyABC123');
```

### The Method Signature

Some of the API methods requires you to add a *method signature* to their calls.
Fortunately, the Client will automatically create it and add it to the request
when needed. Prior to use such methods, you must define the client's API shared
*secret*. To do that, you can either specify the secret as second argument of
the client's constructor or set it using the `->setSecret()` method.

```
<?php

$client = new Lastfm\Client('MyApiKeyABC123', 'TheSecretXxx');
```

### The Session

The session is used to authenticate the client on the Last.fm API. I will not
explain all the Last.fm authentication mechanisms here. The only thing you must
to know is that some methods requires the client to be authenticated. Prior to
use such methods, you must define the client's session by either passing it as
third argument of the constructor or setting it using the `->setSession()`
method.

```php
<?php

$session = new Lastfm\Session('John', 'TheSessionKey');
$client = new Lastfm\Client('MyApiKey', 'TheSecret', $session);
```
[lastfm-api]: http://www.lastfm.fr/api
