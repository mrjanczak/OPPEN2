<?php

namespace Oppen\ProjectBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'temp_contract' table.
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
class TempContractTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Oppen.ProjectBundle.Model.map.TempContractTableMap';

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
        $this->setName('temp_contract');
        $this->setPhpName('TempContract');
        $this->setClassname('Oppen\\ProjectBundle\\Model\\TempContract');
        $this->setPackage('src.Oppen.ProjectBundle.Model');
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
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', false, 50, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', false, 50, null);
        $this->addColumn('PESEL', 'Pesel', 'VARCHAR', false, 50, null);
        $this->addColumn('NIP', 'Nip', 'VARCHAR', false, 50, null);
        $this->addColumn('street', 'Street', 'VARCHAR', false, 50, null);
        $this->addColumn('house', 'House', 'VARCHAR', false, 5, null);
        $this->addColumn('flat', 'Flat', 'VARCHAR', false, 5, null);
        $this->addColumn('code', 'Code', 'VARCHAR', false, 6, null);
        $this->addColumn('city', 'City', 'VARCHAR', false, 50, null);
        $this->addColumn('district', 'District', 'VARCHAR', false, 50, null);
        $this->addColumn('country', 'Country', 'VARCHAR', false, 50, null);
        $this->addColumn('bank_account', 'BankAccount', 'VARCHAR', false, 32, null);
        $this->addColumn('bank_name', 'BankName', 'VARCHAR', false, 50, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // TempContractTableMap
