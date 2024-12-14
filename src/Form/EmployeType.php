<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // partie construction du formulaire avec les bonnes indications de données
        $builder
            ->add('nom', TextType::class, [
                'attr' => [
                    'class' => 'form-control' // équivaut à attribuer la valeur 'form-control' à l'attribut 'class' dans la balise input -> <input type="text" id="name" name="name" class="form-control" />
                ]
            ])

            ->add('prenom', TextType::class, [
                'attr' => [
                    'class' => 'form-control' // équivaut à attribuer la valeur 'form-control' à l'attribut 'class' dans la balise input -> <input type="text" id="name" name="name" class="form-control" />
                ]
            ])

            ->add('dateNaissance', null, [
                'attr' => [
                    'class' => 'form-control' // équivaut à attribuer la valeur 'form-control' à l'attribut 'class' dans la balise input -> <input type="text" id="name" name="name" class="form-control" />
                ],
                'widget' => 'single_text',
            ], DateType::class) // attention à l'ordre des arguments !

            ->add('dateEmbauche', null, [
                'attr' => [
                    'class' => 'form-control' // équivaut à attribuer la valeur 'form-control' à l'attribut 'class' dans la balise input -> <input type="text" id="name" name="name" class="form-control" />
                ],
                'widget' => 'single_text',
            ], DateType::class) // attention à l'ordre des arguments !

            ->add('ville', TextType::class, [
                'required' => false, // on rend ce champ non obligatoire dans la saisie de notre formulaire : seul moyen possible
                'attr' => [
                    'class' => 'form-control' // équivaut à attribuer la valeur 'form-control' à l'attribut 'class' dans la balise input -> <input type="text" id="name" name="name" class="form-control" />
                ]
            ])

            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'attr' => [
                    'class' => 'form-control' // équivaut à attribuer la valeur 'form-control' à l'attribut 'class' dans la balise input -> <input type="text" id="name" name="name" class="form-control" />
                ],

                // 'choice_label' => 'id',
                'choice_label' => 'raisonSociale', // si je n'ai pas de choice_label, il affichera ce qu'il récupère dans __toString() de Entreprise. choice_label ne peut être qu'un champ.
            ]) // faire attention à ce type ! 


            // je rajoute à la main un bouton pour valider le formulaire
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary' // équivaut à attribuer la valeur 'form-control' à l'attribut 'class' dans la balise input -> <input type="text" id="name" name="name" class="form-control" />
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
