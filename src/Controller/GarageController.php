<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\Ville;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class GarageController extends AbstractController
{
    private EntityManagerInterface $em;

    private UserRepository $userRepository;

    /**
     * GarageController constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }


    /**
     * @Route("/garage/add", name="garage")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_PROFESSIONNEL')")
     */
    public function index(Request $request): Response
    {
        $post_data = json_decode($request->getContent());


        $user = $this->userRepository->find($post_data->user);
        $ville = new Ville();
        $ville->setNomVille($post_data->nomVille);
        $ville->setCodePostal($post_data->codePostal);
        $ville->setAdresse1($post_data->adresse1);
        $ville->setAdresse2($post_data->adresse2);
        $ville->setAdresse3($post_data->adresse3);
        $garage = new Garage();
        $garage->setNom($post_data->nom);
        $garage->setTelephone($post_data->telephone);
        $garage->setVille($ville);
        $garage->setUser($user);
        $this->em->persist($garage);
        $this->em->persist($ville);
        $this->em->flush();


        return new JsonResponse([
            'response' => 'bon'
        ]);
    }
}
