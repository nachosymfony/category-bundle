<?php

namespace nacholibre\CategoryBundle\Model;

use nacholibre\CategoryBundle\Form\CategoryType;

class CategoryManager {
    public function __construct($container, $em) {
        $this->container = $container;
        $this->em = $em;
    }

    private function generateCatLevel($level, $category, $children = []) {
        return [
            'children' => $children,
            'category' => $category,
            'level' => $level,
        ];
    }

    public function generateFormFlatArrayCategories() {
        $hierarchy = $this->getCategoryHierarchy();

        $flatCategories = [];

        foreach($hierarchy as $name => $data) {
            $obj = $data['category'];
            $obj->setLevel($data['level']);
            $flatCategories[] = $obj;

            foreach($data['children'] as $data) {
                $obj = $data['category'];
                $obj->setLevel($data['level']);
                $flatCategories[] = $obj;

                foreach($data['children'] as $data) {
                    $obj = $data['category'];
                    $obj->setLevel($data['level']);
                    $flatCategories[] = $obj;
                }
            }
        }

        return $flatCategories;
    }

    public function getTypeConfig($type) {
        $config = $this->container->getParameter('nacholibre_category');

        return $config['types'][$type];
    }

    public function getRepo($type) {
        $typeData = $this->getTypeConfig($type);

        $repo = $this->em->getRepository($typeData['entity_class']);

        return $repo;
    }

    public function getCategoryHierarchy($type) {
        $hierarchy = [];

        $repo = $this->getRepo($type);
        $cats = $repo->findBy([], [
            'position' => 'ASC'
        ]);

        //root level
        foreach($cats as $cat) {
            if ($cat->getParent() == null) {
                $children = [];

                foreach($cat->getChildren() as $child) {
                    $secondLevelChildren = [];

                    foreach($child->getChildren() as $sChild) {
                        $secondLevelChildren[$sChild->getName()] = $this->generateCatLevel('3', $sChild, []);
                    }

                    $children[$child->getName()] = $this->generateCatLevel('2', $child, $secondLevelChildren);
                }

                $hierarchy[$cat->getName()] = $this->generateCatLevel('1', $cat, $children);
            }
        }

        return $hierarchy;
    }

    public function createCategory($type) {
        $config = $this->getTypeConfig($type);

        return new $config['entity_class'];
    }

    public function getForm($type) {
        $config = $this->getTypeConfig($type);

        if ($config['form_class']) {
            return $config['form_class'];
        } else {
            return CategoryType::class;
        }
    }
}
