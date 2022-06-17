<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\User;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditRestaurantController extends AbstractController
{
    #[Route('/restaurants/edit', name: 'restaurant_edit')]
    public function __invoke(Request $request, RestaurantRepository $restaurantRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $restaurant = $user->getFavorite() ?? new Restaurant;

        $this->denyAccessUnlessGranted('editRestaurant', $restaurant);

        $form = $this->restaurantFormInit($restaurant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $restaurant = $form->getData();
            $user->setFavorite($restaurant);

            $restaurantRepository->persist($restaurant);
            $restaurantRepository->flush();

            return $this->redirectToRoute('get_restaurant', ['id' => $restaurant->getId()]);
        }

        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            if ($errors->count() === 1 && $error->getCause()->getCode() === UniqueEntity::NOT_UNIQUE_ERROR) {
                $favorite = $restaurantRepository->findOneByName($restaurant->getName());
                $user->setFavorite($favorite);

                $entityManager->detach($restaurant);

                $userRepository->persist($user);
                $userRepository->flush();
                return $this->redirectToRoute('get_restaurant', ['id' => $favorite->getId()]);
            }
        }

        return $this->render('edit_restaurant/restaurant.html.twig', ['form' => $form->createView(), 'restaurantId' => $restaurant->getId()]);
    }

    private function restaurantFormInit(Restaurant $restaurant): FormInterface
    {
        return $this->createFormBuilder($restaurant, ['attr' => ['name' => 'restaurant_form']])
            ->add('name', TextType::class, [
                'label' => 'Nom du restaurant : ',
                'data' => $restaurant->getName(),
            ])
            ->add('send', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();
    }
}
