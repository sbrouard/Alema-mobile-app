<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="pictures")
 */
class Picture
{
   /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(mimeTypes={"image/gif, image/jpeg, image/png"})
     */
    public $pictureName;

    /**
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="idPicture")
     * @ORM\JoinColumn(name="id_trip", referencedColumnName="id")
     */
    protected $idTrip;

    /**
     * @ORM\OneToMany(targetEntity="LikePicture", mappedBy="idPicture")
     */
    protected $likePicture;

    public function __construct()
    {
        $this->date = new DateTime('now');
        $this->likePicture = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getPictureName()
    {
        return $this->pictureName;
    }

    public function setPictureName($pictureName)
    {
        $this->pictureName = $pictureName;
    }

    public function getIdTrip(){
        return $this->idTrip;
    }

    public function setIdTrip($idTrip){
        $this->idTrip = $idTrip;
    }

    public function getLikePicture(){
        return $this->likePicture;
    }

    public function setLikePicture($likePicture){
        $this->likePicture = $likePicture;
    }


}