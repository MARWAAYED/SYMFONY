<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
                $this->getConfiguration("Titre","Tappez un titre pour votre annance"))
            ->add('slug', TextType::class,
                $this->getConfiguration("Adresse web","Tappez l'adresse web (automatique)", [
                    'required' => false
                ]))
                ->add('coverImage', UrlType::class,
                $this->getConfiguration("URL de l'image","Donnez l'url de l'image de votre annance"))
            ->add('introduction', TextType::class,
            $this->getConfiguration("Introduction","Donnez une description globale"))
            ->add('context', TextareaType::class,
                $this->getConfiguration("decription detaillee","Donnez une description detaillee pour votre annance"))
            ->add('rooms', IntegerType::class,
            $this->getConfiguration("Nombre de chambre","le nombre des chmbres disponible"))
            ->add('preice', MoneyType::class,
                $this->getConfiguration("Prix par nuit","indiquez le prix pour une nuit"))
            ->add(
                'images', CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true
                ]

            )
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
