<?php

namespace App\Form;

use App\Entity\UniteEntity;
use App\Entity\ModuleEntity;
use Symfony\Component\Form\AbstractType;
use App\Repository\UniteEntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomModule', TextType::class, ['attr'=> ['class' => 'form-control' ], 'label'=> false])
            ->add('unite', EntityType::class, [
                'class' => UniteEntity::class,
                'query_builder' => function (UniteEntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom_unite', 'ASC');
                },
                'choice_label' => 'nom_unite',
                'label' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider', 'attr' => ['class' => 'btn btn-lg btn-primary mt-3']]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModuleEntity::class,
        ]);
    }
}
