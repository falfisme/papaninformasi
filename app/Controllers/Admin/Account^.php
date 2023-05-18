<?php

namespace App\Controllers\Admin;
use App\Libraries\AccountLib;

use CodeIgniter\Controller;

class Account extends Controller {

    public function __construct()
    {
        $this->account = new AccountLib;
    }

    public function index()
    {
        $data = ['title' => 'User Management'];
        return view('common/header', $data) . view('account/index') . view('common/footer');  
    }

    public function login()
    {
        return view('account/login');  
    }

}

/* End of file Login   .php */
