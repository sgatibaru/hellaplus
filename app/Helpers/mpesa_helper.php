<?php
function format_account_balance($data) {
    $data = @json_decode($data);

    if(!$data) return false;

    $master = new stdClass;
    $master->ResultType = $data->Result->ResultType;
    $master->ResultCode = $data->Result->ResultCode;
    $master->ResultDesc = $data->Result->ResultDesc;
    $master->OriginatorConversationID = $data->Result->OriginatorConversationID;
    $master->ConversationID = $data->Result->ConversationID;
    $master->TransactionID = $data->Result->TransactionID;
    if(isset($data->Result->ResultParameters->ResultParameter) && is_array($data->Result->ResultParameters->ResultParameter)){
        foreach($data->Result->ResultParameters->ResultParameter as $item){
            $item = (array) $item;
            $key = $item['Key'];
            $master->$key = (isset($item['Value'])) ? $item['Value'] : null;
        }
    }

    //If we dont have the balance information, there's no need of continuing
    if(!isset($master->AccountBalance)) return (object) $master;

    //Get values from the | delimited string
    // "Working Account|KES|46713.00|46713.00|0.00|0.00&Float Account|KES|0.00|0.00|0.00|0.00&
    // Utility Account|KES|49217.00|49217.00|0.00|0.00&
    // Charges Paid Account|KES|-220.00|-220.00|0.00|0.00&
    // Organization Settlement Account|KES|0.00|0.00|0.00|0.00"
    // $master->AccountBalance = "Working Account|KES|46713.00|46713.00|0.00|0.00&Float Account|KES|0.00|0.00|0.00|0.00&Utility Account|KES|49217.00|49217.00|0.00|0.00&Charges Paid Account|KES|-220.00|-220.00|0.00|0.00";

    $tmp = explode('&', $master->AccountBalance);
    foreach($tmp as $pipes) {
        //We have: Working Account|KES|46713.00|46713.00|0.00|0.00
        $xx = explode('|', $pipes);
        $arr = new stdClass();
        $arr->name = $xx[0]; //Working Account
        $arr->currency = $xx[1]; //KES
        $arr->amount = $xx[2]; // 46713.00
        $arr->other = $xx[3]; // 46713.00
        $master->Balances[] = $arr;
    }

    return (object) $master;
}

function format_b2c($data) {
    $data = @json_decode($data);

    if(!$data) return false;

    $master = new stdClass;
    $master->ResultType = $data->Result->ResultType;
    $master->ResultCode = $data->Result->ResultCode;
    $master->ResultDesc = $data->Result->ResultDesc;
    $master->OriginatorConversationID = $data->Result->OriginatorConversationID;
    $master->ConversationID = $data->Result->ConversationID;
    $master->TransactionID = $data->Result->TransactionID;
    if(isset($data->Result->ResultParameters->ResultParameter) && is_array($data->Result->ResultParameters->ResultParameter)){
        foreach($data->Result->ResultParameters->ResultParameter as $item){
            $item = (array) $item;
            $key = $item['Key'];
            $master->$key = (isset($item['Value'])) ? $item['Value'] : null;
        }
    }

    return (object) $master;
}