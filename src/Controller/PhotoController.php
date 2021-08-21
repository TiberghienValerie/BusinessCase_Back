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


class PhotoController extends AbstractController
{
    private EntityManagerInterface $em;

    private AnnonceRepository $annonceRepository;

    private PhotoRepository $photoRepository;

    /**
     * PhotoController constructor.
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
     * @Route("/photo/upload/{id}", name="upload")
     */
    public function upload(Request $request, string $id, SluggerInterface $slugger): Response
    {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

            try {
                /* uploads images dans le dossier */
                $folderPath = $this->getParameter('upload_directory') . '/' . $id . '/';
                if(!file_exists($folderPath)) mkdir($folderPath);
                $image_parts = explode(";base64,", $request->fileSource);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $original = pathInfo($request->file, PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($original);
                $extension = pathInfo($request->file, PATHINFO_EXTENSION);
                $file1 = $safeFilename . '-' . uniqid() . '.' . $extension;
                $file = $folderPath . $file1;
                file_put_contents($file, $image_base64);

                /* Ajout image dans la base de donnée */
                $annonceEntity = $this->annonceRepository->find($id);

                $photoEntity = new Photo();
                $photoEntity->setNomPhotos($request->name);
                $photoEntity->setPathPhotos($file1);
                $photoEntity->setAnnonce($annonceEntity);


                $this->em->persist($photoEntity);
                $this->em->flush();

                return new JsonResponse([
                    'response' => 'bon'
                ]);
            } catch (FileException $e) {

            }


    }

    /**
     * @IsGranted("ROLE_PROFESSIONNEL")
     * @Route("/photo/remove/{id}", name="remove")
     */
    public function remove(Request $request, string $id): Response
    {
        try {
            $photoEntity = $this->photoRepository->find($id);
            $folderPath = $this->getParameter('upload_directory') . '/' . $photoEntity->getAnnonce()->getId() . '/' . $photoEntity->getPathPhotos();
            unlink($folderPath);
            $this->em->remove($photoEntity);
            $this->em->flush();
            return new JsonResponse([
                'response' => 'bon'
            ]);

        }catch (FileException $e) {
            // unable to upload the photo, give up
        }


    }


}
