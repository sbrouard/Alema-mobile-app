<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="participate_trips")
 */
class ParticipateTrip
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Trip", inversedBy="childParticipate")
	 * @ORM\JoinColumn(name="id_trip", referencedColumnName="id")
	 */
	protected $idTrip;

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Child", inversedBy="tripParticipate")
	 * @ORM\JoinColumn(name="id_child", referencedColumnName="id")
	 */
	protected $idChild;


	public function getIdTrip(){
		return $this->idTrip;
	}

	public function setIdTrip($idTrip){
		$this->idTrip = $idTrip;
		return $this;
	}

	public function getIdChild(){
		return $this->idChild;
	}

	public function setIdChild($idChild){
		$this->idChild = $idChild;
	}


}