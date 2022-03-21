<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{

    /**
     * configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getCinfiguration($label, $placeholder, $options = []){
        return array_merge([
            'label' => $label,
            'attr' => ['placeholder' => $placeholder]
        ], $options);

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
                $this->getCinfiguration("Titre","Tappez un titre pour votre annance"))
            ->add('slug', TextType::class,
                $this->getCinfiguration("Adresse web","Tappez l'adresse web (automatique)", [
                    'required' => false
                ]))
                ->add('coverImage', UrlType::class,
                $this->getCinfiguration("URL de l'image","Donnez l'url de l'image de votre annance"))
            ->add('introduction', TextType::class,
            $this->getCinfiguration("Introduction","Donnez une description globale"))
            ->add('context', TextareaType::class,
                $this->getCinfiguration("decription detaillee","Donnez une description detaillee pour votre annance"))
            ->add('rooms', IntegerType::class,
            $this->getCinfiguration("Nombre de chambre","le nombre des chmbres disponible"))
            ->add('preice', MoneyType::class,
                $this->getCinfiguration("Prix par nuit","indiquez le prix pour une nuit"))
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
