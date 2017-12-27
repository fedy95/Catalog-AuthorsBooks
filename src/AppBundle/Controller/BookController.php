<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller{
    /**
     * @Route("/book", name="book_list")
     */
    public function indexAction(Request $request){
        return $this->render('book/index.html.twig');
    }

    /**
     * @Route("/book/create", name="book_create")
     */
    public function createAction(Request $request){
        return $this->render('book/create.html.twig');
    }

    /**
     * @Route("/book/edit/{id}", name="book_edit")
     */
    public function editAction($id, Request $request){
        return $this->render('book/edit.html.twig');
    }

    /**
     * @Route("/book/remove", name="book_remove")
     */
    //TODO test
    public function removeAction(Request $request){
        return $this->render('book/remove.html.twig');
    }
}
