<?php

namespace App\Controller\Admin;

use \App\Entity\Episode;
use App\Form\EpisodeType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EpisodeController extends AbstractController
{
    #[Route('/admin/episode/create', name: 'admin_episode_create')]
    public function create(Request $request)
    {
        //jai besoin de cree une instance de la class episode
       $episode = new Episode();

        //jai besoin de mon formulaire, a qui je vais donner l'instance de ma class
        $form = $this->createForm(EpisodeType::class,$episode);
        $form->handleRequest($request);
        //si tu as bien des données a traité dans la requete 
        if($form->isSubmitted() && $form->isValid()){
            //code
        }
        return $this->render("admin/episode/create.html.twig",[
            'form' => $form->createView()
        ]);
    } 
}