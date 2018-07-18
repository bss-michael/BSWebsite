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
        


        return $this->render('admin/Project/allProjects.html.twig', [
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
            return $this->render('admin/Project/createProject.html.twig', [
                'method' => $request->getMethod()
            ]);
        }

    }



    /**
     * @Route("/admin/projects/{projectSlug}/edit", name="editProject")
     */
    public function editProject(Request $request, $projectSlug, SlugHelper $slugHelper)
    {
       $em = $this->getDoctrine()->getManager();

       $project = $em->getRepository(Project::class)->findOneBy(['Slug' => $projectSlug]);

       if(!$project)
       {
           throw $this->createNotFoundException('No project found');
       };

       if($request->getMethod() == "POST")
       {
           //Handle POST
           //First, make sure the slug is unique. If not inform the user
           $slug = $slugHelper->create($request->get('Slug'));
           $slugCheck = $em->getRepository(Project::class)->findOneBy(['Slug' => $slug]);
           if($slugCheck)
           {
               if($slugCheck->getId() != $request->get('Id'))
               {
                   $this->addFlash(
                       'error',
                       'Slug already in use: ' . $slug
                   );

                   return $this->redirectToRoute('editProject', array('projectSlug' => $projectSlug));
               }
           }

           $project->setName($request->get('Name'));
           $project->setDescription($request->get('Description'));
           $project->setSlug($slug);

           $em->flush();

           return $this->redirectToRoute('projects');

       }else{
           //Handle GET
           return $this->render('admin/Project/editProject.html.twig', ['project' => $project]);
       }        
    }
    
    /**
     * @Route("/admin/projects/{projectSlug}/delete", name="deleteProjecte")
     */
    public function deleteProject($projectSlug)
    {
        $em = $this->getDoctrine()->getManager();

        $project =  $em->getRepository(Project::class)->findOneBy(['Slug' => $projectSlug]);
        $modules = $em->getRepository(Module::class)->findBy(['Project' => $project->getId()]);

        //Remove the project
        if($project)
        {
            $em->remove($project);
        }

        //Remove any modules associated with the project
        if($modules)
        {
            foreach($modules as $module)
            {
                $em->remove($module);
            }
        }

        $em->flush();

        return $this->redirectToRoute('projects');

    }


    /**
     * @Route("/admin/projects/{projectSlug}", name="viewProject")
     */
    public function viewProject($projectSlug)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->findOneBy(['Slug' => $projectSlug]);
        $modules = $this->getDoctrine()->getRepository(Module::class)->findBy(['Project' => intval($project->getId())]);

        return $this->render('admin/Project/viewProject.html.twig', [
            'project' => $project,
            'modules' => $modules
        ]);
    }



}
