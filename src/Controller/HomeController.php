<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {
 
    /**
     * @Route("/bonjour/{prenom}/age/{age}", name="hello")
     * @Route("/bonjour", name="hello_base")
     * @Route("/bonjour/{prenom}", name="hello_prenom")
     *
     * Montre la page qui dit bonjour
     * 
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0) {
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
            );
    }

    /**
     * @Route("/", name="homepage")
     */
    
    public function home(){
        $prenoms = ["lior" => 20, "Joseph" => 12, "Annie" => 55];
        return $this->render(
            'home.html.twig',
            [
                'title' => "aurevoir tout le monde",
                'age' => 12,
                'tableau' => $prenoms
                ]
            );
    }

    /**
     * @Route("/test", name="test")
     */
    
    public function test(){
        return $this->render('test.html.twig'
            );
    }

    /**
     * @Route("/dryyss", name="dryyss_index")
     * 
     * @return Reponse
     */
    public function dryyss()
    {
        return $this->render('dryyss.html.twig');
    }
}
?>