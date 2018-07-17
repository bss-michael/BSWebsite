<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Project;
use App\Entity\Module;
use App\Services\SlugHelper;

class ModuleController extends Controller
{



    /**
     * @Route("/admin/projects/{projectSlug}/modules/add", name="addModule")
     */
    public function addModule(Request $request, $projectSlug, SlugHelper $slugHelper)
    {
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository(Project::class)->findOneBy(['Slug' => $projectSlug]);

        if($request->getMethod() == "POST")
        {


            $module = new Module();
            $module->setProject(intval($project->getId()));
            $module->setName($request->get('Name'));
            $module->setSummary($request->get('Summary'));
            $module->setDiscussion($request->get('Discussion'));

         
            $slug = $slugHelper->create($request->get('Name'));
            $slug = $slugHelper->getUnique($slug, $em);

            $module->setSlug($slug);

            $em->persist($module);

            $em->flush();

            return $this->redirectToRoute('viewProject', array('projectSlug' => $projectSlug));


        }else {
            return $this->render('admin/addModule.html.twig', [
                'project' => $project
            ]);
        }

    }



    /**
     * @Route("/admin/projects/{projectSlug}/modules/{moduleSlug}/delete", name="deleteModule")
     */
    public function deleteModule($projectSlug, $moduleSlug)
    {
        $em = $this->getDoctrine()->getManager();

        $module =  $em->getRepository(Module::class)->findOneBy(['Slug' => $moduleSlug]);

        if($module)
        {
            $em->remove($module);
            $em->flush();
        }

        return $this->redirectToRoute('viewProject', array('projectSlug' => $projectSlug));

    }

    /**
     * @Route("/admin/projects/{projectSlug}/modules/{moduleSlug}/edit", name="editModule")
     */
    public function editModule(Request $request, $projectSlug, $moduleSlug, SlugHelper $slugHelper)
    {
        $em = $this->getDoctrine()->getManager();

        $module = $em->getRepository(Module::class)->findOneBy(['Slug' => $moduleSlug]);

        if(!$module)
        {
            throw $this->createNotFoundException('No module found');
        };

        if($request->getMethod() == "POST")
        {
            //Handle POST
            //First, make sure the slug is unique. If not inform the user
            $slug = $slugHelper->create($request->get('Slug'));
            $slugCheck = $em->getRepository(Module::class)->findOneBy(['Slug' => $slug]);
            if($slugCheck)
            {
                if($slugCheck->getId() != $request->get('Id'))
                {
                    $this->addFlash(
                        'error',
                        'Slug already in use: ' . $slug
                    );

                    return $this->redirectToRoute('editModule', array('projectSlug' => $projectSlug, 'moduleSlug' => $modelSlug));
                }
            }

            $module->setName($request->get('Name'));
            $module->setSummary($request->get('Summary'));
            $module->setDiscussion($request->get('Discussion'));
            $module->setSlug($slug);

            $em->flush();

            return $this->redirectToRoute('viewModule', array('projectSlug' => $projectSlug, 'moduleSlug' => $slug));

        }else{
            //Handle GET
            return $this->render('admin/editModule.html.twig', ['module' => $module]);
        }        
    }


    /**
     * @Route("/admin/projects/{projectSlug}/modules/{moduleSlug}", name="viewModule")
     */
    public function index($projectSlug, $moduleSlug)
    {
        //Get module from Database
        $module = $this->getDoctrine()->getRepository(Module::class)->findOneBy(['Slug' => $moduleSlug]);

        return $this->render('admin/viewModule.html.twig', [
            'module' => $module
        ]);

    }

}
