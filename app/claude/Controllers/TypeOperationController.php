<?php

namespace App\Controllers;

use App\Services\TypeOperationService;

class TypeOperationController extends BaseController
{
    protected TypeOperationService $typeOperationService;

    public function __construct()
    {
        $this->typeOperationService = new TypeOperationService();
    }

    public function index()
    {
        return view('type_operation/index', [
            'typeOperations' => $this->typeOperationService->getAll()
        ]);
    }

    public function create()
    {
        return view('type_operation/create');
    }

    public function store()
    {
        try {

            $data = [
                'libelle' => $this->request->getPost('libelle')
            ];

            $this->typeOperationService->create($data);

            return redirect()
                ->to('/type-operations')
                ->with('success', "Type d'opération ajouté");

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());

        }
    }

    public function edit($id)
    {
        $typeOperation = $this->typeOperationService->getById((int) $id);

        if (!$typeOperation) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('type_operation/edit', [
            'typeOperation' => $typeOperation
        ]);
    }

    public function update($id)
    {
        try {

            $data = [
                'libelle' => $this->request->getPost('libelle')
            ];

            $this->typeOperationService->update((int) $id, $data);

            return redirect()
                ->to('/type-operations')
                ->with('success', "Type d'opération modifié");

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

            $this->typeOperationService->delete((int) $id);

            return redirect()
                ->to('/type-operations')
                ->with('success', "Type d'opération supprimé");

        } catch (\Exception $e) {

            return redirect()
                ->to('/type-operations')
                ->with('error', $e->getMessage());

        }
    }
}
