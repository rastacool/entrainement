<?php
 
namespace App\Service;

use Twig\Environment;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RequestStack;


class PaginationService{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath) {
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    public function setRoute($route){
        $this->route = $route;

        return $this;
    }

    public function getRoute(){
        return $this->route;
    }

    public function display(){
        $this->twig->display($this->templatePath,[
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route,
        ]);
    }

    public function getPages() {
        if(!empty($this->entityclass)){
            throw new \Exception("vous n'avez pas spécifié l'entité sur la quelle nous devons paginez ! Utilisez la méthode setEntityclass() de votre objet PaginationService");
        }
        // 1) Connaitre le total des enregistrement de la table
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        //2) faire la division l'arrondir et renvoyer
        $pages = ceil($total / $this->limit);

        return $pages ;
    }


    public function getData() {
        if(!empty($this->entityclass)){
            throw new \Exception("vous n'avez pas spécifié l'entité sur la quelle nous devons paginez ! Utilisez la méthode setEntityclass() de votre objet PaginationService");
        }
        // 1) Calculer l'offset
        $offset = $this->currentPage * $this->limit  -  $this->limit;

        // 2) Demander au repository de trouver les éléments
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        // 3) Renvoyer les éléments en question
        return $data ;
    }

    public function setPage($page) {
        $this->currentPage = $page;

        return $this;
    }

    public function getPage() {
        return $this->currentPage;
    }   
    
    public function setlimit($limit) {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function setEntityClass($entityClass) {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass() {
        return $this->getEntityClass;
    }
}