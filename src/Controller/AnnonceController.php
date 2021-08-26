<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Photo;
use App\Repository\AnnonceRepository;
use App\Repository\CarburantRepository;
use App\Repository\GarageRepository;
use App\Repository\ModeleRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\ExtensionFileException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;


class AnnonceController extends AbstractController
{
    private EntityManagerInterface $em;

    private AnnonceRepository $annonceRepository;

    private PhotoRepository $photoRepository;

    private GarageRepository $garageRepository;

    private CarburantRepository $carburantRepository;

    private ModeleRepository $modeleRepository;

    /**
     * AnnonceController constructor.
     * @param EntityManagerInterface $em
     * @param AnnonceRepository $annonceRepository
     * @param PhotoRepository $photoRepository
     * @param GarageRepository $garageRepository
     * @param CarburantRepository $carburantRepository
     * @param ModeleRepository $modeleRepository
     */
    public function __construct(EntityManagerInterface $em, AnnonceRepository $annonceRepository, PhotoRepository $photoRepository, GarageRepository $garageRepository, CarburantRepository $carburantRepository, ModeleRepository $modeleRepository)
    {
        $this->em = $em;
        $this->annonceRepository = $annonceRepository;
        $this->photoRepository = $photoRepository;
        $this->garageRepository = $garageRepository;
        $this->carburantRepository = $carburantRepository;
        $this->modeleRepository = $modeleRepository;
    }


    /**
     * @IsGranted("ROLE_PROFESSIONNEL")
     * @Route("/annonce/delete/{id}", name="delete")
     */
    public function delete(Request $request, string $id): Response
    {
        try {
            $annonceEntity = $this->annonceRepository->find($id);
            $photoEntities = $this->photoRepository->findBy([
                'annonce' => $id
            ]);

           if(sizeof($photoEntities)>0) {
                foreach($photoEntities as $photo) {
                    $folderPath = $this->getParameter('upload_directory') . '/' . $annonceEntity->getId() . '/' . $photo->getPathPhotos();
                    unlink($folderPath);
                    $this->em->remove($photo);
                    $this->em->flush();
                }
                rmdir($this->getParameter('upload_directory') . '/' . $annonceEntity->getId());
            }
            $this->em->remove($annonceEntity);
            $this->em->flush();

            return new JsonResponse([
                'response' => 'bon'
            ]);

        }catch (FileException $e) {
            // unable to upload the photo, give up
        }


    }

    private function getToken($length){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        return $token;
    }

    /**
     * @IsGranted("ROLE_PROFESSIONNEL")
     * @Route("/annonce/add", name="annonceadd")
     */
    public function add(Request $request): Response
    {
        $post_data = json_decode($request->getContent());

        $garage = $this->garageRepository->find($post_data->garage);
        $carburant = $this->carburantRepository->find($post_data->carburant);
        $modele = $this->modeleRepository->find($post_data->modele);

        $annonce = new Annonce();
        $annonce->setNom($post_data->nom);
        $annonce->setAnneeCirculation($post_data->anneeCirculation);
        $annonce->setKilometrage($post_data->kilometrage);
        $annonce->setPrix($post_data->prix);
        $annonce->setCarburant($carburant);
        $annonce->setGarage($garage);
        $annonce->setModele($modele);
        $annonce->setRefAnnonce($this->getToken(10));
        $annonce->setDateAnnonce(New \DateTime());
        $annonce->setDescriptionLongue($post_data->description);
        $annonce->setDescriptionCourte(substr($post_data->description, 0,255));
        $this->em->persist($annonce);
        $this->em->flush();

        return new JsonResponse([
            'response' => 'bon'
        ]);
    }

    /**
     * @IsGranted("ROLE_PROFESSIONNEL")
     * @Route("/annonce/update/{id}", name="annonceupdate")
     */
    public function update(Request $request, string $id): Response
    {
        $post_data = json_decode($request->getContent());

        $garage = $this->garageRepository->find($post_data->garage);
        $carburant = $this->carburantRepository->find($post_data->carburant);
        $modele = $this->modeleRepository->find($post_data->modele);

        $annonce = $this->annonceRepository->find($id);
        $annonce->setNom($post_data->nom);
        $annonce->setAnneeCirculation($post_data->anneeCirculation);
        $annonce->setKilometrage($post_data->kilometrage);
        $annonce->setPrix($post_data->prix);
        $annonce->setCarburant($carburant);
        $annonce->setGarage($garage);
        $annonce->setModele($modele);
        $annonce->setDateAnnonce(New \DateTime());
        $annonce->setDescriptionLongue($post_data->description);
        $annonce->setDescriptionCourte(substr($post_data->description, 0,255));
        $this->em->persist($annonce);
        $this->em->flush();

        return new JsonResponse([
            'response' => 'bon'
        ]);
    }

}
