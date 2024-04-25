<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\TeamComposition;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamCompositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('isHost', CheckboxType::class, [
            //     'required' => false,
            //     'data' => $builder->getData()->isHost()
            // ])
            // ->add('isGuest', CheckboxType::class, [
            //     'required' => false,
            //     'data' => $builder->getData()->isGuest()
            // ])
            ->add('player', EntityType::class, [
                'class' => Player::class,
                'attr' => ['class' => 'form-control js-example-basic-single'],
                'label' => "Nom du joueur",
                'choice_label' => 'pseudo',
            ])
            // ->add('team', EntityType::class, [
            //     'class' => Team::class,
            //     'choice_label' => 'name',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TeamComposition::class,
        ]);
    }
}
