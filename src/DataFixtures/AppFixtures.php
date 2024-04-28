<?php

namespace App\DataFixtures;

use App\Entity\Player;
use App\Entity\Team;
use App\Entity\TeamComposition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTime;
use DateTimeZone;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $playersData = [
            [
                'email' => 'john@example.com',
                'lastName' => 'Doe',
                'firstName' => 'John',
                'pseudo' => 'johndoe',
            ],
            [
                'email' => 'alice@example.com',
                'lastName' => 'Smith',
                'firstName' => 'Alice',
                'pseudo' => 'alicesmith',
            ],
            [
                'email' => 'bob@example.com',
                'lastName' => 'Johnson',
                'firstName' => 'Bob',
                'pseudo' => 'bobjohnson',
            ],
            [
                'email' => 'dupond@example.com',
                'lastName' => 'Dupond',
                'firstName' => 'Jean',
                'pseudo' => 'jeanDupond',
            ],
            // Ajoutez plus de joueurs selon vos besoins
        ];

        $teamsData = [
            ['name' => 'Team A'],
            ['name' => 'Team B'],
        ];

        // Générer les joueurs
        foreach ($playersData as $playerData){
            $player = new Player();
            $player->setEmail($playerData["email"]);
            $password = $this->hasher->hashPassword($player, 'azerty1234');
            $player->setPassword($password);
            $player->setPseudo($playerData["pseudo"]);
            $player->setFirstName($playerData["firstName"]); // Utilisation de "firstName" au lieu de "firstname"
            $player->setLastName($playerData["lastName"]); // Utilisation de "lastName" au lieu de "lastname"

            $manager->persist($player);
        }

        // Générer les équipes
        foreach ($teamsData as $oneTeam){
            $team = new Team();
            $team->setName($oneTeam["name"]);

            $manager->persist($team);
        }

        $manager->flush();

        // Associer les joueurs aux équipes en tant qu'hôte ou invité
        $players = $manager->getRepository(Player::class)->findAll();
        $teams = $manager->getRepository(Team::class)->findAll();

        $playerCount = 0;
        foreach ($players as $player) {
            $teamIndex = $playerCount % count($teams);
            $team = $teams[$teamIndex];

            $teamComposition = new TeamComposition();
            $teamComposition->setPlayer($player);
            $teamComposition->setTeam($team);

            // Définir aléatoirement si le joueur est l'hôte ou l'invité
            $isHost = rand(0, 1);
            $teamComposition->setHost($isHost);
            $teamComposition->setGuest(!$isHost);

            $manager->persist($teamComposition);

            $playerCount++;
        }

        $manager->flush();
    }




}
