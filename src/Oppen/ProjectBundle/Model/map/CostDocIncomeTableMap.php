<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cost_doc_income' table.
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
class CostDocIncomeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.CostDocIncomeTableMap';

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
        $this->setName('cost_doc_income');
        $this->setPhpName('CostDocIncome');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\CostDocIncome');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('value', 'Value', 'FLOAT', false, null, null);
        $this->addForeignKey('cost_doc_id', 'CostDocId', 'INTEGER', 'cost_doc', 'id', false, null, null);
        $this->addForeignKey('income_id', 'IncomeId', 'INTEGER', 'income', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('CostDoc', 'Oppen\\ProjectBundle\\Model\\CostDoc', RelationMap::MANY_TO_ONE, array('cost_doc_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Income', 'Oppen\\ProjectBundle\\Model\\Income', RelationMap::MANY_TO_ONE, array('income_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // CostDocIncomeTableMap
