<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON(['error' => 'Vous devez être connecté.']);
        }

        if ($arguments) {
            $typeUserId = (int)session()->get('type_user_id');

            $typesAutorises = [
                'operateur' => 1,
                'client' => 2,
            ];

            $attendu = $typesAutorises[$arguments[0]] ?? null;

            if ($attendu !== null && $typeUserId !== $attendu) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON(['error' => "Accès non autorisé pour ce type d'utilisateur."]);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}