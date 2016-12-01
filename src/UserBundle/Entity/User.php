<?php

namespace UserBundle\Entity;

use AppBundle\Entity\ChatMessage;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Groups;

/**
 * Class User
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;

    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;

    /**
     * @ORM\Column(name="$first_name", type="string", length=255, nullable=true)
     */
    private $first_name;

    /**
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ChatMessage", mappedBy="user", cascade={"persist"})
     * @var ArrayCollection
     */
    private $messages;

    public function __construct(){
        parent::__construct();
    }

    /**
     * @param mixed $facebook_id
     * @return User
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
        return $this;
    }

    /**
     * @param mixed $facebook_access_token
     * @return User
     */
    public function setFacebookAccessToken($facebook_access_token)
    {
        $this->facebook_access_token = $facebook_access_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @Groups({"Default"})
     * @return mixed
     */
    public function getId()
    {
        return parent::getId();
    }

    /**
     * @Groups({"Default"})
     * @return string
     */
    public function getUsername()
    {
        return parent::getUsername();
    }

    /**
     * Add message
     *
     * @param \AppBundle\Entity\ChatMessage $message
     *
     * @return User
     */
    public function addMessage(ChatMessage $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\ChatMessage $message
     */
    public function removeMessage(ChatMessage $message)
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
     * Set color
     *
     * @param string $color
     *
     * @return User
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @Groups({"Default"})
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @Groups({"Default"})
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @Groups({"Default"})
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }
}
