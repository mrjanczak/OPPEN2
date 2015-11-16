<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'project' table.
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
class ProjectTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.ProjectTableMap';

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
        $this->setName('project');
        $this->setPhpName('Project');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\Project');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('name', 'Name', 'VARCHAR', true, 250, null);
        $this->getColumn('name', false)->setPrimaryString(true);
        $this->addColumn('status', 'Status', 'INTEGER', false, null, null);
        $this->addColumn('desc', 'Desc', 'LONGVARCHAR', false, null, null);
        $this->addColumn('place', 'Place', 'VARCHAR', false, 100, null);
        $this->addColumn('from_date', 'FromDate', 'DATE', false, null, null);
        $this->addColumn('to_date', 'ToDate', 'DATE', false, null, null);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('year_id', 'YearId', 'INTEGER', 'year', 'id', false, null, null);
        $this->addForeignKey('file_id', 'FileId', 'INTEGER', 'file', 'id', false, null, null);
        $this->addForeignKey('file_cat_id', 'FileCatId', 'INTEGER', 'file_cat', 'id', false, null, null);
        $this->addForeignKey('income_acc_id', 'IncomeAccId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addForeignKey('cost_acc_id', 'CostAccId', 'INTEGER', 'account', 'id', false, null, null);
        $this->addForeignKey('bank_acc_id', 'BankAccId', 'INTEGER', 'account', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Year', 'Oppen\\ProjectBundle\\Model\\Year', RelationMap::MANY_TO_ONE, array('year_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('File', 'Oppen\\ProjectBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('file_id' => 'id', ), null, null);
        $this->addRelation('CostFileCat', 'Oppen\\ProjectBundle\\Model\\FileCat', RelationMap::MANY_TO_ONE, array('file_cat_id' => 'id', ), null, null);
        $this->addRelation('IncomeAcc', 'Oppen\\ProjectBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('income_acc_id' => 'id', ), null, null);
        $this->addRelation('CostAcc', 'Oppen\\ProjectBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('cost_acc_id' => 'id', ), null, null);
        $this->addRelation('BankAcc', 'Oppen\\ProjectBundle\\Model\\Account', RelationMap::MANY_TO_ONE, array('bank_acc_id' => 'id', ), null, null);
        $this->addRelation('Bookk', 'Oppen\\ProjectBundle\\Model\\Bookk', RelationMap::ONE_TO_MANY, array('id' => 'project_id', ), 'CASCADE', null, 'Bookks');
        $this->addRelation('Income', 'Oppen\\ProjectBundle\\Model\\Income', RelationMap::ONE_TO_MANY, array('id' => 'project_id', ), 'CASCADE', null, 'Incomes');
        $this->addRelation('Cost', 'Oppen\\ProjectBundle\\Model\\Cost', RelationMap::ONE_TO_MANY, array('id' => 'project_id', ), 'CASCADE', null, 'Costs');
        $this->addRelation('Task', 'Oppen\\ProjectBundle\\Model\\Task', RelationMap::ONE_TO_MANY, array('id' => 'project_id', ), 'CASCADE', null, 'Tasks');
    } // buildRelations()

} // ProjectTableMap
