<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cost_income' table.
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
class CostIncomeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.CostIncomeTableMap';

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
        $this->setName('cost_income');
        $this->setPhpName('CostIncome');
        $this->setClassname('AppBundle\\Model\\CostIncome');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('value', 'Value', 'FLOAT', false, null, null);
        $this->addForeignKey('cost_id', 'CostId', 'INTEGER', 'cost', 'id', false, null, null);
        $this->addForeignKey('income_id', 'IncomeId', 'INTEGER', 'income', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Cost', 'AppBundle\\Model\\Cost', RelationMap::MANY_TO_ONE, array('cost_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Income', 'AppBundle\\Model\\Income', RelationMap::MANY_TO_ONE, array('income_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // CostIncomeTableMap
