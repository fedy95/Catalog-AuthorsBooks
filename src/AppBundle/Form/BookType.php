<?php

namespace AppBundle\Form;

use AppBundle\Entity\Book;
use AppBundle\Entity\Author;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Название',
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px')))
            ->add('fileName', FileType::class, array(
                'label' => 'Произведение в формате PDF (не более 50 Мб)',
                'attr' => array(
                'style' => 'margin-bottom:15px'
                )))
            ->add('pageNumber', TextType::class, array(
                'label' => 'Количество страниц',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px')))
//            ->add('yearPublication', BirthdayType::class, array(
//                'label' => 'Год публикации',
//                'required' => false,
//                'placeholder' => array(
//                    'year' => 'Год', 'month' => 'Месяц', 'day' => 'День'),
//                'attr' => array(
//                    'style' => 'margin-bottom:15px'
//                )))
            ->add('yearPublication', IntegerType::class, array(
                'label' => 'Год публикации',
                'required' => false,
                'attr' => array(
                    'min' => '1400', 'max' => date('Y'),
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px')
                ))
            ->add('ISBN', TextType::class, array(
                'label' => 'Международный номер книги',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px')))
            ->add('imageName', FileType::class, array(
                'label' => 'Изображение в формате JPG или PNG (не более 5 Мб)',
                'required' => false,
                'attr' => array(
                    'style' => 'margin-bottom:15px')))
            ->add('authors', EntityType::class, array(
                'label' => 'Автор произведения',
                'attr' => array(
                    'style' => 'margin-bottom:15px'),
                'class' => Author::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')->orderBy('c.surname');
                }
                ))
            ->add('Save', SubmitType::class, array(
                'label' => 'Добавить произведение',
                'attr' => array(
                    'class' => 'btn btn-success',
                    'style' => 'margin-bottom:15px')));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Book::class,
        ));
    }
}