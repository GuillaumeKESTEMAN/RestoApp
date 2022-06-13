<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Restaurant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class RestaurantController extends AbstractController
{
    #[Route('/restaurant/{id}', name: 'restaurant')]
    public function __invoke(string $id, ManagerRegistry $doctrine): Response
    {
        if (!$restaurant = $this->findRestaurant($id, $doctrine)) {
            throw $this->createNotFoundException(sprintf('Le restaurant n°%s n\'existe pas.', $id));
        }

        $meals = $this->findMeals($restaurant->getId(), $doctrine);
        $meals = !is_null($meals) ? $meals : [];

        return $this->render('restaurant/restaurant.html.twig', [ 'restaurant' => $restaurant, 'meals' => $meals ]);
    }

    private function findRestaurant(string $id, ManagerRegistry $doctrine): ?Restaurant
    {
        $repository = $doctrine->getRepository(Restaurant::class);
        return $repository->findOneBy(['id' => $id]);
    }

    private function findMeals(string $restaurantId, ManagerRegistry $doctrine): ?array
    {
        $repository = $doctrine->getRepository(Meal::class);
        return $repository->findBy(['restaurantId' => $restaurantId]);
    }
}
