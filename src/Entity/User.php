<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Role;
use App\Entity\Blog;
use App\Entity\Forum;
use App\Entity\Answer;
use App\Entity\Category;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="L'email est déjàp pris")
 * @UniqueEntity(fields="username", message="Le pseudo est déjà pris")
 */
class User implements UserInterface, \Serializable
{
    /**
     * User id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * User email
    * @ORM\Column(type="string", length=255, unique=true)
    * @Assert\NotBlank()
    * @Assert\Email()
    */
    private $email;

    /**
     * User username
    * @ORM\Column(type="string", length=255, unique=true)
    * @Assert\NotBlank()
    */
    private $username;

    /**
     * User plain password
    * @Assert\Length(max=4096)
    */
    private $plainPassword;

    /**
     * User password
    * @ORM\Column(type="string", length=64)
    */
    private $password;

    /**
     *User image
    * @ORM\Column(type="string")
    * @Assert\File(mimeTypes={ "image/jpeg" })
    */
    private $image;

    /**
     * User blog
    * @ORM\OneToMany(targetEntity="App\Entity\Blog", mappedBy="user")
    **/
    private $blog;

    /**
     * User category
    * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="user")
    **/
    private $category;

    /**
     * User author
    * @ORM\OneToMany(targetEntity="App\Entity\Forum", mappedBy="user")
    **/
    private $author;

    /**
     * User comment
    * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="user")
    **/
    private $comment;

    /**
     * User role
     * @ORM\ManyToOne(targetEntity="App\Entity\Role", inversedBy="user")
     * @ORM\JoinColumn(nullable=true)
     */
    private $role;

    public function __construct()
    {
      $this->blog = new ArrayCollection();
      $this->comment = new ArrayCollection();
      $this->category = new ArrayCollection();
      $this->author = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int   $id         The user id
     * @return void
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Return the email of user
     * @return string
    **/
    public function getEmail(): ?string
    {
      return $this->email;
    }

    /**
     * Set email of user
     * @return void
    */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the user username
     * @return string
     */
    public function getUsername(): ?string
    {
      return $this->username;
    }

    /**
     * Set the user username
     * @param string $username
     * @return void
     */
    public function setUsername(string $username)
    {
      $this->username = $username;
    }

    /**
     * Get the password without encodage
     * @return mixed
     */
    public function getPlainPassword()
    {
      return $this->plainPassword;
    }

    /**
     * Set the plain password
     * @param string $plainPassword
     * @return void
     */
    public function setPlainPassword(string $plainPassword): void
    {
      $this->plainPassword = $plainPassword;
    }

    /**
     * Get the password encode
     * @return string
     */
    public function getPassword(): ?string
    {
      return $this->password;
    }

    /**
     * Set the password encode
     * @param string $password
     */
    public function setPassword(string $password): void
    {
      $this->password = $password;
    }

    /**
     *
     * @return null|string
     */
    public function getSalt()
    {
      return null;
    }

    /**
     * Get the user role (admin or user)
     * @return array
     */
    public function getRoles(): array
    {
      return array($this->role->getName());
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
      return $this->role;
    }

    /**
     * Edit the role user
     * @param \App\Entity\Role $role
     */
    public function setRole(Role $role)
    {
      $this->role = $role;
    }

    /**
     * Remove sensitive informations on the user
     */
    public function eraseCredentials()
    {
    }

    /**
    * Return the path of the image
    *
    * @return String
    **/
    public function getImage()
    {
      return $this->image;
    }

    /**
     * Serialize the information
     * @return string
     */
    public function serialize(): string
    {
      return serialize(array(
        $this->id,
        $this->username,
        $this->password
      ));
    }

    /**
     * Unserialized a string
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized): void
    {
      list(
        $this->id,
        $this->username,
        $this->password
        ) = unserialize($serialized);
    }

    /**
    * Set the path of the image
    *
    * @var String
    *
    * @return String
    **/
    public function setImage($image)
    {
      $this->image = $image;
    }

    /**
    * Return the user's forum post
    *
    * @return Collection|Forum[]| Array of user's forum post
    **/
    public function getForum()
    {
      return $this->forum;
    }

    /**
     * Get all the comment os the user
    * @return Collection|Comment[]
    **/
    public function getComment()
    {
      return $this->comment;
    }

    /**
    * @return Collection|Blog[]
    **/
    public function getBlog()
    {
      return $this->blog;
    }

    /**
    * Get the user answer
    *
    * @return Collection|Answer[]
    */
    public function getAnswer()
    {
      return $this->answer;
    }

    /**
    * @return Collection|Category[]
    **/
    public function getCategory()
    {
      return $this->category;
    }
}
