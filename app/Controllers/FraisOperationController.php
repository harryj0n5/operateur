<?php

namespace App\Controllers;

use App\Services\FraisOperationService;

class FraisOperationController extends BaseController
{
    protected FraisOperationService $fraisOperationService;

    public function __construct()
    {
        $this->fraisOperationService = new FraisOperationService();
    }

    public function index()
    {
        return view('frais_operation/index', [
            'fraisOperations' => $this->fraisOperationService->getAll()
        ]);
    }

    public function create()
    {
        return view('frais_operation/create', [
            'typeOperations' => $this->fraisOperationService->getTypeOperations()
        ]);
    }

    public function store()
    {
        try {

            $data = [
                'montant_min' => $this->request->getPost('montant_min'),
                'montant_max' => $this->request->getPost('montant_max'),
                'frais' => $this->request->getPost('frais'),
                'type_operation_id' => $this->request->getPost('type_operation_id')
            ];

            $this->fraisOperationService->create($data);

            return redirect()
                ->to('/frais-operations')
                ->with('success', "Frais d'opération ajouté");

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function edit($id)
    {
        $fraisOperation = $this->fraisOperationService->getById((int) $id);

        if (!$fraisOperation) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('frais_operation/edit', [
            'fraisOperation' => $fraisOperation,
            'typeOperations' => $this->fraisOperationService->getTypeOperations()
        ]);
    }

    public function update($id)
    {
        try {

            $data = [
                'montant_min' => $this->request->getPost('montant_min'),
                'montant_max' => $this->request->getPost('montant_max'),
                'frais' => $this->request->getPost('frais'),
                'type_operation_id' => $this->request->getPost('type_operation_id')
            ];

            $this->fraisOperationService->update((int) $id, $data);

            return redirect()
                ->to('/frais-operations')
                ->with('success', "Frais d'opération modifié");

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

            $this->fraisOperationService->delete((int) $id);

            return redirect()
                ->to('/frais-operations')
                ->with('success', "Frais d'opération supprimé");

        } catch (\Exception $e) {

            return redirect()
                ->to('/frais-operations')
                ->with('error', $e->getMessage());

        }
    }
}
