<?php

namespace Interpro\Seo\Executors;

use Interpro\Core\Contracts\Ref\ARef;

use Interpro\Core\Contracts\Executor\OwnSynchronizer as OwnSynchronizerInterface;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Core\Exception\SyncException;
use Interpro\Seo\Model\Seo;

class Synchronizer implements OwnSynchronizerInterface
{

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'seo';
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     * @param \Interpro\Core\Contracts\Taxonomy\Fields\OwnField $own
     *
     * @return void
     */
    public function sync(ARef $ref, OwnField $own)
    {
        $ownerType = $ref->getType();
        $owner_type_name = $ownerType->getName();
        $own_name = $own->getName();
        $id = $ref->getId();
        $field_type_name = $own->getFieldTypeName();

        if($field_type_name !== 'seo')
        {
            throw new SyncException('Seo не обрабатывает тип '.$field_type_name);
        }

        $model = Seo::where('entity_name', '=', $owner_type_name)->where('entity_id', '=', $id)->where('name', '=', $own_name)->first();

        if(!$model)
        {
            Seo::create(['entity_name' => $owner_type_name, 'entity_id' => $id, 'name' => $own_name, 'value' => '']);
        }
    }

}
