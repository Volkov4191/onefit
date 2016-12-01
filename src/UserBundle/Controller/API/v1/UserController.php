<?php

namespace UserBundle\Controller\API\v1;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Управление пользователями REST
 *
 * Class UserController
 *
 * @Rest\RouteResource("User")
 * @Rest\Version("v1")
 * @Rest\NamePrefix("v1_")
 *
 * @package UserBundle\Controller\API\v1
 */
class UserController extends FOSRestController
{
    /**
     * Получить всех пользователей в чате.
     * По умолчанию выдает всех
     *
     * @return Response
     * @internal param $chatId
     */
    public function cgetAction(){
        $userRepository = $this->getDoctrine()->getManager()->getRepository('UserBundle:User');
        $view = $this->view(null, Response::HTTP_OK)
            ->setData(['users' => $userRepository->findAll()])
            ->setTemplate('UserBundle::index.html.twig')
        ;
        return $this->handleView($view);
    }
}