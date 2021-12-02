<?php

namespace App\Controller;

use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class EpisodeController extends AbstractController
{
    #[Route('/episode/list', name: 'episode_list')]
    public function list(EpisodeRepository $episodeRepository)
    {
        $episodes = $episodeRepository->findAll();

        return $this->render("episode/list.html.twig",[
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