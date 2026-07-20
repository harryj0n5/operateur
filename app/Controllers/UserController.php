<?php

namespace App\Controllers;

use App\Services\UserService;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function login()
    {
        $telephone = $this->request->getPost('telephone');

        if (!$telephone) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Le numéro de téléphone est requis.']);
        }

        try {
            $user = $this->userService->loginOuCreer($telephone);
            return $this->response->setStatusCode(200)->setJSON($user);
        } catch (\RuntimeException $e) {
            return $this->response->setStatusCode(400)->setJSON(['error' => $e->getMessage()]);
        }
    }
}