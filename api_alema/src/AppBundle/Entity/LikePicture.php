<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="like_pictures", uniqueConstraints={@ORM\UniqueConstraint(name="like_picture_user_picture_unique",columns={"login_user", "id_picture"})})
 */
class LikePicture
{
	
	/**
	 * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="likePicture")
	 * @ORM\JoinColumn(name="login_user", referencedColumnName="login")
	 */
	protected $loginUser;

	/**
	 * @ORM\ManyToOne(targetEntity="Picture", inversedBy="likePicture")
	 * @ORM\JoinColumn(name="id_picture", referencedColumnName="id")
	 */
	protected $idPicture;

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

	public function getIdPicture(){
		return $this->idPicture;
	}

	public function setIdPicture($idPicture){
		$this->idPicture = $idPicture;
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