<?php

namespace App\Controller;

use App\Entity\Carro;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dom\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


final class CarroController extends AbstractController
{
    #[Route('/carro', name: 'app_carro', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $carros = $em->getRepository(Carro::class)->findAll();
        $data = [];
        foreach ($carros as $carro) {
            $data[] = [
                'id' => $carro->getId(),
                'fabricante' => $carro->getFabricante(),
                'modelo' => $carro->getModelo(),
                'ano' => $carro->getAno(),
                'version' => $carro->getVersion(),
                'combustivel' => $carro->getCombustivel(),
            ];
        }

        return new JsonResponse($data);
    }
    #[Route('/carro', methods: ['POST'], name: 'app_carro_create')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['user_id'])) {
            return $this->json(['error' => 'ID do Usuário é necessario'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['id' => $data['user_id']]);
        if($user === null) {
            return $this->json(['error' => 'ID de usuário nao encontrado'], 400);
        }
        if($user->getRole() !== 'admin') {
            return $this->json(['error' => 'Acesso negado, somente para administradores'], 403);
        }

        $carro = new Carro();
        $carro->setFabricante($data['fabricante'] ?? '');
        $carro->setModelo($data['modelo'] ?? '');
        $carro->setAno($data['ano'] ?? '');
        $carro->setVersion($data['version'] ?? '');
        $carro->setCombustivel($data['combustivel'] ?? '');

        $em->persist($carro);
        $em->flush();

        return $this->json(['message' => 'Carro criado com sucesso', 'id' => $carro->getId()]);
    }

    #[Route('/carro/{id}', methods: ['PUT'], name: 'app_carro_update')]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!isset($data['user_id'])) {
            return $this->json(['error' => 'ID do Usuário é necessario'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['id' => $data['user_id']]);
        if($user === null) {
            return $this->json(['error' => 'ID de usuário nao encontrado'], 400);
        }
        if($user->getRole() !== 'admin') {
            return $this->json(['error' => 'Acesso negado, somente para administradores'], 403);
        }



        $carro = $em->getRepository(Carro::class)->findOneBy(['id' => $id]);
        if($carro === null) {
            return $this->json(['error' => 'Carro nao encontrado'], 404);
        }

        $carro->setFabricante($data['fabricante'] ?? $carro->getFabricante());
        $carro->setModelo($data['modelo'] ?? $carro->getModelo());
        $carro->setAno($data['ano'] ?? $carro->getAno());
        $carro->setVersion($data['version'] ?? $carro->getVersion());
        $carro->setCombustivel($data['combustivel'] ?? $carro->getCombustivel());

        $em->persist($carro);
        $em->flush();

        return $this->json(['message' => 'Carro atualizado com sucesso']);
    }

    #[Route('/carro/{id}', methods: ['DELETE'], name: 'app_carro_delete')]
    public function delete(int $id, Request $request , EntityManagerInterface $em): JsonResponse{
        $data = json_decode($request->getContent(), true);

        if(!isset($data['user_id'])) {
            return $this->json(['error' => 'ID do Usuário é necessario'], 400);
        }

        $user = $em->getRepository(User::class)->findOneBy(['id' => $data['user_id']]);
        if($user === null) {
            return $this->json(['error' => 'ID de usuário nao encontrado'], 400);
        }
        if($user->getRole() !== 'admin') {
            return $this->json(['error' => 'Acesso negado, somente para administradores'], 403);
        }

        $carro = $em->getRepository(Carro::class)->findOneBy(['id' => $id]);
        if($carro === null) {
            return $this->json(['error' => 'Carro nao encontrado'], 404);
        }

        $em->remove($carro);
        $em->flush();

        return $this->json(['message' => 'Carro deletado com sucesso']);
    }
}
