<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ContactController extends AbstractController
{
    #[Route(path: '/contact', name: 'contact')]
    public function contact(Request $request, ContactRepository $contactRepository): Response
    {
        $user = $this->getUser();

        $contact = new Contact();

        $form = $this->formInit($contact, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Contact $contact */
            $contact = $form->getData();

            // date_default_timezone_set('UTC'); ça c'est plutôt à configurer au niveau de ton php.ini
            $contact->setDate(new \DateTime()); // on va plutôt stocker un objet DateTime, ou sinon un DateTimeImmutable
            $contact->setUser($user); // on va plutôt définir une relation.

            // on préfèrera injecter un repository, pour s'abstraire de la notion de doctrine.
            $contactRepository->persist($contact);
            $contactRepository->flush();

            // si tu veux afficher à nouveau tu as juste à reprendre le builder précédent. Une autre pratique est de rediriger.
            // en prime tu peux utiliser la session pour afficher un message de confirmation :
            $this->addFlash('success', 'Message envoyé, Merci !');

            $contact = new Contact();

            $form = $this->formInit($contact, $user);
        }

        return $this->render('contact/contact.html.twig', ['form' => $form->createView()]);
    }

    private function formInit(Contact $contact, UserInterface $user): FormInterface
    {
        $formBuilder = $this->createFormBuilder($contact)
            ->add('email', TextType::class, [
                'label' => 'Email : ',
                'data' => $user->getEmail(),
                'attr' => ['readonly' => true, 'class' => 'grey-background'],
            ])
            ->add('sujet', TextType::class, [
                'label' => 'Sujet : ',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description : ',
            ])
            ->add('send', SubmitType::class, ['label' => 'Envoyer']);

        return $formBuilder->getForm();
    }
}
