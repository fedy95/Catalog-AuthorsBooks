<?php

namespace fedy95\CatalogBundle\Controller;

use fedy95\CatalogBundle\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends Controller
{
    /**
     * Lists all author entities.
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $authors = $em->getRepository('fedy95CatalogBundle:Author')->findAll();
        return $this->render('@fedy95Catalog/author/index.html.twig', array(
            'authors' => $authors,
        ));
    }

    /**
     * Creates a new author entity.
     */
    public function newAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm('fedy95\CatalogBundle\Form\AuthorType', $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('author_index', array('id' => $author->getId()));
        }

        return $this->render('@fedy95Catalog/author/new.html.twig', array(
            'author' => $author,
            'form' => $form->createView(),
        ));
    }

    /**
     * TODO произвдения
     * Finds and displays a author entity.
     */
    public function showAction(Author $author)
    {
        $deleteForm = $this->createDeleteForm($author);

        return $this->render('@fedy95Catalog/author/show.html.twig', array(
            'author' => $author,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing author entity.
     */
    public function editAction(Request $request, Author $author)
    {
        $deleteForm = $this->createDeleteForm($author);
        $editForm = $this->createForm('fedy95\CatalogBundle\Form\AuthorType', $author);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('author_index', array('id' => $author->getId()));
        }
        return $this->render('@fedy95Catalog/author/edit.html.twig', array(
            'author' => $author,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a author entity.
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository('fedy95CatalogBundle:Author')->find($id);
        $em->remove($author);
        $em->flush();
        $this->addFlash(
            'notice',
            'Автор удален из каталога'
        );
        return $this->redirectToRoute('author_index');
    }

    /**
     * Creates a form to delete a author entity.
     * @param Author $author The author entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Author $author)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('author_delete', array('id' => $author->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
