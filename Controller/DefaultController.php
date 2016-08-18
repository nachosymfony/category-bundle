<?php

namespace nacholibre\CategoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {
    public function indexAction() {
        return $this->render('nacholibreCategoryBundle:Default:index.html.twig');
    }
}
