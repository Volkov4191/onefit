<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Chat controller.
 *
 * @Route("chat")
 */
class ChatController extends Controller
{
    /**
     * Lists all chat entities.
     *
     * @Route("/", name="chat_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $chats = $em->getRepository('AppBundle:Chat')->findAll();

        return $this->render('AppBundle:chat:index.html.twig', array(
            'chats' => $chats,
        ));
    }


    /**
     * Creates a new chat entity.
     *
     * @Route("/new", name="chat_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $chat = new Chat();
        $form = $this->createForm('AppBundle\Form\ChatType', $chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chat);
            $em->flush($chat);

            return $this->redirectToRoute('chat_messages', array('id' => $chat->getId()));
        }

        return $this->render('AppBundle:chat:new.html.twig', array(
            'chat' => $chat,
            'form' => $form->createView(),
        ));
    }


    /**
     * Displays a form to edit an existing chat entity.
     *
     * @Route("/{id}/edit", name="chat_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Chat $chat)
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('chat_index');
        }

        $deleteForm = $this->createDeleteForm($chat);
        $editForm = $this->createForm('AppBundle\Form\ChatType', $chat);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('chat_index');
        }

        return $this->render('AppBundle:chat:edit.html.twig', array(
            'chat' => $chat,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a chat entity.
     *
     * @Route("/{id}", name="chat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Chat $chat)
    {
        $form = $this->createDeleteForm($chat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($chat);
            $em->flush($chat);
        }

        return $this->redirectToRoute('chat_index');
    }

    /**
     * Deletes a chat entity.
     *
     * @Route("/{id}/messages", name="chat_messages")
     * @Method("GET")
     */
    public function messagesAction(Chat $chat){
        return $this->render('AppBundle:chat:messages.html.twig', array(
            'chat'     => $chat,
            'messages' => $chat->getMessages(),
        ));
    }

    /**
     * Creates a form to delete a chat entity.
     *
     * @param Chat $chat The chat entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Chat $chat)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('chat_delete', array('id' => $chat->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
