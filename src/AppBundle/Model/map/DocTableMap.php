<?php

namespace AppBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'doc' table.
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
class DocTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.AppBundle.Model.map.DocTableMap';

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
        $this->setName('doc');
        $this->setPhpName('Doc');
        $this->setClassname('AppBundle\\Model\\Doc');
        $this->setPackage('src.AppBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('document_date', 'DocumentDate', 'DATE', false, null, null);
        $this->addColumn('operation_date', 'OperationDate', 'DATE', false, null, null);
        $this->addColumn('receipt_date', 'ReceiptDate', 'DATE', false, null, null);
        $this->addColumn('bookking_date', 'BookkingDate', 'DATE', false, null, null);
        $this->addColumn('payment_deadline_date', 'PaymentDeadlineDate', 'DATE', false, null, null);
        $this->addColumn('payment_date', 'PaymentDate', 'DATE', false, null, null);
        $this->addColumn('payment_method', 'PaymentMethod', 'INTEGER', false, null, null);
        $this->addForeignKey('month_id', 'MonthId', 'INTEGER', 'month', 'id', false, null, null);
        $this->addForeignKey('doc_cat_id', 'DocCatId', 'INTEGER', 'doc_cat', 'id', false, null, null);
        $this->addColumn('reg_idx', 'RegIdx', 'INTEGER', false, null, null);
        $this->addColumn('reg_no', 'RegNo', 'VARCHAR', false, 20, null);
        $this->addColumn('doc_idx', 'DocIdx', 'INTEGER', false, null, null);
        $this->addColumn('doc_no', 'DocNo', 'VARCHAR', false, 20, null);
        $this->getColumn('doc_no', false)->setPrimaryString(true);
        $this->addForeignKey('file_id', 'FileId', 'INTEGER', 'file', 'id', false, null, null);
        $this->addForeignKey('user_id', 'UserId', 'INTEGER', 'fos_user', 'id', false, null, null);
        $this->addColumn('desc', 'Desc', 'VARCHAR', false, 500, null);
        $this->addColumn('comment', 'Comment', 'LONGVARCHAR', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Month', 'AppBundle\\Model\\Month', RelationMap::MANY_TO_ONE, array('month_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('DocCat', 'AppBundle\\Model\\DocCat', RelationMap::MANY_TO_ONE, array('doc_cat_id' => 'id', ), 'CASCADE', null);
        $this->addRelation('File', 'AppBundle\\Model\\File', RelationMap::MANY_TO_ONE, array('file_id' => 'id', ), null, null);
        $this->addRelation('User', 'FOS\\UserBundle\\Propel\\User', RelationMap::MANY_TO_ONE, array('user_id' => 'id', ), null, null);
        $this->addRelation('Bookk', 'AppBundle\\Model\\Bookk', RelationMap::ONE_TO_MANY, array('id' => 'doc_id', ), 'CASCADE', null, 'Bookks');
        $this->addRelation('IncomeDoc', 'AppBundle\\Model\\IncomeDoc', RelationMap::ONE_TO_MANY, array('id' => 'doc_id', ), 'CASCADE', null, 'IncomeDocs');
        $this->addRelation('CostDoc', 'AppBundle\\Model\\CostDoc', RelationMap::ONE_TO_MANY, array('id' => 'doc_id', ), 'CASCADE', null, 'CostDocs');
        $this->addRelation('Contract', 'AppBundle\\Model\\Contract', RelationMap::ONE_TO_MANY, array('id' => 'doc_id', ), null, null, 'Contracts');
    } // buildRelations()

} // DocTableMap
