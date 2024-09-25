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
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel événement
        $event = new Events();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        $isFormInvalid = false;

        // Gestion de la soumission du formulaire
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event->setCreateAt(new \DateTimeImmutable()); // Date de création automatique
                $entityManager->persist($event);
                $entityManager->flush();
    
                // Redirection vers la page d'accueil après la création de l'événement
                return $this->redirectToRoute('app_home');
            } else {
                // Le formulaire est soumis mais invalide
                $isFormInvalid = true;
            }
        }

        // Affichage de la liste des événements
        return $this->render('home/index.html.twig', [
            'eventForm' => $form->createView(),
            'events' => $entityManager->getRepository(Events::class)->findAll(),
            'users' => $entityManager->getRepository(User::class)->findAll(),
            'isFormInvalid' => $isFormInvalid,
        ]);
    }
}
