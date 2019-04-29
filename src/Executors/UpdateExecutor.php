<?php

namespace Interpro\Seo\Executors;

use Interpro\Core\Contracts\Executor\CUpdateExecutor;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Core\Exception\UpdateException;
use Interpro\Seo\Model\Seo;

class UpdateExecutor implements CUpdateExecutor
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
     * @param \Interpro\Core\Contracts\Taxonomy\Fields\OwnField $own
     * @param mixed $value
     *
     * @return void
     */
    public function update(ARef $ref, OwnField $own, $value)
    {
        $type          = $ref->getType();
        $type_name     = $type->getName();
        $id            = $ref->getId();
        $own_type_name = $own->getFieldTypeName();
        $own_name      = $own->getName();

        if($own_type_name !== 'seo')
        {
            throw new UpdateException('Seo не обрабатывает тип '.$own_type_name);
        }

        if(!is_string($value))
        {
            $value = '';
            //throw new UpdateException('Seo поле '.$own_name.' типа '.$own_type_name.' должно быть задано строкой!');
        }

        $field = Seo::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);

        $field->value = $value;
        $field->save();
    }
}
