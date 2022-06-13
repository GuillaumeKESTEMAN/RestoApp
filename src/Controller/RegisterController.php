<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use App\Security\Authenticator\FormAuthenticator;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function index(Request $request, ManagerRegistry $doctrine, FormAuthenticator $formAuthenticator, UserAuthenticatorInterface $authenticator): Response
    {
        $user = new User();
        $error = null;

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

            $repository = $doctrine->getRepository(User::class);

            $emailExists = $repository->findOneBy(['email' => $user->getEmail()]);
            if (!is_null($emailExists)) {
                $error = new Exception('Le mail est déjà associé à un autre compte.');
            }

            $nameExists = $repository->findOneBy(['name' => $user->getName()]);
            if (!is_null($nameExists)) {
                $error = new Exception('Le nom existe déjà.');
            }

            if (is_null($error)) {
                $user->setRoles(['ROLE_USER']);

                $passwordHasherFactory = new PasswordHasherFactory([
                    'legacy' => [
                        'algorithm' => 'sha256',
                        'encode_as_base64' => true,
                        'iterations' => 1,
                    ],

                    User::class => [
                        // the new hasher, along with its options
                        'algorithm' => 'sodium',
                        'migrate_from' => [
                            'bcrypt', // uses the "bcrypt" hasher with the default options
                            'legacy', // uses the "legacy" hasher configured above
                        ],
                    ],
                ]);
                $passwordHasher = new UserPasswordHasher($passwordHasherFactory);

                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword(),
                );

                $user->setPassword($hashedPassword);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $authenticator->authenticateUser(
                    $user,
                    $formAuthenticator,
                    $request
                );

                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('register/register.html.twig', [
            'error' => $error,
            'form' => $form->createView()
        ]);
    }
}
