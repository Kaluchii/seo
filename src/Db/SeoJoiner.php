<?php

namespace Interpro\Seo\Db;

use Illuminate\Support\Facades\DB;
use Interpro\Core\Contracts\Taxonomy\Fields\Field;
use Interpro\Extractor\Contracts\Db\Joiner;
use Interpro\Extractor\Db\QueryBuilder;
use Interpro\Seo\Exception\SeoException;

class SeoJoiner implements Joiner
{
    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @param \Interpro\Core\Contracts\Taxonomy\Fields\Field $field
     * @param array $join_array
     *
     * @return \Interpro\Extractor\Db\QueryBuilder
     */
    public function joinByField(Field $field, $join_array)
    {
        $fieldType = $field->getFieldType();
        $type_name = $fieldType->getName();
        $field_name = $field->getName();

        if($type_name !== 'seo')
        {
            throw new SeoException('Seo не обрабатывает тип '.$type_name);
        }

        $join_q = new QueryBuilder(DB::table('seos'));

        $join_q->select(['seos.entity_name', 'seos.entity_id', 'seos.value as '.$join_array['full_field_names'][0]]);
        $join_q->whereRaw('seos.name = "'.$field_name.'"');

        return $join_q;
    }

    /**
     * @return string
     */
    public function getFamily()
    {
        return 'seo';
    }
}
