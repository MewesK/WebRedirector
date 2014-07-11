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
            ->add('url', 'text', array(
                'required' => true
            ))
            ->add('redirect',  'mewesk_webredirectorbundle_entity_hidden', array(
                'class' => 'MewesKWebRedirectorBundle:Redirect',
                'required' => true
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'MewesK\WebRedirectorBundle\Entity\Test'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mewesk_webredirectorbundle_test';
    }
}
