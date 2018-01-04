<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Author;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;

class BookController extends Controller{
    /**
     * @Route("/book/index", name="book_list")
     * @param Request $request
     * @Method({"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('AppBundle:Book')->findAll();
        return $this->render('book/index.html.twig', array('books' => $books));
    }

    /**
     * @Route("/book/create", name="book_create")
     * @Method({"GET", "POST"})
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
            $book->setFileName($fileName);

            /** @var UploadedFile $image */
            if ($book->getImageName() !== null) {
                $file = $book->getImageName();
                $imageName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );
                $book->setImageName($imageName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirect($this->generateUrl('book_list'));
        }
        return $this->render('book/create.html.twig', array(
            'book' => $book,
            'form' => $form->createView()));
    }

    /**
     * @Route("/book/edit/{id}", name="book_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request)
    {
        $deleteForm = $this->createDeleteForm($id);
        $editform = $this->createForm(BookType::class, $id);
        $editform->handleRequest($request);

        if($editform->isSubmitted() && $editform->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($id);
            $em->flush();

            $this->addFlash(
                'notice',
                'Информация об авторе изменена'
            );
            return $this->redirectToRoute('author_list');
        }
        return $this->render('book/edit.html.twig', array(
            '$book' => $id,
            '$editform' => $editform->createView()
        ));
    }

//        $deleteForm = $this->createDeleteForm();
//        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
//        $editForm->handleRequest($request);
//        if ($editForm->isSubmitted() && $editForm->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//            return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
//        }
//        return $this->render('category/edit.html.twig', array(
//            'category' => $category,
//            'edit_form' => $editForm->createView(),
//            'delete_form' => $deleteForm->createView(),
//        ));

//        return $this->render('book/edit.html.twig', array(
//            'book' => $book, //1
//            'form' => $form->createView()));
//    }

    /**
     * @Route("/book/remove", name="book_remove")
     * @Method({"GET", "POST"})
     */
    //TODO test
    public function removeAction(Request $request){
    }
}
