<?php

namespace AppBundle\Controller\API\v1;

use AppBundle\Entity\ChatMessage;
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
}