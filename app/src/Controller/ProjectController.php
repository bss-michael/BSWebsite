<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Project;
use App\Entity\Module;
use App\Services\SlugHelper;
class ProjectController extends Controller
{
    /**
     * @Route("/admin/projects", name="projects")
     */
    public function index()
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

    public function createProject(Request $request, SlugHelper $slugHelper)
    {
        if($request->getMethod() == "POST")
        {
            $em = $this->getDoctrine()->getManager();

            $project = new Project();
            $project->setName($request->get('Name'));
            $project->setDescription($request->get('Description'));

            $slug = $slugHelper->create($request->get('Name'));
            $slug = $slugHelper->getUniqueProject($slug, $em);

            $project->setSlug($slug);

            $em->persist($project);

            $em->flush();

            return $this->redirectToRoute('projects');


        }else {
            return $this->render('admin/createProject.html.twig', [
                'method' => $request->getMethod()
            ]);
        }

    }



    /**
     * @Route("/admin/projects/{projectSlug}", name="viewProject")
     */
    public function viewProject($projectSlug)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->findOneBy(['Slug' => $projectSlug]);
        $modules = $this->getDoctrine()->getRepository(Module::class)->findBy(['Project' => intval($project->getId())]);

        return $this->render('admin/viewProject.html.twig', [
            'project' => $project,
            'modules' => $modules
        ]);
    }



}
