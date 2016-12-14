<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="access_children")
 */
class AccessChild
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="accessUser")
	 * @ORM\JoinColumn(name="login_user", referencedColumnName="login")
	 */
	protected $loginUser;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Child", inversedBy="accessChildren")
	 * @ORM\JoinColumn(name="id_child", referencedColumnName="id")
	 */
	protected $idChild;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $familyLink;

	public function getLoginUser(){
		return $this->loginUser;
	}

	public function setLoginUser($loginUser){
		$this->loginUser = $loginUser;
		return $this;
	}

	public function getIdChild(){
		return $this->idChild;
	}

	public function setIdChild($idChild){
		$this->idChild = $idChild;
	}

	public function getFamilyLink(){
		return $this->familyLink;
	}

	public function setFamilyLink($familyLink){
		$this->familyLink = $familyLink;
	}

}