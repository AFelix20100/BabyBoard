<?php

namespace App\Security;

use App\Entity\Team;
use App\Entity\Player;
use App\Repository\TeamRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Bundle\SecurityBundle\Security;

class TeamVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    private TeamRepository $teamRepository;

    public function __construct(private Security $security, TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }
        
        // only vote on `Post` objects
        if (!$subject instanceof Team) {
            return false;
        }
        
        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if (!$user instanceof Player) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Team $team */
        $team = $subject;

        return match($attribute) {
            self::EDIT => $this->canEdit($user, $team),
            self::DELETE => $this->canDelete($user, $team),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canEdit(Player $user, Team $team): bool
    {
        // Vérifie si l'utilisateur actuel est l'hôte de l'équipe
        $isHost = $team->getTeamCompositions()->exists(function ($key, $teamComposition) use ($user) {
            return $teamComposition->getPlayer() === $user && $teamComposition->isHost() === true;
        });
        // Retourne true si l'utilisateur est l'hôte de l'équipe
        return $isHost;
    }


    private function canDelete(Player $user, Team $team): bool
    {
        // Vérifie si l'utilisateur peut éditer l'équipe
        if ($this->canEdit($user, $team)) {
            return true;
        }

        // Sinon, vérifie si l'utilisateur est l'hôte de l'équipe
        $isHost = $team->getTeamCompositions()->exists(function ($key, $teamComposition) use ($user) {
            return $teamComposition->getPlayer() === $user && $teamComposition->getIsHost() === true;
        });

        return $isHost;
    }

    
}