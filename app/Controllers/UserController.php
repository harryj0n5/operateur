<?php

namespace App\Controllers;

use App\Models\ConfigurationModel;
use App\Models\FraisOperationModel;
use App\Services\TransactionService;
use App\Services\UserService;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{

    protected UserService $userService;
    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->transactionService = new TransactionService();
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();

        return view('user/index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('user/create', [
            'typeUsers' => $this->userService->getTypeUsers()
        ]);
    }

    public function store()
    {
        try {

            $data = [
                'telephone' => $this->request->getPost('telephone'),
                'solde' => $this->request->getPost('solde'),
                'type_user_id' => $this->request->getPost('type_user_id')
            ];


            $this->userService->creerUser($data);


            return redirect()
                ->to('/users')
                ->with('success', 'Utilisateur ajouté');


        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById((int)$id);


        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }


        return view('user/edit', [
            'user' => $user,
            'typeUsers' => $this->userService->getTypeUsers()
        ]);
    }

    public function update($id)
    {
        try {

            $data = [
                'telephone' => $this->request->getPost('telephone'),
                'solde' => $this->request->getPost('solde'),
                'type_user_id' => $this->request->getPost('type_user_id')
            ];


            $this->userService->updateUser(
                (int)$id,
                $data
            );


            return redirect()
                ->to('/users')
                ->with('success', 'Utilisateur modifié');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function delete($id)
    {
        try {

            $this->userService->deleteUser((int)$id);

            return redirect()
                ->to('/users')
                ->with('success', 'Utilisateur supprimé');

        } catch (\Exception $e) {

            return redirect()
                ->to('/users')
                ->with('error', $e->getMessage());

        }
    }

    public function seConnecter()
    {
        if (session()->get('user_id')) {
            return (int)session()->get('type_user_id') === 1
                ? redirect()->to('/users/')
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
        $userId = session()->get('user_id');

        if (!$userId || (int)session()->get('type_user_id') !== 2) {
            return redirect()->to('/');
        }

        $user = $this->userService->getUserById($userId);

        if (!$user) {
            // Session obsolète (utilisateur supprimé ou base réinitialisée)
            session()->destroy();
            return redirect()->to('/')->with('error', 'Votre session a expiré, veuillez vous reconnecter.');
        }

        $solde = $user['solde'];
        return view('user/dashboard', ['solde' => $solde]);
    }

    public function depot()
    {
        return view('operations/depot');
    }

    public function storeDepot()
    {
        $userId = session()->get('user_id');
        $montant = (float)$this->request->getPost('montant');

        try {
            $this->transactionService->depot($userId, $montant);
            return redirect()->to('/operations/depot')->with('success', 'Dépôt de ' . $montant . ' Ar effectué.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function retrait()
    {
        $fraisModel = new FraisOperationModel();

        $bareme = $fraisModel
            ->where('type_operation_id', 2)
            ->orderBy('montant_min', 'ASC')
            ->findAll();

        return view('operations/retrait', ['bareme' => $bareme]);
    }

    public function storeRetrait()
    {
        $userId = session()->get('user_id');
        $montant = (float)$this->request->getPost('montant');

        try {
            $this->transactionService->retrait($userId, $montant);
            return redirect()->to('/operations/retrait')->with('success', 'Retrait de ' . $montant . ' Ar effectué.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function transfert(): string
    {
        $fraisModel = new FraisOperationModel();

        $bareme = $fraisModel
            ->where('type_operation_id', 3)
            ->orderBy('montant_min', 'ASC')
            ->findAll();

        return view('operations/transfert', ['bareme' => $bareme]);
    }

    public function storeTransfert()
    {
        $userId = session()->get('user_id');
        $telephoneDestinataire = $this->request->getPost('telephone_destinataire');
        $montant = (float)$this->request->getPost('montant');

        try {
            $this->transactionService->transfert($userId, $telephoneDestinataire, $montant);
            return redirect()->to('/operations/transfert')
                ->with('success', 'Transfert de ' . $montant . ' Ar vers ' . $telephoneDestinataire . ' effectué.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function historique()
    {
        $userId = session()->get('user_id');
        $historique = $this->transactionService->historiqueDetaille($userId);

        return view('operations/historique', ['historique' => $historique]);
    }
}

