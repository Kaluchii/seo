<?php

namespace Interpro\Seo\Executors;

use Interpro\Core\Contracts\Executor\CInitializer;
use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Fields\OwnField;
use Interpro\Core\Exception\InitException;
use Interpro\Seo\Model\Seo;

class Initializer implements CInitializer
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
     * @param mixed $value
     *
     * @return void
     */
    public function init(ARef $ref, OwnField $own, $value = null)
    {
        $type          = $ref->getType();
        $type_name     = $type->getName();
        $id            = $ref->getId();
        $own_type_name = $own->getFieldTypeName();
        $own_name      = $own->getName();

        if($own_type_name === 'seo')
        {
            if($value === null)
            {
                $value = '';
            }

            if(!is_string($value))
            {
                throw new InitException('Seo поле '.$own_name.' типа '.$own_type_name.' должно быть задано строкой!');
            }

            $field = Seo::firstOrNew(['entity_name' => $type_name, 'entity_id' => $id, 'name' => $own_name]);
        }
        else
        {
            throw new InitException('Seo не обрабатывает тип '.$type_name);
        }

        $field->value = $value;
        $field->save();
    }
}
