<?php

namespace App\Controller;

use App\Entity\Animaux;
use App\Form\AnimalsType;
use App\Repository\AnimauxRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\FamillesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(AnimauxRepository $animauxRepository)
    {
        $animals = $animauxRepository->findAll();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {


            if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                return $this->render('admin/admin.html.twig',
                    [
                        'animals'=>$animals
                    ]
                );
            } else {
                return $this->redirectToRoute('home_lesZanimeo');
            }
        } else {
            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    /**
     * @Route("/admin/form_animals_insert", name="form_animals_insert")
     */
    public function insertAnimals(Request $request, EntityManagerInterface $entityManager, AnimauxRepository $animauxRepository)
    {
        $animal = new Animaux();
        $form = $this->createForm(AnimalsType::class, $animal);
        $formAnimalsView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);
            /** @var UploadedFile $imageFile */
            $imageFile = $form['image']->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                // Nécessaire pour inclure le nom du fichier en tant qu'URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Déplace le fichier dans le dossier des images de l'image
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                // Met à jour l'image pour stocker le nouveau nom de l'image
                $animal->setImage($newFilename);
            }

            $entityManager->persist($animal);
                $entityManager->flush();

            if ($form->isSubmitted() && $form->isValid()) {

                $this->addFlash('Success', 'L\'animal a bien été enregistré !');

            } else {

                $this->addFlash('Fail', 'L\'animal n\'a pas été enregistré, veuillez réessayer.');
            }
        }

        return $this->render('admin/adminAnimalsInsert.html.twig',
            [
                'formAnimalsView' => $formAnimalsView
            ]
        );
    }


    /**
     * @Route("/admin/animals/{id}/delete", name="animals_delete")
     */
    public function removeAnimals($id, AnimauxRepository $animauxRepository, EntityManagerInterface $entityManager)
    {
        $animals = $animauxRepository->find($id);

        $entityManager->remove($animals);
        $entityManager->flush();

        $this->addFlash('Success', 'L\'animal a bien été supprimé !');

        return $this->render('base/message.html.twig');
    }



    /**
     * @Route("/admin/form_animals_update/{id}", name="form_animals_update")
     *
     */
    public function updateAnimals($id, Request $request, EntityManagerInterface $entityManager, AnimauxRepository $animauxRepository)
    {
        $animals = $animauxRepository->find($id);
        $form = $this->createForm(AnimalsType::class, $animals);

        $formAnimalsView = $form->createView();

        if ($request->isMethod('Post')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $entityManager->persist($animals);
                $entityManager->flush();
            }
        }

        return $this->render('admin/adminAnimalsUpdate.html.twig',
            [
                'formAnimalsView' => $formAnimalsView
            ]
        );
    }















}

