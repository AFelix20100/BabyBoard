<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('PointsBlue')
            ->add('PointsRed')
            ->add('RedTeam', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('BlueTeam', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('winnerTeam', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
