<?php
namespace UserBundle\EventListener;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UserBundle\Service\RandomizerInterface;


/**
 * Перехват событий при регистрации
 *
 * Class RegistrationListener
 */
class RegistrationListener implements EventSubscriberInterface
{
    private $manager;
    private $randomizer;

    public function __construct(\FOS\UserBundle\Doctrine\UserManager $manager, RandomizerInterface $randomizer){
        $this->manager = $manager;
        $this->randomizer = $randomizer;
    }
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationConfirmed'
        );
    }

    /**
     * Присвание случайных имен при регистрации
     * Используется в качестве присвоения имен тем, кто зарегистрировался через форму,а не соц.сеть
     *
     * @param FilterUserResponseEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function onRegistrationConfirmed(FilterUserResponseEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        /** @var \AppBundle\Entity\User $user */
        $user = $event->getUser();

        $user
            ->setFirstName($this->randomizer->getFirstName())
            ->setLastName($this->randomizer->getLastName())
            ->setColor($this->randomizer->getColor());

        $this->manager->updateUser($user);
    }
}