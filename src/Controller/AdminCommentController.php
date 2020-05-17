<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\CommentRepository;
use App\Controller\AdminCommentController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin_comment")
     * 
     * @return Response
     */
    public function comment(CommentRepository $repo)
    {
        return $this->render('admin/comment/comment.html.twig', [
            'comment' => $repo->findall(),
        ]
        );
    }
}
