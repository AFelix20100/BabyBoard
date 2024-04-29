<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Team;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Callback;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('PointsBlue', IntegerType::class,[
                'label' => "Point équipe Bleu",
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 10,
                        'message' => 'Les points doivent être inférieurs ou égaux à 10.',
                    ]),
                ],
            ])
            ->add('PointsRed', IntegerType::class,[
                'label' => "Point équipe Rouge",
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 10,
                        'message' => 'Les points doivent être inférieurs ou égaux à 10.',
                    ]),
                ],
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
