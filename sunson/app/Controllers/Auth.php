<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $helpers = ['form'];

    public function login()
    {
        if (session()->has('user_id')) {
            if (session('must_change_password')) {
                return redirect()->to('/change-password');
            }

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

        if (! $user || ! $user['is_active'] || ! password_verify((string) $this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Invalid username or password.');
        }

        session()->regenerate();
        session()->set([
            'user_id'    => $user['id'],
            'first_name' => $user['first_name'],
            'role'       => $user['role'],
            'last_activity' => time(),
            'must_change_password' => (bool) $user['must_change_password'],
        ]);

        if ($user['must_change_password']) {
            return redirect()->to('/change-password');
        }

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
            'gender'       => 'required|max_length[20]',
            'email'        => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'phone_number' => 'required|regex_match[/^[0-9+\-\s]{7,20}$/]',
            'address'      => 'required|max_length[500]',
            'username'     => 'required|max_length[50]|is_unique[users.username]',
            'password'     => 'required|min_length[12]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = (new UserModel())->insert([
            'first_name'   => $this->request->getPost('first_name'),
            'middle_name'  => $this->request->getPost('middle_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'birthdate'    => $this->request->getPost('birthdate'),
            'gender'       => $this->request->getPost('gender'),
            'email'        => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number'),
            'address'      => $this->request->getPost('address'),
            'department'   => null,
            'role'         => 'customer',
            'username'     => $this->request->getPost('username'),
            'password'     => $this->request->getPost('password'),
            'is_active'    => 1,
            'must_change_password' => 0,
        ]);
        if (! $userId) {
            return redirect()->back()->withInput()->with('error', 'We could not create your account. Please try again.');
        }

        return redirect()->to('/login')->with('success', 'Account created. You can now sign in.');
    }

    public function changePassword()
    {
        if (! session()->has('user_id')) {
            return redirect()->to('/login');
        }

        if (! $this->validPasswordSession()) {
            return redirect()->to('/login')->with('error', 'Your session expired. Please sign in again.');
        }

        return view('auth/change_password');
    }

    public function savePassword()
    {
        if (! session()->has('user_id')) {
            return redirect()->to('/login');
        }

        if (! $this->validPasswordSession()) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Your session expired. Please sign in again.');
        }

        $rules = [
            'password' => 'required|min_length[12]',
            'password_confirm' => 'required|matches[password]',
        ];
        if (! session('must_change_password')) {
            $rules['current_password'] = 'required';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find((int) session('user_id'));
        if (! $user || ! $user['is_active']) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Your account is unavailable.');
        }
        if (! session('must_change_password') && ! password_verify((string) $this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Your current password is incorrect.');
        }
        if (session('must_change_password') && password_verify((string) $this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->with('error', 'Your new password must be different from the temporary password.');
        }

        if (! $userModel->update(session('user_id'), [
            'password' => $this->request->getPost('password'),
            'must_change_password' => 0,
        ])) {
            return redirect()->back()->with('error', 'We could not update your password. Please try again.');
        }

        session()->set('last_activity', time());
        session()->set('must_change_password', false);

        return redirect()->to('/dashboard')->with('success', 'Password updated.');
    }

    private function validPasswordSession(): bool
    {
        $lastActivity = (int) session('last_activity');
        return $lastActivity > 0 && time() - $lastActivity <= 1800;
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
}
