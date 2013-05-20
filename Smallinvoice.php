<?php

/**
 * A class to use the API of smallinvoice.com as an integrator (Version 1.00)
 *
 * For more information about smallinvoice and how to use it as an integrator see
 * http://www.smallinvoice.ch/CH/en/home/Create-and-manage-invoices-and-offers-for-free.html
 *
 * API documentation can be found here:
 * http://www.smallinvoice.ch/api/general/overview
 *
 *
 *  Copyright (c) 2013, Lourens Systems GmbH
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 *   * Redistributions of source code must retain the above copyright
 *  notice, this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright
 *  notice, this list of conditions and the following disclaimer in the
 *  documentation and/or other materials provided with the distribution.
 *   * Neither the name of the <organization> nor the
 *  names of its contributors may be used to endorse or promote products
 *  derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 *  ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @link http://www.smallinvoice.ch/api/general/overview
 */

class Smallinvoice
{
    /**
     * @constant string Library-Version
     */
    const VERSION = 1.00;

    /**
     * @var string Base URL of Smallinvoice API
     */
    protected $sBaseURL = 'https://api.smallinvoice.com';

    /**
     * @var string Auth token
     */
    private $sToken;

    /**
     * Constructor of class
     *
     * @param string $sToken Auth token
     * @throws Exception Wrong connection method
     */
    public function __construct($sToken)
    {
        $this->sToken = $sToken;
    }

    /**
     * You can list your available invoices
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function invoice_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("invoice/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your invoice
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @return object
     */
    public function invoice_get($iInvoiceId)
    {
        return $this->execute("invoice/get/id/$iInvoiceId");
    }

    /**
     * You can get your invoice as pdf
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @return object
     */
    public function invoice_pdf($iInvoiceId)
    {
        return $this->execute("invoice/pdf/id/$iInvoiceId");
    }

    /**
     * You can get an invoice preview in PNG form for each page of a document
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @param int $iPage The page you want to retrieve
     * @param int $iSize The size in pixels
     * @return object
     */
    public function invoice_preview($iInvoiceId, $iPage = 1, $iSize = 595)
    {
        return $this->execute("invoice/preview/id/$iInvoiceId/page/$iPage/size/$iSize");
    }

    /**
     * You can add a new invoice
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function invoice_add($objData)
    {
        return $this->execute("invoice/add", $objData);
    }

    /**
     * You can edit an invoice
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @param object $objData Body parameters
     * @return object
     */
    public function invoice_edit($iInvoiceId, $objData)
    {
        return $this->execute("invoice/edit/id/$iInvoiceId", $objData);
    }

    /**
     * You can delete an invoice
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @return object
     */
    public function invoice_delete($iInvoiceId)
    {
        return $this->execute("invoice/delete/id/$iInvoiceId");
    }

    /**
     * You can email your invoice
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @param object $objData Body parameters
     * @return object
     */
    public function invoice_email($iInvoiceId, $objData)
    {
        return $this->execute("invoice/email/id/$iInvoiceId", $objData);
    }

    /**
     * You can send your invoice via postmail
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @param object $objData Body parameters
     * @return object
     */
    public function invoice_post($iInvoiceId, $objData)
    {
        return $this->execute("invoice/post/id/$iInvoiceId", $objData);
    }

    /**
     * You can change status of your invoice
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @param object $objData Body parameters
     * @return object
     */
    public function invoice_status($iInvoiceId, $objData)
    {
        return $this->execute("invoice/status/id/$iInvoiceId", $objData);
    }

    /**
     * You can add a payment to your invoice
     *
     * See http://www.smallinvoice.ch/api/endpoints/invoices
     *
     * @param int $iInvoiceId The Id of the invoice
     * @param object $objData Body parameters
     * @return object
     */
    public function invoice_payment($iInvoiceId, $objData)
    {
        return $this->execute("invoice/payment/id/$iInvoiceId", $objData);
    }

    /**
     * You can list your available offers
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function offer_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("offer/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your offer
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @return object
     */
    public function offer_get($iOfferId)
    {
        return $this->execute("offer/get/id/$iOfferId");
    }

