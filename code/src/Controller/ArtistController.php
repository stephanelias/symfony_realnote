<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Photo;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/artist")
 */
class ArtistController extends AbstractController
{
    /**
     * @Route("/", name="app_artist_index", methods={"GET"})
     */
    public function index(ArtistRepository $artistRepository): Response
    {
        return $this->render('artist/index.html.twig', [
            'artists' => $artistRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_artist_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ArtistRepository $artistRepository): Response
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if ($photo !== null ) {
                $file_name = md5(uniqid()).'.'.$photo->guessExtension() ;
                $photo->move(
                    $this->getParameter('images_directory'),
                    $file_name
                );
                $artist_photo = new Photo() ;
                $artist_photo->setName($file_name) ;
                $artist->setPhoto($artist_photo) ;
            }
            $artistRepository->add($artist, true);

            return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artist/new.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_artist_show", methods={"GET"})
     */
    public function show(Artist $artist): Response
    {
        return $this->render('artist/show.html.twig', [
            'artist' => $artist,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_artist_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Artist $artist, ArtistRepository $artistRepository): Response
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            if ($photo !== null ) {
                if ($artist->getPhoto() !== null ) {
                    unlink($this->getParameter('images_directory').'/'.$artist->getPhoto()->getName());
                    $artist->setPhoto(null) ;
                }
                $file_name = md5(uniqid()).'.'.$photo->guessExtension() ;
                $photo->move(
                    $this->getParameter('images_directory'),
                    $file_name
                );
                $artist_photo = new Photo() ;
                $artist_photo->setName($file_name) ;
                $artist->setPhoto($artist_photo) ;
            }
            $artistRepository->add($artist, true);

            return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('artist/edit.html.twig', [
            'artist' => $artist,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_artist_delete", methods={"POST"})
     */
    public function delete(Request $request, Artist $artist, ArtistRepository $artistRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artist->getId(), $request->request->get('_token'))) {
            if ($artist->getPhoto() !== null ) {
                unlink($this->getParameter('images_directory').'/'.$artist->getPhoto()->getName());
            }
            $artistRepository->remove($artist, true);
        }

        return $this->redirectToRoute('app_artist_index', [], Response::HTTP_SEE_OTHER);
    }
}
