<?php

# Include the Autoloader (see "Libraries" for install instructions)
require '../assets/vendor/autoload.php';

use Mailgun\Mailgun;

$mgClient = Mailgun::create('438d0ee511bc66a813b9ac5cade3d59a-fe066263-cb55b91f', 'https://api.mailgun.net/v3/sandboxafc130ff998a4d8a801bd44223a416b4.mailgun.org');
$domain = "sandboxafc130ff998a4d8a801bd44223a416b4.mailgun.org";
$params = array(
  'from'    => 'Excited User <YOU@YOUR_DOMAIN_NAME>',
  'to'      => 'test-jx6vwz80m@srv1.mail-tester.com',
  'subject' => 'Hello',
  'text'    => 'Testing some Mailgun awesomness!'
);

# Make the call to the client.
$mgClient->messages()->send($domain, $params);