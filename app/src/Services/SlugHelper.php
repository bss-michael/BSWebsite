<?php

namespace App\Services;

use App\Entity\Module;

class SlugHelper
{
    /**
     * Checks for uniqueness, and alters slug if necessary
     */
    public function getUnique($slug, $em)
    {
        $slugEnd = "";
        $i=0;
        $slugCheck = true;

        while($slugCheck) //Do until checking the database for slug returns null (ie, there is no entry using that slug)
        {
            $slugCheck = $em->getRepository(Module::class)->findOneBy(['Slug' => $slug.$slugEnd]);
                if($slugCheck)
                {

                    //Increment $i and add to the end of the slug
                    $i++;
                    $slugEnd = "-".$i;
               
                }
        }

        $slug .= $slugEnd;
        
        return $slug;
    }

    /**
     *     Replace spaces with dashes, strip nonalphanumeric characters, and change to all lowercase
     */
    public function create($slug)
    {
                    $slug = str_replace(" ", "-", $slug);
                    $slug = $result = preg_replace("/[^a-zA-Z0-9-]+/", "", $slug);
                    $slug = strtolower($slug);

                    return $slug;
        
    }
}
