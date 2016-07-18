<?php

namespace nacholibre\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BaseCategoryType extends AbstractType
{
    private $container;

    function __construct($container) {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$parameters = $this->container->getParameter('nacholibre_pages');
        //$editor = $parameters['editor'];
        //$categoryManager = $this->container->get('nacholibre.category.manager');

        //$formCategories = $categoryManager->generateFormFlatArrayCategories();

        $builder->add('name', TextType::class, [
            'label' => 'category.name',
            'required' => true,
        ]);

        //$builder->add('parent', EntityType::class, [
        //    'class' => 'nacholibre\CategoryBundle\Entity\Category',
        //    'choices' => $formCategories,
        //    'choices_as_values' => true,
        //    'choice_label' => function($category) {
        //        $prefix = '';

        //        if ($category->getLevel() == 2) {
        //            $prefix = '-';
        //        }

        //        if ($category->getLevel() == 3) {
        //            $prefix = '--';
        //        }

        //        return sprintf('%s%s', $prefix, $category->getName());
        //    },
        //    'choice_attr' => function($val, $key, $index) {
        //        $disabled = false;

        //        if ($val->getLevel() >= 3) {
        //            $disabled = true;
        //        }

        //        return $disabled ? ['disabled' => 'disabled'] : [];
        //    },
        //    'label' => 'sub.category',
        //    'required' => false,
        //    'placeholder' => 'sub.this.is.main.cat',
        //]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([]);
    }
}
