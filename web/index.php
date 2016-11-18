<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Stripe\Stripe::setApiKey(getenv('STRIPE_TEST_SECRET_KEY'));

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->get('/', function () use ($app) {
  return $app['twig']->render('index.twig');
});

$app->post('/pay-ideal', function (Request $request) use ($app) {
  $owner_name = $request->get('owner-name');
  $return_url = $request->getUriForPath('/continue');
  $source = \Stripe\Source::create(array(
    'type' => 'ideal',
    'amount' => 1099,
    'currency' => 'eur',
    'owner' => array('name' => $owner_name),
    'redirect' => array('return_url' => $return_url),
  ));
  return $app->redirect($source->redirect->url);
});

$app->get('/continue', function (Request $request) use ($app) {
  return $app['twig']->render('continue.twig', array(
    'request' => $request,
    'publishable_key' => getenv('STRIPE_TEST_PUBLISHABLE_KEY'),
  ));
});

$app->post('/webhook', function (Request $request) use ($app) {
  $event = json_decode($request->getContent());
  if ($event->type == 'source.chargeable') {
    $source = $event->data->object;
    $charge = \Stripe\Charge::create(array(
      'amount' => $source->amount,
      'currency' => $source->currency,
      'source' => $source->id,
      'description' => "iDEAL payment for " . $source->owner->name,
    ));
  }
  return new Response('', 200);
});

$app->run();
