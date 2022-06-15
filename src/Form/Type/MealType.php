<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Meal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MealType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $canGiveAMeal = $options['canGiveAMeal'];
        $meal = $options['meal'];
        $mealFormClass = $canGiveAMeal ? '' : 'grey-background';

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du plat : ',
                'data' => $meal->getName(),
                'attr' => ['readonly' => !$canGiveAMeal, 'class' => $mealFormClass, 'disabled' => !$canGiveAMeal],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Meal::class,
            'canGiveAMeal' => false,
            'meal' => null,
        ]);
    }

    public function getName()
    {
        return 'plat';
    }

    public function getBlockPrefix()
    {
        return 'plat';
    }
}
