<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use \DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="comments")
 */
class Comment
{
	
	/**
	 * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="comment")
	 * @ORM\JoinColumn(name="login_user", referencedColumnName="login")
	 */
	protected $loginUser;

	/**
	 * @ORM\ManyToOne(targetEntity="Trip", inversedBy="comment")
	 * @ORM\JoinColumn(name="id_trip", referencedColumnName="id")
	 */
	protected $idTrip;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $text;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $date;

	public function __construct()
    {
        $this->date = new DateTime('now');
    }

    public function getId(){
    	return $this->id;
    }

    public function setId($id){
    	$this->id = $id;
    	return $this;
    }
    
	public function getLoginUser(){
		return $this->loginUser;
	}

	public function setLoginUser($loginUser){
		$this->loginUser = $loginUser;
		return $this;
	}

	public function getIdTrip(){
		return $this->idTrip;
	}

	public function setIdTrip($idTrip){
		$this->idTrip = $idTrip;
		return $this;
	}

	public function getText(){
		return $this->text;
	}

	public function setText($text){
		$this->text = $text;
		return $this;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($date){
		$this->date = $date;
		return $this;
	}

}