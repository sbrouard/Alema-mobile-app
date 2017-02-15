<?php
namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use AppBundle\Entity\Actuality;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class ActualityController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"actuality"})
     * @Rest\GET("/trips/{idTrip}/actualities")
     */
    public function getActualitiesAction(Request $request){
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
           ->from('AppBundle:Actuality', 'p')
           ->orderBy('p.date', 'DESC')
           ->where('p.idTrip = :idTrip')
           ->setParameter('idTrip', $idTrip);
        $actualities = $qb->getQuery()->getResult();
        return $actualities;

    }

	/**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"actuality"})
     * @Rest\POST("/trips/{idTrip}/actualities")
     */
    public function postActualityAction(Request $request)
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
    	$actuality = new Actuality();
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
            $this->getParameter('actualities_directory'),
            $fileName
        );

        // Update the 'brochure' property to store the PDF file name
        // instead of its contents
        $actuality->setPictureName($fileName);
        $actuality->setIdTrip($trip);
        $actuality->setTitle($request->get('title'));
        $actuality->setText($request->get('text'));
        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($actuality);
        $em->flush();
        return $actuality;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/actualities/{id}")
     */
    public function removeActualityAction(Request $request)
    {
        $loginConnect = $this->get('security.token_storage')->getToken()->getUser()->getLogin();
        $actuality = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Actuality')
                ->find($request->get('id'));
        if(empty($actuality)){
            $this->actualityNotFound();
        }
        $trip = $this->get('doctrine.orm.entity_manager')
              ->getRepository('AppBundle:Trip')
              ->findOneById($actuality->getIdTrip()->getId());
        $director = $this->container->get('security.authorization_checker')->isGranted('ROLE_DIRECTOR') && $trip->getManager()->getUser()->getLogin() === $loginConnect;
        if(!$director){
            return $this->userNotPermit();
        }
        $fileName = $this->getParameter('actualities_directory').'/'.$actuality->getPictureName();
        $fs = new Filesystem();
        if($fs->exists($fileName)){
            $fs->remove($fileName);
        }
        else{
            $this->actualityNotFound($fileName);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        // $likeActuality = $actuality->getLikeActuality();
        // foreach ($likeActuality as $like) {
        //     $em->remove($like);
        // }
        $em->remove($actuality);
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

    private function actualityNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Actuality not found');
    }


    private function tripNotFound()
    {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Trip not found');
    }
}