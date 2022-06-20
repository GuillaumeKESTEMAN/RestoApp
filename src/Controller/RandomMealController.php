<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\RandomMeal;
use App\EntityById;
use App\Repository\MealRepository;
use App\Repository\RandomMealRepository;
use App\Repository\RestaurantRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RandomMealController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/random_meal', name: 'random_meal')]
    public function index(RandomMealRepository $randomMealRepository, MealRepository $mealRepository, RestaurantRepository $restaurantRepository): Response
    {
        $lastRandomMeal = $randomMealRepository->getLastEntitiesById();
        $randomMeal = null;

        if (count($lastRandomMeal) === 0 or time() - strtotime($lastRandomMeal[0]->getDate()->format('Y-m-d H:i:s')) > 60 * 60 * 24) {
            $lastMeal = $mealRepository->getLastEntitiesById();

            if (count($lastRandomMeal) !== 0) {
                $meal = $this->getRandomMeal($lastMeal[0]->getId(), $mealRepository);

                if (null !== $meal) {
                    $randomMeal = new RandomMeal();
                    $randomMeal->setMeal($meal);
                    $randomMeal->setDate(new \DateTime());

                    $randomMealRepository->persist($randomMeal);
                    $randomMealRepository->flush();
                }
            }
        } else {
            $randomMeal = $lastRandomMeal[0];
            $meal = $mealRepository->findOneBy(['id' => $randomMeal->getMeal()->getId()]);
            $restaurant = $restaurantRepository->findOneBy(['id' => $meal->getRestaurant()->getId()]);
            $meal->setRestaurant($restaurant);
            $randomMeal->setMeal($meal);
        }

        return $this->render('random_meal/index.html.twig', ['meal' => $randomMeal ? $randomMeal->getMeal() : null]);
    }

    /**
     * @throws Exception
     */
    private function getRandomMeal(int $maxId, MealRepository $mealRepository): ?Meal
    {
        $randomId = random_int(1, $maxId);

        $qb = $mealRepository->createQueryBuilder('p')
            ->where('p.id >= ' . $randomId)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
