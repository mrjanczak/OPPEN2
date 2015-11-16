<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'template' table.
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
class TemplateTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.TemplateTableMap';

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
        $this->setName('template');
        $this->setPhpName('Template');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\Template');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('symbol', 'Symbol', 'VARCHAR', false, 100, null);
        $this->getColumn('symbol', false)->setPrimaryString(true);
        $this->addColumn('type', 'Type', 'INTEGER', false, null, null);
        $this->addColumn('as_contract', 'AsContract', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_report', 'AsReport', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_booking', 'AsBooking', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_transfer', 'AsTransfer', 'BOOLEAN', false, 1, false);
        $this->addColumn('contents', 'Contents', 'LONGVARCHAR', false, null, null);
        $this->addColumn('data', 'Data', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Report', 'Oppen\\ProjectBundle\\Model\\Report', RelationMap::ONE_TO_MANY, array('id' => 'template_id', ), null, null, 'Reports');
        $this->addRelation('Contract', 'Oppen\\ProjectBundle\\Model\\Contract', RelationMap::ONE_TO_MANY, array('id' => 'template_id', ), null, null, 'Contracts');
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
            'archivable' =>  array (
  'archive_table' => '',
  'archive_phpname' => NULL,
  'archive_class' => '',
  'log_archived_at' => 'true',
  'archived_at_column' => 'archived_at',
  'archive_on_insert' => 'false',
  'archive_on_update' => 'false',
  'archive_on_delete' => 'true',
),
        );
    } // getBehaviors()

} // TemplateTableMap
