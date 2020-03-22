<?php


namespace App\Entities;


use Carbon\Carbon;
use CodeIgniter\Entity;
use Config\Database;

class BusinessEntity extends Entity
{
    public function getTodaysTotalTransactions()
    {
        $builder = Database::connect()->table('transactions');
        $total = $builder->selectSum('trans_amount', 'total')->where('date', date('m-d-Y'))->where('shortcode', $this->shortcode)->where('trans_type', 'income')->get()->getRow();

        $total = $total->total ? $total->total : 0.00;
        return number_format($total, 2);
    }

    public function getTodaysTotalReversals()
    {
        $builder = Database::connect()->table('transactions');
        $to = Carbon::now('Africa/Nairobi')->format('m-d-Y');
        $from = Carbon::now('Africa/Nairobi')->subDays(29)->format('m-d-Y');

        $total = $builder->selectSum('trans_amount', 'total')->where('date', date('m-d-Y'))->where('shortcode', $this->shortcode)->where('trans_type', 'reversal')->get()->getRow();

        $total = $total->total ? $total->total : 0.00;
        return number_format($total, 2);
    }

    public function getLastTotalTransactions($from = FALSE, $to = FALSE)
    {
        $to = $to ? $to : Carbon::now('Africa/Nairobi')->format('m-d-Y');
        $from = $from ? $from : Carbon::now('Africa/Nairobi')->subDays(29)->format('m-d-Y');

        $builder = Database::connect()->table('transactions');
        $total = $builder->selectSum('trans_amount', 'total')->groupStart()->where('date >=', $from)->where('date <=', $to)->groupEnd()->where('shortcode', $this->shortcode)->where('trans_type', 'income')->get()->getRow();

        $total = $total->total ? $total->total : 0.00;
        return number_format($total, 2);
    }

    public function getLastTotalReversals($from = FALSE, $to = FALSE)
    {

        $to = $to ? $to : Carbon::now('Africa/Nairobi')->format('m-d-Y');
        $from = $from ? $from : Carbon::now('Africa/Nairobi')->subDays(29)->format('m-d-Y');

        $builder = Database::connect()->table('transactions');

        $total = $builder->selectSum('trans_amount', 'total')->groupStart()->where('date >=', $from)->where('date <=', $to)->groupEnd()->where('shortcode', $this->shortcode)->where('trans_type', 'reversal')->get()->getRow();

        $total = $total->total ? $total->total : 0.00;
        return number_format($total, 2);
    }

    public function getTotalTransactions()
    {
        $builder = Database::connect()->table('transactions');
        $total = $builder->selectSum('trans_amount', 'total')->where('date', date('m-d-Y'))->where('shortcode', $this->shortcode)->where('trans_type', 'income')->get()->getRow();

        $total = $total->total ? $total->total : 0.00;
        return number_format($total, 2);
    }

    public function graph()
    {
        $labels = [];
        $income = [];
        $reversals = [];
        $to = Carbon::now('Africa/Nairobi')->toDateTime();
        $from = Carbon::now('Africa/Nairobi')->subDays(30)->toDateTime();

        $periods = new \DatePeriod($from, new \DateInterval('P1D'), $to->modify('+1 day'));
        $builder = Database::connect()->table('transactions');
        foreach($periods as $period) {
            array_push($labels, $period->format('d M'));
            $total = $builder->selectSum('trans_amount', 'total')->where('date', $period->format('m-d-Y'))->where('shortcode', $this->shortcode)->where('trans_type', 'income')->get()->getRow();
            $total = $total->total ? $total->total : 0.00;
            array_push($income, number_format($total, 2));
            $total = $builder->selectSum('trans_amount', 'total')->where('date', $period->format('m-d-Y'))->where('shortcode', $this->shortcode)->where('trans_type', 'reversal')->get()->getRow();
            $total = $total->total ? $total->total : 0.00;
            array_push($reversals, number_format($total, 2));
        }
        return [
            'labels'    => $labels,
            'income'   => $income,
            'reversals' => $reversals
        ];
    }

    public function getAllTransactions()
    {
        $builder = Database::connect()->table('transactions');
        return $builder->where('shortcode', $this->shortcode)->orderBy('id', 'DESC')->get()->getResultObject();
    }

    public function getReversedTransactions()
    {

    }
}