<?php

namespace AppBundle\Controller\API\v1;

use AppBundle\Entity\ChatMessage;
use AppBundle\Form\ChatMessageType;
use Doctrine\Common\Persistence\ObjectRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class CommonController extends FOSRestController
{
    protected $repositoryClassName = 'AppBundle:ChatMessage';

    /**
     * Получить репозиторий
     *
     * @param string $name
     * @return ObjectRepository
     */
    protected function getRepository($name = ''){
        return $this->getDoctrine()->getManager()->getRepository($name ?: $this->repositoryClassName);
    }

    /**
     * Получить сущность по ID
     *
     * @param $id
     * @return Object
     */
    protected function getEntity($id){
        /** @var ObjectRepository $entityRepository */
        $entityRepository = $this->getRepository();
        $entity = $entityRepository->find($id);
        if (!$entity){
            throw $this->createNotFoundException();
        }
        return $entity;
    }


    /**
     * Создать форму для редактирования или получения сообщений
     *
     * @param ChatMessage $message
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @throws \Twig_Error
     */
    protected function handleMessageFormView(ChatMessage $message, Request $request){
        $isNewEntity = intval($message->getId()) == 0;

        $form = $this->createForm(ChatMessageType::class, $message, [
            'action' => $this->generateUrl( $isNewEntity ?  'v1_post_message' : 'v1_put_message', ['message' => $message->getId(), '_format' => $request->get('_format')]),
            'method' => $isNewEntity ? Request::METHOD_POST : Request::METHOD_PUT,
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
     * Обработать форму
     *
     * @param Request $request
     * @param ChatMessage $message
     * @return Response
     */
    protected function processMessageForm(Request $request, ChatMessage $message){
        $isNewEntity = intval($message->getId()) == 0;

        $form = $this->createForm(ChatMessageType::class, $message);
        if ($isNewEntity){
            $form->handleRequest($request);
        }else{
            $form->submit($request->request->get($form->getName())); # handleRequest не работает с PUT
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $isNewEntity && $em->persist($message);
            $em->flush();

            $responseCode = $isNewEntity ?  Response::HTTP_CREATED : Response::HTTP_OK;

            $view = $this->view(null, $responseCode)
                ->setData(['message' => $message])
                ->setTemplate('AppBundle:message:show.html.twig')
            ;
            return $this->handleView($view);
        }

        $view = $this->view(null, Response::HTTP_BAD_REQUEST)
            ->setData(['form' => $form])
            ->setTemplate('AppBundle:message:show.html.twig')
        ;
        return $this->handleView($view);
    }
}