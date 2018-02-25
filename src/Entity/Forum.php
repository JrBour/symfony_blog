<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use \DateTime;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForumRepository")
 */
class Forum
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length=200)
    *
    * @Assert\NotBlank(message="Un titre est requis")
    **/
    private $title;

    /**
    * @ORM\Column(type="string")
    *
    * @Assert\File(mimeTypes={ "image/jpeg", "image/png" })
    **/
    private $picture;

    /**
    * @ORM\Column(type="text")
    **/
    private $content;

    /**
    * @ORM\Column(type="datetime")
    **/
    private $created_at;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    **/
    private $updated_at;

    /**
    * Return the title
    *
    * @return String
    **/
    public function getTitle()
    {
      return $this->title;
    }

    /**
    * Set the title
    *
    * @var String
    *
    * @return String
    **/
    public function setTitle(string $title)
    {
      $this->title = $title;
      return $this;
    }

    /**
    * Get the content
    *
    * @return String
    **/
    public function getContent()
    {
        return $this->content;
    }

    /**
    * Set the content
    *
    * @var String
    *
    * @return String
    **/
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
    * Get the picture
    *
    * @return String $this Return the url of the picture
    **/
    public function getPicture()
    {
      return $this->picture;
    }

    /**
    * Set the url of the picture
    *
    * @var String $url The url of the picture
    *
    * @return String $this Return the url of the new picture
    **/
    public function setPicture(string $url)
    {
      $this->getPicture = $url;
      return $this;
    }

    /**
    * Get the date where the question was created
    *
    * @return Datetime
    **/
    public function getCreatedAt()
    {
      return $this->created_at;
    }

    /**
    * Set the date where the questions was created
    *
    * @var Datetime
    *
    * @return Datetime
    **/
    public function setCreatedAt(DateTime $createdAt)
    {
      $this->created_at = $createdAt;
      return $this;
    }

    /**
    * Get the update date
    *
    * @return Datetime
    **/
    public function getUpdatedAt()
    {
      return $this->updated_at;
    }

    /**
    * Set the update date
    *
    * @var Datetime
    *
    * @return Datetime
    **/
    public function setUpdatedAt(DateTime $updatedAt)
    {
      $this->updated_at = $updatedAt;
      return $this;
    }

}