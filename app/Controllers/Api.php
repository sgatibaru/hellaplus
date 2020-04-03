<?php


namespace App\Controllers;


class Api extends BaseController
{
    /**
     * @var \CodeIgniter\Database\BaseConnection
     */
    private $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function setup($id = FALSE) {
        //Register URLS
        $business = (new \App\Models\BusinessModel())->find($id);
        if(!$business) {
            $response = [
                'status'    => 'error',
                'title'     => 'Unknown Shortcode',
                'message'   => 'M-Pesa Response could not be decoded'
            ];
        } else {
            $mpesa = new \App\Libraries\MpesaC2B(true);
            $mpesa->paybill = $business->shortcode;

            $mpesa->consumer_key = $business->consumer_key;
            $mpesa->consumer_secret = $business->consumer_secret;

            $mpesa->confirmation_url = site_url('api/confirm/'.$business->shortcode.'/'.md5(trim($business->shortcode)),'https'); //C2B confirmation URL
            $mpesa->validation_url = site_url('api/validation/'.$business->shortcode.'/'.md5(trim($business->shortcode)),'https');; //C2B validation URL

            $resp = $mpesa->c2b();
            if($res = json_decode($resp)) {
                if(isset($res->ResponseDescription)) {
                    if($res->ResponseDescription == 'success') {
                        \Config\Database::connect()->table('businesses')->where('id', $business->id)->update(['api_setup'=>1]);
                        $msg = "API has been set up successfully";
                        $response = [
                            'status'    => 'success',
                            'title'     => 'Setup Complete',
                            'message'   => $msg,
                            'callback'  => 'window.location.reload(true)'
                        ];
                    } else {
                        $msg = $res->ResponseDescription;
                        $response = [
                            'status'    => 'error',
                            'title'     => 'An Error Occured',
                            'message'   => $msg
                        ];
                    }
                } else {
                    $response = [
                        'status'    => 'error',
                        'title'     => 'An Error Occured',
                        'message'   => 'M-Pesa Response could not be decoded. Please check your API Settings in the settings page'
                    ];
                }
            } else {
                $response = [
                    'status'    => 'error',
                    'title'     => 'An Error Occured',
                    'message'   => 'M-Pesa Response could not be decoded. Please check your API Settings in the settings page'
                ];
            }
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function confirm($shortcode, $key){
        $data = file_get_contents('php://input');

        if($data = json_decode($data)) {
            $to_db = [
                'shortcode'     => $shortcode,
                'date'          => date('m-d-Y'),
                'trans_id'      => $data->TransID,
                'trans_amount'  => $data->TransAmount,
                'ref_number'    => $data->BillRefNumber,
                'org_balance'   => $data->OrgAccountBalance,
                'thirdparty_id' => $data->ThirdPartyTransID,
                'msisdn'        => $data->MSISDN,
                'fname'         => $data->FirstName,
                'mname'         => $data->MiddleName,
                'lname'         => $data->LastName,
                'trans_time'    => $data->TransTime,
                'trans_type'    => 'income'
            ];

            if($this->db->table('transactions')->insert($to_db)) {
                $response = [
                    'ResultCode' => 0,
                    'ResultDesc' => 'Confirmation received successfully'
                ];
                $customer = [
                    'phone'     => $data->MSISDN,
                    'fname'         => $data->FirstName,
                    'mname'         => $data->MiddleName,
                    'lname'         => $data->LastName,
                ];
                if(!(new \App\Models\CustomerModel())->where('phone', $data->MSISDN)->get()->getRowObject()) {
                    \Config\Database::connect()->table('customers')->insert($customer);
                }
                if(get_option('sms_active', get_parent_option('sms_api', 'sms_active', false)) == 1) {
                    if($template = get_option('sms_template', get_parent_option('sms_api', 'sms_template', FALSE))) {
                        $message = \Config\Services::parser()->setData((array)$data)->renderString($template);
                        (new \App\Libraries\SMS())->send_sms($data->MSISDN, $message);
                    }
                }
                @\Config\Database::connect()->table('businesses')->where('shortcode', $shortcode)->update(['api_setup'=>1]);
            } else {
                $response = [
                    'ResultCode' => 501,
                    'ResultDesc' => 'Database error'
                ];
            }
        } else {
            $response = [
                'ResultCode' => 403,
                'ResultDesc' => 'Invalid Request'
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function validation($shortcode, $key){
        $response = [
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ];
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function reversal_url($shortcode, $key){
        $data = file_get_contents('php://input');
        $actual = $data;
        $response = [
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ];
        $data = $this->format_crazy($data);
        if(!$data || !isset($data->ResultCode)) {
            $response = [
                'ResultCode' => 409,
                'ResultDesc' => 'Invalid data submitted'
            ];
        } else {
            if($data->ResultCode == '0') {
                \Config\Database::connect()->table('transactions')->where('trans_id', $data->TransactionID)->update(['trans_type'=>'reversal']);
            } else {
                //Probably log responses?
                $log = [
                    'shortcode'     => $shortcode,
                    'status'        => $data->ResultCode,
                    'info'          => $data->ResultDesc,
                    'actual_data'   => $actual
                ];
                @\Config\Database::connect()->table('logs')->insert($log);
            }
            //TODO: Maybe log the Reversal response?
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function balance_url($shortcode, $key){
        $data = file_get_contents('php://input');
        $actual = $data;
        $data = format_account_balance($data);
        if($data) {
            if($data->ResultCode == 0) {
                //Only consider successful requests
                //set_option($shortcode.'_balance', $actual);
            }
        }
        set_option($shortcode.'_balance', $actual);
        set_option($shortcode.'_last_balance_check', time());
        //TODO: Maybe log the failed information in the logs table

        $response = [
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function result_url($shortcode, $key) {
        $data = file_get_contents('php://input');
        $actual = $data;
        $data = format_b2c($data);
        $to_db = [
            'result_code'       => $data->ResultCode,
            'trx_id'            => @$data->TransactionReceipt,
            'trx_time'          => @$data->TransactionCompletedDateTime,
            'receiver_name'     => @$data->ReceiverPartyPublicName,
            'actual_data'       => $actual,
            'result_desc'       => @$data->ResultDesc
        ];
        \Config\Database::connect()->table('b2c')->where('conversation_id', $data->ConversationID)->update($to_db);

        $response = [
            'ResultCode' => 0,
            'ResultDesc' => 'Success'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    private function format_crazy($data) {
        $master = array();
        $data = json_decode($data);
        if(!$data) {
            return false;
        }
        $result = $data->Result;
        $master['ResultType'] = $result->ResultType;
        $master['ResultCode'] = $result->ResultCode;
        $master['TransactionID'] = $result->TransactionID;
        $master['ResultDesc'] = $result->ResultDesc;
        $master['OriginatorConversationID'] = $result->OriginatorConversationID;
        $master['ConversationID'] = $result->ConversationID;
        if($result->ResultCode == 0){
            if(isset($result->ResultParameters)){
                foreach($result->ResultParameters->ResultParameter as $item){
                    $item = (array) $item;
                    $master[$item['Key']] = ((isset($item['Value'])) ? $item['Value'] : NULL);
                }
            }
        }else{
            if(isset($result->ResultParameters)){
                $master[$result->ResultParameters->ResultParameter->Key] = $result->ResultParameters->ResultParameter->Value;
            }
        }
        return (object) $master;
    }
}