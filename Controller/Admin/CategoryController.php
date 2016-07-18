<?php

namespace nacholibre\CategoryBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use nacholibre\CategoryBundle\Form\CategoryType;

use nacholibre\CategoryBundle\Entity\Category;

/**
* @Route("/{type}")
*/
class CategoryController extends Controller {
    /**
     * @Route("/", name="nacholibre.category.admin.index")
     */
    public function indexAction(Request $request, $type) {
        $categoryManager = $this->get('nacholibre.category.manager');

        $hierarchy = $categoryManager->getCategoryHierarchy($type);

        return $this->render('nacholibreCategoryBundle:Admin:index.html.twig', [
            'hierarchy' => $hierarchy,
            'type' => $type,
            //'allowAdd' => $allowAdd,
        ]);
    }

    private function getCategoryRepo($type) {
        $categoryManager = $this->get('nacholibre.category.manager');

        return $categoryManager->getRepo($type);
    }

    /**
     * @Route("/{id}/change_position/{position}", name="nacholibre.category.admin.change_position", options={"expose"=true})
     */
    public function changeCategoryPositionAction($id, $position, $type) {
        $repo = $this->getCategoryRepo($type);
        $em = $this->getDoctrine()->getManager();

        $category = $repo->find(['id' => $id]);
        $category->setPosition($position);

        $em->persist($category);
        $em->flush();

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/{id}/change_parent/{newParent}/{position}", name="nacholibre.category.admin.change_parent", options={"expose"=true})
     */
    public function changeCategoryParentAction($id, $newParent, $position, $type) {
        $em = $this->getDoctrine()->getManager();

        $repo = $this->getCategoryRepo($type);
        $category = $repo->find(['id' => $id]);

        if ($newParent != '0') {
            $newParent = $repo->findOneBy(['id' => $newParent]);
            $category->setParent($newParent);
        } else {
            $category->setParent(null);
        }

        $category->setPosition($position);

        $em->persist($category);
        $em->flush();

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/new_ajax", name="nacholibre.category.admin.new_ajax", options={"expose"=true})
     */
    public function createCategoryAjaxAction(Request $request, $type) {
        $em = $this->getDoctrine()->getManager();
        $repo = $this->getCategoryRepo($type);

        $translated = $this->get('translator')->trans('default_category_name');
        $name = $translated . ' ' . rand(1, 999);

        $category = $this->get('nacholibre.category.manager')->createCategory($type);
        $category->setName($name);

        $em->persist($category);
        $em->flush();

        //make last
        $category->setPosition(9999);

        return new JsonResponse(['status' => 'ok', 'category_id' => $category->getID(), 'name' => $name]);
    }

    /**
     * @Route("/new", name="nacholibre.category.admin.new", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $type) {
        $category = $this->get('nacholibre.category.manager')->createCategory($type);
        //$pagesManager = $this->get('nacholibre.pages.manager');
        //$page = $pagesManager->createPage();

        $categoryManager = $this->get('nacholibre.category.manager');
        $typeConfig = $categoryManager->getTypeConfig($type);
        $form = $this->createForm($categoryManager->getForm($type), $category, [
            'data_class' => $typeConfig['entity_class']
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $page = $form->getData();

            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('nacholibre.category.admin.index', ['type' => $type]);
        }

        return $this->render('nacholibreAdminBundle:misc:_add_edit.html.twig', [
            'form' => $form->createView(),
            'headerTitle' => 'Add Category',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="nacholibre.category.admin.edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id, $type) {
        $repo = $this->getCategoryRepo($type);
        $category = $repo->find(['id' => $id]);

        //$pagesManager = $this->get('nacholibre.pages.manager');
        //$repo = $pagesManager->getRepo();
        //$page = $repo->find($id);

        $categoryManager = $this->get('nacholibre.category.manager');
        $typeConfig = $categoryManager->getTypeConfig($type);
        $form = $this->createForm($categoryManager->getForm($type), $category, [
            'data_class' => $typeConfig['entity_class']
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $category = $form->getData();

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('nacholibre.category.admin.edit', array('id' => $category->getId(), 'type' => $type) );
        }

        return $this->render('nacholibreAdminBundle:misc:_add_edit.html.twig', [
            'form' => $form->createView(),
            'headerTitle' => 'Edit Category',
        ]);
    }

    ///**
    // * @Route("/delete/{id}", name="nacholibre.info_page.admin.delete")
    // */
    //public function deleteAction($id) {
    //    $pagesManager = $this->get('nacholibre.pages.manager');
    //    $repo = $pagesManager->getRepo();
    //    $page = $repo->find($id);

    //    if ($page->getStatic()) {
    //        return $this->redirectToRoute('admin.info_page');
    //    }

    //    $em = $this->getDoctrine()->getManager();
    //    $em->remove($page);
    //    $em->flush();

    //    return $this->redirectToRoute('nacholibre.info_page.admin.index');
    //}
}
