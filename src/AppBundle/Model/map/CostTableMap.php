<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cost' table.
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
class CostTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.CostTableMap';

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
        $this->setName('cost');
        $this->setPhpName('Cost');
        $this->setClassname('AppBundle\\Model\\Cost');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 100, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('value', 'Value', 'FLOAT', false, null, null);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('project_id', 'ProjectId', 'INTEGER', 'project', 'id', false, null, null);
        $this->addForeignKey('file_id', 'FileId', 'INTEGER', 'file', 'id', false, null, null);
        $this->addForeignKey('bank_acc_id', 'BankAccId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addForeignKey('cost_acc_id', 'CostAccId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Project', 'AppBundle\\Model\\Project', RelationMap::MANY_TO_ONE, array('project_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('File', 'AppBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('file_id' => 'id', ), null, null);
        $this->addRelation('BankAcc', 'AppBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('bank_acc_id' => 'id', ), null, null);
        $this->addRelation('CostAcc', 'AppBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('cost_acc_id' => 'id', ), null, null);
        $this->addRelation('CostIncome', 'AppBundle\\Model\\CostIncome', RelationMap::ONE_TO_MANY, array('id' => 'cost_id', ), 'CASCADE', null, 'CostIncomes');
        $this->addRelation('CostDoc', 'AppBundle\\Model\\CostDoc', RelationMap::ONE_TO_MANY, array('id' => 'cost_id', ), 'CASCADE', null, 'CostDocs');
        $this->addRelation('Contract', 'AppBundle\\Model\\Contract', RelationMap::ONE_TO_MANY, array('id' => 'cost_id', ), 'CASCADE', null, 'Contracts');
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
  'scope_column' => 'project_id',
),
        );
    } // getBehaviors()

} // CostTableMap
