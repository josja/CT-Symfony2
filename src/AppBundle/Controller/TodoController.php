<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Todo;
use AppBundle\Form\TodoType;

/**
 * Todo controller.
 *
 * @Route("/")
 */
class TodoController extends Controller
{
    /**
     * Lists all Todo entities.
     *
     * @Route("/", name="todo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $todos = $em->getRepository('AppBundle:Todo')->findAll();

        //// Pre generate each delete form for the Delete button
        $deleteForms = array();
        foreach ($todos as $todo) {
            $deleteForms[$todo->getId()] = $this->createDeleteForm($todo)->createView();
        }
        ////

        return $this->render('todo/index.html.twig', array(
            'todos' => $todos,
            'deleteForms' => $deleteForms
        ));
    }

    /**
     * Creates a new Todo entity.
     *
     * @Route("/new", name="todo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $todo = new Todo();
        $form = $this->createForm('AppBundle\Form\TodoType', $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/new.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Todo entity.
     *
     * @Route("/{id}", name="todo_show")
     * @Method("GET")
     */
    public function showAction(Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);

        return $this->render('todo/show.html.twig', array(
            'todo' => $todo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Todo entity.
     *
     * @Route("/{id}/edit", name="todo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Todo $todo)
    {
        $deleteForm = $this->createDeleteForm($todo);
        $editForm = $this->createForm('AppBundle\Form\TodoType', $todo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->addFlash(
                'notice',
                'The Todo has been stored.'
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('todo/edit.html.twig', array(
            'todo' => $todo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Sets existing Todo entity to DONE.
     *
     * @Route("/{id}/done", name="todo_done")
     * @Method({"GET"})
     */
    public function doneAction(Todo $todo)
    {
        if (true) {
            $this->addFlash(
                'notice',
                'The Todo has been set to Done.'
            );

            $em = $this->getDoctrine()->getManager();
            $todo->setDone(true);
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_index');
        }
    }

    /**
     * Deletes a Todo entity.
     *
     * @Route("/{id}", name="todo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Todo $todo)
    {
        $form = $this->createDeleteForm($todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'notice',
                'The Todo has been deleted.'
            );
            $em = $this->getDoctrine()->getManager();
            $em->remove($todo);
            $em->flush();
        }

        return $this->redirectToRoute('todo_index');
    }

    /**
     * Creates a form to delete a Todo entity.
     *
     * @param Todo $todo The Todo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Todo $todo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('todo_delete', array('id' => $todo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
