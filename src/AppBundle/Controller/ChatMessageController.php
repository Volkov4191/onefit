<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ChatMessage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Chatmessage controller.
 *
 * @Route("message")
 */
class ChatMessageController extends Controller
{
    /**
     * Lists all chatMessage entities.
     *
     * @Route("/", name="message_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $chatMessages = $em->getRepository('AppBundle:ChatMessage')->findAll();

        return $this->render('chatmessage/index.html.twig', array(
            'chatMessages' => $chatMessages,
        ));
    }

    /**
     * Creates a new chatMessage entity.
     *
     * @Route("/new", name="message_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $chatMessage = new Chatmessage();
        $form = $this->createForm('AppBundle\Form\ChatMessageType', $chatMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chatMessage);
            $em->flush($chatMessage);

            return $this->redirectToRoute('message_show', array('id' => $chatMessage->getId()));
        }

        return $this->render('chatmessage/new.html.twig', array(
            'chatMessage' => $chatMessage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a chatMessage entity.
     *
     * @Route("/{id}", name="message_show")
     * @Method("GET")
     */
    public function showAction(ChatMessage $chatMessage)
    {
        $deleteForm = $this->createDeleteForm($chatMessage);

        return $this->render('chatmessage/show.html.twig', array(
            'chatMessage' => $chatMessage,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing chatMessage entity.
     *
     * @Route("/{id}/edit", name="message_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ChatMessage $chatMessage)
    {
        $deleteForm = $this->createDeleteForm($chatMessage);
        $editForm = $this->createForm('AppBundle\Form\ChatMessageType', $chatMessage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_edit', array('id' => $chatMessage->getId()));
        }

        return $this->render('chatmessage/edit.html.twig', array(
            'chatMessage' => $chatMessage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a chatMessage entity.
     *
     * @Route("/{id}", name="message_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ChatMessage $chatMessage)
    {
        $form = $this->createDeleteForm($chatMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chatMessage);
            $em->flush($chatMessage);
        }

        return $this->redirectToRoute('message_index');
    }

    /**
     * Creates a form to delete a chatMessage entity.
     *
     * @param ChatMessage $chatMessage The chatMessage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ChatMessage $chatMessage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('message_delete', array('id' => $chatMessage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
