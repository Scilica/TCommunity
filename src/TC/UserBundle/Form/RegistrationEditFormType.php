<?php

namespace TC\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class RegistrationEditFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('plainPassword')
        ->add('groups', EntityType::class, array(
            'class' => 'TCUserBundle:Groups',
            'choice_label' => 'name',
            'multiple' => true,
            'required' => false,
            'label' => 'Role'))
        ->add('enabled', ChoiceType::class, array(
            'label' => 'Statut du compte',
    'choices'  => array(
        'Actif' => true,
        'Innactif' => false,
    )))
        ->add('Editer', SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return RegistrationFormType::class;
    }


}
