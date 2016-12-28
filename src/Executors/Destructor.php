<?php

namespace Interpro\Seo\Executors;

use Interpro\Core\Contracts\Executor\CDestructor;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Seo\Model\Seo;

class Destructor implements CDestructor
{
    /**
     * @return string
     */
    public function getFamily()
    {
        return 'seo';
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     *
     * @return void
     */
    public function delete(ARef $ref)
    {
        $type      = $ref->getType();
        $type_name = $type->getName();
        $id        = $ref->getId();

        Seo::where('entity_name', '=', $type_name)->where('entity_id', '=', $id)->delete();
    }
}
