<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Comment;
use App\Form\AnnonceType;
use App\Form\CommentType;
use App\Service\PaginationService;
use App\Repository\CommentRepository;
use App\Controller\AdminCommentController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment/{page<\d+>?1}", name="admin_comment")
     * permet d'afficher tout es commentaire
     * @return Response
     */
    public function comment(CommentRepository $repo, $page , PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
        ->setPage($page);
        return $this->render('admin/comment/comment.html.twig', [
            'pagination' => $pagination
        ]
        );
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/comment/{id}/edit", name="admin_comment_edit")
     * 
     * @param Comment $comment
     * @return Response 
     */

    public function edit(Comment $comment, Request $request) {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "le commentaire <strong>{$comment->getid()} </strong> a bien été modifié "
            );
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet de supprimer une annonce
     * @Route("/admin/comment/{id}/delete", name="admin_comment_delete")
     * 
     * @param Comment $comment
     * @param ObjetManager $manager
     * @return Response
     */
    public function delete(Comment $comment){
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($comment);
        $manager->flush();

        $this->addflash(
            'success',
            "l'annonce <strong> {$comment->getid()} </strong> a bien été supprimée !"
        );
        
        return $this->redirectToRoute('admin_comment');
    }

}
