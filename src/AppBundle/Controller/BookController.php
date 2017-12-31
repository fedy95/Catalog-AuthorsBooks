<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;

class BookController extends Controller{
    /**
     * @Route("/book/index", name="book_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request){
        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->findAll();
        return $this->render('book/index.html.twig', array('books' => $books));
    }

    /**
     * @Route("/book/create", name="book_create")
     */
    public function createAction(Request $request){
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $book */
            $file = $book->getFileName();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('books_directory'),
                $fileName
            );

            /** @var UploadedFile $image */
            $file = $book->getImageName();
            $imageName = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $this->getParameter('images_directory'),
                $imageName
            );
            $book->setImageName($imageName);


            $book->setFileName($fileName);
//            $book->setImageName($imageName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirect($this->generateUrl('book_list'));
        }
        return $this->render('book/create.html.twig', array(
            'form' => $form->createView(),
        ));
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
    }
}
