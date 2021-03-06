<?php

namespace App\Controller\Admin;

use \App\Entity\Episode;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function show(int $id, EpisodeRepository $episodeRepository): Response
    {
        $episode = $episodeRepository->find($id);

        if (!$episode) {
            $this->addFlash("danger","L'épisode est introuvable.");
            return $this->redirectToRoute("admin_episode_list");
        }

        return $this->render("admin/episode/show.html.twig",[
            "episode" => $episode
        ]);
    }

    #[Route('/admin/episode/delete/{id}', name: 'admin_episode_delete')]
    public function delete(int $id,EpisodeRepository $episodeRepository, EntityManagerInterface $em): Response
    {
        $episode= $episodeRepository->find($id);

        if (!$episode) {
            $this->addFlash("danger","Cet épisode est introuvable");
            $this->redirectToRoute("admin_episode_list");
        }

        $em->remove($episode);

        $em->flush();

        $this->addFlash("success","L'épisode a bien ete supprimé");

        return $this->redirectToRoute("admin_episode_list");
    }

    #[Route('/admin/episode/edit/{id}', name: 'admin_episode_edit')]
    public function edit(int $id,EpisodeRepository $episodeRepository, EntityManagerInterface $em, Request $request)
    {
        $episode= $episodeRepository->find($id);

        if (!$episode) {
            $this->addFlash("danger","L'épisode est introuvable en base de données.");
           return $this->redirectToRoute("admin_episode_list");
        }

        $form = $this->createForm(EpisodeType::class,$episode);

        $form->handleRequest($request);
        //si tu as bien des données a traité dans la requete 
        if($form->isSubmitted() && $form->isValid()){
           
            $em->flush();
            
            $this->addFlash("sucess","L'épisode a bien été modifié.");

            return $this->redirectToRoute("admin_episode_list");
        }
        return $this->render("admin/episode/edit.html.twig",[
            'form' => $form->createView()

        ]);
        }
}