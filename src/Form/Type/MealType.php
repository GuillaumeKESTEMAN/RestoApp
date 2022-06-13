<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // var_dump($options);

        $canGiveAMeal = $options['canGiveAMeal'];
        $meal = $options['meal'];
        $mealFormClass = $canGiveAMeal ? '' : 'grey-background';

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du plat : ',
                'data' => $meal->getName(),
                'attr' => ['readonly' => !$canGiveAMeal, 'class' => $mealFormClass, 'disabled' => !$canGiveAMeal],
            ])
            ->add('userId', HiddenType::class, [
                'data' => $meal->getUserId(),
            ])
            ->add('restaurantId', HiddenType::class, [
                'data' => $meal->getRestaurantId(),
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Meal',
            'canGiveAMeal' => false,
            'meal' => null,
        ]);
    }

    public function getName() {
        return 'plat';
    }

    public function getBlockPrefix()
    {
        return 'plat';
    }
}
