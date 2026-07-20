<?php

namespace App\Controllers;

use App\Models\ConfigurationModel;
use App\Services\UserService;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        if (session()->get('user_id')) {
            return session()->get('type_user_id') === 1
                ? redirect()->to('/operateur/dashboard')
                : redirect()->to('/client/dashboard');
        }

        $configurationModel = new ConfigurationModel();
        $prefixes = array_column($configurationModel->select('prefix')->findAll(), 'prefix');

        return view('user/login', ['prefixes' => $prefixes]);
    }

    public function login()
    {
        $json = $this->request->getJSON(true); // true = tableau associatif
        $telephone = $json['telephone'] ?? null;

        if (!$telephone || !preg_match('/^0\d{9}$/', $telephone)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Numéro de téléphone invalide.']);
        }


        try {
            $user = $this->userService->loginOuCreer($telephone);

            session()->set([
                'user_id' => $user['id'],
                'type_user_id' => $user['type_user_id'],
                'telephone' => $user['telephone'],
            ]);

            return $this->response->setStatusCode(200)->setJSON($user);
        } catch (\RuntimeException $e) {
            return $this->response->setStatusCode(400)->setJSON(['error' => $e->getMessage()]);
        }
    }
}