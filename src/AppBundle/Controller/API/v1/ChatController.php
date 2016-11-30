<?php
namespace AppBundle\Controller\API\v1;
use AppBundle\Entity\Chat;
use AppBundle\Entity\ChatMessage;
use AppBundle\Form\ChatMessageType;
use AppBundle\Repository\Chat as ChatRepository;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function getMessagesAction($chatId, Request $request){
        /** @var Chat $chat */
        $chat = $this->getChat($chatId);
        $currentUser = $this->getUser();

        $data = ['items' => $chat->getMessages(), 'chat' => $chat];

        if ($currentUser){
            $chatMessage = new Chatmessage();
            $chatMessage
                ->setChat($chat)
                ->setUser($this->getUser())
            ;
            $data['messageForm'] = $this
                ->createForm('AppBundle\Form\ChatMessageType', $chatMessage, [
                    'action' => $this->generateUrl('v1_post_message', ['_format' => $request->get('_format')]),
                    'method' => 'POST',
                ])
                ->createView();
        }

        $view = $this->view($data, 200)
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
        $chat = $this->getChat($chatId);
        $user = $this->getUser();

        if (!$user){
            $view = $this->view(null, Response::HTTP_FORBIDDEN)
                ->setData([])
                ->setTemplate('AppBundle:message:edit.html.twig')
            ;

            return $this->handleView($view);
        }

        $message =  new ChatMessage();
        $message
            ->setChat($chat)
            ->setUser($user);


        $form = $this->createForm(ChatMessageType::class, $message, [
            'action' => $this->generateUrl( 'v1_post_message', ['_format' => $request->get('_format')] ),
            'method' =>'POST' ,
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
     * Получить всех пользователей в чате.
     * По умолчанию выдает всех
     *
     * @param Chat $chat
     * @return Response
     */
    public function getUsersAction($chatId){
        $userRepository = $this->getRepository('AppBundle:User');
        $view = $this->view(null, Response::HTTP_OK)
            ->setData(['users' => $userRepository->findAll()])
            ->setTemplate('UserBundle::index.html.twig')
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

    /**
     * Получить сообщение по ID
     *
     * @param $id
     * @return Chat
     */
    private function getChat($id){
        /** @var ChatRepository $messageRepository */
        $chatRepository = $this->getRepository();
        $chat = $chatRepository->find($id);
        if (!$chat){
            throw $this->createNotFoundException();
        }
        return $chat;
    }
}