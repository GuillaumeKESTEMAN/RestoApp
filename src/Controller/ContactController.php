<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

class ContactController extends AbstractController
{
    #[Route(path: '/contact', name: 'contact')]
    public function contact(Request $request, ManagerRegistry $doctrine, Security $security)
    {
        $contact = new Contact();

        $formBuilder = $this->createFormBuilder($contact)
            ->add('email', TextType::class, [
                'label' => 'Email : ',
                'data' => $security->getUser()->getEmail(),
                'attr' => ['readonly' => true, 'class' => 'grey-background'],
            ])
            ->add('userId', HiddenType::class, [
                'data' => $security->getUser()->getId(),
            ])
            ->add('sujet', TextType::class, [
                'label' => 'Sujet : ',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description : ',
            ])
            ->add('send', SubmitType::class, ['label' => 'Envoyer']);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();

            date_default_timezone_set('UTC');
            $contact->setDate(date('d-m-Y h:i:s A'));

            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $contact = new Contact();

            $formBuilder = $this->createFormBuilder($contact)
                ->add('email', TextType::class, [
                    'label' => 'Email : ',
                    'data' => $security->getUser()->getEmail(),
                    'attr' => ['readonly' => true, 'class' => 'grey-background'],
                ])
                ->add('userId', HiddenType::class, [
                    'data' => $security->getUser()->getId(),
                ])
                ->add('sujet', TextType::class, [
                    'label' => 'Sujet : ',
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description : ',
                ])
                ->add('send', SubmitType::class, ['label' => 'Envoyer']);

            $form = $formBuilder->getForm();
        }

        return $this->render('contact/contact.html.twig', ['form' => $form->createView()]);
    }
}
