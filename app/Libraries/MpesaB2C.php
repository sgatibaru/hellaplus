<?php


namespace App\Libraries;


use Exception;

class MpesaB2C
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
     * The Lipa Na MPesa paybill number SAG Key
     * @var string $lipa_na_mpesa_key
     */
    public $initiator_username;
    /**
     * The Mpesa portal Password
     * @var string $initiator_password
     */
    public $initiator_password;
    public $balance_check_result_url;
    public $status_request_result_url;
    public $reverse_transaction_result_url;
    public $b2c_result_url;
    /**
     * The signed API credentials
     * @var string $cred
     */
    private $cred;
    /**
     * @var string
     */
    private $callback_baseurl;

    /**
     * Construct method
     *
     * Initializes the class with an array of API values.
     *
     * @param array $config
     * @return void
     */

    public function __construct($env = false)
    {
        //Base URL for the API endpoints. This is basically the 'common' part of the API endpoints
        if ($env) {
            $this->env = 'live';
            $this->base_url = 'https://api.safaricom.co.ke/mpesa/';
        } else {
            $this->env = 'sandbox';
            $this->base_url = 'https://sandbox.safaricom.co.ke/mpesa/';
        }

        $this->consumer_key = '';    //App Key. Get it at https://developer.safaricom.co.ke
        $this->consumer_secret = '';                    //App Secret Key. Get it at https://developer.safaricom.co.ke
        $this->paybill = '';
        $this->initiator_username = '';                    //Initiator Username. Your API operator username
        $this->initiator_password = '';                //Initiator password. Your API operator password

        $this->callback_baseurl = '';

        $pubkey = file_get_contents(dirname(__FILE__) . '/cert.cer');
        openssl_public_encrypt($this->initiator_password, $output, $pubkey, OPENSSL_PKCS1_PADDING);
        $this->cred = base64_encode($output);

        //We override the above $this->cred with the testing credentials
        //$this->cred = 'jQGehsgnujMdEnVOhGq3YdX72blQnpZ+RPgYhe15kU2+UiUkauYDbsxbv+rgVgK4nKU/90R6V7CZDx4+e6KcYQMKCwJht9FfdxG3gC8g2fgxlrCvR+RnObwLOBfJ9htDVyUCJjxP31J/RoC7j25N3g7WDRfcoDXrhRUmG9NGLua+leF6ssJrNxFv6S0aT8S1ihl3aueGAuZxWr7OnbagZZElPueAZKEs8IJDKCh4xkZVUevvUysZCZuHqchMKLYDv80zK/XJ46/Ja/7F1+Qw7180bR/XcptV3ttXV56kGvJ/GMp6FUUem32o2bJMvu+6AkqJnczj0QNq5ZVtTudjvg==';
    }

    /**
     * Submit Request
     *
     * Handles submission of all API endpoints queries
     *
     * @param string $url The API endpoint URL
     * @param object|boolean $data The data to POST to the endpoint $url
     * @return object|boolean Curl response or FALSE on failure
     * @throws Exception if the Access Token is not valid
     */

    private function submit_request($url, $data)
    { // Returns cURL response

        $credentials = base64_encode($this->consumer_key . ':' . $this->consumer_secret);
        if ($this->env == 'live') {
            $cred_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        } else {
            $cred_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $cred_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials, 'Content-Type: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);

        $response = @json_decode($response);

        $access_token = @$response->access_token;

        // The above $access_token expires after an hour, find a way to cache it to minimize requests to the server
        if (!$access_token) {
            //throw new Exception("Invalid access token generated");
            return false;
        }

        if ($access_token != '' || $access_token !== FALSE) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } else {
            return FALSE;
        }
    }

    /**
     * Business to Client
     *
     * This method is used to send money to the clients Mpesa account.
     *
     * @param int $amount The amount to send to the client
     * @param int $phone The phone number of the client in the format 2547xxxxxxxx
     * @param string $type The Transaction type SalaryPayment, PromotionPayment etc
     * @return object Curl Response from submit_request, FALSE on failure
     * @throws Exception
     */

    public function b2c($amount, $phone, $type)
    {
        $request_data = array(
            'InitiatorName' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'CommandID' => $type,
            'Amount' => $amount,
            'PartyA' => $this->paybill,
            'PartyB' => $phone,
            'Remarks' => 'This is a test comment or remark',
            'QueueTimeOutURL' => $this->b2c_result_url,
            'ResultURL' => $this->b2c_result_url,
            'Occasion' => '' //Optional
        );
        $data = json_encode($request_data);
        $url = $this->base_url . 'b2c/v1/paymentrequest';
        return $this->submit_request($url, $data);
    }

    /**
     * Transaction status request
     *
     * This method is used to check a transaction status
     *
     * @param string $transaction ID eg LH7819VXPE
     * @return object Curl Response from submit_request, FALSE on failure
     * @throws Exception
     */

    public function status_request($transaction = 'LH7819VXPE')
    {
        $data = array(
            'CommandID' => 'TransactionStatusQuery',
            'PartyA' => $this->paybill,
            'IdentifierType' => 4,
            'Remarks' => 'Testing API',
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'QueueTimeOutURL' => $this->status_request_result_url,
            'ResultURL' => $this->status_request_result_url,
            'TransactionID' => $transaction,
            'Occassion' => 'Test'
        );
        $data = json_encode($data);
        $url = $this->base_url . 'transactionstatus/v1/query';
        return $this->submit_request($url, $data);
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
     * @throws Exception
     */

    public function reverse_transaction($receiver, $trx_id, $amount)
    {
        $data = array(
            'CommandID' => 'TransactionReversal',
            'ReceiverParty' => $receiver,
            'ReceiverIdentifierType' => 1, //1=MSISDN, 2=Till_Number, 4=Shortcode
            'Remarks' => 'Testing',
            'Amount' => $amount,
            'Initiator' => $this->initiator_username,
            'SecurityCredential' => $this->cred,
            'QueueTimeOutURL' => $this->reverse_transaction_result_url,
            'ResultURL' => $this->reverse_transaction_result_url,
            'TransactionID' => $trx_id
        );
        $data = json_encode($data);
        $url = $this->base_url . 'reversal/v1/request';
        return $this->submit_request($url, $data);
    }
}