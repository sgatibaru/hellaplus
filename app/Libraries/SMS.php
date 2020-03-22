<?php


namespace App\Libraries;


use AfricasTalking\SDK\AfricasTalking;

class SMS
{
    public function send_sms($numbers, $message) {
        $username = get_option('sms_username', get_parent_option('sms_api', 'sms_api_username', ''));
        $apiKey = get_option('sms_apikey', get_parent_option('sms_api', 'sms_api_apikey', ''));
        $senderID = get_option('sms_sender_id', get_parent_option('sms_api', 'sms_api_sender_id', ''));
        $options = [
            'to'    => $numbers,
            'message'   => $message,
            'enqueue'   => true,
        ];
        if($senderID){
            $options['from'] = $senderID;
        }
        $AT = new AfricasTalking($username, $apiKey);

        $SMS = $AT->sms();
        $resp = $SMS->send($options);
        return $resp;
    }
}