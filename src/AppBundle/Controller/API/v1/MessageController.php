<?php

namespace AppBundle\Controller\API\v1;

use AppBundle\Entity\ChatMessage;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Управление сообщениями REST
 *
 * Class MessageController
 *
 * @Rest\RouteResource("Message")
 * @Rest\Version("v1")
 * @Rest\NamePrefix("v1_")
 *
 * @package AppBundle\Controller\API\v1
 * @method ChatMessage getEntity($id);
 */
class MessageController extends CommonController
{
    protected $repositoryClassName = 'AppBundle:ChatMessage';

    /**
     * Добавить сообщение
     *
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request){
        return $this->processMessageForm($request, new Chatmessage());
    }

    /**
     * Изменить сообщение
     *
     * @param Request $request
     * @return Response
     */
    public function putAction(Request $request, ChatMessage $message){
        return $this->processMessageForm($request, $message);
    }

    /**
     * Получить форму для редактирования сообщения
     *
     * @param $id
     * @return Response
     */
    public function getEditAction($id, Request $request){
        $message = $this->getEntity($id);
        return $this->handleMessageFormView($message, $request);
    }
}