    /**
     * You can get your offer as pdf
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @return object
     */
    public function offer_pdf($iOfferId)
    {
        return $this->execute("offer/pdf/id/$iOfferId");
    }

    /**
     * You can get an offer preview in PNG form for each page of a document
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @param int $iPage The page you want to retrieve
     * @param int $iSize The size in pixels
     * @return object
     */
    public function offer_preview($iOfferId, $iPage = 1, $iSize = 595)
    {
        return $this->execute("offer/preview/id/$iOfferId/page/$iPage/size/$iSize");
    }

    /**
     * You can add a new offer
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function offer_add($objData)
    {
        return $this->execute("offer/add", $objData);
    }

    /**
     * You can edit an offer
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @param object $objData Body parameters
     * @return object
     */
    public function offer_edit($iOfferId, $objData)
    {
        return $this->execute("offer/edit/id/$iOfferId", $objData);
    }

    /**
     * You can delete an offer
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @return object
     */
    public function offer_delete($iOfferId)
    {
        return $this->execute("offer/delete/id/$iOfferId");
    }

    /**
     * You can email your offer
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @param object $objData Body parameters
     * @return object
     */
    public function offer_email($iOfferId, $objData)
    {
        return $this->execute("offer/email/id/$iOfferId", $objData);
    }

    /**
     * You can send your offer via postmail
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @param object $objData Body parameters
     * @return object
     */
    public function offer_post($iOfferId, $objData)
    {
        return $this->execute("offer/post/id/$iOfferId", $objData);
    }

    /**
     * You can change status of your offer
     *
     * See http://www.smallinvoice.ch/api/endpoints/offers
     *
     * @param int $iOfferId The Id of the offer
     * @param object $objData Body parameters
     * @return object
     */
    public function offer_status($iOfferId, $objData)
    {
        return $this->execute("offer/status/id/$iOfferId", $objData);
    }

    /**
     * You can list your available receipts
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function receipt_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("receipt/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your receipt
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @return object
     */
    public function receipt_get($iReceiptId)
    {
        return $this->execute("receipt/get/id/$iReceiptId");
    }

    /**
     * You can get your receipt as pdf
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @return object
     */
    public function receipt_pdf($iReceiptId)
    {
        return $this->execute("receipt/pdf/id/$iReceiptId");
    }

    /**
     * You can get a receipt preview in PNG form for each page of a document
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @param int $iPage The page you want to retrieve
     * @param int $iSize The size in pixels
     * @return object
     */
    public function receipt_preview($iReceiptId, $iPage = 1, $iSize = 595)
    {
        return $this->execute("receipt/preview/id/$iReceiptId/page/$iPage/size/$iSize");
    }

    /**
     * You can add a new receipt
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function receipt_add($objData)
    {
        return $this->execute("receipt/add", $objData);
    }

    /**
     * You can edit a receipt
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @param object $objData Body parameters
     * @return object
     */
    public function receipt_edit($iReceiptId, $objData)
    {
        return $this->execute("receipt/edit/id/$iReceiptId", $objData);
    }

    /**
     * You can delete a receipt
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @return object
     */
    public function receipt_delete($iReceiptId)
    {
        return $this->execute("receipt/delete/id/$iReceiptId");
    }

    /**
     * You can email your receipt
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @param object $objData Body parameters
     * @return object
     */
    public function receipt_email($iReceiptId, $objData)
    {
        return $this->execute("receipt/email/id/$iReceiptId", $objData);
    }

    /**
     * You can send your receipt via postmail
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @param object $objData Body parameters
     * @return object
     */
    public function receipt_post($iReceiptId, $objData)
    {
        return $this->execute("receipt/post/id/$iReceiptId", $objData);
    }

    /**
     * You can change status of your receipt
     *
     * See http://www.smallinvoice.ch/api/endpoints/receipts
     *
     * @param int $iReceiptId The Id of the receipt
     * @param object $objData Body parameters
     * @return object
     */
    public function receipt_status($iReceiptId, $objData)
    {
        return $this->execute("receipt/status/id/$iReceiptId", $objData);
    }

