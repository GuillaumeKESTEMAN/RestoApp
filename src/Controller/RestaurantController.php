<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Restaurant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    #[Route('/restaurants/{id}', name: 'restaurant')]
    public function __invoke(Restaurant $restaurant): Response
    {
        return $this->render('restaurant/restaurant.html.twig', ['restaurant' => $restaurant]);
    }
}
