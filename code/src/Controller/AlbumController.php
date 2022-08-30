<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Cover;
use App\Entity\Photo;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Repository\ArtistRepository;
use App\Service\NoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/album")
 */
class AlbumController extends AbstractController
{
    /**
     * @Route("/", name="app_album_index", methods={"GET"})
     */
    public function index(AlbumRepository $albumRepository): Response
    {
        return $this->render('album/index.html.twig', [
            'albums' => $albumRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_album_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AlbumRepository $albumRepository, NoteService $noteService, ArtistRepository $artistRepository): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover = $form->get('cover')->getData();
            if ($cover !== null ) {
                $file_name = md5(uniqid()).'.'.$cover->guessExtension() ;
                $cover->move(
                    $this->getParameter('images_directory'),
                    $file_name
                );
                $album_cover = new Cover() ;
                $album_cover->setName($file_name) ;
                $album->setCover($album_cover) ;
            }
            $albumRepository->add($album, true);
            $noteService->titleToRepo($album,$artistRepository);
            return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/new.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_album_show", methods={"GET"})
     */
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'artist1' => $album->getArtists()->get(0),
            'album' => $album,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_album_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository, NoteService $noteService, ArtistRepository $artistRepository): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cover = $form->get('cover')->getData();
            if ($cover !== null ) {
                if ($album->getCover() !== null ) {
                    unlink($this->getParameter('images_directory').'/'.$album->getCover()->getName());
                    $album->setCover(null) ;
                }
                $file_name = md5(uniqid()).'.'.$cover->guessExtension() ;
                $cover->move(
                    $this->getParameter('images_directory'),
                    $file_name
                );
                $album_cover = new Cover() ;
                $album_cover->setName($file_name) ;
                $album->setCover($album_cover) ;
            }
            $albumRepository->add($album, true);
            $noteService->titleToRepo($album,$artistRepository);
            return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('album/edit.html.twig', [
            'album' => $album,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_album_delete", methods={"POST"})
     */
    public function delete(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            if ($album->getCover() !== null ) {
                unlink($this->getParameter('images_directory').'/'.$album->getCover()->getName());
            }
            $albumRepository->remove($album, true);
        }

        return $this->redirectToRoute('app_album_index', [], Response::HTTP_SEE_OTHER);
    }
}
