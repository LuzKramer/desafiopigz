<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Veiculo;
use App\Entity\Carro;
use App\Entity\Fipe;
use App\Entity\User;
use Dom\Entity;

final class VeiculoController extends AbstractController
{
    #[Route('/veiculo', name: 'app_veiculo', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {

        $veiculos = $em->getRepository(Veiculo::class)->findAll();

        $data = [];
        foreach ($veiculos as $veiculo) {
            $carro = $em->getRepository(Carro::class)->find($veiculo->getCarroId());
            $fipe = $em->getRepository(Fipe::class)->findOneBy(['carro_id' => $carro->getId()]);
            $valorFipe = $fipe ? $fipe->getValor() : null;
            $diferenca = $valorFipe !== null ? $veiculo->getValor() - $valorFipe : null;
            $data[] = [
                'id' => $veiculo->getId(),
                'carro' => [
                    'id' => $carro->getId(),
                    'fabricante' => $carro->getFabricante(),
                    'modelo' => $carro->getModelo(),
                    'version' => $carro->getVersion(),
                    'combustivel' => $carro->getCombustivel(),
                    'ano' => $carro->getAno(),
                ],
                'kilometragem' => $veiculo->getKilometragem(),
                'cor' => $veiculo->getCor(),
                'valor' => $veiculo->getValor(),
                'fipe' => $valorFipe,
                'diferenca' => $diferenca



            ];
        }
        return new JsonResponse($data);
    }
    #[Route('/veiculo/{id}', methods: ['GET'], name: 'app_veiculo_get')]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $veiculo = $em->getRepository(Veiculo::class)->findOneBy(['id' => $id]);
        if ($veiculo === null) {
            return $this->json(['error' => 'Veiculo nao encontrado'], 404);
        }
        $carro = $em->getRepository(Carro::class)->find($veiculo->getCarroId());
        $fipe = $em->getRepository(Fipe::class)->findOneBy(['carro_id' => $carro->getId()]);
        $valorFipe = $fipe ? $fipe->getValor() : null;
        $diferenca = $valorFipe !== null ? $veiculo->getValor() - $valorFipe : null;
        $data[] = [
            'id' => $veiculo->getId(),
            'carro' => [
                'id' => $carro->getId(),
                'fabricante' => $carro->getFabricante(),
                'modelo' => $carro->getModelo(),
                'version' => $carro->getVersion(),
                'combustivel' => $carro->getCombustivel(),
                'ano' => $carro->getAno(),
            ],
            'kilometragem' => $veiculo->getKilometragem(),
            'cor' => $veiculo->getCor(),
            'valor' => $veiculo->getValor(),
            'fipe' => $valorFipe,
            'diferenca' => $diferenca



        ];
        return new JsonResponse($data);
    }

    #[Route('/veiculo', methods: ['POST'], name: 'app_veiculo_create')]
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

        $veiculo = new Veiculo();
        $veiculo->setKilometragem($data['kilometragem']);
        $veiculo->setCor($data['cor']);
        $veiculo->setValor($data['valor']);
        $veiculo->setCarroId($data['carro_id']);

        $em->persist($veiculo);
        $em->flush();

        return $this->json(['message' => 'Fipe criado com sucesso']);
    }

    #[Route('/veiculo/{id}', methods: ['PUT'], name: 'app_veiculo_update')]
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

        $veiculo = $em->getRepository(Veiculo::class)->findOneBy(['id' => $id]);
        if ($veiculo === null) {
            return $this->json(['error' => 'Veiculo nao encontrado'], 404);
        }

        $veiculo->setKilometragem($data['kilometragem'] ?? $veiculo->getKilometragem());
        $veiculo->setCor($data['cor'] ?? $veiculo->getCor());
        $veiculo->setValor($data['valor' ?? $veiculo->getValor()]);

        $em->persist($veiculo);
        $em->flush();

        return $this->json(['message' => 'Veiculo atualizado com sucesso']);
    }
    #[Route('/veiculo/{id}', methods: ['DELETE'], name: 'app_veiculo_delete')]
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

        $veiculo = $em->getRepository(Veiculo::class)->findOneBy(['id' => $id]);
        if ($veiculo === null) {
            return $this->json(['error' => 'Veiculo nao encontrado'], 404);
        }

        $em->remove($veiculo);
        $em->flush();

        return $this->json(['message' => 'Veiculo deletado com sucesso']);
    }
}
