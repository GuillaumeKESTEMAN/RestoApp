<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Restaurant;
use App\Repository\MealRepository;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/restaurant/{id}', name: 'get_restaurant')]
class RestaurantIdController extends AbstractController
{
    public function __invoke(Restaurant $restaurant, RestaurantRepository $restaurantRepository, MealRepository $mealRepository, Request $request): Response
    {
        $user = $this->getUser();
        $meal = new Meal();

        $formBuilder = $this->createFormBuilder($meal)
            ->add('name', TextType::class, [
                'label' => 'Ajouter un plat : ',
            ])
            ->add('send', SubmitType::class, ['label' => 'Envoyer']);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Meal $meal */
            $meal = $form->getData();

            $meal->setUser($user);
           $restaurant->addMeal($meal);

            $mealRepository->persist($meal);
            $mealRepository->flush();
            $this->addFlash('success', 'Votre plat a été ajouté !');

            return $this->redirectToRoute('get_restaurant', ['id' => $restaurant->getId()]);
        }

        $previousRestaurants = $restaurantRepository->getPreviousEntitiesById($restaurant->getId());
        $nextRestaurants = $restaurantRepository->getNextEntitiesById($restaurant->getId());

        return $this->render(
            'restaurant_id/restaurant.html.twig',
            [
                'restaurant' => $restaurant,
                'nbrMealsItems' => count($restaurant->getMeals()),
                'form' => $form->createView(),
                'previousRestaurants' => $previousRestaurants,
                'nextRestaurants' => $nextRestaurants
            ]);
    }
}
