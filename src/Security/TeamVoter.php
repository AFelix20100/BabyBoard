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
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
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
        dd($team->getOwner($this->teamRepository, $user, $team));
        // this assumes that the Post object has a `getOwner()` method
        return $user === $team->getOwner($this->teamRepository, $user, $team);
    }


    private function canDelete(Player $user, Team $team): bool
    {
        if ($this->canEdit($user, $team)) {
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
        return $user === $team->getOwner($this->teamRepository, $user, $team);
    }
    
}