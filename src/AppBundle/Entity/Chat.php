<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Groups;

/**
 * Class Chat
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Chat")
 * @ORM\Table(name="chat")
 *
 */
class Chat
{
    /**
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ChatMessage", mappedBy="chat", cascade={"persist"})
     * @var ArrayCollection
     */
    private $messages;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @Groups({"Default"})
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Chat
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @Groups({"Default"})
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add message
     *
     * @param \AppBundle\Entity\ChatMessage $message
     *
     * @return Chat
     */
    public function addMessage(\AppBundle\Entity\ChatMessage $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\ChatMessage $message
     */
    public function removeMessage(\AppBundle\Entity\ChatMessage $message)
    {
        $this->messages->removeElement($message);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return string
     */
    public function __toString(){
        return $this->getName();
    }


}