    /**
     * You can list your available confirmations
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function confirmations_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("confirmation/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your confirmation
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @return object
     */
    public function confirmations_get($iConfirmationId)
    {
        return $this->execute("confirmation/get/id/$iConfirmationId");
    }

    /**
     * You can get your confirmation as pdf
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @return object
     */
    public function confirmations_pdf($iConfirmationId)
    {
        return $this->execute("confirmation/pdf/id/$iConfirmationId");
    }

    /**
     * You can get a confirmation preview in PNG form for each page of a document
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @param int $iPage The page you want to retrieve
     * @param int $iSize The size in pixels
     * @return object
     */
    public function confirmations_preview($iConfirmationId, $iPage = 1, $iSize = 595)
    {
        return $this->execute("confirmation/preview/id/$iConfirmationId/page/$iPage/size/$iSize");
    }

    /**
     * You can add a new confirmation
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function confirmations_add($objData)
    {
        return $this->execute("confirmation/add", $objData);
    }

    /**
     * You can edit a confirmation
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @param object $objData Body parameters
     * @return object
     */
    public function confirmation_edit($iConfirmationId, $objData)
    {
        return $this->execute("confirmation/edit/id/$iConfirmationId", $objData);
    }

    /**
     * You can delete a confirmation
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @return object
     */
    public function confirmation_delete($iConfirmationId)
    {
        return $this->execute("confirmation/delete/id/$iConfirmationId");
    }

    /**
     * You can email your confirmation
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @param object $objData Body parameters
     * @return object
     */
    public function confirmation_email($iConfirmationId, $objData)
    {
        return $this->execute("confirmation/email/id/$iConfirmationId", $objData);
    }

    /**
     * You can send your confirmation via postmail
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @param object $objData Body parameters
     * @return object
     */
    public function confirmation_post($iConfirmationId, $objData)
    {
        return $this->execute("confirmation/post/id/$iConfirmationId", $objData);
    }

    /**
     * You can change status of your confirmation
     *
     * See http://www.smallinvoice.ch/api/endpoints/confirmations
     *
     * @param int $iConfirmationId The Id of the confirmation
     * @param object $objData Body parameters
     * @return object
     */
    public function confirmation_status($iConfirmationId, $objData)
    {
        return $this->execute("confirmation/status/id/$iConfirmationId", $objData);
    }

    /**
     * You can list your available letters
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function letter_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("letter/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your letter
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @return object
     */
    public function letter_get($iLetterId)
    {
        return $this->execute("letter/get/id/$iLetterId");
    }

    /**
     * You can get your letter as pdf
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @return object
     */
    public function letter_pdf($iLetterId)
    {
        return $this->execute("letter/pdf/id/$iLetterId");
    }

    /**
     * You can get a letter preview in PNG form for each page of a document
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @param int $iPage The page you want to retrieve
     * @param int $iSize The size in pixels
     * @return object
     */
    public function letter_preview($iLetterId, $iPage = 1, $iSize = 595)
    {
        return $this->execute("letter/preview/id/$iLetterId/page/$iPage/size/$iSize");
    }

    /**
     * You can add a new letter
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function letter_add($objData)
    {
        return $this->execute("letter/add", $objData);
    }

    /**
     * You can edit a letter
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @param object $objData Body parameters
     * @return object
     */
    public function letter_edit($iLetterId, $objData)
    {
        return $this->execute("letter/edit/id/$iLetterId", $objData);
    }

    /**
     * You can delete a letter
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @return object
     */
    public function letter_delete($iLetterId)
    {
        return $this->execute("letter/delete/id/$iLetterId");
    }

    /**
     * You can email your letter
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @param object $objData Body parameters
     * @return object
     */
    public function letter_email($iLetterId, $objData)
    {
        return $this->execute("letter/email/id/$iLetterId", $objData);
    }

    /**
     * You can send your letter via postmail
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @param object $objData Body parameters
     * @return object
     */
    public function letter_post($iLetterId, $objData)
    {
        return $this->execute("letter/post/id/$iLetterId", $objData);
    }

    /**
     * You can change status of your letter
     *
     * See http://www.smallinvoice.ch/api/endpoints/letters
     *
     * @param int $iLetterId The Id of the letter
     * @param object $objData Body parameters
     * @return object
     */
    public function letter_status($iLetterId, $objData)
    {
        return $this->execute("letter/status/id/$iLetterId", $objData);
    }

