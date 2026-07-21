<?php

namespace App\Controllers;

use App\Services\OperateurService;

class OperateurController extends BaseController
{
    protected OperateurService $operateurService;

    public function __construct()
    {
        $this->operateurService = new OperateurService();
    }

    public function index()
    {
        return view('operateur/index', [
            'operateurs' => $this->operateurService->getAll()
        ]);
    }

    public function create()
    {
        return view('operateur/create');
    }

    public function store()
    {
        try {

            $data = [
                'libelle' => $this->request->getPost('libelle'),
                'principale' => $this->request->getPost('principale'),
                'pourcentage_frais' => $this->request->getPost('pourcentage_frais')
            ];

            $this->operateurService->create($data);

            return redirect()
                ->to('/operateurs')
                ->with('success', 'Opérateur ajouté');

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function edit($id)
    {
        $operateur = $this->operateurService->getById((int) $id);

        if (!$operateur) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('operateur/edit', [
            'operateur' => $operateur
        ]);
    }

    public function update($id)
    {
        try {

            $data = [
                'libelle' => $this->request->getPost('libelle'),
                'principale' => $this->request->getPost('principale'),
                'pourcentage_frais' => $this->request->getPost('pourcentage_frais')
            ];

            $this->operateurService->update((int) $id, $data);

            return redirect()
                ->to('/operateurs')
                ->with('success', 'Opérateur modifié');

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

            $this->operateurService->delete((int) $id);

            return redirect()
                ->to('/operateurs')
                ->with('success', 'Opérateur supprimé');

        } catch (\Exception $e) {

            return redirect()
                ->to('/operateurs')
                ->with('error', $e->getMessage());

        }
    }
}
