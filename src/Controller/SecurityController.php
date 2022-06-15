<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion et les éventuelles erreurs.
     * Attention : n'utilise PAS un formulaire du composant Form.
     */
    #[Route(path: '/login', name: 'login')]
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            // Nom d'utilisateur entré (si il existe)
            'last_username' => $authenticationUtils->getLastUsername(),
            // Erreur d'authentification (si elle existe)
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }
}
