<?php

namespace App\Controllers;


class Main extends BaseController
{

    public function index() {
        $data = ['title' => 'Home'];
        return view('common/header', $data) . view('common/footer');
    }
}