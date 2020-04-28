<a href="https://www.twilio.com">
  <img src="https://static0.twilio.com/marketing/bundles/marketing/img/logos/wordmark-red.svg" alt="Twilio" width="250" />
</a>

# Block Spam Calls. Powered by Twilio - PHP/Laravel

![](https://github.com/TwilioDevEd/block-spam-calls-php/workflows/Laravel/badge.svg)

> We are currently in the process of updating this sample template. If you are encountering any issues with the sample, please open an issue at [github.com/twilio-labs/code-exchange/issues](https://github.com/twilio-labs/code-exchange/issues) and we'll try to help you.

Learn how to use Twilio add-ons to block spam calls.

Follow the beginning of the [Block Spam Calls and RoboCalls guide](https://www.twilio.com/docs/voice/tutorials/block-spam-calls-and-robocalls-python) to learn how to add the spam filtering add-ons.


## Local development

1. First you need to install [PHP](http://php.net/manual/en/install.php).

1. Clone this repository and `cd` into its directory:
   ```bash
   git clone https://github.com/TwilioDevEd/block-spam-calls-php.git
   cd block-spam-calls-php
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
https://<your-ngrok-subdomain>.ngrok.io/api/voice
```

## Run the tests

You can run the tests locally running:

```bash
./vendor/bin/phpunit
```

## Meta

* No warranty expressed or implied. Software is as is. Diggity.
* The CodeExchange repository can be found [here](https://github.com/twilio-labs/code-exchange/).
* [MIT License](http://www.opensource.org/licenses/mit-license.html)
* Lovingly crafted by Twilio Developer Education.
