<?php

namespace Interpro\Seo\Collections;

use Interpro\Core\Contracts\Ref\ARef;
use Interpro\Core\Contracts\Taxonomy\Types\CType;
use Interpro\Extractor\Contracts\Collections\MapCCollection;
use Interpro\Extractor\Contracts\Items\COwnItem;
use Interpro\Seo\Creation\SeoItemFactory;

class MapSeoCollection implements MapCCollection
{
    private $items = [];
    private $cap;
    private $factory;

    public function __construct(SeoItemFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'seo';
    }

    private function getCap(CType $type)
    {
        $type_name = $type->getName();

        if($type_name === 'seo')
        {
            $this->cap = $this->factory->create($type, false, true);
        }
        else
        {
            throw new \Exception('Тип '.$type_name.' не поддерживается)');
        }

        return $this->cap;
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     * @param string $field_name
     *
     * @return \Interpro\Extractor\Contracts\Items\COwnItem
     */
    public function getItem(ARef $ref, $field_name)
    {
        $ownerType = $ref->getType();
        $type_name = $ownerType->getName();
        $key = $field_name.'_'.$ref->getId();

        if(!array_key_exists($type_name, $this->items))
        {
            $this->items[$type_name] = [];
        }

        if(!array_key_exists($key, $this->items[$type_name]))
        {
            $fieldType = $ownerType->getFieldType($field_name);

            return $this->getCap($fieldType);
        }

        return $this->items[$type_name][$key];
    }

    /**
     * @param \Interpro\Core\Contracts\Ref\ARef $ref
     * @param string $field_name
     * @param \Interpro\Extractor\Contracts\Items\COwnItem $item
     *
     * @return void
     */
    public function addItem(ARef $ref, $field_name, COwnItem $item)
    {
        $ownerType = $ref->getType();
        $type_name = $ownerType->getName();
        $key = $field_name.'_'.$ref->getId();

        if(!array_key_exists($type_name, $this->items))
        {
            $this->items[$type_name] = [];
        }

        $this->items[$type_name][$key] = $item;
    }

}
