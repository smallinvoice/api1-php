<?php

include "Smallinvoice.php";

$objSmallinvoice = new Smallinvoice('mytoken');

try {
    /*
     * Creating address object using standard class
     */
    $objAddress = new stdClass();
    $objAddress->primary = 1;
    $objAddress->street = "Longstreet";
    $objAddress->streetno = "5";
    $objAddress->street2 = "";
    $objAddress->code = 3122;
    $objAddress->city = "Kehrsatz";
    $objAddress->country = "CH";

    /*
     * Creating client object using standard class and attaching collection of addresses' objects
     */
    $objClient = new stdClass();
    $objClient->type = 1;
    $objClient->gender = 1;
    $objClient->name = 'ACME GmbH';
    $objClient->language = 'de';
    $objClient->addresses = array($objAddress);

    $objClientResponse = $objSmallinvoice->client_add($objClient);
    $objClientResponse = $objSmallinvoice->client_get($objClientResponse->id);

    /*
     * Creating position object using array object class
     */
    $objPosition = new ArrayObject(array(
        'type' => 1,
        'number' => 'X-25',
        'name' => 'Product X 25',
        'description' => 'Enjoy sailing your boat with the new X 25',
        'cost' => 100.85,
        'unit' => 7,
        'amount' => 1,
        'vat' => 8,
        'discount' => 0
    ));

    /*
     * Creating invoice object using array object class and attaching collection of positions' array objects
     */
    $objInvoice = new ArrayObject(array(
        //'number' => 'N-34245', //Optional, if you would like to specify the invoice number yourself
        'client_id' => $objClientResponse->item->id,
        //'client_address_id' => $objClientResponse->item->addresses[0]->id, //Optional if you would like a different address
        //'client_contact_id' => $objClientResponse->item->contacts[0]->id,  //Optional if you would like to have a contact
        'introduction' => 'Thank you for buying our products',
        'conditions' => 'Please pay as soon as possible',
        'currency' => 'CHF',
        'date' => date('Y-m-d'),
        'due' => date('Y-m-d'),
        'language' => 'de',
        'vat_included' => 0,
        'esr' => 0, //If you are using the swiss ESR set this to 1, ESR+ = 2, Red ES = 3
        'discount' => 5, //5 % discount
        'discount_type' => 0, //Set to 1 if you are not passing % in 'discount'
        'positions' => array($objPosition)
    ));

    $objInvoiceResponse = $objSmallinvoice->invoice_add($objInvoice);

    /*
     * Sending an invoice
     */
    $objSmallinvoice->invoice_post($objInvoiceResponse->id, new ArrayObject(array('speed' => 1)));

} catch (Exception $e) {
    echo 'An error occured with number <b>' . $e->getCode() . '</b> and message <b>' . $e->getMessage() . '</b>';
}