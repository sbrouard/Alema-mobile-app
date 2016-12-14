<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @ORM\Table(name="directors")
* @ORM\Entity
*/
class Director
{
	/**
	 * @ORM\Id
	 * @ORM\OneToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user", referencedColumnName="login")
	 */
	protected $user;

	/**
	 * @ORM\OneToMany(targetEntity="Trip", mappedBy="manager")
	 * @ORM\JoinColumn(name="id_trip", referencedColumnName="id")
	 */
	protected $tripManage;

	public function __construct()
	{
		$this->tripManage = new ArrayCollection();
	}

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
		return $this;
	}

	public function getTripManage(){
		return $this->tripManage;
	}

	public function setTripManage($tripManage){
		$this->tripManage = $tripManage;
		return $this;
	}
}