    /**
     * You can list your available clients
     *
     * See http://www.smallinvoice.ch/api/endpoints/clients
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function client_list($iLimit = 0, $iPage = 1, $sSort = 'name', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("client/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your client
     *
     * See http://www.smallinvoice.ch/api/endpoints/clients
     *
     * @param int $iClientId The Id of the client
     * @return object
     */
    public function client_get($iClientId)
    {
        return $this->execute("client/get/id/$iClientId");
    }

    /**
     * You can add a new client
     *
     * See http://www.smallinvoice.ch/api/endpoints/clients
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function client_add($objData)
    {
        return $this->execute("client/add", $objData);
    }

    /**
     * You can edit a client
     *
     * See http://www.smallinvoice.ch/api/endpoints/clients
     *
     * @param int $iClientId The Id of the client
     * @param object $objData Body parameters
     * @return object
     */
    public function client_edit($iClientId, $objData)
    {
        return $this->execute("client/edit/id/$iClientId", $objData);
    }

    /**
     * You can delete a client
     *
     * See http://www.smallinvoice.ch/api/endpoints/clients
     *
     * @param int $iClientId The Id of the client
     * @return object
     */
    public function client_delete($iClientId)
    {
        return $this->execute("client/delete/id/$iClientId");
    }

    /**
     * You can list your available catalog
     *
     * See http://www.smallinvoice.ch/api/endpoints/catalog
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function catalog_list($iLimit = 0, $iPage = 1, $sSort = 'name', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("catalog/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your catalog
     *
     * See http://www.smallinvoice.ch/api/endpoints/catalog
     *
     * @param int $iCatalogId The Id of the catalog
     * @return object
     */
    public function catalog_get($iCatalogId)
    {
        return $this->execute("catalog/get/id/$iCatalogId");
    }

    /**
     * You can add a new catalog
     *
     * See http://www.smallinvoice.ch/api/endpoints/catalog
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function catalog_add($objData)
    {
        return $this->execute("catalog/add", $objData);
    }

    /**
     * You can edit a catalog
     *
     * See http://www.smallinvoice.ch/api/endpoints/catalog
     *
     * @param int $iCatalogId The Id of the catalog
     * @param object $objData Body parameters
     * @return object
     */
    public function catalog_edit($iCatalogId, $objData)
    {
        return $this->execute("catalog/edit/id/$iCatalogId", $objData);
    }

    /**
     * You can delete a catalog
     *
     * See http://www.smallinvoice.ch/api/endpoints/catalog
     *
     * @param int $iCatalogId The Id of the catalog
     * @return object
     */
    public function catalog_delete($iCatalogId)
    {
        return $this->execute("catalog/delete/id/$iCatalogId");
    }

    /**
     * You can list your available projects
     *
     * See http://www.smallinvoice.ch/api/endpoints/projects
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function project_list($iLimit = 0, $iPage = 1, $sSort = 'name', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("project/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your project
     *
     * See http://www.smallinvoice.ch/api/endpoints/projects
     *
     * @param int $iProjectId The Id of the project
     * @return object
     */
    public function project_get($iProjectId)
    {
        return $this->execute("project/get/id/$iProjectId");
    }

    /**
     * You can add a new project
     *
     * See http://www.smallinvoice.ch/api/endpoints/projects
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function project_add($objData)
    {
        return $this->execute("project/add", $objData);
    }

    /**
     * You can edit a project
     *
     * See http://www.smallinvoice.ch/api/endpoints/projects
     *
     * @param int $iProjectId The Id of the project
     * @param object $objData Body parameters
     * @return object
     */
    public function project_edit($iProjectId, $objData)
    {
        return $this->execute("project/edit/id/$iProjectId", $objData);
    }

    /**
     * You can delete a project
     *
     * See http://www.smallinvoice.ch/api/endpoints/projects
     *
     * @param int $iProjectId The Id of the project
     * @return object
     */
    public function project_delete($iProjectId)
    {
        return $this->execute("project/delete/id/$iProjectId");
    }

