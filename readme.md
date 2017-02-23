<a href="https://www.twilio.com">
  <img src="https://static0.twilio.com/marketing/bundles/marketing/img/logos/wordmark-red.svg" alt="Twilio" width="250" />
</a>

# Block Spam Calls. Powered by Twilio - PHP/Laravel
[![Build
Status](https://travis-ci.org/TwilioDevEd/block-spam-calls-php.svg?branch=master)](https://travis-ci.org/TwilioDevEd/block-spam-calls-php)

Learn how to use Twilio add-ons to block spam calls.

## Local development

First you need to install [PHP](http://php.net/manual/en/install.php) and [Laravel](https://laravel.com/docs/5.4).

To run the app locally, clone this repository and `cd` into its directory:

1. First clone this repository and `cd` into its directory:
   ```bash
   git clone git@github.com:TwilioDevEd/block-spam-calls-php.git

   cd block-spam-calls-php
   ```

1. Install dependencies:

    ```bash
    composer install --no-interaction
    ```

1. Run the application.

  ```bash
  $ php artisan serve
  ```

To actually forward incoming calls, your development server will need to be publicly accessible. [We recommend using ngrok to solve this problem](https://www.twilio.com/blog/2015/09/6-awesome-reasons-to-use-ngrok-when-testing-webhooks.html).

Once you have started ngrok, update your TwiML app's voice URL setting to use your ngrok hostname, so it will look something like this:

```bash
http://88b37ada.ngrok.io/api/voice
```

1. Check it out at [http://localhost:8000](http://localhost:8000)

That's it

## Run the tests

You can run the tests locally running:

```bash
./vendor/bin/phpunit
```

## Meta

* No warranty expressed or implied. Software is as is. Diggity.
* [MIT License](http://www.opensource.org/licenses/mit-license.html)
* Lovingly crafted by Twilio Developer Education.
