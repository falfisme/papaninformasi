<?php

namespace App\Controllers\Admin;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index() {
        $data = ['title' => 'Home'];
        return view('common/header', $data) . view('dashboard/index') . view('common/footer');

        // echo 'wah gela seh';
    }
}