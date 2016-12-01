<?php
namespace AppBundle\Controller\API\v1;
use AppBundle\Entity\Chat;
use AppBundle\Entity\ChatMessage;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Управление чатами REST
 *
 * Class ChatController
 *
 * @Rest\RouteResource("Chat")
 * @Rest\Version("v1")
 * @Rest\NamePrefix("v1_")
 *
 * @method Chat getEntity($id);
 */
class ChatController extends CommonController
{
    protected $repositoryClassName = 'AppBundle:Chat';

    /**
     * Получить сообщения из конкретного чата
     *
     * @param $chatId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getMessagesAction($chatId, Request $request){
        /** @var Chat $chat */
        $chat = $this->getEntity($chatId);

        $view = $this
            ->view(['messages' => $chat->getMessages()], Response::HTTP_OK)
            ->setTemplate('AppBundle:message:index.html.twig')
            ;
        return $this->handleView($view);
    }

    /**
     * Получить форму для добавления комментария
     *
     * @param $chatId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function getMessageNewAction($chatId, Request $request){
        $chat = $this->getEntity($chatId);
        $user = $this->getUser();

        if (!$user){
            return $this->handleView($this->view(null, Response::HTTP_FORBIDDEN));
        }

        $message =  new ChatMessage();
        $message
            ->setChat($chat)
            ->setUser($user);

       return $this->handleMessageFormView($message, $request);
    }
}