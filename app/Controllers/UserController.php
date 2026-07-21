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
                ? redirect()->to('/operateur/dashboard')
                : redirect()->to('/client/dashboard');
        }

        $configurationModel = new ConfigurationModel();
        $prefixes = $configurationModel
            ->select('configuration.prefix')
            ->join('operateur', 'operateur.id = configuration.operateur_id')
            ->where('operateur.principale', 1)
            ->findAll();

        $prefixes = array_column($prefixes, 'prefix');

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

        $solde = $this->userService->soldeClient($userId);
        return view('user/dashboard', ['solde' => $solde]);
    }

    public function dashboardOperateur()
    {
        $date = date('Y-m-d');

        try {
            $gain = $this->userService->situationGain($date);
        } catch (\Exception $e) {
            $gain = [
                'total_operateur_principal' => 0,
                'total_autres_operateurs' => 0,
                'nombre_transaction' => 0
            ];
        }

        return view('user/dashboard_operateur', [
            'date' => $date,
            'gain' => $gain,
            'nbUsers' => count($this->userService->getAllUsers())
        ]);
    }

    public function situationGain()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $situation = null;
        $error = null;

        try {
            $situation = $this->userService->situationGain($date);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return view('user/situation_gain', [
            'date' => $date,
            'situation' => $situation,
            'error' => $error
        ]);
    }

    /**
     * NOUVEAU : "Situation des montants à envoyer à chaque opérateur".
     * Nécessite une route, par exemple :
     *   $routes->get('operateur/montants-a-envoyer', 'UserController::situationMontantsOperateurs');
     * et une vue user/situation_montants_operateurs.php.
     */
    public function situationMontantsOperateurs()
    {
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $situation = null;
        $error = null;

        try {
            $situation = $this->userService->situationMontantsOperateurs($date);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return view('user/situation_montants_operateurs', [
            'date' => $date,
            'situation' => $situation,
            'error' => $error
        ]);
    }

    public function situationGainClient()
    {
        $clientId = $this->request->getGet('client_id');
        $date = $this->request->getGet('date') ?: date('Y-m-d');
        $situation = null;
        $error = null;

        if ($clientId) {
            try {
                $situation = $this->userService->situationGainClient((int)$clientId, $date);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('user/situation_gain_client', [
            'clients' => $this->userService->getClients(),
            'clientId' => $clientId,
            'date' => $date,
            'situation' => $situation,
            'error' => $error
        ]);
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
        $configurationModel = new ConfigurationModel();

        $bareme_transfert = $fraisModel
            ->where('type_operation_id', 3)
            ->orderBy('montant_min', 'ASC')
            ->findAll();

        $bareme_retrait = $fraisModel
            ->where('type_operation_id', 2)
            ->orderBy('montant_min', 'ASC')
            ->findAll();

        $operateurs = $configurationModel
            ->select('configuration.prefix, operateur.id as operateur_id, operateur.libelle, operateur.principale, operateur.pourcentage_frais')
            ->join('operateur', 'operateur.id = configuration.operateur_id')
            ->findAll();

        return view('operations/transfert', [
            'bareme_transfert' => $bareme_transfert,
            'bareme_retrait' => $bareme_retrait,
            'operateurs' => $operateurs,
        ]);
    }

    public function storeTransfert()
    {
        $userId = session()->get('user_id');

        $telephones = $this->request->getPost('telephones');
        $montant = (float)$this->request->getPost('montant');

        $inclureFraisRetrait = $this->request->getPost('inclure_frais_retrait') == 1;

        try {

            $this->transactionService->transfertMultiple(
                $userId,
                $telephones,
                $montant,
                $inclureFraisRetrait
            );

            return redirect()
                ->to('/operations/transfert')
                ->with('success', 'Transfert effectué.');

        } catch (\RuntimeException $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function historique()
    {
        $userId = session()->get('user_id');
        $historique = $this->transactionService->historiqueDetaille($userId);

        return view('operations/historique', ['historique' => $historique]);
    }
}