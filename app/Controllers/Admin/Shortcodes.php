<?php


namespace App\Controllers\Admin;


use App\Models\BusinessModel;

class Shortcodes extends DashController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = "Shortcodes";
    }

    public function index()
    {

        return $this->_render('dashboard/shortcodes/index', $this->data);
    }

    public function view($id)
    {
        $model = new BusinessModel();
        $shortcode = $model->find($id);
        if(!$shortcode) {
            return redirect()->back()->with('error', 'Shortcode not found');
        }

        $this->data['title'] = $shortcode->name;
        $this->data['shortcode'] = $shortcode;

        return $this->_render('dashboard/shortcodes/view', $this->data);
    }
}