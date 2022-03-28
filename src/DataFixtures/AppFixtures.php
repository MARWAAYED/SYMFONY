<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');

        //creation de role admin 
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        //creation un utilisateur admin
        $adminUser = new User();
        $adminUser->setFirstName('marwa')
                  ->setLastName('ayed')
                  ->setEmail('marwaayed74@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 
                                                           'password'))
                  ->setPicture('https://static.zerochan.net/Jinx.%28League.of.Legends%29.full.3522636.jpg')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>',
                        $faker->paragraphs(3)) . '</P>')
                  ->addUserRole($adminRole);
        $manager->persist($adminUser);


        //gestion des utilisateur
        $users = [];
        $genres = ['male', 'female'];


        for($i = 1; $i <= 10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99). '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');


            $user ->setFirstName($faker->firstname($genre))
            ->setLastName($faker->lastname)
            ->setEmail($faker->email)
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>',$faker->paragraphs(3)) . '</P>')
            ->setHash($hash)
            ->setPicture($picture);


            $manager->persist($user);
            $users[] = $user;
            
        }
        
        
        
        //gestion des annances
        for($i=1;$i<=30;$i++){

            $Ad = new Ad();
                    
            $title = $faker->sentence();
           
            $coverImage = $faker->imageUrl(1000,350);
            $introduction =$faker->paragraph(2);
            $context = '<p>' . join('</p><p>',$faker->paragraphs(5)) . '</P>';
            $user = $users[mt_rand(0, count($users) - 1)];



            $Ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContext($context)
                ->setPreice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);

            for($j=1;$j<=mt_rand(2,5);$j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($Ad);
                $manager->persist($image);
            }
          
            $manager->persist($Ad);
        }

        $manager->flush();
    }
}
