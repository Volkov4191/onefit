<?php
namespace AppBundle\Controller\API\v1;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class ChatController
 *
 * @Rest\RouteResource("Chat")
 * @Rest\Version("v1")
 * @Rest\NamePrefix("v1_")
 *
 */
class ChatController extends FOSRestController
{
    /**
     * Получить список чатов
     *
     * @Rest\View(serializerGroups={"Default"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cgetAction(){
        $chatRepository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Chat');
        $items = $chatRepository->findAll();

        return $this->getView($items);
    }

    /**
     * Получить отображение
     *
     * @param $users
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getView($items){
        $view = $this->view($items, 200)
            ->setTemplateVar('chats')
            ->setTemplate('AppBundle:chat:index.html.twig')
        ;
        return $this->handleView($view);
    }
}