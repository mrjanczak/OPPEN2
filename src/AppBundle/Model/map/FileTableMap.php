<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'file' table.
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
class FileTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.FileTableMap';

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
        $this->setName('file');
        $this->setPhpName('File');
        $this->setClassname('AppBundle\\Model\\File');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('acc_no', 'AccNo', 'INTEGER', false, null, null);
        $this->addForeignKey('file_cat_id', 'FileCatId', 'INTEGER', 'file_cat', 'id', false, null, null);
        $this->addForeignKey('sub_file_id', 'SubFileId', 'INTEGER', 'file', 'id', false, null, null);
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', false, 50, null);
        $this->addColumn('second_name', 'SecondName', 'VARCHAR', false, 50, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', false, 50, null);
        $this->addColumn('maiden_name', 'MaidenName', 'VARCHAR', false, 50, null);
        $this->addColumn('father_name', 'FatherName', 'VARCHAR', false, 50, null);
        $this->addColumn('mother_name', 'MotherName', 'VARCHAR', false, 50, null);
        $this->addColumn('birth_date', 'BirthDate', 'DATE', false, null, null);
        $this->addColumn('birth_place', 'BirthPlace', 'VARCHAR', false, 50, null);
        $this->addColumn('PESEL', 'Pesel', 'VARCHAR', false, 50, null);
        $this->addColumn('Passport', 'Passport', 'VARCHAR', false, 50, null);
        $this->addColumn('NIP', 'Nip', 'VARCHAR', false, 50, null);
        $this->addColumn('profession', 'Profession', 'VARCHAR', false, 50, null);
        $this->addColumn('street', 'Street', 'VARCHAR', false, 50, null);
        $this->addColumn('house', 'House', 'VARCHAR', false, 5, null);
        $this->addColumn('flat', 'Flat', 'VARCHAR', false, 5, null);
        $this->addColumn('code', 'Code', 'VARCHAR', false, 6, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 50, null);
        $this->addColumn('district2', 'District2', 'VARCHAR', false, 50, null);
        $this->addColumn('district', 'District', 'VARCHAR', false, 50, null);
        $this->addColumn('province', 'Province', 'VARCHAR', false, 50, null);
        $this->addColumn('country', 'Country', 'VARCHAR', false, 50, null);
        $this->addColumn('post_office', 'PostOffice', 'VARCHAR', false, 50, null);
        $this->addColumn('bank_account', 'BankAccount', 'VARCHAR', false, 100, null);
        $this->addColumn('bank_name', 'BankName', 'VARCHAR', false, 50, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 50, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 50, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('FileCat', 'AppBundle\\Model\\FileCat', RelationMap::MANY_TO_ONE, array('file_cat_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('SubFile', 'AppBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('sub_file_id' => 'id', ), null, null);
        $this->addRelation('File', 'AppBundle\\Model\\File', RelationMap::ONE_TO_MANY, array('id' => 'sub_file_id', ), null, null, 'Files');
        $this->addRelation('Doc', 'AppBundle\\Model\\Doc', RelationMap::ONE_TO_MANY, array('id' => 'file_id', ), null, null, 'Docs');
        $this->addRelation('BookkEntryRelatedByFileLev1Id', 'AppBundle\\Model\\BookkEntry', RelationMap::ONE_TO_MANY, array('id' => 'file_lev1_id', ), null, null, 'BookkEntriesRelatedByFileLev1Id');
        $this->addRelation('BookkEntryRelatedByFileLev2Id', 'AppBundle\\Model\\BookkEntry', RelationMap::ONE_TO_MANY, array('id' => 'file_lev2_id', ), null, null, 'BookkEntriesRelatedByFileLev2Id');
        $this->addRelation('BookkEntryRelatedByFileLev3Id', 'AppBundle\\Model\\BookkEntry', RelationMap::ONE_TO_MANY, array('id' => 'file_lev3_id', ), null, null, 'BookkEntriesRelatedByFileLev3Id');
        $this->addRelation('Project', 'AppBundle\\Model\\Project', RelationMap::ONE_TO_MANY, array('id' => 'file_id', ), null, null, 'Projects');
        $this->addRelation('Income', 'AppBundle\\Model\\Income', RelationMap::ONE_TO_MANY, array('id' => 'file_id', ), null, null, 'Incomes');
        $this->addRelation('Cost', 'AppBundle\\Model\\Cost', RelationMap::ONE_TO_MANY, array('id' => 'file_id', ), null, null, 'Costs');
        $this->addRelation('Contract', 'AppBundle\\Model\\Contract', RelationMap::ONE_TO_MANY, array('id' => 'file_id', ), null, null, 'Contracts');
    } // buildRelations()

} // FileTableMap
