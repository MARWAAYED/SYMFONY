<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('FR-fr');
     
        for($i=1;$i<=30;$i++){

            $Ad = new Ad();
                    
            $title = $faker->sentence();
           
            $coverImage = $faker->imageUrl(1000,350);
            $introduction =$faker->paragraph(2);
            $context = '<p>' . join('</p><p>',$faker->paragraphs(5)) . '</P>';

            $Ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContext($context)
                ->setPreice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5));

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
