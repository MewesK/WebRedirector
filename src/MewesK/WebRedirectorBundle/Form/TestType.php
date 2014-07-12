<?php

namespace MewesK\WebRedirectorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hostname', 'hidden')
            ->add('path', 'hidden', array(
                'required' => false
            ))
            ->add('destination', 'hidden')
            ->add('useRegex', 'hidden', array(
                'required' => false
            ))
            ->add('usePlaceholders', 'hidden', array(
                'required' => false
            ))
            ->add('enabled', 'hidden', array(
                'required' => false
            ))
            ->add('url', 'text', array(
                'attr' => array('placeholder' => 'Input URL'),
                'horizontal_input_wrapper_class' => 'col-sm-12',
                'horizontal_label_offset_class' => '',
                'label_render' => false,
                'required' => true
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MewesK\WebRedirectorBundle\Entity\Test'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mewesk_webredirectorbundle_test';
    }
}
