<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="users_login_unique",columns={"login"})}
 * )
 */
class User implements UserInterface
{
   /**
    * @ORM\Id
    * @ORM\Column(type="string")
    */
    protected $login;

    /**
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $subscriptionDate;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="AccessChild", mappedBy="loginUser")
     */
    protected $accessChildren;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $roles = array('ROLE_USER');

    protected $plainPassword;

    public function __construct()
    {
        $this->subscriptionDate = new DateTime('now');
        $this->accessChildren = new ArrayCollection();
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSubscriptionDate(){
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate($subscriptionDate){
        $this->subscriptionDate = $subscriptionDate;
        return $this;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
    }

    public function getAccessChildren(){
        return $this->accessChildren;
    }

    public function setAccessChildren($accessChildren){
        $this->accessChildren = $accessChildren;
    }

    public function getPlainPassword(){
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword){
        $this->plainPassword = $plainPassword;
    }

     public function getRoles(){
        return $this->roles;
    }

    public function setRoles(array $roles){
        $this->roles = $roles;
 
        // allows for chaining
        return $this;
    }
 
    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function eraseCredentials()
    {
        // Suppression des données sensibles
        $this->plainPassword = null;
    }
}