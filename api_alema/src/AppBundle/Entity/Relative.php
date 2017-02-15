<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="relatives", uniqueConstraints={@ORM\UniqueConstraint(name="relative_familyNumber_unique",columns={"familyNumber"})})
 */
class Relative
{
	/**
	 * @ORM\Id
	 * @ORM\OneToOne(targetEntity="User")
	 * @ORM\JoinColumn(name="user", referencedColumnName="login")
	 */
	protected $user;

	/**
	 * @ORM\Column(name="familyNumber", type="string")
	 */
	protected $familyNumber;

	/**
     * @ORM\OneToMany(targetEntity="Child", mappedBy="relative", cascade={"persist"})
     * @ORM\JoinColumn(name="login", referencedColumnName="id")
     * @var Child[]
     */
    protected $children;

    public function __construct(){
        $this->children = new ArrayCollection();
    }

	public function getUser(){
		return $this->user;
	}

	public function setUser($user){
		$this->user = $user;
		return $this;
	}

	public function getFamilyNumber(){
		return $this->familyNumber;
	}

	public function setFamilyNumber($familyNumber){
		$this->familyNumber = $familyNumber;
		return $this;
	}

	public function getChildren(){
	 	return $this->children;
	}

	public function setChildren($children){
		$this->children = $children;
	}
}