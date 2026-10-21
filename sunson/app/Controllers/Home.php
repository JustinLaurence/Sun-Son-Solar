<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Home extends BaseController
{
    protected $helpers = ['form'];

    public function index()
    {
        $serviceCategories = [];

        try {
            $serviceCategories = db_connect()->table('service_settings')
                ->where('is_available', 1)
                ->orderBy('service_type')
                ->get()->getResultArray();
        } catch (\Throwable $e) {
            log_message('warning', 'Unable to load service settings: ' . $e->getMessage());
        }

        return view('home', [
            'serviceCategories' => $serviceCategories,
        ]);
    }

    public function requestQuote(): RedirectResponse
    {
        $rules = [
            'name' => 'required|max_length[120]',
            'email' => 'required|valid_email|max_length[100]',
            'phone_number' => 'permit_empty|regex_match[/^[0-9+\-\s]{7,20}$/]',
            'details' => 'required|max_length[4000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/#quote')->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $saved = db_connect()->table('quote_requests')->insert([
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone_number' => $this->request->getPost('phone_number'),
                'details' => $this->request->getPost('details'),
            ]);
        } catch (\Throwable $e) {
            log_message('warning', 'Unable to save quote request: ' . $e->getMessage());
            return redirect()->to('/#quote')->withInput()->with('error', 'Your quote request could not be saved. Please try again.');
        }

        if (! $saved) {
            return redirect()->to('/#quote')->withInput()->with('error', 'Your quote request could not be saved. Please try again.');
        }

        return redirect()->to('/#quote')->with('success', 'Thanks! The Sun Son Solar team will follow up about your quote.');
    }
}
