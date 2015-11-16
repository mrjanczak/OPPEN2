<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'cost_doc' table.
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
class CostDocTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.CostDocTableMap';

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
        $this->setName('cost_doc');
        $this->setPhpName('CostDoc');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\CostDoc');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('value', 'Value', 'FLOAT', false, null, null);
        $this->addColumn('desc', 'Desc', 'VARCHAR', false, 500, null);
        $this->addForeignKey('cost_id', 'CostId', 'INTEGER', 'cost', 'id', false, null, null);
        $this->addForeignKey('doc_id', 'DocId', 'INTEGER', 'doc', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Cost', 'Oppen\\ProjectBundle\\Model\\Cost', RelationMap::MANY_TO_ONE, array('cost_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Doc', 'Oppen\\ProjectBundle\\Model\\Doc', RelationMap::MANY_TO_ONE, array('doc_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('CostDocIncome', 'Oppen\\ProjectBundle\\Model\\CostDocIncome', RelationMap::ONE_TO_MANY, array('id' => 'cost_doc_id', ), 'CASCADE', null, 'CostDocIncomes');
    } // buildRelations()

} // CostDocTableMap
