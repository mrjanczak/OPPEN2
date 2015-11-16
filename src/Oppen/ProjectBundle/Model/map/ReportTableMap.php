<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'report' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Oppen.ProjectBundle.Model.map
 */
class ReportTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.ReportTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('report');
        $this->setPhpName('Report');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\Report');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('shortname', 'Shortname', 'VARCHAR', true, 10, null);
        $this->addColumn('is_locked', 'IsLocked', 'BOOLEAN', false, 1, false);
        $this->addForeignKey('year_id', 'YearId', 'INTEGER', 'year', 'id', false, null, null);
        $this->addForeignKey('template_id', 'TemplateId', 'INTEGER', 'template', 'id', false, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Year', 'Oppen\\ProjectBundle\\Model\\Year', RelationMap::MANY_TO_ONE, array('year_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Template', 'Oppen\\ProjectBundle\\Model\\Template', RelationMap::MANY_TO_ONE, array('template_id' => 'id', ), null, null);
        $this->addRelation('ReportEntry', 'Oppen\\ProjectBundle\\Model\\ReportEntry', RelationMap::ONE_TO_MANY, array('id' => 'report_id', ), 'CASCADE', null, 'ReportEntries');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'sortable' =>  array (
  'rank_column' => 'sortable_rank',
  'use_scope' => 'true',
  'scope_column' => 'year_id',
),
        );
    } // getBehaviors()

} // ReportTableMap