    /**
     * You can list your available costunits
     *
     * See http://www.smallinvoice.ch/api/endpoints/costunits
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function costunit_list($iLimit = 0, $iPage = 1, $sSort = 'name', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("costunit/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your costunit
     *
     * See http://www.smallinvoice.ch/api/endpoints/costunits
     *
     * @param int $iCostunitId The Id of the costunit
     * @return object
     */
    public function costunit_get($iCostunitId)
    {
        return $this->execute("costunit/get/id/$iCostunitId");
    }

    /**
     * You can add a new costunit
     *
     * See http://www.smallinvoice.ch/api/endpoints/costunits
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function costunit_add($objData)
    {
        return $this->execute("costunit/add", $objData);
    }

    /**
     * You can edit a costunit
     *
     * See http://www.smallinvoice.ch/api/endpoints/costunits
     *
     * @param int $iCostunitId The Id of the costunit
     * @param object $objData Body parameters
     * @return object
     */
    public function costunit_edit($iCostunitId, $objData)
    {
        return $this->execute("costunit/edit/id/$iCostunitId", $objData);
    }

    /**
     * You can delete a costunit
     *
     * See http://www.smallinvoice.ch/api/endpoints/costunits
     *
     * @param int $iCostunitId The Id of the costunit
     * @return object
     */
    public function costunit_delete($iCostunitId)
    {
        return $this->execute("costunit/delete/id/$iCostunitId");
    }

    /**
     * You can list your available assigns
     *
     * See http://www.smallinvoice.ch/api/endpoints/assigns
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function assigns_list($iLimit = 0, $iPage = 1, $sSort = 'name', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("assign/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your assign
     *
     * See http://www.smallinvoice.ch/api/endpoints/assigns
     *
     * @param int $iAssignId The Id of the assign
     * @return object
     */
    public function assign_get($iAssignId)
    {
        return $this->execute("assign/get/id/$iAssignId");
    }

    /**
     * You can add a new assign
     *
     * See http://www.smallinvoice.ch/api/endpoints/assigns
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function assign_add($objData)
    {
        return $this->execute("assign/add", $objData);
    }

    /**
     * You can edit a assign
     *
     * See http://www.smallinvoice.ch/api/endpoints/assigns
     *
     * @param int $iAssignId The Id of the assign
     * @param object $objData Body parameters
     * @return object
     */
    public function assign_edit($iAssignId, $objData)
    {
        return $this->execute("assign/edit/id/$iAssignId", $objData);
    }

    /**
     * You can delete a assign
     *
     * See http://www.smallinvoice.ch/api/endpoints/assigns
     *
     * @param int $iAssignId The Id of the assign
     * @return object
     */
    public function assign_delete($iAssignId)
    {
        return $this->execute("assign/delete/id/$iAssignId");
    }

    /**
     * You can list your available times
     *
     * See http://www.smallinvoice.ch/api/endpoints/times
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function time_list($iLimit = 0, $iPage = 1, $sSort = 'date', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("time/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your time
     *
     * See http://www.smallinvoice.ch/api/endpoints/times
     *
     * @param int $iTimeId The Id of the time
     * @return object
     */
    public function time_get($iTimeId)
    {
        return $this->execute("time/get/id/$iTimeId");
    }

    /**
     * You can add a new time
     *
     * See http://www.smallinvoice.ch/api/endpoints/times
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function time_add($objData)
    {
        return $this->execute("time/add", $objData);
    }

    /**
     * You can edit a time
     *
     * See http://www.smallinvoice.ch/api/endpoints/times
     *
     * @param int $iTimeId The Id of the time
     * @param object $objData Body parameters
     * @return object
     */
    public function time_edit($iTimeId, $objData)
    {
        return $this->execute("time/edit/id/$iTimeId", $objData);
    }

    /**
     * You can delete a time
     *
     * See http://www.smallinvoice.ch/api/endpoints/times
     *
     * @param int $iTimeId The Id of the time
     * @return object
     */
    public function time_delete($iTimeId)
    {
        return $this->execute("time/delete/id/$iTimeId");
    }

