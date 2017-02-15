<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="access_children", uniqueConstraints={@ORM\UniqueConstraint(name="access_child_user_child_unique",columns={"login_user", "id_child"})})
 */
class AccessChild
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="accessUser")
	 * @ORM\JoinColumn(name="login_user", referencedColumnName="login")
	 */
	protected $loginUser;

	/**
	 * @ORM\ManyToOne(targetEntity="Child", inversedBy="accessChildren")
	 * @ORM\JoinColumn(name="id_child", referencedColumnName="id")
	 */
	protected $idChild;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $familyLink;

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

	public function getIdChild(){
		return $this->idChild;
	}

	public function setIdChild($idChild){
		$this->idChild = $idChild;
		return $this;
	}

	public function getFamilyLink(){
		return $this->familyLink;
	}

	public function setFamilyLink($familyLink){
		$this->familyLink = $familyLink;
		return $this;
	}

}