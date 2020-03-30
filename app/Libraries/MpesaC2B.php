<?php


namespace App\Libraries;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MpesaC2B
{
    /**
     * The environment in use, live for production and sandbox for sandbox
     */
    public $env = 'live'; //or sandbox
    /**
     * The common part of the MPesa API endpoints
     * @var string $base_url
     */
    public $base_url;
    /**
     * The consumer key
     * @var string $consumer_key
     */
    public $consumer_key;
    /**
     * The consumer key secret
     * @var string $consumer_secret
     */
    public $consumer_secret;
    /**
     * The MPesa C2B Paybill number
     * @var int $paybill
     */
    public $paybill;
    /**
     * The Lipa Na MPesa paybill number
     * @var int $lipa_na_mpesa
     */
    public $lipa_na_mpesa;
    /**
     * The Lipa Na MPesa paybill number SAG Key
     * @var string $lipa_na_mpesa_key
     */
    public $lipa_na_mpesa_key;
    /**
     * The Mpesa portal Username
     * @var string $initiator_username
     */
    public $initiator_username;
    /**
     * The Mpesa portal Password
     * @var string $initiator_password
     */
    public $initiator_password;
    /**
     * The Callback common part of the URL eg "https://domain.com/callbacks/"
     * @var string $initiator_password
     */
    public $callback_baseurl;
    /**
     * The test phone number provided by safaricom. For developers
     * @var string $test_msisdn
     */
    public $test_msisdn;
    /**
     * The signed API credentials
     * @var string $cred
     */
    private $cred;

    //Others
    public $confirmation_url;
    public $validation_url;
    public $balance_check_result_url;
    public $status_request_result_url;
    public $reverse_transaction_result_url;
    public $lnmo_callback;

    /**
     * Construct method
     *
     * Initializes the class with an array of API values.
     *
     * @param array $config
     * @return void
     * @throws exception if the values array is not valid
     */

    public function __construct($env = false){
        //Base URL for the API endpoints. This is basically the 'common' part of the API endpoints
        if(active_business()->env != 'sandbox') {
            $this->env = 'live';
            $this->base_url = 'https://api.safaricom.co.ke/mpesa/';
        } else {
            $this->env = 'sandbox';
            $this->base_url = 'https://sandbox.safaricom.co.ke/mpesa/';
        }

        $this->consumer_key = ''; 	//App Key. Get it at https://developer.safaricom.co.ke
        $this->consumer_secret = ''; 					//App Secret Key. Get it at https://developer.safaricom.co.ke
        $this->paybill = ''; 									//The paybill/till/lipa na mpesa number
        $this->lipa_na_mpesa = '';								//Lipa Na Mpesa online checkout
        $this->lipa_na_mpesa_key = '';	//Lipa Na Mpesa online checkout password
        $this->initiator_username = ''; 					//Initiator Username. Your API operator username
        $this->initiator_password = ''; 				//Initiator password. Your API operator password

        $this->callback_baseurl = '';
        $this->test_msisdn = '';

        //We override the above $this->cred with the testing credentials
        //$this->cred = 'jQGehsgnujMdEnVOhGq3YdX72blQnpZ+RPgYhe15kU2+UiUkauYDbsxbv+rgVgK4nKU/90R6V7CZDx4+e6KcYQMKCwJht9FfdxG3gC8g2fgxlrCvR+RnObwLOBfJ9htDVyUCJjxP31J/RoC7j25N3g7WDRfcoDXrhRUmG9NGLua+leF6ssJrNxFv6S0aT8S1ihl3aueGAuZxWr7OnbagZZElPueAZKEs8IJDKCh4xkZVUevvUysZCZuHqchMKLYDv80zK/XJ46/Ja/7F1+Qw7180bR/XcptV3ttXV56kGvJ/GMp6FUUem32o2bJMvu+6AkqJnczj0QNq5ZVtTudjvg==';
    }

    /**
     * Submit Request
     *
     * Handles submission of all API endpoints queries
     *
     * @param string $url The API endpoint URL
     * @param json $data The data to POST to the endpoint $url
     * @return object|boolean Curl response or FALSE on failure
     * @throws exception if the Access Token is not valid
     */

    private function submit_request($url, $data){ // Returns cURL response

        $credentials = base64_encode($this->consumer_key.':'.$this->consumer_secret);
        if($this->env == 'live') {
            $cred_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        } else {
            $cred_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        }
        $client = new Client();
        try {
            $request = $client->get($cred_url, array(
                'headers'   => array('content-type'  => 'application/json', 'Authorization' => 'Basic '.$credentials)
            ));
            $response = $request->getBody()->getContents();
        } catch (RequestException $e) {
            $response = $e->getResponse()->getBody()->getContents();
        }

        $response = @json_decode($response);
        $access_token = @$response->access_token;

        // The above $access_token expires after an hour, find a way to cache it to minimize requests to the server
        if (!$access_token) {
            //throw new Exception("Invalid access token generated");
            return false;
        }

        if ($access_token != '' || $access_token !== FALSE) {

            $client = new Client();
            try {
                $request = $client->post($url, array(
                    'headers'   => array('content-type'  => 'application/json', 'Authorization' => 'Bearer '.$access_token),
                    'body'      => $data
                ));
                $response = $request->getBody()->getContents();
            } catch (RequestException $e) {
                $response = $e->getResponse()->getBody()->getContents();
            }
            return $response;
        } else {
            return FALSE;
        }
    }

