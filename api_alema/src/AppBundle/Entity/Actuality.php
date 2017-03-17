<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="actualities")
 */
class Actuality
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
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $text;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(mimeTypes={"image/gif, image/jpeg, image/png"})
     */
    public $pictureName;

    /**
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="idActuality")
     * @ORM\JoinColumn(name="id_trip", referencedColumnName="id")
     */
    protected $idTrip;

    /**
     * @ORM\OneToMany(targetEntity="LikeActuality", mappedBy="idActuality")
     */
    protected $likeActuality;

    public function __construct()
    {
        $this->date = new DateTime('now');
        $this->likeActuality = new ArrayCollection();
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
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

    public function getLikeActuality(){
        return $this->likeActuality;
    }

    public function setLikeActuality($likeActuality){
        $this->likeActuality = $likeActuality;
    }


}