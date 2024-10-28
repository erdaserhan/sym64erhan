<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use Faker\Factory as Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,

    )
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');
        $slugify = new Slugify();

        // admin
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFullname('admin Admin');
        $user->setEmail($faker->email());
        $user->setUniqid(uniqid("admin_", true));
        $user->setActivate(true);
        $users[] = $user;
        $manager->persist($user);


        // redac
        for($i = 1; $i <= 5; $i++){
            $user = new User();
            $user->setUsername('redac'.$i);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'redac'.$i));
            $user->setRoles(['ROLE_REDAC']);
            $user->setFullname($faker->name());
            $user->setEmail($faker->email());
            $user->setUniqid(uniqid("redac_", true));
            $user->setActivate(true);
            $users[] = $user;
            $manager->persist($user);
        }

        // User
        for($i=1; $i<=24; $i++){
            $user = new User();
            $user->setUsername('user'.$i);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'user'.$i));
            $user->setRoles(['ROLE_USER']);
            $user->setFullname($faker->name());
            $user->setEmail($faker->email());
            $user->setUniqid(uniqid("user_", true));
            $active = mt_rand(1,4)<4;
            $user->setActivate($active);
            $manager->persist($user);
        }

        // Article
        for($i=1; $i<=160; $i++){
            $article = new Article();
            $article->setTitle($faker->sentence(5));
            $article->setText($faker->paragraph(5, true));
            $article->setTitleSlug($slugify->slugify($article->getTitle()));
            $article->setArticleDateCreate($faker->dateTimeBetween('-6 months'));
            $hasard = mt_rand(1,4)<4;
            $article->setPublished($hasard);
            if($hasard){
                $article->setArticleDatePosted($faker->dateTimeBetween($article->getArticleDateCreate()));
            }
            $articles[] = $article;
            $article->setUser($faker->randomElement($users));
            $manager->persist($article);
        }




        $manager->flush();
    }
}
