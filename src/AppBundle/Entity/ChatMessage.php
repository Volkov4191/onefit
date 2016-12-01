<?php

namespace AppBundle\Entity;
use AppBundle\Entity\Chat;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Groups;
use UserBundle\Entity\User;

/**
 * Сообщение в чате
 *
 * Class ChatMessage
 * @package AppBundle\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChatMessage")
 * @ORM\Table(name="chat_message")
 * @ORM\HasLifecycleCallbacks()
 */
class ChatMessage
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chat", inversedBy="messages")
     * @ORM\JoinColumn(name="chat_id", referencedColumnName="id", nullable=false)
     */
    private $chat;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $created_at;

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
     * Set text
     *
     * @param string $text
     *
     * @return ChatMessage
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @Groups({"Default"})
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set chat
     *
     * @param Chat $chat
     *
     * @return ChatMessage
     */
    public function setChat(Chat $chat)
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * Get chat
     *
     * @Groups({"Default"})
     * @return Chat
     */
    public function getChat()
    {
        return $this->chat;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return ChatMessage
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @Groups({"Default"})
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ChatMessage
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @Groups({"Default"})
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @ORM\PrePersist()
     */
    public function onPrePersist(){
        return $this->setCreatedAt(new \DateTime());
    }
}
