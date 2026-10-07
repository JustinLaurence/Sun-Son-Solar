<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['form'];

    public function login()
    {
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Please fill in both your username and password.');
        }

        $user = (new UserModel())->where('username', $this->request->getPost('username'))->first();

        if (! $user || ! password_verify((string) $this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }

        session()->regenerate();
        session()->set([
            'user_id'    => $user['id'],
            'first_name' => $user['first_name'],
            'role'       => $user['role'],
        ]);

        return redirect()->to('/dashboard');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function attemptRegister()
    {
        $rules = [
            'first_name'   => 'required|max_length[50]',
            'middle_name'  => 'permit_empty|max_length[50]',
            'last_name'    => 'required|max_length[50]',
            'birthdate'    => 'required|valid_date[Y-m-d]',
            'gender'       => 'required|in_list[Male,Female]',
            'email'        => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'phone_number' => 'required|regex_match[/^[0-9+\-\s]{7,20}$/]',
            'address'      => 'required|max_length[500]',
            'department'   => 'permit_empty|in_list[Administration,IT,Dispatch,Accounting,HR,Marketing,Sales,Customer Service]',
            'username'     => 'required|max_length[50]|is_unique[users.username]',
            'password'     => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        (new UserModel())->insert([
            'first_name'   => $this->request->getPost('first_name'),
            'middle_name'  => $this->request->getPost('middle_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'birthdate'    => $this->request->getPost('birthdate'),
            'gender'       => $this->request->getPost('gender'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address'      => $this->request->getPost('address'),
            'department'   => $this->request->getPost('department') ?: null,
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
        ]);

        return redirect()->to('/register')->with('success', 'Account created!');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
