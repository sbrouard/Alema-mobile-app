<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Entity\Picture;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class PictureController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"picture"})
     * @Rest\GET("/trips/{idTrip}/pictures")
     */
    public function getPicturesAction(Request $request){
        $idTrip = $request->get('idTrip');
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $trip = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:Trip')
              ->find($idTrip);
        if(empty($trip)){
            $this->tripNotFound();
        }
        $director = $this->container->get('security.authorization_checker')->isGranted('ROLE_DIRECTOR') && $trip->getManager()->getUser()->getLogin() === $loginConnect;
        if(!$director && $this->userIsPermit($idTrip, $loginConnect) === false){
            return $this->userNotPermit();
        }
        $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();
        $qb->select('p')
           ->from('AppBundle:Picture', 'p')
           ->orderBy('p.date', 'DESC')
           ->where('p.idTrip = :idTrip')
           ->setParameter('idTrip', $idTrip);
        $pictures = $qb->getQuery()->getResult();
        return $pictures;

    }

	/**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"picture"})
     * @Rest\POST("/trips/{idTrip}/pictures")
     */
    public function postPictureAction(Request $request)
    {
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $idTrip = $request->get('idTrip');
        $trip = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Trip')
            ->find($idTrip);
        if(empty($trip)){
            $this->tripNotFound();
        }
        $director = $this->container->get('security.authorization_checker')->isGranted('ROLE_DIRECTOR') && $trip->getManager()->getUser()->getLogin() === $loginConnect;
        if(!$director){
            return $this->userNotPermit();
        }
    	$picture = new Picture();
        $file = $request->files->get('pictureName');
        $idTrip = $request->get('idTrip');
        $trip = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Trip')
            ->find($idTrip);
		// $file stores the uploaded image file
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        // Move the file to the directory where brochures are stored
        $file->move(
            $this->getParameter('pictures_directory'),
            $fileName
        );

        // Update the 'brochure' property to store the PDF file name
        // instead of its contents
        $picture->setPictureName($fileName);
        $picture->setIdTrip($trip);
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($picture);
        $em->flush();
        return $picture;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/pictures/{id}")
     */
    public function removePicturesAction(Request $request)
    {
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $picture = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Picture')
                ->find($request->get('id'));
        if(empty($picture)){
            $this->pictureNotFound();
        }
        $trip = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:Trip')
              ->findOneById($picture->getIdTrip()->getId());
        $director = $this->container->get('security.authorization_checker')->isGranted('ROLE_DIRECTOR') && $trip->getManager()->getUser()->getLogin() === $loginConnect;
        if(!$director){
            return $this->userNotPermit();
        }
        $fileName = $this->getParameter('pictures_directory').'/'.$picture->getPictureName();
        $fs = new Filesystem();
        if($fs->exists($fileName)){
            $fs->remove($fileName);
        }
        else{
            $this->pictureNotFound($fileName);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $likePicture = $picture->getLikePicture();
        foreach ($likePicture as $like) {
            $em->remove($like);
        }
        $em->remove($picture);
        $em->flush();
    }

    private function userIsPermit($idTrip, $login){
        $user = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:User')
                ->find($login);
        $children = $user->getAccessChildren();
        $trip = new ArrayCollection();
        foreach ($children as $child) {
            $id = $child->getIdChild()->getId();
            $participateTrip = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:ParticipateTrip')
                ->findByIdChild($id);
            if(!empty($participateTrip)){
                foreach ($participateTrip as $trips) {
                    $trip->add($trips->getIdTrip()->getId());
                }
            }
        }
        return $trip->indexOf(intval($idTrip));
    }

    private function userNotPermit(){
        throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('Bad user',null, 403);
    }

    private function pictureNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Picture not found');
    }


    private function tripNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Trip not found');
    }
}