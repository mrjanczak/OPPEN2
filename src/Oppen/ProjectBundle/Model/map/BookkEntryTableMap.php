<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'bookk_entry' table.
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
class BookkEntryTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.BookkEntryTableMap';

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
        $this->setName('bookk_entry');
        $this->setPhpName('BookkEntry');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\BookkEntry');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('acc_no', 'AccNo', 'VARCHAR', false, 20, null);
        $this->addColumn('value', 'Value', 'FLOAT', false, null, null);
        $this->addColumn('side', 'Side', 'TINYINT', false, null, null);
        $this->addForeignKey('bookk_id', 'BookkId', 'INTEGER', 'bookk', 'id', false, null, null);
        $this->addForeignKey('account_id', 'AccountId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addForeignKey('file_lev1_id', 'FileLev1Id', 'INTEGER', 'file', 'id', false, null, null);
        $this->addForeignKey('file_lev2_id', 'FileLev2Id', 'INTEGER', 'file', 'id', false, null, null);
        $this->addForeignKey('file_lev3_id', 'FileLev3Id', 'INTEGER', 'file', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Bookk', 'Oppen\\ProjectBundle\\Model\\Bookk', RelationMap::MANY_TO_ONE, array('bookk_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Account', 'Oppen\\ProjectBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('account_id' => 'id', ), null, null);
        $this->addRelation('FileLev1', 'Oppen\\ProjectBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('file_lev1_id' => 'id', ), null, null);
        $this->addRelation('FileLev2', 'Oppen\\ProjectBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('file_lev2_id' => 'id', ), null, null);
        $this->addRelation('FileLev3', 'Oppen\\ProjectBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('file_lev3_id' => 'id', ), null, null);
    } // buildRelations()

} // BookkEntryTableMap
