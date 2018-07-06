<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Project;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/admin/projects", name="projects")
     */
    public function projects()
    {
        $projects = $this->getDoctrine()
        ->getRepository(Project::class)
        ->findAll();
        


        return $this->render('admin/projects.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/admin/projects/create", name="createProject")
     */

    public function createProject(Request $request)
    {
        if($request->getMethod() == "POST")
        {
            $em = $this->getDoctrine()->getManager();

            $project = new Project();
            $project->setName($request->get('Name'));
            $project->setDescription($request->get('Description'));

            $em->persist($project);

            $em->flush();

            return $this->redirectToRoute('admin');


        }else {
            return $this->render('admin/createProject.html.twig', [
                'method' => $request->getMethod()
            ]);
        }

    }

}
