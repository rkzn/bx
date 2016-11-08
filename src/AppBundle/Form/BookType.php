<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ISBN', 'text');
        $builder->add('title', 'text');
        $builder->add('author', 'text');
        $builder->add('year', 'text');
        $builder->add('publisher', 'text');
        $builder->add('ImageUrlS', 'text');
        $builder->add('ImageUrlM', 'text');
        $builder->add('ImageUrlL', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => 'AppBundle\Entity\Book',
            'intention'          => 'book',
            'translation_domain' => 'AppBundle'
        ));
    }

    public function getName()
    {
        return 'book';
    }
}
