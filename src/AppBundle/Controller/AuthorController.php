<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends Controller{
    /**
     * @Route("/author/index", name="author_list")
     */
    public function indexAction(Request $request){
        return $this->render('author/index.html.twig');
    }

    /**
     * @Route("/author/create", name="author_create")
     */
    public function createAction(Request $request){
        return $this->render('author/create.html.twig');
    }

    /**
     * @Route("/author/edit/{id}", name="author_edit")
     */
    public function editAction($id, Request $request){
        return $this->render('author/edit.html.twig');
    }

    /**
     * @Route("/author/remove", name="author_remove")
     */
    //TODO test
    public function removeAction(Request $request){
        return $this->render('author/remove.html.twig');
    }
}
