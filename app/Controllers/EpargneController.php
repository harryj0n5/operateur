<?php

namespace App\Controllers;

use App\Services\CoffreEpargneService;

class ConfigurationController extends BaseController
{
    protected CoffreEpargneService $coffreEpargneService;

    public function __construct()
    {
        $this->coffreEpargneService = new CoffreEpargneService();
    }

    public function index()
    {
        return view('epargne/index');
    }

   

    public function store()
    {
        try {

            $data = [
                'telephone' => $this->request->getPost('telephone'),
                'solde' => $this->request->getPost('solde'),
                'pourcentage' => $this->request->getPost('pourcentage'),

            ];

            $this->coffreEpargneService->create($data);

            return redirect()
                ->to('/configurations')
                ->with('success', 'Configuration ajoutée');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function edit($id)
    {
        $configuration = $this->coffreEpargneService->getById((int) $id);

        if (!$configuration) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('configuration/edit', [
            'configuration' => $configuration,
            'operateurs' => $this->coffreEpargneService->getOperateurs()
        ]);
    }

    public function update($id)
    {
        try {

            $data = [
                'prefix' => $this->request->getPost('prefix'),
                'operateur_id' => $this->request->getPost('operateur_id')
            ];

            $this->coffreEpargneService->update((int) $id, $data);

            return redirect()
                ->to('/configurations')
                ->with('success', 'Configuration modifiée');

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

            $this->coffreEpargneService->delete((int) $id);

            return redirect()
                ->to('/configurations')
                ->with('success', 'Configuration supprimée');

        } catch (\Exception $e) {

            return redirect()
                ->to('/configurations')
                ->with('error', $e->getMessage());

        }
    }
}
