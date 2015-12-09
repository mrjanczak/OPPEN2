<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'template_archive' table.
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
class TemplateArchiveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.TemplateArchiveTableMap';

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
        $this->setName('template_archive');
        $this->setPhpName('TemplateArchive');
        $this->setClassname('AppBundle\\Model\\TemplateArchive');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(false);
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
        $this->addColumn('archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // TemplateArchiveTableMap
