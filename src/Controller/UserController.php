<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;


final class UserController extends AbstractController
{
    #[Route('/user', methods: ['POST'], name: 'app_user_create')]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setName($data['name'] ?? '');
        $user->setRole($data['role'] ?? '');

        $em->persist($user);
        $em->flush();

        return $this->json(['message'=> 'Usuario criado com sucesso', 'id' => $user->getId()]);
    }

    #[Route('/user/{id}', methods: ['DELETE'], name: 'app_user_delete')]
    public function delete(int $id, Request $request , EntityManagerInterface $em): JsonResponse{
        $user = $em->getRepository(User::class)->findOneBy(['id' => $id]);
        if($user === null) {
            return $this->json(['error' => 'Usuario nao encontrado'], 404);
        }

        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Usuario deletado com sucesso']);
    }
}
