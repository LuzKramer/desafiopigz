<?php

namespace App\Controller;

use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Fipe;
use App\Entity\Carro;
use App\Entity\User;
use App\Entity\Veiculo;

final class FipeController extends AbstractController
{
    #[Route('/fipe', name: 'app_fipe', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $fipes = $em->getRepository(Fipe::class)->findAll();
        $data = [];
        foreach ($fipes as $fipe) {
            $carro = $em->getRepository(Carro::class)->find($fipe->getCarroId());
            $data[] = [
                'id' => $fipe->getId(),
                'carro' => [
                    'id' => $carro->getId(),
                    'fabricante' => $carro->getFabricante(),
                    'modelo' => $carro->getModelo(),
                    'ano' => $carro->getAno(),
                    'version' => $carro->getVersion(),
                    'combustivel' => $carro->getCombustivel(),
                ],
                'valor' => $fipe->getValor()
            ];
        }
        return new JsonResponse($data);
    }

    #[Route('/fipe', methods: ['POST'], name: 'app_fipe_create')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        if (!isset($data['user_id'])) {
            return $this->json(['error' => 'ID do Usuário é necessario'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['id' => $data['user_id']]);
        if ($user === null) {
            return $this->json(['error' => 'ID de usuário nao encontrado'], 400);
        }
        if ($user->getRole() !== 'admin') {
            return $this->json(['error' => 'Acesso negado, somente para administradores'], 403);
        }




        if (!isset($data['carro_id'])) {
            return $this->json(['error' => 'ID do Carro é necessario'], 400);
        }

        $carro = $em->getRepository(Carro::class)->findOneBy(['id' => $data['carro_id']]);
        if ($carro === null) {
            return $this->json(['error' => 'ID de carro nao encontrado'], 400);
        }

        $fipe = new Fipe();
        $fipe->setCarroId($data['carro_id'] ?? '');
        $fipe->setValor($data['valor'] ?? '');

        $em->persist($fipe);
        $em->flush();

        return $this->json(['message' => 'Fipe criado com sucesso', 'id' => $fipe->getId()]);
    }

    #[Route('/fipe/{id}', methods: ['DELETE'], name: 'app_fipe_delete')]
    public function delete(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['user_id'])) {
            return $this->json(['error' => 'ID do Usuário é necessario'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['id' => $data['user_id']]);
        if ($user === null) {
            return $this->json(['error' => 'ID de usuário nao encontrado'], 400);
        }
        if ($user->getRole() !== 'admin') {
            return $this->json(['error' => 'Acesso negado, somente para administradores'], 403);
        }

        $fipe = $em->getRepository(Fipe::class)->findOneBy(['id' => $id]);
        if ($fipe === null) {
            return $this->json(['error' => 'Fipe nao encontrado'], 404);
        }
        $veiculo = $em->getRepository(Veiculo::class)->findOneBy(['carro_id' => $fipe->getCarroId()]);
        if ($veiculo !== null) {
            return $this->json(['error' => 'Veiculo a venda vinculado a fipe nao pode ser deletado'], 400);
        }

        $em->remove($fipe);
        $em->flush();

        return $this->json(['message' => 'Fipe deletado com sucesso']);
    }

    #[Route('/fipe/{id}', methods: ['PUT'], name: 'app_fipe_update')]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['user_id'])) {
            return $this->json(['error' => 'ID do Usuário é necessario'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['id' => $data['user_id']]);
        if ($user === null) {
            return $this->json(['error' => 'ID de usuário nao encontrado'], 400);
        }
        if ($user->getRole() !== 'admin') {
            return $this->json(['error' => 'Acesso negado, somente para administradores'], 403);
        }

        $fipe = $em->getRepository(Fipe::class)->findOneBy(['id' => $id]);
        if ($fipe === null) {
            return $this->json(['error' => 'Fipe nao encontrado'], 404);
        }

        $fipe->setCarroId($data['carro_id'] ?? $fipe->getCarroId());
        $fipe->setValor($data['valor'] ?? $fipe->getValor());

        $em->persist($fipe);
        $em->flush();

        return $this->json(['message' => 'Fipe atualizado com sucesso']);
    }

    #[Route('/fipe/{id}', methods: ['GET'], name: 'app_fipe_get')]
    public function get(int $id, EntityManagerInterface $em): JsonResponse
    {
        $fipe = $em->getRepository(Fipe::class)->findOneBy(['id' => $id]);
        if ($fipe === null) {
            return $this->json(['error' => 'Fipe nao encontrado'], 404);
        }

        $carro = $em->getRepository(Carro::class)->find($fipe->getCarroId());
        $data[] = [
            'id' => $fipe->getId(),
            'carro' => [
                'id' => $carro->getId(),
                'fabricante' => $carro->getFabricante(),
                'modelo' => $carro->getModelo(),
                'ano' => $carro->getAno(),
                'version' => $carro->getVersion(),
                'combustivel' => $carro->getCombustivel(),
            ],
            'valor' => $fipe->getValor()
        ];
        return $this->json($data);
    }
}