    /**
     * You can list your available accounts
     *
     * See http://www.smallinvoice.ch/api/endpoints/accounts
     *
     * @param int $iLimit Limit the amount of results
     * @param int $iPage When limiting the results, specifies page
     * @param string $sSort Sorts the list by the available values
     * @param string $sSortType Defines the way of sorting
     * @param string $aFilter Set of filters for list
     * @return object
     */
    public function accounts_list($iLimit = 0, $iPage = 1, $sSort = 'name', $sSortType = 'desc', $aFilter = array())
    {
        return $this->execute("account/list/limit/$iLimit/page/$iPage/sort/$sSort/sorttype/$sSortType" . $this->parse_filters($aFilter));
    }

    /**
     * You can get your account
     *
     * See http://www.smallinvoice.ch/api/endpoints/accounts
     *
     * @param int $iAccountId The Id of the account
     * @return object
     */
    public function account_get($iAccountId)
    {
        return $this->execute("account/get/id/$iAccountId");
    }

    /**
     * You can add a new account
     *
     * See http://www.smallinvoice.ch/api/endpoints/accounts
     *
     * @param object $objData Body Parameters
     * @return mixed
     */
    public function account_add($objData)
    {
        return $this->execute("account/add", $objData);
    }

    /**
     * You can edit a account
     *
     * See http://www.smallinvoice.ch/api/endpoints/accounts
     *
     * @param int $iAccountId The Id of the account
     * @param object $objData Body parameters
     * @return object
     */
    public function account_edit($iAccountId, $objData)
    {
        return $this->execute("account/edit/id/$iAccountId", $objData);
    }

    /**
     * You can delete a account
     *
     * See http://www.smallinvoice.ch/api/endpoints/accounts
     *
     * @param int $iAccountId The Id of the account
     * @return object
     */
    public function account_delete($iAccountId)
    {
        return $this->execute("account/delete/id/$iAccountId");
    }

    private function execute($sKeyword, $aBodyParameters = array(), $sFile = false)
    {
        /* put together parameters */
        $aData         = array();
        $aData['data'] = json_encode($aBodyParameters);
        if ($sFile) $aData['file'] = '@' . $sFile;

        /* prepare URL */
        $aURLParts = array(
            $this->sBaseURL,
            $sKeyword,
            'token',
            $this->sToken
        );
        $sURL      = implode('/', $aURLParts);

        /* data may not be empty */
        if (isset($aData['data']) && (count(json_decode($aData['data'])) == 0 || $aData['data'] == '')) {
            unset($aData['data']);
        }

        $objCurlConn = curl_init();
        curl_setopt($objCurlConn, CURLOPT_URL, $sURL);
        curl_setopt($objCurlConn, CURLOPT_POST, 1);
        curl_setopt($objCurlConn, CURLOPT_POSTFIELDS, $aData);
        curl_setopt($objCurlConn, CURLOPT_RETURNTRANSFER, 1);

        /*
         * If you are having issues with invalid certificate you could optionally uncomment
         * these following two lines (not recommended)
         * The alternative would be to update your CA Root Certificate Bundle.
        */
//            curl_setopt($objCurlConn, CURLOPT_SSL_VERIFYHOST, 0);
//            curl_setopt($objCurlConn, CURLOPT_SSL_VERIFYPEER, 0);

        $mResponse = curl_exec($objCurlConn);

        if ($mResponse === FALSE) {
            throw new Exception('An error occured with the curl connection: ' . curl_error($objCurlConn));
        }

        /* if PDF or Image, output plain result */
        if (substr($mResponse, 0, 4) == '%PDF' || substr($mResponse, 1, 3) == 'PNG') {
            return $mResponse;
        }

        $objResponse = json_decode($mResponse);
        if ($objResponse->error) {
            throw new Exception($objResponse->errormessage, $objResponse->errorcode);
        } else {
            return $objResponse;
        }
    }

    private function parse_filters($aFilters)
    {
        $aSets = array();

        foreach ($aFilters as $sFilter => $sValue) {
            $aSets[] = "{$sFilter}:{$sValue}";
        }

        $sFilter = implode(';', $aSets);

        if ($sFilter) {
            return "/filter/$sFilter";
        } else {
            return '';
        }
    }
}