<?php

namespace App\Entity;

use App\Repository\TeamCompositionRepository;
use App\Repository\TeamRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Event\PrePersistEventArgs;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[HasLifecycleCallbacks]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $RedTeam = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $BlueTeam = null;

    #[ORM\Column(nullable: true)]
    private ?int $PointsBlue = null;

    #[ORM\Column(nullable: true)]
    private ?int $PointsRed = null;

    #[ORM\ManyToOne(inversedBy: 'wonGames')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Team $winnerTeam = null;

    #[PrePersist]
    public function setCreatedAtOnCreate(PrePersistEventArgs $eventArgs): void
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
        $this->createdAt = $now;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getRedTeam(): ?Team
    {
        return $this->RedTeam;
    }

    public function setRedTeam(?Team $RedTeam): static
    {
        $this->RedTeam = $RedTeam;

        return $this;
    }

    public function getBlueTeam(): ?Team
    {
        return $this->BlueTeam;
    }

    public function setBlueTeam(?Team $BlueTeam): static
    {
        $this->BlueTeam = $BlueTeam;

        return $this;
    }

    public function getPointsBlue(): ?int
    {
        return $this->PointsBlue;
    }

    public function setPointsBlue(?int $PointsBlue): static
    {
        $this->PointsBlue = $PointsBlue;

        return $this;
    }

    public function getPointsRed(): ?int
    {
        return $this->PointsRed;
    }

    public function setPointsRed(?int $PointsRed): static
    {
        $this->PointsRed = $PointsRed;

        return $this;
    }

    public function getWinnerTeam(): ?Team
    {
        return $this->winnerTeam;
    }

    public function setWinnerTeam(?Team $winnerTeam): static
    {
        $this->winnerTeam = $winnerTeam;

        return $this;
    }
    private function getTeamOwner(Team $team): ?Player
    {
        $teamComposition = $team->getTeamCompositions()->filter(function ($teamComposition) {
            return $teamComposition->isHost();
        })->first();

        return $teamComposition ? $teamComposition->getPlayer() : null;
    }
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->RedTeam === $this->BlueTeam) {
            $context->buildViolation("L'équipe rouge et l'équipe bleue ne peuvent pas être les mêmes")
                ->atPath('RedTeam')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateTeamOwners(ExecutionContextInterface $context): void
    {
        $redTeamOwner = $this->getTeamOwner($this->RedTeam);
        $blueTeamOwner = $this->getTeamOwner($this->BlueTeam);

        if ($redTeamOwner && $blueTeamOwner && $redTeamOwner->getId() === $blueTeamOwner->getId()) {
            $context->buildViolation("Les propriétaires des équipes rouge et bleue doivent être différents")
                ->atPath('RedTeam')
                ->addViolation();
        }
    }
    #[Assert\Callback]
    public function validatePlayers(ExecutionContextInterface $context): void
    {
        $blueTeamPlayers = $this->getBlueTeam()->getTeamCompositions();
        $redTeamPlayers = $this->getRedTeam()->getTeamCompositions();

        foreach ($blueTeamPlayers as $key1 => $blueTeamPlayer) {
            foreach ($redTeamPlayers as $key2 => $redTeamPlayer) {
                if ($blueTeamPlayer->getPlayer()->getId() === $redTeamPlayer->getPlayer()->getId()) {
                    $playerName = $blueTeamPlayer->getPlayer()->getPseudo(); // Obtenez le pseudo du joueur
                    $context->buildViolation(sprintf('Le joueur "%s" ne peut pas appartenir à deux équipes différentes.', $playerName))
                        ->atPath('RedTeam')
                        ->addViolation();
                    return;
                }
            }
        }
    }



}
