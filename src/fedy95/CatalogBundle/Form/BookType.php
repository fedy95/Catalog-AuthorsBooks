<?php

namespace fedy95\CatalogBundle\Form;

//use fedy95\CatalogBundle\Form\BookType;
use fedy95\CatalogBundle\Entity\Author;
use fedy95\CatalogBundle\Entity\Book;
use fedy95\CatalogBundle\Form\AuthorType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;',
                ],
                'label' => 'Название'
            ])
            ->add('fileName', FileType::class, array('data_class' => null), [
                'attr' => [
                    'class' => 'file',
                    'style' => 'margin-bottom:15px;',
                    'data-allowed-file-extensions' => '["pdf"]'
                ],
                'label' => 'Произведение в формате PDF (не более 50 Мб)',
            ])
            ->add('pageNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;'
                ],
                'label' => 'Количество страниц',
                'required' => false
            ])
            ->add('yearPublication', IntegerType::class, [
                'attr' => [
                    'min' => '1400', 'max' => date('Y'),
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                ],
                'label' => 'Год публикации',
                'required' => false
            ])
            ->add('ISBN', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;'
                ],
                'label' => 'Международный номер книги',
                'required' => false
            ])
            ->add('imageName', FileType::class, [
                'attr' => [
                    'class' => 'file',
                    'style' => 'margin-bottom:15px;',
                    'data-allowed-file-extensions' => '["jpg", "png"]'
                ],
                'label' => 'Изображение в формате JPG или PNG (не более 3 Мб)',
                'data_class' => null,
                'required' => false
            ])
            ->add('authors', EntityType::class, [
//                'attr' => [
//                    'multiple class' => 'form-control',
//                    'style' => 'margin-bottom:25px'],
                'label' => 'Авторы произведения',
                'class' => Author::class,
                'expanded' => true,
                'multiple' => true,
//                'placeholder' => 'Select',
//                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.surname');
                }
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
            'data_class' => 'fedy95\CatalogBundle\Entity\Book'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fedy95_catalogbundle_book';
    }


}
