<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\Restaurant;
use App\Entity\RestaurantUserConnection;
use App\Entity\User;
use App\Form\Type\MealType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;

class EditRestaurantController extends AbstractController
{
    #[Route('/restaurant/edit', name: 'restaurant_edit')]
    public function __invoke(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {
        $restaurant = $this->findRestaurant($doctrine, $security);

        $canGiveAMeal = !is_null($restaurant->getId());

        $meal = $this->findMeal($restaurant->getId(), $doctrine, $security);

        $this->denyAccessUnlessGranted('edit', $restaurant);

        $form = $this->formInit($restaurant, $canGiveAMeal, $meal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $restaurant = $form->getData();

            if (is_null($restaurant->getId())) {
                $repository = $doctrine->getRepository(Restaurant::class);
                $restaurantAlreadyExists = $repository->findOneBy(['name' => $restaurant->getName()]);

                $entityManager = $doctrine->getManager();

                if (is_null($restaurantAlreadyExists)) {
                    $entityManager->persist($restaurant);
                    $entityManager->flush();
                } else {
                    $restaurant = $restaurantAlreadyExists;
                }

                $restaurantUserConnection = new RestaurantUserConnection();
                $restaurantUserConnection->setRestaurantId($restaurant->getId());
                $restaurantUserConnection->setUserId($security->getUser()->getId());

                $entityManager->persist($restaurantUserConnection);
                $entityManager->flush();
            } else {
                $entityManager = $doctrine->getManager();
                $entityManager->persist($restaurant);
                $entityManager->flush();
            }

            if (!is_null($form->getData()->plat) && $canGiveAMeal) {
                $mealAlreadyExists = $meal->getId();

                $meal->setName($form->getData()->plat->getName());

                $entityManager->persist($meal);
                $entityManager->flush();

                if (!$mealAlreadyExists) {
                    $userId = $security->getUser()->getId();

                    $userRepository = $doctrine->getRepository(User::class);
                    $user = $userRepository->findOneBy(['id' => $userId]);

                    $user->setMealId($meal->getId());

                    $entityManager->persist($user);
                    $entityManager->flush();
                }
            } else if (!is_null($form->getData()->plat) && !$canGiveAMeal && $restaurant->getId()) {
                $form = $this->formInit($restaurant, true, $meal);
            }

            return $this->render('edit_restaurant/restaurant.html.twig', ['form' => $form->createView()]);
        }


        return $this->render('edit_restaurant/restaurant.html.twig', ['form' => $form->createView()]);
    }

    private function findRestaurant(ManagerRegistry $doctrine, Security $security): ?Restaurant
    {
        $restaurant = new Restaurant();
        $userId = $security->getUser()->getId();

        $repository = $doctrine->getRepository(RestaurantUserConnection::class);
        $restaurantExists = $repository->findOneBy(['userId' => $userId]);

        if (!is_null($restaurantExists)) {
            $repository = $doctrine->getRepository(Restaurant::class);
            $restaurant = $repository->findOneBy(['id' => $restaurantExists->getRestaurantId()]);
        }

        $restaurant->setUserId($userId);

        return $restaurant;
    }

    private function findMeal(?int $restaurantId, ManagerRegistry $doctrine, Security $security): ?Meal
    {
        $meal = new Meal();
        $userId = $security->getUser()->getId();

        if (!is_null($restaurantId)) {
            $meal->setRestaurantId($restaurantId);

            $userRepository = $doctrine->getRepository(User::class);
            $user = $userRepository->findOneBy(['id' => $userId]);

            $mealId = $user->getMealId();

            if (!is_null($mealId)) {
                $mealRepository = $doctrine->getRepository(Meal::class);
                $mealExists = $mealRepository->findOneBy(['id' => $mealId]);

                if (!is_null($mealExists)) {
                    $meal = $mealExists;
                }
            }

        }

        $meal->setUserId($userId);

        return $meal;
    }

    private function formInit(Restaurant $restaurant, bool $canGiveAMeal, Meal $meal): FormInterface
    {
        return $this->createFormBuilder($restaurant, ['attr' => ['name' => 'restaurant_form']])
            ->add('name', TextType::class, [
                'label' => 'Nom du restaurant : ',
                'data' => $restaurant->getName(),
            ])
            ->add('plat', MealType::class, ['label' => false, 'required' => false, 'canGiveAMeal' => $canGiveAMeal, 'meal' => $meal])
            ->add('send', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();
    }
}
