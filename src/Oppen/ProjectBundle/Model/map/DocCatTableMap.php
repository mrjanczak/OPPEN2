<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'doc_cat' table.
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
class DocCatTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.DocCatTableMap';

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
        $this->setName('doc_cat');
        $this->setPhpName('DocCat');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\DocCat');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 100, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('symbol', 'Symbol', 'VARCHAR', false, 10, null);
        $this->addColumn('doc_no_tmp', 'DocNoTmp', 'VARCHAR', false, 20, null);
        $this->addColumn('as_income', 'AsIncome', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_cost', 'AsCost', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_bill', 'AsBill', 'BOOLEAN', false, 1, false);
        $this->addColumn('is_locked', 'IsLocked', 'BOOLEAN', false, 1, false);
        $this->addForeignKey('year_id', 'YearId', 'INTEGER', 'year', 'id', false, null, null);
        $this->addForeignKey('file_cat_id', 'FileCatId', 'INTEGER', 'file_cat', 'id', false, null, null);
        $this->addForeignKey('commitment_acc_id', 'CommitmentAccId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addForeignKey('tax_commitment_acc_id', 'TaxCommitmentAccId', 'INTEGER', 'account', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Year', 'Oppen\\ProjectBundle\\Model\\Year', RelationMap::MANY_TO_ONE, array('year_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('FileCat', 'Oppen\\ProjectBundle\\Model\\FileCat', RelationMap::MANY_TO_ONE, array('file_cat_id' => 'id', ), null, null);
        $this->addRelation('CommitmentAcc', 'Oppen\\ProjectBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('commitment_acc_id' => 'id', ), null, null);
        $this->addRelation('TaxCommitmentAcc', 'Oppen\\ProjectBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('tax_commitment_acc_id' => 'id', ), null, null);
        $this->addRelation('Doc', 'Oppen\\ProjectBundle\\Model\\Doc', RelationMap::ONE_TO_MANY, array('id' => 'doc_cat_id', ), 'CASCADE', null, 'Docs');
    } // buildRelations()

} // DocCatTableMap
