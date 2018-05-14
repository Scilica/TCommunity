<?php

namespace TC\WriterBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class CategoryEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->remove('game');
    }/**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CategoryType::class;
    }


}
