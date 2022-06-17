<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\MealRepository;
use App\Repository\RestaurantRepository;
use App\EntityById;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantsController extends AbstractController
{
    #[Route('/restaurants/', name: 'restaurants')]
    public function __invoke(RestaurantRepository $restaurantRepository, MealRepository $mealRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();

        return $this->render('restaurants/restaurants.html.twig', ['restaurants' => $restaurants]);
    }
}
