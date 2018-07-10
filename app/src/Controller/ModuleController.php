<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Module;

class ModuleController extends Controller
{

    /**
     * @Route("/admin/projects/{projectID}/module/{moduleID}", name="viewModule")
     */
    public function index($projectID, $moduleID)
    {
        //Get module from Database
        $module = $this->getDoctrine()->getRepository(Module::class)->find($moduleID);

        return $this->render('admin/viewModule.html.twig', [
            'module' => $module
        ]);

    }

    /**
     * @Route("/admin/projects/{projectID}/module/add", name="addModule")
     */
    public function addModule(Request $request, $projectID)
    {
        if($request->getMethod() == "POST")
        {
            $em = $this->getDoctrine()->getManager();


            $module = new Module();
            $module->setProject(intval($projectID));
            $module->setName($request->get('Name'));
            $module->setSummary($request->get('Summary'));
            $module->setDiscussion($request->get('Discussion'));

            $em->persist($module);

            $em->flush();

            return $this->redirectToRoute('viewProject', array('projectID' => $projectID));


        }else {
            return $this->render('admin/addModule.html.twig', [
                'project' => $projectID
            ]);
        }

    }

    /**
     * @Route("/admin/projects/{projectID}/module/{moduleID}/delete", name="deleteModule")
     */
    public function deleteModule($projectID, $moduleID)
    {
        $em = $this->getDoctrine()->getManager();

        $module =  $em->getRepository(Module::class)->find($moduleID);

        if($module)
        {
            $em->remove($module);
            $em->flush();
        }

        return $this->redirectToRoute('viewProject', array('projectID' => $projectID));

    }

}
