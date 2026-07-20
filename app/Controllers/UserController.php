<?php

namespace App\Controllers;

use App\Services\UserService;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{

    protected UserService $userService;


    public function __construct()
    {
        $this->userService = new UserService();
    }



    /**
     * GET /users
     * Liste tous les utilisateurs
     */
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





  

}