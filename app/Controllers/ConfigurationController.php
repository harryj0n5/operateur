<?php

namespace App\Controllers;

use App\Services\ConfigurationService;

class ConfigurationController extends BaseController
{
    protected ConfigurationService $configurationService;

    public function __construct()
    {
        $this->configurationService = new ConfigurationService();
    }

    public function index()
    {
        return view('configuration/index', [
            'configurations' => $this->configurationService->getAll()
        ]);
    }

    public function create()
    {
        return view('configuration/create', [
            'operateurs' => $this->configurationService->getOperateurs()
        ]);
    }

    public function store()
    {
        try {

            $data = [
                'prefix' => $this->request->getPost('prefix'),
                'operateur_id' => $this->request->getPost('operateur_id')
            ];

            $this->configurationService->create($data);

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
        $configuration = $this->configurationService->getById((int) $id);

        if (!$configuration) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('configuration/edit', [
            'configuration' => $configuration,
            'operateurs' => $this->configurationService->getOperateurs()
        ]);
    }

    public function update($id)
    {
        try {

            $data = [
                'prefix' => $this->request->getPost('prefix'),
                'operateur_id' => $this->request->getPost('operateur_id')
            ];

            $this->configurationService->update((int) $id, $data);

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

            $this->configurationService->delete((int) $id);

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
