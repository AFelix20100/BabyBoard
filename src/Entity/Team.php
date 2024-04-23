<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, TeamComposition>
     */
    #[ORM\OneToMany(targetEntity: TeamComposition::class, mappedBy: 'team')]
    private Collection $teamCompositions;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'RedTeam')]
    private Collection $games;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'winnerTeam')]
    private Collection $wonGames;

    public function __construct()
    {
        $this->teamCompositions = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->wonGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, TeamComposition>
     */
    public function getTeamCompositions(): Collection
    {
        return $this->teamCompositions;
    }

    public function addTeamComposition(TeamComposition $teamComposition): static
    {
        if (!$this->teamCompositions->contains($teamComposition)) {
            $this->teamCompositions->add($teamComposition);
            $teamComposition->setTeam($this);
        }

        return $this;
    }

    public function removeTeamComposition(TeamComposition $teamComposition): static
    {
        if ($this->teamCompositions->removeElement($teamComposition)) {
            // set the owning side to null (unless already changed)
            if ($teamComposition->getTeam() === $this) {
                $teamComposition->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setRedTeam($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getRedTeam() === $this) {
                $game->setRedTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getWonGames(): Collection
    {
        return $this->wonGames;
    }

    public function addWonGame(Game $wonGame): static
    {
        if (!$this->wonGames->contains($wonGame)) {
            $this->wonGames->add($wonGame);
            $wonGame->setWinnerTeam($this);
        }

        return $this;
    }

    public function removeWonGame(Game $wonGame): static
    {
        if ($this->wonGames->removeElement($wonGame)) {
            // set the owning side to null (unless already changed)
            if ($wonGame->getWinnerTeam() === $this) {
                $wonGame->setWinnerTeam(null);
            }
        }

        return $this;
    }
}
