<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/user/{start}", name="user_list", defaults={"start": 0}, methods={"GET"})
     */
    public function listAction(int $start): Response
    {
        return $this->render('user/list.html.twig', [
            'start' => $start,
            'users' => $this->manager->getRepository(User::class)->findBy([], ['id' => 'ASC'], 10, $start),
            'max' => $this->manager->getRepository(User::class)->createQueryBuilder('t')->select('count(t.id)')->getQuery()->getSingleScalarResult(),
        ]);
    }

    /**
     * @Route("/user/{user}", name="user_enable", methods={"PUT"})
     */
    public function enableAction(int $user): Response
    {
        $user = $this->manager->getRepository(User::class)->find($user);
        if ($this->getUser() === $user) return new JsonResponse(['message' => 'Niew wyłączaj sam siebie'], Response::HTTP_NOT_ACCEPTABLE);
        if ($this->manager->getRepository(User::class)->countActive() === 1) return new JsonResponse(['message' => 'To jest ostatni aktywny użytkownik'], Response::HTTP_NOT_ACCEPTABLE);
        $user->toggle();
        $this->manager->flush();
        return new JsonResponse(['message' => 'Zrobione']);
    }
}
