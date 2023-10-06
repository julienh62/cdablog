<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Tag;
use App\Entity\Post;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

  public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $faker->addProvider(new PicsumPhotosProvider($faker));

        // Créez un utilisateur administrateur
        $admin = new User();
        $admin->setPassword(
            $this->hasher->hashPassword(
                $admin, "azerty"
            )
        );
        $admin->setName("Julien Hennebo");
        $admin->setEmail("jhennebo@gmail.com");
        $admin->setAvatar("440562-64f9f96f8bc88.jpg");
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Créez 3 tags au total
        $tagNames = $faker->unique()->words(3); // Créez 3 noms de tags uniques
        $tags = [];
        foreach ($tagNames as $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // Générez les 5 utilisateurs supplémentaires
        for ($i = 0; $i < 5; $i++) {
            $avatarUrl = $faker->imageUrl(600, 400, true);

            $user = new User();
            $user->setPassword(
                $this->hasher->hashPassword(
                    $user, "test_pass"
                )
            );
            $user->setName($faker->name);
            $user->setEmail($faker->email);
            $user->setAvatar($avatarUrl);
            $manager->persist($user);

            // Créez un certain nombre de posts liés à chaque utilisateur
            for ($j = 0; $j < 3; $j++) {
                $post = new Post();
                $post->setTitle($faker->sentence(4));
                $post->setContent($faker->paragraph(3));
                $post->setCreatedAt($faker->dateTimeBetween('-1 year', 'now'));
                $post->setUpdateAt($faker->dateTimeBetween('-1 year', 'now'));
                $post->setImage($faker->imageUrl(800, 600, true));
                $post->setUser($user);
                $post->setIsArchived($faker->boolean(30));

                // Associez chaque post à un ou deux tags de manière aléatoire
                $randomTags = $faker->randomElements($tags, $faker->numberBetween(1, 2));
                foreach ($randomTags as $tag) {
                    $post->addPostTag($tag);
                }

                $manager->persist($post);
            }
        }

        $manager->flush();
    }
}
