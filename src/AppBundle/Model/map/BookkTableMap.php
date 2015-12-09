<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'bookk' table.
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
class BookkTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.BookkTableMap';

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
        $this->setName('bookk');
        $this->setPhpName('Bookk');
        $this->setClassname('AppBundle\\Model\\Bookk');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('no', 'No', 'INTEGER', false, null, null);
        $this->addColumn('desc', 'Desc', 'VARCHAR', false, 500, null);
        $this->getColumn('desc', false)->setPrimaryString(true);
        $this->addColumn('is_accepted', 'IsAccepted', 'BOOLEAN', false, 1, false);
        $this->addColumn('bookking_date', 'BookkingDate', 'DATE', false, null, null);
        $this->addForeignKey('doc_id', 'DocId', 'INTEGER', 'doc', 'id', false, null, null);
        $this->addForeignKey('project_id', 'ProjectId', 'INTEGER', 'project', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Doc', 'AppBundle\\Model\\Doc', RelationMap::MANY_TO_ONE, array('doc_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Project', 'AppBundle\\Model\\Project', RelationMap::MANY_TO_ONE, array('project_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('BookkEntry', 'AppBundle\\Model\\BookkEntry', RelationMap::ONE_TO_MANY, array('id' => 'bookk_id', ), 'CASCADE', null, 'BookkEntries');
    } // buildRelations()

} // BookkTableMap
