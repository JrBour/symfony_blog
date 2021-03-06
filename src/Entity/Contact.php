<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=100)
    */
    private $firstname;

    /**
    * @ORM\Column(type="string", length=100)
    *
    * @Assert\NotBlank()
    */
    private $lastname;

    /**
    * @ORM\Column(type="string", length=320)
    * @Assert\Email(message="'{{value}}'  n'est adresse mail valide")
    */
    private $mail;

    /**
    * @ORM\Column(type="text")
    */
    private $message;

    /**
    * Return id of the message
    *
    * @return Int
    **/
    public function getId()
    {
      return $this->id;
    }

    /**
    * Set the id of message
    *
    * @var Int
    *
    * @return Int
    */
    public function setId(int $id)
    {
      $this->id = $id;
      return $this;
    }

    /**
    * Return firstname of the user who has send the message
    *
    * @return String
    */
    public function getFirstname()
    {
      return $this->firstname;
    }

    /**
    * Set the firstname of the user who has send the message
    *
    * @var String
    *
    * @return String
    **/
    public function setFirstname(String $firstname)
    {
      $this->firstname = $firstname;
      return $this;
    }

    /**
    * Return the lastname of the user who has send the message
    *
    * @return String
    */
    public function getLastname()
    {
      return $this->lastname;
    }

    /**
    * Set the lastname of the user who has send the messages
    *
    * @var String
    *
    * @return String
    **/
    public function setLastname(string $lastname)
    {
      $this->lastname = $lastname;
      return $this;
    }

    /**
    * Return the mail of the user
    *
    * @return String
    */
    public function getMail()
    {
      return $this->mail;
    }

    /**
    * Set the mail of user
    *
    * @var String
    *
    * @return String
    */
    public function setMail(string $mail)
    {
      $this->mail = $mail;
      return $this;
    }

    /**
    * Return the message
    *
    * @return String
    */
    public function getMessage()
    {
      return $this->message;
    }

    /**
    * Set the message
    *
    * @var String
    *
    * @return String
    **/
    public function setMessage(string $message)
    {
      $this->message = $message;
      return $this;
    }

}
