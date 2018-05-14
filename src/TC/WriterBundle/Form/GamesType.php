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
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GamesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add('title', TextType::class)
        ->add('date', DateType::class)
        ->add('editor', TextType::class)
        ->add('type', TextType::class)
        ->add('plateform', TextType::class)
        ->add('description', TextAreaType::class, array(
    'attr' => array('maxlength' => 1000)))
        ->add('coverAlt', TextType::class)
        ->add('coverUrl',ElFinderType::class, array('instance'=>'form', 'enable'=>true))
        ->add('jaquetteAlt', TextType::class)
        ->add('jaquetteUrl',ElFinderType::class, array('instance'=>'form', 'enable'=>true))
        ->add('best', CheckboxType::class, array('required' => false))
        ->add('save', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TC\WriterBundle\Entity\Games'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'tc_writerbundle_games';
    }


}
