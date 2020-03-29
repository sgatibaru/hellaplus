<?php


namespace App\Controllers;


use CodeIgniter\Config\Config;

class Transactions extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'Transactions';
    }

    public function index()
    {
        if ($bs = active_business()) {
            if($bs->type == 'C2B') {
                return $this->_renderPage('admin/transactions', $this->data);
            } else {
                return $this->_renderPage('admin/b2c/transactions', $this->data);
            }
        } else {
            return $this->_renderPage('admin/empty', $this->data);
        }
    }

    public function filter() {
        if($this->request->getPost()) {
            $from = $this->request->getPost('from');
            $from = date('m-d-Y', strtotime($from));
            $to = $this->request->getPost('to');
            $to = date('m-d-Y', strtotime($to));
            $transactions = \Config\Database::connect()->table('transactions')->where('shortcode', active_business()->shortcode)->where('date >=', $from)->where('date <=', $to)->get()->getResultObject();
            $html = view('admin/transactions_filter', ['transactions'=>$transactions]);
            $html = str_replace(array("\r", "\n"), '', $html);
            $response = [
                'status'    => 'success',
                'title'     => '',
                'message'   => '',
                'notify'    => false,
                'callback'  => 'setTransactions(\''.$html.'\')'
            ];
        } else {
            $response = [
                'status'    => 'success',
                'title'     => '',
                'message'   => '',
                'notify'    => false
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function reverse($id) {
        $business = active_business();
        $mpesa = new \App\Libraries\MpesaC2B(true);
        $mpesa->paybill = $business->shortcode;

        $mpesa->consumer_key = $business->consumer_key;
        $mpesa->consumer_secret = $business->consumer_secret;

        $mpesa->initiator_username = $business->initiator_username;
        $mpesa->initiator_password = $business->initiator_password;

        $mpesa->reverse_transaction_result_url = site_url('api/reversalurl/'.$business->shortcode.'/'.md5(trim($business->shortcode)),'https');

        $transaction = \Config\Database::connect()->table('transactions')->where('id', $id)->get()->getRowObject();
        if($transaction) {
            $resp = $mpesa->reverse_transaction($transaction->msisdn, $transaction->trans_id, $transaction->trans_amount);
            if($resp && $resp = json_decode($resp)) {
                if(isset($resp->ResultCode) && $resp->ResultCode == 0){
                    $response = [
                        'status'    => 'success',
                        'title'     => 'Reversal Request Sent',
                        'message'   => 'Reversal request has been sent successfully',
                        'callback'  => '$(\'.modal\').modal(\'hide\');'
                    ];
                } else {
                    $response = [
                        'status'    => 'error',
                        'title'     => 'Reversal Failed',
                        'message'   => 'An API Error occured',
                        'callback'  => '$(\'.modal\').modal(\'hide\');'
                    ];
                }
                //TODO: Maybe log the Reverse request?
            } else {
                $response = [
                    'status'    => 'error',
                    'title'     => 'Reversal Failed',
                    'message'   => 'Invalid response from the API',
                    'callback'  => '$(\'.modal\').modal(\'hide\');'
                ];
            }
        } else {
            $response = [
                'status'    => 'error',
                'title'     => 'Transaction Not Found',
                'message'   => 'The specified Transaction was not found in the database',
                'callback'  => '$(\'.modal\').modal(\'hide\');'
            ];
        }

        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function reports(){
        if($this->request->getPost()){
            $from = $this->request->getPost('from');
            $to = $this->request->getPost('to');
            $business = active_business();
            $builder = \Config\Database::connect()->table('transactions');

            $totalTransactions = $builder->selectSum('trans_amount', 'total')->groupStart()->where('date >=', date('m-d-Y', strtotime($from)))->where('date <=', date('m-d-Y', strtotime($to)))->groupEnd()->where('shortcode', $business->shortcode)->where('trans_type', 'income')->get()->getRow();
            $totalTransactions =  $totalTransactions->total ? $totalTransactions->total : 0.00;
            $totalTransactions = number_format($totalTransactions, 2);

            $totalReversals = $builder->selectSum('trans_amount', 'total')->groupStart()->where('date >=', date('m-d-Y', strtotime($from)))->where('date <=', date('m-d-Y', strtotime($to)))->groupEnd()->where('shortcode', $business->shortcode)->where('trans_type', 'reversal')->get()->getRow();
            $totalReversals =  $totalReversals->total ? $totalReversals->total : 0.00;
            $totalReversals = number_format($totalReversals, 2);

            $totalTransRows = $builder->groupStart()->where('date >=', date('m-d-Y', strtotime($from)))->where('date <=', date('m-d-Y', strtotime($to)))->groupEnd()->where('shortcode', $business->shortcode)->countAllResults(true);
            $totalReversalRows = $builder->groupStart()->where('date >=', date('m-d-Y', strtotime($from)))->where('date <=', date('m-d-Y', strtotime($to)))->groupEnd()->where('shortcode', $business->shortcode)->where('trans_type', 'reversal')->countAllResults(true);
            $totalSuccessRows = $totalTransRows-$totalReversalRows;
            $reports = [
                'income'    => [
                    'total'     => get_option('currency', 'Kshs').' '.$totalTransactions,
                    'count'     => $totalSuccessRows
                ],
                'reversals' => [
                    'total'     => get_option('currency', 'Kshs').' '.$totalReversals,
                    'count'     => $totalReversalRows
                ]
            ];
            $response = [
                'status'    => 'success',
                'title'     => '',
                'message'   => '',
                'notify'    => false,
                'callback'  => 'reports('.json_encode($reports).')'
            ];

        } else {
            $response = [
                'status'    => 'error',
                'title'     => '',
                'message'   => 'Invalid Request',
                'notifyType'    => 'toastr'
            ];
        }
        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }

    public function send_money() {
        $validation = \Config\Services::validation();
        $validation->setRule('command', 'Transaction Type', 'trim|required|in_list[PromotionPayment,SalaryPayment]');
        $validation->setRule('phone', 'Phone Number', 'trim|required|is_numeric|exact_length[12]');
        $validation->setRule('amount', 'Amount', 'trim|numeric|is_natural|greater_than[49]');
        if($validation->withRequest($this->request)->run()) {
            // Make response
            $mpesa = new \App\Libraries\MpesaB2C(false);
            $business = active_business();
            $mpesa->paybill = $business->shortcode;

            $mpesa->consumer_key = $business->consumer_key;
            $mpesa->consumer_secret = $business->consumer_secret;

            $mpesa->initiator_username = $business->initiator_username;
            $mpesa->initiator_password = $business->initiator_password;

            $mpesa->b2c_result_url = site_url('api/resulturl/'.$business->shortcode.'/'.md5(trim($business->shortcode)), 'https');
            //$mpesa->b2c_result_url = 'https://dev.bnnito254.com/cb.php';

            $amount = $this->request->getPost('amount');
            $phone = $this->request->getPost('phone');
            $command = $this->request->getPost('command');

            try {
                $mpesa_request = $mpesa->b2c($amount, $phone, $command);
            } catch (\Exception $e) {
                $mpesa_request = false;
            }
            $res = @json_decode($mpesa_request);

            if($res) {
                if(isset($res->ResponseCode) && $res->ResponseCode == '0') {
                    $to_db = [
                        'conversation_id'   => $res->ConversationID,
                        'request_code'      => $res->ResponseCode,
                        'phone'             => $phone,
                        'amount'            => $amount,
                        'date'              => date('m-d-Y'),
                        'shortcode'         => $business->shortcode
                    ];
                    \Config\Database::connect()->table('b2c')->insert($to_db);
                    $response = [
                        'status'    => 'success',
                        'title'     => 'Success',
                        'message'   => 'Disbursement request sent successfully',
                        'callback'  => '$(\'.modal\').modal(\'hide\');'
                    ];

                    // TODO: probably log requests?
                } else {
                    if(isset($res->ResponseDescription)) {
                        $response = [
                            'status'    => 'error',
                            'title'     => 'API Error',
                            'message'   => $res->ResponseDescription,
                            'callback'  => '$(\'.modal\').modal(\'hide\');'
                        ];
                    } else {
                        if(isset($res->errorMessage)) {
                            $msg = $res->errorMessage;
                        } else {
                            $msg = 'An unknown API error occurred';
                        }
                        $response = [
                            'status'    => 'error',
                            'title'     => 'API Error',
                            'message'   => $msg,
                            'callback'  => '$(\'.modal\').modal(\'hide\');'
                        ];
                    }
                }
            } else {
                $response = [
                    'status'    => 'error',
                    'title'     => 'API Error',
                    'message'   => 'An API error occurred',
                    'callback'  => '$(\'.modal\').modal(\'hide\');'
                ];
            }

        } else {
            $response = [
                'status'    => 'error',
                'title'     => 'Validation Errors',
                'message'   => implode(', ', $validation->getErrors()),
                'callback'  => '$(\'.modal\').modal(\'hide\');'
            ];
        }

        return $this->response->setContentType('application/json')->setBody(json_encode($response));
    }
}