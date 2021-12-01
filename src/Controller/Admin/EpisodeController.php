<?php

namespace App\Controller\Admin;

use \App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EpisodeController extends AbstractController
{
    #[Route('/admin/episode/create', name: 'admin_episode_create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        //jai besoin de cree une instance de la class episode
       $episode = new Episode();

        //jai besoin de mon formulaire, a qui je vais donner l'instance de ma class
        $form = $this->createForm(EpisodeType::class,$episode);
        $form->handleRequest($request);
        //si tu as bien des données a traité dans la requete 
        if($form->isSubmitted() && $form->isValid()){
            $em->persist(($episode));

            $em->flush();

            $this->addFlash("success", "L'épisode" . 
            $episode->getName() .  " a bien été ajouté.");

            return $this->redirectToRoute("admin_episode_create");
        }
        return $this->render("admin/episode/create.html.twig",[
            'form' => $form->createView()
        ]);
    } 

    #[Route('/admin/episode/list', name: 'admin_episode_list')]
    public function list(EpisodeRepository $episodeRepository)
    {
        $episodes = $episodeRepository->findAll();

        return $this->render("admin/episode/list.html.twig",[
            'episodes' => $episodes
        ]);
    }

    #[Route('/admin/episode/show/{id}', name: 'admin_episode_show')]
    public function show(int $id, EpisodeRepository $episodeRepository)
    {
        $episode = $episodeRepository->find($id);
    }
}