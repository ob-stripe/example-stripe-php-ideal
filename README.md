# Stripe iDEAL PHP example

This is a simple example project illustrating how to implement [Stripe's iDEAL payment method](https://stripe.com/docs/ideal) to accept iDEAL payments with a PHP backend.

## Use with Heroku

Deploy the project on your Heroku account:

[![Deploy](https://www.herokucdn.com/deploy/button.png)](https://heroku.com/deploy)

When deploying, you'll be prompted for your publishable and secret API keys (you can find both of these in the [API keys](https://dashboard.stripe.com/account/apikeys) tab of your account settings).

Once the app is running on Heroku, head back to your [webhooks](https://dashboard.stripe.com/account/webhooks) settings and add a test webhook endpoint with your app's URL followed by `/webhooks.php`. E.g. if the URL of the Heroku app is `https://thawing-crag-63114.herokuapp.com`, set the endpoint's URL to be `https://thawing-crag-63114.herokuapp.com/webhooks.php`. The webhook must be set to receive at least `source.chargeable` events.
