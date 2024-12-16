<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/data', name: 'get_data', methods: ['GET'])]
    public function getData(): JsonResponse
    {
        $data = [
            'message' => 'Hello from Symfony',
            'status' => 'success',
        ];

        return new JsonResponse($data);
    }
}