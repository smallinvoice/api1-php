<?php

require_once __DIR__ . '/../vendor/autoload.php';

use smallinvoice\Smallinvoice;

$smallinvoice = new Smallinvoice('INSERTYOURTOKEN');

try {
    /*
     * Creating address object using standard class
     */
    $address           = new \stdClass();
    $address->primary  = 1;
    $address->street   = "Examplestreet";
    $address->streetno = "10";
    $address->street2  = "";
    $address->code     = 8000;
    $address->city     = "Zurich";
    $address->country  = "CH";

    /*
     * Creating client object using standard class and attaching collection of addresses' objects
     */
    $client            = new \stdClass();
    $client->type      = 1;
    $client->gender    = 1;
    $client->name      = 'ACME GmbH';
    $client->language  = 'en';
    $client->addresses = array($address);

    $clientAddResponse = $smallinvoice->client_add($client);
    $clientGetResponse = $smallinvoice->client_get($clientAddResponse->id);

    /*
     * Creating position object using array object class
     */
    $position = new \ArrayObject(array(
        'type'        => 2,
        'number'      => 'X25',
        'name'        => 'Product X25',
        'description' => 'Enjoy sailing your boat with the new X25 Yaaadrive',
        'cost'        => 1000.85,
        'unit'        => 7,
        'amount'      => 1,
        'vat'         => 7.7,
        'discount'    => 0
    ));

    /*
     * Creating invoice object using array object class and attaching collection of positions' array objects
     */
    $invoice = new \ArrayObject(array(
        //'number'            => 'N-34245', //Optional, if you would like to specify the invoice number yourself
        'client_id'         => $clientGetResponse->item->id,
        //'client_address_id' => $clientGetResponse->item->addresses[0]->id, //Optional if you would like a different address
        //'client_contact_id' => $clientGetResponse->item->contacts[0]->id, //Optional if you would like to have a contact
        'introduction'      => 'Thank you for buying our products',
        'conditions'        => 'Please pay as soon as possible',
        'currency'          => 'CHF',
        'date'              => '2018-03-01',
        'due'               => '2018-03-31',
        'language'          => 'en',
        'vat_included'      => 0,
        'discount'          => 5, //5 % discount
        'discount_type'     => 0, //Set to 1 if you are not passing % in 'discount'
        'positions'         => array($position)
    ));

    $response = $smallinvoice->invoice_add($invoice);

	echo "Finished" . PHP_EOL;
} catch (\Exception $e) {
    echo 'An error occured with number <b>' . $e->getCode() . '</b> and message <b>' . $e->getMessage() . '</b>' . PHP_EOL;
}