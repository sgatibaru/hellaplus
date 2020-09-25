<?php


namespace App\Libraries;


use AfricasTalking\SDK\AfricasTalking;

class SMS
{
    public function send_sms($numbers, $message, $shortcode_obj = FALSE) {
        $o_owner = 'NONE';
        if($shortcode_obj && isset($shortcode_obj) && !empty($shortcode_obj)) {
            $o_owner = $shortcode_obj->id;
        }
        $username = get_option($o_owner.'_sms_username', get_parent_option('sms_api', 'sms_api_username', ''));
        $apiKey = get_option($o_owner.'_sms_apikey', get_parent_option('sms_api', 'sms_api_apikey', ''));
        $senderID = get_option($o_owner.'_sms_sender_id', get_parent_option('sms_api', 'sms_api_sender_id', ''));
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
        return $SMS->send($options);
    }
}