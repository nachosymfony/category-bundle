<?php

namespace nacholibre\CategoryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\CallbackTransformer;

class CategorySelectorType extends AbstractType {
    private $container;
    private $em;

    function __construct($em, $container) {
        $this->em = $em;
        $this->container = $container;
    }

    public function finishView(FormView $view, FormInterface $form, array $options) {
        parent::finishView($view, $form, $options);

        $em = $this->em;

        $type = $options['mapping'];

        $catConfig = $this->container->getParameter('nacholibre_category');
        $config = $catConfig['types'][$type];

        $categoryManager = $this->container->get('nacholibre.category.manager');

        $data = $categoryManager->getCategoryHierarchy($type);

        $view->vars['categories'] = $data;

        //$entityClass = $config['entity_class'];

        //$repo = $em->getRepository($entityClass);

        //$categories = $repo->findBy();

        //var_dump($config);
        //$em = $this->getDoctrine()->getManager();
        //$repo = $em->getRepository('AppBundle:value');

        //$helper = $this->container->get('nacholibre.rich_uploader.helper');
        //$em = $this->em;

        //$name = $form->getName();
        //$method = 'get' . ucwords($name);

        //$entity = $form->getParent()->getData();
        //$type = get_class($entity->$method());

        //$repo = $em->getRepository($options['entity_class']);

        //$ids = explode(',', $view->vars['data']);
        //$images = $repo->findById($ids);

        //$config = $helper->getEntityClassConfiguration($options['entity_class']);
        //$configName = $helper->getEntityConfigName($options['entity_class']);

        //$view->vars['images_data'] = $images;
        //$view->vars['nacholibre_multiple'] = $options['multiple'];
        //$view->vars['nacholibre_size'] = $options['size'];
        //$view->vars['nacholibre_entity_class'] = $options['entity_class'];
        //$view->vars['nacholibre_mime_types'] = $config['mime_types'];
        //$view->vars['nacholibre_max_size'] = $config['max_size'];
        //$view->vars['nacholibre_config_name'] = $configName;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $em = $this->em;
        $type = $options['mapping'];

        $catConfig = $this->container->getParameter('nacholibre_category');
        $config = $catConfig['types'][$type];

        $repo = $em->getRepository($config['entity_class']);

        $builder->addModelTransformer(new CallbackTransformer(
            function ($categoriesAsText) use ($options) {
                if (!$categoriesAsText) {
                    return null;
                }

                //if ($options['multiple'] == false) {
                //    $file = $filesAsText;
                //    return $file->getID();
                //}

                $newCats = [];
                foreach($categoriesAsText as $cat) {
                    $newCats[] = $cat->getID();
                }

                return implode(',', $newCats);
            },
            function ($textAsCategories) use ($repo, $options) {
                $ids = explode(',', $textAsCategories);

                $categories = $repo->findById($ids);

                return $categories;

                //if ($options['multiple'] == false) {
                //    if (count($files) > 0) {
                //        return $files[0];
                //    } else {
                //        return null;
                //    }
                //}

                //return $files;
            }
        ));
        //$parameters = $this->container->getParameter('nacholibre_pages');
        //$editor = $parameters['editor'];
        //$categoryManager = $this->container->get('nacholibre.category.manager');

        //$formCategories = $categoryManager->generateFormFlatArrayCategories();

        //$builder->add('name', TextType::class, [
        //    'label' => 'category.name',
        //    'required' => true,
        //]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'mapping' => null,
        ]);
    }

    public function getParent() {
        return HiddenType::class;
    }

    public function getBlockPrefix() {
        return 'nacholibre_category_type';
    }
}
