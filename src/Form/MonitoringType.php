<?php

namespace App\Form;

use App\Entity\ModuleEntity;
use App\Entity\MonitoringEntity;
use Symfony\Component\Form\AbstractType;
use App\Repository\ModuleEntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MonitoringType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('module', EntityType::class, [
            'class' => ModuleEntity::class,
            'query_builder' => function (ModuleEntityRepository $er) {
                return $er->createQueryBuilder('m')
                    ->orderBy('m.nom_module', 'ASC');
            },
            'choice_label' => 'nom_module',
            'label' => false,
            'attr' => ['class' => 'form-control']
        ])
        ->add('save', SubmitType::class, ['label' => 'Valider', 'attr' => ['class' => 'btn btn-lg btn-primary mt-3']]) 
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MonitoringEntity::class,
        ]);
    }
}
