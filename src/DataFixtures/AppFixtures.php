<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Community;
use App\Entity\Comment;

use App\Entity\Friendship;
use App\Entity\IsInCommunity;
use App\Entity\Participation;
use App\Entity\Settings;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $fakeImages = array(
            "OP7_01.jpg",
            "OP7_02.jpg",
            "OP7_03.jpg",
            "OP7_04.jpg",
            "OP7_05.jpg",
            "OP7T_1.jpg",
            "OP7T_2.jpg",
            "OP7T_3.jpg",
            "OP8_1.jpg",
            "OP8_2.jpg",
            "OP8_3.jpg",
            "OP8_4.jpg",
            "OPTV_1.jpg",
            "OPTV_2.jpg",
            "OPTV_3.jpg",
        );

        $fakeLocale = array(
            "fr_FR",
            "en_US"
        );

        $settings = array();
        for ($i = 0; $i < 20; $i++) {
            $settings[$i] = new Settings();
            $settings[$i]->setLanguage($fakeLocale[rand(0, 1)]);
            $settings[$i]->setDarkMode($faker->boolean);

            $manager->persist($settings[$i]);
        }

        $users = array();
        for ($i = 0; $i < 20; $i++) {
            if ($i == 0) {
                $users[$i] = new User();
                $users[$i]->setEmail("admin@admin");
                $users[$i]->setRoles(array("ROLE_ADMIN"));
                $users[$i]->setCity("AdminLand");
                $users[$i]->setPseudo("Adminer");
                $users[$i]->setPassword('$argon2id$v=19$m=65536,t=4,p=1$ZXguRTJETlZBSFFJUi9nNQ$M7oH5G2QzUY3UvcnV/K692ysCcH9cd5zjtXaHcA8JUg');
                $users[$i]->setImage($fakeImages[rand(0, count($fakeImages) - 1)]);
                $users[$i]->setSettings($settings[$i]);
            } else {
                $users[$i] = new User();
                $users[$i]->setEmail($faker->email);
                $users[$i]->setCity($faker->city);
                $users[$i]->setPseudo($faker->name);
                $users[$i]->setPassword($faker->password);
                $users[$i]->setImage($fakeImages[rand(0, count($fakeImages) - 1)]);
                $users[$i]->setSettings($settings[$i]);
            }

            $manager->persist($users[$i]);
        }

        $events = array();
        for ($i = 0; $i < 30; $i++) {
            $events[$i] = new Event();
            $events[$i]->setUser($users[rand(0, 19)]);
            $events[$i]->setDateCreation($faker->dateTime);
            $events[$i]->setDateModification($faker->dateTime);
            $events[$i]->setName($faker->catchPhrase);
            $events[$i]->setDescription($faker->sentence);
            $events[$i]->setLocation($faker->city);
            $events[$i]->setdate($faker->dateTimeBetween('now', '+1 years'));
            $events[$i]->setEndDate($faker->dateTimeBetween($events[$i]->getdate(), $events[$i]->getdate()->format('Y-m-d H:i:s').'+5 hours'));
            $events[$i]->setNbParticipant($faker->randomNumber(1));
            $events[$i]->setImage($fakeImages[rand(0, count($fakeImages) - 1)]);
            for ($j = 0; $j < 10; $j++) {
                $tag = new Tag();
                $tag->setName($faker->word);
                $events[$i]->addTag($tag);
            } 

            $manager->persist($events[$i]);
        }

        $communitys = array();
        for ($i = 0; $i < 15; $i++) {
            $communitys[$i] = new Community();
            $communitys[$i]->setDateCreation($faker->dateTime);
            $communitys[$i]->setDateModification($faker->dateTime);
            $communitys[$i]->setName($faker->company);

            $manager->persist($communitys[$i]);
        }

        $comments = array();
        for ($i = 0; $i < 500; $i++) {
            $comments[$i] = new Comment();
            $comments[$i]->setDateCreation($faker->dateTime);
            $comments[$i]->setDateModification($faker->dateTime);
            $comments[$i]->setUser($users[rand(0, 19)]);
            $comments[$i]->setDate($faker->dateTimeBetween('now', '+1 years'));
            $comments[$i]->setText($faker->sentence);
            $comments[$i]->setEvent($events[rand(0, 29)]);

            $manager->persist($comments[$i]);
        }

        $friendships = array();
        for ($i = 0; $i < count($users); $i++) {
            for ($j = $i + 1; $j < count($users); $j++) {
                $friendships[$i + $j] = new Friendship();
                $friendships[$i + $j]->setDate($faker->dateTime);
                $friendships[$i + $j]->setFirst_user($users[$i]);
                $friendships[$i + $j]->setSecond_user($users[$j]);
                $friendships[$i + $j]->setValidate($faker->boolean);

                $manager->persist($friendships[$i + $j]);
            }
        }

        $isInCommunitys = array();
        for ($i = 0; $i < count($users); $i++) {
            for ($j = $i + 1; $j < count($communitys); $j++) {
                $isInCommunitys[$i + $j] = new IsInCommunity();
                $isInCommunitys[$i + $j]->setDate($faker->dateTime);
                $isInCommunitys[$i + $j]->setUser($users[$i]);
                $isInCommunitys[$i + $j]->setCommunity($communitys[$j]);

                $manager->persist($isInCommunitys[$i + $j]);
            }
        }

        $participations = array();
        for ($i = 0; $i < count($users); $i++) {
            for ($j = 0; $j < count($events); $j++) {
                if (count($events[$j]->getParticipations()) + 1 <= $events[$j]->getNbParticipant()) {
                    $participations[$i + $j] = new Participation();
                    $participations[$i + $j]->setDate($faker->dateTime);
                    $participations[$i + $j]->setUser($users[$i]);
                    $participations[$i + $j]->setEvent($events[$j]);
                    $events[$j]->addParticipation($participations[$i + $j]);

                    $manager->persist($participations[$i + $j]);
                }
            }
        }

        $manager->flush();
    }
}
