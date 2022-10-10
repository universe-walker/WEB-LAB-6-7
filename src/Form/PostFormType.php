<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'label_attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Заголовок новости:',
            ])
            ->add('Annotation', TextType::class, [
                'label_attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Аннотация новости:',
            ])
            ->add('Text', TextareaType::class, [
                'label_attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Текст новости:',
                'attr' => [
                    'cols' => '5',
                    'rows' => '5',
                ]
            ])
            ->add('submit', SubmitType::class, ['label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
