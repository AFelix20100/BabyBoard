<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('PointsBlue', IntegerType::class,[
                'label' => "Point équipe Bleu"
            ])
            ->add('PointsRed', IntegerType::class,[
                'label' => "Point équipe Rouge"
            ])
            ->add('RedTeam', EntityType::class, [
                'label' => 'Équipe Rouge',
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('BlueTeam', EntityType::class, [
                'label' => 'Équipe Bleu',
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('winnerTeam', EntityType::class, [
                'label' => 'Équipe Gagnante',
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
