<?php

namespace fedy95\CatalogBundle\Controller;

use fedy95\CatalogBundle\Entity\Book;
use fedy95\CatalogBundle\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    /**
     * Lists all book entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $books = $em->getRepository('fedy95CatalogBundle:Book')->findAll();
        return $this->render('@fedy95Catalog/book/index.html.twig', array(
            'books' => $books,
        ));
    }

    /**
     * Creates a new book entity.
     */
    public function newAction(Request $request)
    {
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
            return $this->redirect($this->generateUrl('book_index'));
        }
        return $this->render('@fedy95Catalog/book/new.html.twig', array(
            'book' => $book,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a book entity.
     */
    public function showAction(Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);
        return $this->render('@fedy95Catalog/book/show.html.twig', array(
            'book' => $book,
        ));
    }

    /**
     * Displays a form to edit an existing book entity.
     */
    public function editAction(Request $request, Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);
        $editForm = $this->createForm('fedy95\CatalogBundle\Form\BookType', $book);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
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
            return $this->redirectToRoute('book_index', array('id' => $book->getId()));
        }
        return $this->render('@fedy95Catalog/book/edit.html.twig', array(
            'book' => $book,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a book entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository('fedy95CatalogBundle:Book')->find($id);
        $em->remove($author);
        $em->flush();
        $this->addFlash(
            'notice',
            'Произведение удалено из каталога'
        );
        return $this->redirectToRoute('book_index');
    }

    /**
     * Creates a form to delete a book entity.
     * @param Book $book The book entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Book $book)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('book_delete', array('id' => $book->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
