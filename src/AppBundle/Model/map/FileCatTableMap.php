<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'file_cat' table.
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
class FileCatTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.FileCatTableMap';

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
        $this->setName('file_cat');
        $this->setPhpName('FileCat');
        $this->setClassname('AppBundle\\Model\\FileCat');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('symbol', 'Symbol', 'VARCHAR', false, 10, null);
        $this->addColumn('as_project', 'AsProject', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_income', 'AsIncome', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_cost', 'AsCost', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_contractor', 'AsContractor', 'BOOLEAN', false, 1, false);
        $this->addColumn('is_locked', 'IsLocked', 'BOOLEAN', false, 1, false);
        $this->addForeignKey('year_id', 'YearId', 'INTEGER', 'year', 'id', false, null, null);
        $this->addForeignKey('sub_file_cat_id', 'SubFileCatId', 'INTEGER', 'file_cat', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Year', 'AppBundle\\Model\\Year', RelationMap::MANY_TO_ONE, array('year_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('SubFileCat', 'AppBundle\\Model\\FileCat', RelationMap::MANY_TO_ONE, array('sub_file_cat_id' => 'id', ), null, null);
        $this->addRelation('FileCat', 'AppBundle\\Model\\FileCat', RelationMap::ONE_TO_MANY, array('id' => 'sub_file_cat_id', ), null, null, 'FileCats');
        $this->addRelation('File', 'AppBundle\\Model\\File', RelationMap::ONE_TO_MANY, array('id' => 'file_cat_id', ), 'CASCADE', null, 'Files');
        $this->addRelation('DocCat', 'AppBundle\\Model\\DocCat', RelationMap::ONE_TO_MANY, array('id' => 'file_cat_id', ), null, null, 'DocCats');
        $this->addRelation('AccountRelatedByFileCatLev1Id', 'AppBundle\\Model\\Account', RelationMap::ONE_TO_MANY, array('id' => 'file_cat_lev1_id', ), null, null, 'AccountsRelatedByFileCatLev1Id');
        $this->addRelation('AccountRelatedByFileCatLev2Id', 'AppBundle\\Model\\Account', RelationMap::ONE_TO_MANY, array('id' => 'file_cat_lev2_id', ), null, null, 'AccountsRelatedByFileCatLev2Id');
        $this->addRelation('AccountRelatedByFileCatLev3Id', 'AppBundle\\Model\\Account', RelationMap::ONE_TO_MANY, array('id' => 'file_cat_lev3_id', ), null, null, 'AccountsRelatedByFileCatLev3Id');
        $this->addRelation('Project', 'AppBundle\\Model\\Project', RelationMap::ONE_TO_MANY, array('id' => 'file_cat_id', ), null, null, 'Projects');
    } // buildRelations()

} // FileCatTableMap
