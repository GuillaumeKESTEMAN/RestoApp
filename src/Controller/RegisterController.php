<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Authenticator\FormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function index(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, FormAuthenticator $formAuthenticator, UserAuthenticatorInterface $authenticator): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();

        $formBuilder = $this->createFormBuilder($user)
            ->add('name', TextType::class, [
                'label' => 'Nom d\'utilisateur : ',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email : ',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe : ',
            ])
            ->add('send', SubmitType::class, ['label' => 'Envoyer']);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword()));

            $userRepository->add($user, true);

            $authenticator->authenticateUser(
                $user,
                $formAuthenticator,
                $request
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
