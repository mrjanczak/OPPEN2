<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'income_doc' table.
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
class IncomeDocTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.IncomeDocTableMap';

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
        $this->setName('income_doc');
        $this->setPhpName('IncomeDoc');
        $this->setClassname('AppBundle\\Model\\IncomeDoc');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('value', 'Value', 'FLOAT', false, null, null);
        $this->addColumn('desc', 'Desc', 'VARCHAR', false, 500, null);
        $this->addForeignKey('income_id', 'IncomeId', 'INTEGER', 'income', 'id', false, null, null);
        $this->addForeignKey('doc_id', 'DocId', 'INTEGER', 'doc', 'id', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Income', 'AppBundle\\Model\\Income', RelationMap::MANY_TO_ONE, array('income_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('Doc', 'AppBundle\\Model\\Doc', RelationMap::MANY_TO_ONE, array('doc_id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

} // IncomeDocTableMap
