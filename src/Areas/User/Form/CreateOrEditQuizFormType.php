<?php

namespace App\Areas\User\Form;

use App\Entity\User;
use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateOrEditQuizFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, [
                'label' => 'Nom du quiz',
            ])
            ->add('isVisible', CheckboxType::class, [
                'mapped' => true,
                'required' => false,
                'label' => 'Visible'

            ])
            ->add('resultDisplay', ChoiceType::class, ['label' => 'Type d\' affichage des résultats',
            'choices' => [
                'Seulement le score' => Quiz::ONLYSCORE,
                'Mentionne si l\'utilisateur a correctement répondu ou non' => Quiz::HIDEANSWER,
                'Affiche toutes les réponses' => Quiz::SHOWANSWER
            ],
            'placeholder' => 'Veuillez sélectionner un type d\'affichage.',
          ])
            ->setMethod('GET')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
