<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'account' table.
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
class AccountTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.AccountTableMap';

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
        $this->setName('account');
        $this->setPhpName('Account');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\Account');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('acc_no', 'AccNo', 'VARCHAR', false, 100, null);
        $this->getColumn('acc_no', false)->setPrimaryString(true);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 100, null);
        $this->addColumn('report_side', 'ReportSide', 'TINYINT', false, null, null);
        $this->addColumn('as_bank_acc', 'AsBankAcc', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_income', 'AsIncome', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_cost', 'AsCost', 'BOOLEAN', false, 1, false);
        $this->addColumn('inc_open_b', 'IncOpenB', 'BOOLEAN', false, 1, false);
        $this->addColumn('inc_close_b', 'IncCloseB', 'BOOLEAN', false, 1, false);
        $this->addColumn('as_close_b', 'AsCloseB', 'BOOLEAN', false, 1, false);
        $this->addForeignKey('year_id', 'YearId', 'INTEGER', 'year', 'id', false, null, null);
        $this->addForeignKey('file_cat_lev1_id', 'FileCatLev1Id', 'INTEGER', 'file_cat', 'id', false, null, null);
        $this->addForeignKey('file_cat_lev2_id', 'FileCatLev2Id', 'INTEGER', 'file_cat', 'id', false, null, null);
        $this->addForeignKey('file_cat_lev3_id', 'FileCatLev3Id', 'INTEGER', 'file_cat', 'id', false, null, null);
        $this->addColumn('tree_left', 'TreeLeft', 'INTEGER', false, null, null);
        $this->addColumn('tree_right', 'TreeRight', 'INTEGER', false, null, null);
        $this->addColumn('tree_level', 'TreeLevel', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Year', 'Oppen\\ProjectBundle\\Model\\Year', RelationMap::MANY_TO_ONE, array('year_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('FileCatLev1', 'Oppen\\ProjectBundle\\Model\\FileCat', RelationMap::MANY_TO_ONE, array('file_cat_lev1_id' => 'id', ), null, null);
        $this->addRelation('FileCatLev2', 'Oppen\\ProjectBundle\\Model\\FileCat', RelationMap::MANY_TO_ONE, array('file_cat_lev2_id' => 'id', ), null, null);
        $this->addRelation('FileCatLev3', 'Oppen\\ProjectBundle\\Model\\FileCat', RelationMap::MANY_TO_ONE, array('file_cat_lev3_id' => 'id', ), null, null);
        $this->addRelation('DocCatRelatedByCommitmentAccId', 'Oppen\\ProjectBundle\\Model\\DocCat', RelationMap::ONE_TO_MANY, array('id' => 'commitment_acc_id', ), null, null, 'DocCatsRelatedByCommitmentAccId');
        $this->addRelation('DocCatRelatedByTaxCommitmentAccId', 'Oppen\\ProjectBundle\\Model\\DocCat', RelationMap::ONE_TO_MANY, array('id' => 'tax_commitment_acc_id', ), null, null, 'DocCatsRelatedByTaxCommitmentAccId');
        $this->addRelation('BookkEntry', 'Oppen\\ProjectBundle\\Model\\BookkEntry', RelationMap::ONE_TO_MANY, array('id' => 'account_id', ), null, null, 'BookkEntries');
        $this->addRelation('ProjectRelatedByIncomeAccId', 'Oppen\\ProjectBundle\\Model\\Project', RelationMap::ONE_TO_MANY, array('id' => 'income_acc_id', ), null, null, 'ProjectsRelatedByIncomeAccId');
        $this->addRelation('ProjectRelatedByCostAccId', 'Oppen\\ProjectBundle\\Model\\Project', RelationMap::ONE_TO_MANY, array('id' => 'cost_acc_id', ), null, null, 'ProjectsRelatedByCostAccId');
        $this->addRelation('ProjectRelatedByBankAccId', 'Oppen\\ProjectBundle\\Model\\Project', RelationMap::ONE_TO_MANY, array('id' => 'bank_acc_id', ), null, null, 'ProjectsRelatedByBankAccId');
        $this->addRelation('IncomeRelatedByBankAccId', 'Oppen\\ProjectBundle\\Model\\Income', RelationMap::ONE_TO_MANY, array('id' => 'bank_acc_id', ), null, null, 'IncomesRelatedByBankAccId');
        $this->addRelation('IncomeRelatedByIncomeAccId', 'Oppen\\ProjectBundle\\Model\\Income', RelationMap::ONE_TO_MANY, array('id' => 'income_acc_id', ), null, null, 'IncomesRelatedByIncomeAccId');
        $this->addRelation('CostRelatedByBankAccId', 'Oppen\\ProjectBundle\\Model\\Cost', RelationMap::ONE_TO_MANY, array('id' => 'bank_acc_id', ), null, null, 'CostsRelatedByBankAccId');
        $this->addRelation('CostRelatedByCostAccId', 'Oppen\\ProjectBundle\\Model\\Cost', RelationMap::ONE_TO_MANY, array('id' => 'cost_acc_id', ), null, null, 'CostsRelatedByCostAccId');
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
            'nested_set' =>  array (
  'left_column' => 'tree_left',
  'right_column' => 'tree_right',
  'level_column' => 'tree_level',
  'use_scope' => 'true',
  'scope_column' => 'year_id',
  'method_proxies' => 'false',
),
        );
    } // getBehaviors()

} // AccountTableMap
