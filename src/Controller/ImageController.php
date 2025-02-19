<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Service\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ImageController extends AbstractController
{

    private $em;
    private $fileService;

    public function __construct(EntityManagerInterface $em, FileService $fileService) {
        $this->em = $em;
        $this->fileService = $fileService;
    }

    #[Route('/', name: 'list')]
    public function list(Request $request): Response {
        $image = new Image();
        $form = $this->createForm(ImageType::class, $image);
        $images = $this->em->getRepository(Image::class)->getAllImages();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('filename')->getData();
            $filename = $this->fileService->uploadFile($file); // Upload Image to S3
            $image->setFilename($filename);
            $this->em->persist($image);
            $this->em->flush();
            return $this->redirectToRoute('list');
        }
        return $this->render('image/list.html.twig', ['form' => $form->createView(), 'images' => $images]);
    }

    #[Route('/edit-image', name: 'editImage')]
    public function editImage() {
        return $this->render('image/edit.html.twig');
    }

    #[Route('/delete-image/{id}', name: 'deleteImage')]
    public function deleteImage(Image $image) {
        $this->fileService->deleteFile($image->getFilename()); // Delete Image from S3
        $this->em->remove($image); // Delete from Database
        $this->em->flush();
        return $this->redirectToRoute('list');
    }

}
