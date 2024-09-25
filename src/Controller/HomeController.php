<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\User;
use App\Form\EventFormType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function eventform(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $event = new Events();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $name = $form->get('name')->getData();
            $date = $form->get('date')->getData();
            $place = $form->get('place')->getData();
            $participants = $form->get('participants')->getData();
            $description = $form->get('description')->getData();
            $image = $form->get('image')->getData();

            // $classroomid = $request->get('classroom');

            // $user->setClassroom($entityManager->getRepository(Classroom::class)->find((int)$classroomid));

            // $user->setCreateAt(new \DateTimeImmutable());

            // encode the plain password

            $entityManager->persist($event);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
            'eventForm' => $form,
            'events' => $entityManager->getRepository(Events::class)->findAll(),
        ]);
    }
}
