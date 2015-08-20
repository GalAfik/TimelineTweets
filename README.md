# Twitter API - User Timeline Example

## About

This application combines a JavaScript XHR request with a PHP script to pull down Tweets from a user's timeline.

## Setup

Locate the `config/auth-temp.ini` and create a copy called `config/auth.ini`. This file must be kept secret and it will not be tracked by git.

Populate the configuration options with your token and key information.

### auth.ini

Supply your access tokens/keys for the options in the authentication configuration file.

```ini
; Twitter OAuth Config options

[OAuth]
oauth_access_token = "Access Key"
oauth_access_token_secret = "Access Secret"

[Consumer]
consumer_key = "Consumer Key"
consumer_secret = "Consumer Secret"
```

## Example

The following example pulls down the five; most recent Tweets from Bill Gate's Twitter timeline.

    http://localhost/TimelineTweets/index.html?user_id=50393960&count=5

The response is in JSON format using Twitter's v1.1 API with OAuth.

## Attributions

Based off of Andy Fitch's [twitter-proxy][1] Github project.

  [1]: https://github.com/andyfitch/twitter-proxy
