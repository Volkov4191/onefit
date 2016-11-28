<?php
namespace AppBundle\Controller\API\v1;
use AppBundle\Entity\Chat;
use AppBundle\Entity\ChatMessage;
use AppBundle\Form\ChatMessageType;
use AppBundle\Repository\Chat as ChatRepository;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

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
     * Получить сообщения из конкретного чата
     *
     * @param $chatId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMessagesAction($chatId){
        /** @var Chat $chat */
        $chat = $this->getRepository()->find(intval($chatId));
        if (!$chat){
            throw $this->createNotFoundException();
        }
        $currentUser = $this->getUser();

        $data = ['items' => $chat->getMessages()];
        if ($currentUser){
            $chatMessage = new Chatmessage();
            $chatMessage
                ->setChat($chat)
                ->setUser($this->getUser())
            ;
            $data['messageForm'] = $this
                ->createForm('AppBundle\Form\ChatMessageType', $chatMessage)
                ->createView();
        }

        $view = $this->view($data, 200)
            ->setTemplate('AppBundle:message:index.html.twig')
            ;
        return $this->handleView($view);
    }

    /**
     * Получить отображение
     *
     * @param $items
     * @param string $templateName
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function getView($items, $templateName = 'AppBundle:chat:index.html.twig'){
        $view = $this->view($items, 200)
            ->setTemplateVar('chats')
            ->setTemplate($templateName)
        ;
        return $this->handleView($view);
    }

    /**
     * Получить репозиторий
     *
     * @param string $name
     * @return ChatRepository
     */
    private function getRepository($name = 'AppBundle:Chat'){
        return $this->getDoctrine()->getManager()->getRepository($name);
    }
}