    /**
     * Client to Business
     *
     * This method is used to register URLs for callbacks when money is sent from the MPesa toolkit menu
     *
     * @param string $confirmURL The local URL that MPesa calls to confirm a payment
     * @param string $ValidationURL The local URL that MPesa calls to validate a payment
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function c2b(){
        $request_data = array(
            'ShortCode' => $this->paybill,
            'ResponseType' => 'Completed',
            'ConfirmationURL' => $this->confirmation_url,
            'ValidationURL' => $this->validation_url
        );
        $data = json_encode($request_data);
        $url = $this->base_url.'c2b/v1/registerurl';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * C2B Simulation
     *
     * This method is used to simulate a C2B Transaction to test your ConfirmURL and ValidationURL in the Client to Business method
     *
     * @param int $amount The amount to send to Paybill number
     * @param int $msisdn A dummy Safaricom phone number to simulate transaction in the format 2547xxxxxxxx
     * @param string $ref A reference name for the transaction
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function simulate_c2b($amount, $msisdn, $ref){
        $data = array(
            'ShortCode' => $this->paybill,
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'Msisdn' => $msisdn,
            'BillRefNumber' => $ref
        );
        $data = json_encode($data);
        $url = $this->base_url.'c2b/v1/simulate';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Check Balance
     *
     * Check Paybill balance
     *
     * @return object Curl Response from submit_request, FALSE on failure
     */
    public function check_balance(){
        $data = array(
            'CommandID' => 'AccountBalance',
            'PartyA' => $this->paybill,
            'IdentifierType' => '4',
            'Remarks' => 'Remarks or short description',
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->get_credential(),
            'QueueTimeOutURL' => $this->balance_check_result_url,
            'ResultURL' => $this->balance_check_result_url
        );
        $data = json_encode($data);
        $url = $this->base_url.'accountbalance/v1/query';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Transaction status request
     *
     * This method is used to check a transaction status
     *
     * @param string $transaction ID eg LH7819VXPE
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function status_request($transaction = 'LH7819VXPE'){
        $data = array(
            'CommandID' => 'TransactionStatusQuery',
            'PartyA' => $this->paybill,
            'IdentifierType' => 4,
            'Remarks' => 'Testing API',
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->get_credential(),
            'QueueTimeOutURL' => $this->status_request_result_url,
            'ResultURL' => $this->status_request_result_url,
            'TransactionID' => $transaction,
            'Occassion' => 'Test'
        );
        $data = json_encode($data);
        $url = $this->base_url.'transactionstatus/v1/query';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /**
     * Transaction Reversal
     *
     * This method is used to reverse a transaction
     *
     * @param int $receiver Phone number in the format 2547xxxxxxxx
     * @param string $trx_id Transaction ID of the Transaction you want to reverse eg LH7819VXPE
     * @param int $amount The amount from the transaction to reverse
     * @return object Curl Response from submit_request, FALSE on failure
     */

    public function reverse_transaction($receiver, $trx_id, $amount){
        $data = array(
            'CommandID' => 'TransactionReversal',
            'ReceiverParty' => $receiver,
            'RecieverIdentifierType' => 1, //1=MSISDN, 2=Till_Number, 4=Shortcode
            'Remarks' => 'Testing',
            'Amount' => $amount,
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->get_credential(),
            'QueueTimeOutURL' => $this->reverse_transaction_result_url,
            'ResultURL' => $this->reverse_transaction_result_url,
            'TransactionID' => $trx_id
        );
        $data = json_encode($data);
        $url = $this->base_url.'reversal/v1/request';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    /*********************************************************************
     *
     * 	LNMO APIs
     *
     * *******************************************************************/

    public function lnmo_request($amount, $phone, $ref = "Payment"){
        if(!is_numeric($amount) || $amount < 10 || !is_numeric($phone)){
            throw new Exception("Invalid amount and/or phone number. Amount should be 10 or more, phone number should be in the format 254xxxxxxxx");
            return FALSE;
        }
        $timestamp = date('YmdHis');
        $passwd = base64_encode($this->lipa_na_mpesa.$this->lipa_na_mpesa_key.$timestamp);
        $data = array(
            'BusinessShortCode' => $this->lipa_na_mpesa,
            'Password' => $passwd,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->lipa_na_mpesa,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->lnmo_callback,
            'AccountReference' => $ref,
            'TransactionDesc' => 'testing too',
        );
        $data = json_encode($data);
        $url = $this->base_url.'stkpush/v1/processrequest';

        $response = $this->submit_request($url, $data);

        return $response;
    }

    private function lnmo_query($checkoutRequestID = null){
        $timestamp = date('YmdHis');
        $passwd = base64_encode($this->lipa_na_mpesa.$this->lipa_na_mpesa_key.$timestamp);

        if($checkoutRequestID == null || $checkoutRequestID == ''){
            //throw new Exception("Checkout Request ID cannot be null");
            return FALSE;
        }

        $data = array(
            'BusinessShortCode' => $this->lipa_na_mpesa,
            'Password' => $passwd,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestID
        );
        $data = json_encode($data);
        $url = $this->base_url.'stkpushquery/v1/query';
        $response = $this->submit_request($url, $data);
        return $response;
    }

    private function get_credential() {
        $credential = get_option($this->paybill.'_credential', FALSE);
        if($credential && $credential != '') {
            $this->cred = $credential;
        } else {
            if($this->env == 'sandbox') {
                $pubkey = file_get_contents(dirname(__FILE__) . '/cert-sandbox.cer');
            } else {
                $pubkey = file_get_contents(dirname(__FILE__) . '/cert-prod.cer');
            }

            openssl_public_encrypt($this->initiator_password, $output, $pubkey, OPENSSL_PKCS1_PADDING);
            $this->cred = base64_encode($output);
        }
        return $this->cred;
    }
}