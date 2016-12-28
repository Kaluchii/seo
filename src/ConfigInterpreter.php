<?php

namespace Interpro\Seo;

use Interpro\Core\Taxonomy\Collections\ManifestsCollection;

class ConfigInterpreter
{
    public function interpretConfig(array $config)
    {
        $family = 'seo';

        $fields = [];

        if(array_key_exists('fields', $config))
        {
            $fields = $config['fields'];
        }

        $owners = [];

        if(array_key_exists('owners', $config))
        {
            $owners = $config['owners'];
        }

        $man = new \Interpro\Core\Taxonomy\Manifests\CTypeManifest($family, 'seo', $fields, $owners);

        return $man;
    }

}
