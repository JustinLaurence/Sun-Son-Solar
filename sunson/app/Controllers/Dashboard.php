<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! session()->has('user_id')) {
            return redirect()->to('/login');
        }

        return view('dashboard/index', [
            'firstName' => session('first_name'),
            'role'      => session('role'),
        ]);
    }
}
