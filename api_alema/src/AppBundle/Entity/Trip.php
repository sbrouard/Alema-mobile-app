<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="trips")
 */
class Trip
{
   /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateStart;

    /**
     * @ORM\Column(type="date")
     */
    protected $dateEnd;

    /**
     * @ORM\Column(type="string")
     */
    protected $location;

    /**
     * @ORM\Column(type="string")
     */
    protected $urlPicture;

    /**
     * @ORM\ManyToOne(targetEntity="Director", inversedBy="tripManage")
     * @ORM\JoinColumn(name="login_director", referencedColumnName="user")
     */
    protected $manager;

    /**
     * @ORM\OneToMany(targetEntity="ParticipateTrip", mappedBy="idTrip")
     */
    protected $childParticipate;

    public function __construct()
    {
        $this->childParticipate = new ArrayCollection();
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDateStart()
    {
        return $this->dateStart;
    }

    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getUrlPicture()
    {
        return $this->urlPicture;
    }

    public function setUrlPicture($urlPicture)
    {
        $this->urlPicture = $urlPicture;
    }

    public function getManager(){
        return $this->manager;
    }

    public function setManager($manager){
        $this->manager = $manager;
        return $this;
    }

    public function getChildParticipate(){
        return $this->childParticipate;
    }

    public function setChildParticipate($childParticipate){
        $this->childParticipate = $childParticipate;
        return $this;
    }
}