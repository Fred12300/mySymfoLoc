<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Tool;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'nom' // Déplacement du placeholder dans 'attr'
                ],
                'label' => 'Nom',
                'label_attr' => ['class' => 'mt-3']
            ])
            ->add('mainPic', FileType::class, ['attr' => ['class'=> 'form-control mb-3'], 'label'=>'Image'])
            // ->add('pic2', FileType::class, ['required' => false])
            // ->add('pic3', FileType::class, ['required' => false])
            ->add('description', TextType::class, ['attr' => ['class'=> 'form-control mb-3'], 'label'=>'Description'])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'attr' => ['class'=> 'form-control']
            ])
            ->add('Enregistrer', SubmitType::class, ['attr' => ['class'=> 'btn btn-primary my-4']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tool::class,
        ]);
    }
}
