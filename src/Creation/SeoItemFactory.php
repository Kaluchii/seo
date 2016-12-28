<?php

namespace Interpro\Seo\Creation;

use Interpro\Core\Contracts\Taxonomy\Types\CType;
use Interpro\Extractor\Contracts\Creation\CItemFactory;
use Interpro\Extractor\Items\CItem;
use Interpro\Seo\Exception\SeoException;

class SeoItemFactory implements CItemFactory
{
    private $cap_value;

    public function __construct()
    {
        $this->cap_value = '';
    }

    /**
     * @param \Interpro\Core\Contracts\Taxonomy\Types\CType $type
     * @param mixed $value
     *
     * @return \Interpro\Extractor\Contracts\Items\COwnItem COwnItem
     */
    public function create(CType $type, $value)
    {
        $type_name = $type->getName();

        if($type_name !== 'seo')
        {
            throw new SeoException('Seo не обрабатывает тип '.$type_name);
        }

        $item = new CItem($type, $value, false);

        return $item;
    }

    /**
     * @param \Interpro\Core\Contracts\Taxonomy\Types\CType $type
     *
     * @return \Interpro\Extractor\Contracts\Items\COwnItem COwnItem
     */
    public function createCap(CType $type)
    {
        $type_name = $type->getName();

        if($type_name !== 'seo')
        {
            throw new SeoException('Создание заглушки типа '.$type_name.' в пакете seo не поддерживается!');
        }

        $value = $this->cap_value;

        $item = new CItem($type, $value, true);

        return $item;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'seo';
    }

}
