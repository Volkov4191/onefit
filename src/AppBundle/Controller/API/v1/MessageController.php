<?php

namespace AppBundle\Controller\API\v1;

use AppBundle\Entity\ChatMessage;
use AppBundle\Form\ChatMessageType;
use AppBundle\Repository\ChatMessage as ChatMessageRepository;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MessageController
 *
 * @Rest\RouteResource("Message")
 * @Rest\Version("v1")
 * @Rest\NamePrefix("v1_")
 *
 * @package AppBundle\Controller\API\v1
 */
class MessageController extends FOSRestController
{
    /**
     * Добавить сообщение
     *
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request){
        $chatMessage = new Chatmessage();
        $form = $this->createForm('AppBundle\Form\ChatMessageType', $chatMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($chatMessage);
            $em->flush();

            $view = $this->view(null, Response::HTTP_CREATED)
                ->setData(['message' => $chatMessage])
                ->setTemplate('AppBundle:message:show.html.twig')
            ;
            return $this->handleView($view);
        }

        $view = $this->view(null, Response::HTTP_BAD_REQUEST)
            ->setData(['form' => $form])
            ->setTemplate('AppBundle:message:new.html.twig')
        ;
        return $this->handleView($view);
    }

    /**
     * Изменить сообщение
     *
     * @param Request $request
     * @return Response
     */
    public function putAction(Request $request, ChatMessage $message){

        $form = $this->createForm(ChatMessageType::class, $message);
        $form->submit($request->request->get($form->getName())); # handleRequest не работает с PUT

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $view = $this->view(null, Response::HTTP_CREATED)
                ->setData(['message' => $message])
                ->setTemplate('AppBundle:message:show.html.twig')
            ;
            return $this->handleView($view);
        }

        $view = $this->view(null, Response::HTTP_BAD_REQUEST)
            ->setData(['form' => $form])
            ->setTemplate('AppBundle:message:new.html.twig')
        ;
        return $this->handleView($view);
    }

    /**
     * Получить форму для редактирования сообщения
     *
     * @param $id
     * @return Response
     */
    public function getNewAction(Request $request){
        return $this->getActionForm(new ChatMessage(), $request);
    }

    /**
     * Получить форму для редактирования сообщения
     *
     * @param $id
     * @return Response
     */
    public function getEditAction($id, Request $request){
        $message = $this->getMessage($id);
        return $this->getActionForm($message, $request);
    }

    private function getActionForm(ChatMessage $message, Request $request){
        $form = $this->createForm(ChatMessageType::class, $message, [
            'action' => $this->generateUrl( $message->getId() ? 'v1_put_message' : 'v1_post_message', ['message' => $message->getId(), '_format' => $request->get('_format')]),
            'method' => $message->getId() ? 'PUT' : 'POST',
        ]);

        switch($request->get('_format')){
            case 'json':
            case 'xml':{
                $data = ['html' => $this->get('templating')->render('AppBundle:message:form.html.twig', ['form' => $form->createView()])];
                break;
            }
            default:{
                $data = ['form' => $form->createView()];
                break;
            }
        }

        $view = $this->view(null, Response::HTTP_OK)
            ->setData($data)
            ->setTemplate('AppBundle:message:edit.html.twig')
        ;

        return $this->handleView($view);
    }

    /**
     * Получить сообщение по ID
     *
     * @param $id
     * @return ChatMessage
     */
    private function getMessage($id){
        /** @var ChatMessageRepository $messageRepository */
        $messageRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:ChatMessage');
        $message = $messageRepository->find($id);
        if (!$message){
            throw $this->createNotFoundException();
        }
        return $message;
    }
}