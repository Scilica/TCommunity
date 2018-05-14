<?php

namespace TC\WriterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $jeux = $options['Game'];
        $builder
        ->add('title', TextType::class)
        ->add('game', EntityType::class, array(
            'class' => 'TCWriterBundle:Games',
             'query_builder' => function (\Doctrine\ORM\EntityRepository $er) use($jeux) {
        return $er->FindThisGamese($jeux);},
            'choice_label' => 'title',
            'multiple' => false,))
        ->add('miniatureAlt', TextType::class)
        ->add('miniatureUrl',ElFinderType::class, array('instance'=>'form', 'enable'=>true))
        ->add('save', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TC\WriterBundle\Entity\Category'
        ));
        $resolver->setRequired(['Game']);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tc_writerbundle_category';
    }


}
