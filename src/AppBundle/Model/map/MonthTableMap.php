<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'month' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.AppBundle.Model.map
 */
class MonthTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.MonthTableMap';

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
        $this->setName('month');
        $this->setPhpName('Month');
        $this->setClassname('AppBundle\\Model\\Month');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 10, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('is_active', 'IsActive', 'BOOLEAN', false, 1, false);
        $this->addColumn('is_closed', 'IsClosed', 'BOOLEAN', false, 1, false);
        $this->addColumn('from_date', 'FromDate', 'DATE', false, null, null);
        $this->addColumn('to_date', 'ToDate', 'DATE', false, null, null);
        $this->addForeignKey('year_id', 'YearId', 'INTEGER', 'year', 'id', false, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Year', 'AppBundle\\Model\\Year', RelationMap::MANY_TO_ONE, array('year_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Doc', 'AppBundle\\Model\\Doc', RelationMap::ONE_TO_MANY, array('id' => 'month_id', ), 'CASCADE', null, 'Docs');
        $this->addRelation('Contract', 'AppBundle\\Model\\Contract', RelationMap::ONE_TO_MANY, array('id' => 'month_id', ), 'CASCADE', null, 'Contracts');
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
  'use_scope' => 'false',
  'scope_column' => '',
),
        );
    } // getBehaviors()

} // MonthTableMap
