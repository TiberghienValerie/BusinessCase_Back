<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Photo;
use App\Repository\AnnonceRepository;
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

    /**
     * AnnonceController constructor.
     * @param EntityManagerInterface $em
     * @param AnnonceRepository $annonceRepository
     * @param PhotoRepository $PhotoRepository
     */
    public function __construct(EntityManagerInterface $em, AnnonceRepository $annonceRepository, PhotoRepository $photoRepository)
    {
        $this->em = $em;
        $this->annonceRepository = $annonceRepository;
        $this->photoRepository = $photoRepository;
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

            foreach($photoEntities as $photo) {
                $folderPath = $this->getParameter('upload_directory') . '/' . $annonceEntity->getId() . '/' . $photo->getPathPhotos();
                unlink($folderPath);
                $this->em->remove($photo);
                $this->em->flush();
            }
            rmdir($this->getParameter('upload_directory') . '/' . $annonceEntity->getId());
            $this->em->remove($annonceEntity);
            $this->em->flush();

            return new JsonResponse([
                'response' => 'bon'
            ]);

        }catch (FileException $e) {
            // unable to upload the photo, give up
        }


    }




}
