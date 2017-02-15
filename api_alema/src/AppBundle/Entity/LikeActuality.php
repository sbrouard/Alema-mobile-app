<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="like_actualities", uniqueConstraints={@ORM\UniqueConstraint(name="like_actuality_user_actuality_unique",columns={"login_user", "id_actuality"})})
 */
class LikeActuality
{
	
	/**
	 * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="likeActuality")
	 * @ORM\JoinColumn(name="login_user", referencedColumnName="login")
	 */
	protected $loginUser;

	/**
	 * @ORM\ManyToOne(targetEntity="Actuality", inversedBy="likeActuality")
	 * @ORM\JoinColumn(name="id_actuality", referencedColumnName="id")
	 */
	protected $idActuality;

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

	public function getIdActuality(){
		return $this->idActuality;
	}

	public function setIdActuality($idActuality){
		$this->idActuality = $idActuality;
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