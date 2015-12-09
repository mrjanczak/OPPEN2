<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'contract' table.
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
class ContractTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.ContractTableMap';

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
        $this->setName('contract');
        $this->setPhpName('Contract');
        $this->setClassname('AppBundle\\Model\\Contract');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('contract_no', 'ContractNo', 'VARCHAR', false, 20, null);
        $this->addColumn('contract_date', 'ContractDate', 'DATE', false, null, null);
        $this->addColumn('contract_place', 'ContractPlace', 'VARCHAR', false, 20, null);
        $this->addColumn('event_desc', 'EventDesc', 'VARCHAR', false, 1000, null);
        $this->addColumn('event_date', 'EventDate', 'DATE', false, null, null);
        $this->addColumn('event_place', 'EventPlace', 'VARCHAR', false, 100, null);
        $this->addColumn('event_name', 'EventName', 'VARCHAR', false, 100, null);
        $this->addColumn('event_role', 'EventRole', 'VARCHAR', false, 100, null);
        $this->addColumn('gross', 'Gross', 'FLOAT', false, null, null);
        $this->addColumn('income_cost', 'IncomeCost', 'FLOAT', false, null, null);
        $this->addColumn('tax', 'Tax', 'FLOAT', false, null, null);
        $this->addColumn('netto', 'Netto', 'FLOAT', false, null, null);
        $this->addColumn('tax_coef', 'TaxCoef', 'FLOAT', false, null, null);
        $this->addColumn('cost_coef', 'CostCoef', 'FLOAT', false, null, null);
        $this->addColumn('payment_period', 'PaymentPeriod', 'VARCHAR', false, 16, null);
        $this->addColumn('payment_method', 'PaymentMethod', 'INTEGER', false, null, null);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', false, null, null);
        $this->addForeignKey('cost_id', 'CostId', 'INTEGER', 'cost', 'id', false, null, null);
        $this->addForeignKey('template_id', 'TemplateId', 'INTEGER', 'template', 'id', false, null, null);
        $this->addForeignKey('file_id', 'FileId', 'INTEGER', 'file', 'id', false, null, null);
        $this->addForeignKey('doc_id', 'DocId', 'INTEGER', 'doc', 'id', false, null, null);
        $this->addForeignKey('month_id', 'MonthId', 'INTEGER', 'month', 'id', false, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Cost', 'AppBundle\\Model\\Cost', RelationMap::MANY_TO_ONE, array('cost_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Template', 'AppBundle\\Model\\Template', RelationMap::MANY_TO_ONE, array('template_id' => 'id', ), null, null);
        $this->addRelation('File', 'AppBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('file_id' => 'id', ), null, null);
        $this->addRelation('Doc', 'AppBundle\\Model\\Doc', RelationMap::MANY_TO_ONE, array('doc_id' => 'id', ), null, null);
        $this->addRelation('Month', 'AppBundle\\Model\\Month', RelationMap::MANY_TO_ONE, array('month_id' => 'id', ), 'CASCADE', null);
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
  'scope_column' => 'cost_id',
),
        );
    } // getBehaviors()

} // ContractTableMap
