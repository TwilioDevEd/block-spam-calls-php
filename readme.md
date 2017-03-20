<a href="https://www.twilio.com">
  <img src="https://static0.twilio.com/marketing/bundles/marketing/img/logos/wordmark-red.svg" alt="Twilio" width="250" />
</a>

# Block Spam Calls. Powered by Twilio - PHP/Laravel
[![Build
Status](https://travis-ci.org/TwilioDevEd/block-spam-calls-php.svg?branch=master)](https://travis-ci.org/TwilioDevEd/block-spam-calls-php)

Learn how to use Twilio add-ons to block spam calls.

## Local development

First you need to install [PHP](http://php.net/manual/en/install.php) and [Laravel](https://laravel.com/docs/5.4).

### Windows
To install PHP on Windows installation is straightforward with [Chocolatey](https://chocolatey.org/install):
1. First install PHP
    ```bash
    choco install php -y
    ```

1. Enable required extensions in `php.ini`
    ```bash
    # Uncomment the following line:
    ;extension=php_openssl.dll
    ;extension=php_mbstring.dll
    ```
1. Install Composer
    ```bash
    choco install composer -y
    ```

### The following steps apply to Windows and all other platforms

1. First clone this repository and `cd` into its directory:
   ```bash
   git clone https://github.com/TwilioDevEd/block-spam-calls-php.git
   cd block-spam-calls-php
   ```

1. Install Laravel
    ```bash
    composer global require "laravel/installer"
    ```

1. Install dependencies:

    ```bash
    composer install --no-interaction
    ```

1. Create the `.env` file with the default application settings:
    ```bash
    cp .env.example .env
    ```

1. Generate the `APP_KEY`:
    ```bash
    php artisan key:generate
    ```

1. Run the application.

    ```bash
    php artisan serve
    ```

To actually forward incoming calls, your development server will need to be publicly accessible. [We recommend using ngrok to solve this problem](https://www.twilio.com/blog/2015/09/6-awesome-reasons-to-use-ngrok-when-testing-webhooks.html).

Once you have started ngrok, update your TwiML app's voice URL setting to use your ngrok hostname, so it will look something like this:

```bash
http://88b37ada.ngrok.io/api/voice
```

## Run the tests

You can run the tests locally running:

```bash
./vendor/bin/phpunit
```

## Meta

* No warranty expressed or implied. Software is as is. Diggity.
* [MIT License](http://www.opensource.org/licenses/mit-license.html)
* Lovingly crafted by Twilio Developer Education.
