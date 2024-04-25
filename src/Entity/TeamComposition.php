<?php

namespace App\Entity;

use App\Repository\TeamCompositionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: TeamCompositionRepository::class)]
class TeamComposition
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isHost = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isGuest = null;

    #[ORM\ManyToOne(inversedBy: 'teamCompositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'teamCompositions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isHost(): ?bool
    {
        return $this->isHost;
    }

    public function setHost(bool $isHost): static
    {
        $this->isHost = $isHost;

        return $this;
    }

    public function isGuest(): ?bool
    {
        return $this->isGuest;
    }

    public function setGuest(bool $isGuest): static
    {
        $this->isGuest = $isGuest;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    #[Assert\Callback]
    public function validateHostAddition(ExecutionContextInterface $context): void
    {
        foreach ($this->team->getTeamCompositions() as $teamComposition) {
            if ($teamComposition->isHost() && $teamComposition->getPlayer() === $this->player) {
                $context->buildViolation("Le joueur ne peut pas s'ajouter à l'équipe s'il est déjà l'hôte.")
                    ->atPath('player')
                    ->addViolation();
                break;
            }
        }
    }

    #[Assert\Callback]
    public function validateMaxPlayers(ExecutionContextInterface $context): void
    {
        if ($this->team->getTeamCompositions()->count() >= 2) {
            $context->buildViolation("L'équipe ne peut avoir que deux joueurs au maximum.")
                ->atPath('player')
                ->addViolation();
        }
    }
}
