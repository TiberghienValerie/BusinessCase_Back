<?php

namespace App\Controller;

use App\Entity\Garage;
use App\Entity\Ville;
use App\Repository\GarageRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class GarageController extends AbstractController
{
    private EntityManagerInterface $em;

    private UserRepository $userRepository;

    private VilleRepository $villeRepository;

    private GarageRepository $garageRepository;

    /**
     * GarageController constructor.
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param VilleRepository $villeRepository
     * @param GarageRepository $garageRepository
     */
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, VilleRepository $villeRepository, GarageRepository $garageRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->villeRepository = $villeRepository;
        $this->garageRepository = $garageRepository;
    }


    /**
     * @IsGranted("ROLE_PROFESSIONNEL")
     * @Route("/garage/add", name="garageadd")
     */
    public function add(Request $request): Response
    {
        $post_data = json_decode($request->getContent());

        $user = $this->userRepository->find($post_data->user);

        $ville = $this->villeRepository->findOneBy([
            'nomVille'=>$post_data->nomVille,
            'codePostal' => $post_data->codePostal,
            'adresse1' => $post_data->adresse1,
            'adresse2' => $post_data->adresse2,
            'adresse3' => $post_data->adresse3
        ]);

        if($ville === null) {
            $ville = new Ville();
            $ville->setNomVille($post_data->nomVille);
            $ville->setCodePostal($post_data->codePostal);
            $ville->setAdresse1($post_data->adresse1);
            $ville->setAdresse2($post_data->adresse2);
            $ville->setAdresse3($post_data->adresse3);
        }
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

    /**
     * @IsGranted("ROLE_PROFESSIONNEL")
     * @Route("/garage/update/{id}", name="garageupdate")
     */
    public function update(Request $request, string $id): Response
    {
        $post_data = json_decode($request->getContent());

        $ville = $this->villeRepository->findOneBy([
            'nomVille'=>$post_data->nomVille,
            'codePostal' => $post_data->codePostal,
            'adresse1' => $post_data->adresse1,
            'adresse2' => $post_data->adresse2,
            'adresse3' => $post_data->adresse3
        ]);

        if($ville === null) {
            $ville = new Ville();
            $ville->setNomVille($post_data->nomVille);
            $ville->setCodePostal($post_data->codePostal);
            $ville->setAdresse1($post_data->adresse1);
            $ville->setAdresse2($post_data->adresse2);
            $ville->setAdresse3($post_data->adresse3);
        }

        $garage = $this->garageRepository->find($id);
        $garage->setNom($post_data->nom);
        $garage->setTelephone($post_data->telephone);
        $garage->setVille($ville);
        $this->em->persist($garage);
        $this->em->persist($ville);
        $this->em->flush();

        return new JsonResponse([
            'response' => 'bon'
        ]);
    }
}
