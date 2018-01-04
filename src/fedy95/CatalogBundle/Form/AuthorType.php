<?php

namespace fedy95\CatalogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;',
                ],
                'label' => 'Фамилия'
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;',
                ],
                'label' => 'Имя'
            ])
            ->add('patronymic', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:25px;',
                ],
                'label' => 'Отчество',
                'required' => false
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning',
                    'style' => 'margin-bottom:5px'],
                'label' => 'Внести изменения'
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'fedy95\CatalogBundle\Entity\Author'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fedy95_catalogbundle_author';
    }


}
