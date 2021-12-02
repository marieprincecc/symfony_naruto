<?php

namespace App\Controller;

use App\Repository\EpisodeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EpisodeController extends AbstractController
{
    #[Route('/episode/list', name: 'episode_list')]
    public function list(EpisodeRepository $episodeRepository, PaginatorInterface $paginator, Request $request)
    {
        $episodes = $paginator->paginate(
            $episodeRepository->findAll(),
            $request->query->getInt('page', 1),6
        );

        return $this->render("episode/list.html.twig", [
            'episodes' => $episodes
        ]);
    }

    #[Route('/episode/show/{id}', name: 'episode_show')]
    public function show(int $id, EpisodeRepository $episodeRepository)
    {
        $episode = $episodeRepository->find($id);

        if (!$episode) {
            $this->addFlash("danger","Cet épisode est introuvable");
            $this->redirectToRoute("episode_list");
        }
        return $this->render('episode/show.html.twig',[
            'episode' => $episode
        ]);
    }
}