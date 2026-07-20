<?php

namespace App\Controllers;

use App\Models\ConfigurationModel;
use App\Services\UserService;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{

    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('users/index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        try {

            $data = [
                'telephone' => $this->request->getPost('telephone'),
                'solde' => $this->request->getPost('solde'),
                'type_user_id' => $this->request->getPost('type_user_id')
            ];


            $this->userService->creer($data);


            return redirect()
                ->to('/users')
                ->with('success', 'Utilisateur ajouté');


        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage());

        }
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);


        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }


        return view('users/edit', [
            'user' => $user
        ]);
    }

    public function update($id)
    {
        $data = [
            'telephone' => $this->request->getPost('telephone'),
            'solde' => $this->request->getPost('solde'),
            'type_user_id' => $this->request->getPost('type_user_id')
        ];


        $this->userService->updateUser(
            $id,
            $data
        );


        return redirect()
            ->to('/users');
    }

    public function delete($id)
    {
        $this->userService->deleteUser($id);

        return redirect()
            ->to('/users');
    }

    public function seConnecter()
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

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function dashboard_client()
    {
        if (!session()->get('user_id') || session()->get('type_user_id') !== 2) {
            return redirect()->to('/');
        }

        $solde = $this->userService->soldeClient(session()->get('user_id'));
        return view('user/dashboard', ["solde" => $solde]);
    }
}