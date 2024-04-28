<?php
namespace App\Form;

use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\TeamComposition;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class GameTypeAddType extends AbstractType
{
    private TeamRepository $teamRepository;
    private Security $security;

    public function __construct(TeamRepository $teamRepository, Security $security)
    {
        $this->teamRepository = $teamRepository;
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();
        $builder
            ->add('RedTeam', EntityType::class, [
                'class' => Team::class,
                'required' => true,
                'label' => "Équipe Rouge",
                'choice_label' => 'name',
                'attr' => ['class' => 'form-control js-example-basic-single'],
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('t')
                        ->join('App\Entity\TeamComposition', 'tc', 'WITH', 't.id = tc.team')
                        ->where('tc.player = :user')
                        ->andWhere('tc.isHost = true')
                        ->andWhere('t.isDeleted = false')
                        ->setParameter('user', $user);
                },
            ])
            ->add('BlueTeam', EntityType::class, [
                'class' => Team::class,
                'label' => "Équipe Bleu",
                'required' => true,
                'attr' => ['class' => 'form-control js-example-basic-single'],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.isDeleted = false');

                },
                'choice_label' => 'name'
            ]);
    }
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('RedTeam', new NotBlank());
        $metadata->addPropertyConstraint('BlueTeam', new NotBlank());

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
