<?php

namespace Oppen\ProjectBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Oppen\ProjectBundle\Model\TempContract;
use Oppen\ProjectBundle\Model\TempContractPeer;
use Oppen\ProjectBundle\Model\map\TempContractTableMap;

abstract class BaseTempContractPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'temp_contract';

    /** the related Propel class for this table */
    const OM_CLASS = 'Oppen\\ProjectBundle\\Model\\TempContract';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Oppen\\ProjectBundle\\Model\\map\\TempContractTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 26;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 26;

    /** the column name for the id field */
    const ID = 'temp_contract.id';

    /** the column name for the contract_no field */
    const CONTRACT_NO = 'temp_contract.contract_no';

    /** the column name for the contract_date field */
    const CONTRACT_DATE = 'temp_contract.contract_date';

    /** the column name for the contract_place field */
    const CONTRACT_PLACE = 'temp_contract.contract_place';

    /** the column name for the event_desc field */
    const EVENT_DESC = 'temp_contract.event_desc';

    /** the column name for the event_date field */
    const EVENT_DATE = 'temp_contract.event_date';

    /** the column name for the event_place field */
    const EVENT_PLACE = 'temp_contract.event_place';

    /** the column name for the event_name field */
    const EVENT_NAME = 'temp_contract.event_name';

    /** the column name for the event_role field */
    const EVENT_ROLE = 'temp_contract.event_role';

    /** the column name for the gross field */
    const GROSS = 'temp_contract.gross';

    /** the column name for the income_cost field */
    const INCOME_COST = 'temp_contract.income_cost';

    /** the column name for the tax field */
    const TAX = 'temp_contract.tax';

    /** the column name for the netto field */
    const NETTO = 'temp_contract.netto';

    /** the column name for the first_name field */
    const FIRST_NAME = 'temp_contract.first_name';

    /** the column name for the last_name field */
    const LAST_NAME = 'temp_contract.last_name';

    /** the column name for the PESEL field */
    const PESEL = 'temp_contract.PESEL';

    /** the column name for the NIP field */
    const NIP = 'temp_contract.NIP';

    /** the column name for the street field */
    const STREET = 'temp_contract.street';

    /** the column name for the house field */
    const HOUSE = 'temp_contract.house';

    /** the column name for the flat field */
    const FLAT = 'temp_contract.flat';

    /** the column name for the code field */
    const CODE = 'temp_contract.code';

    /** the column name for the city field */
    const CITY = 'temp_contract.city';

    /** the column name for the district field */
    const DISTRICT = 'temp_contract.district';

    /** the column name for the country field */
    const COUNTRY = 'temp_contract.country';

    /** the column name for the bank_account field */
    const BANK_ACCOUNT = 'temp_contract.bank_account';

    /** the column name for the bank_name field */
    const BANK_NAME = 'temp_contract.bank_name';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of TempContract objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array TempContract[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. TempContractPeer::$fieldNames[TempContractPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'ContractNo', 'ContractDate', 'ContractPlace', 'EventDesc', 'EventDate', 'EventPlace', 'EventName', 'EventRole', 'Gross', 'IncomeCost', 'Tax', 'Netto', 'FirstName', 'LastName', 'Pesel', 'Nip', 'Street', 'House', 'Flat', 'Code', 'City', 'District', 'Country', 'BankAccount', 'BankName', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'contractNo', 'contractDate', 'contractPlace', 'eventDesc', 'eventDate', 'eventPlace', 'eventName', 'eventRole', 'gross', 'incomeCost', 'tax', 'netto', 'firstName', 'lastName', 'pesel', 'nip', 'street', 'house', 'flat', 'code', 'city', 'district', 'country', 'bankAccount', 'bankName', ),
        BasePeer::TYPE_COLNAME => array (TempContractPeer::ID, TempContractPeer::CONTRACT_NO, TempContractPeer::CONTRACT_DATE, TempContractPeer::CONTRACT_PLACE, TempContractPeer::EVENT_DESC, TempContractPeer::EVENT_DATE, TempContractPeer::EVENT_PLACE, TempContractPeer::EVENT_NAME, TempContractPeer::EVENT_ROLE, TempContractPeer::GROSS, TempContractPeer::INCOME_COST, TempContractPeer::TAX, TempContractPeer::NETTO, TempContractPeer::FIRST_NAME, TempContractPeer::LAST_NAME, TempContractPeer::PESEL, TempContractPeer::NIP, TempContractPeer::STREET, TempContractPeer::HOUSE, TempContractPeer::FLAT, TempContractPeer::CODE, TempContractPeer::CITY, TempContractPeer::DISTRICT, TempContractPeer::COUNTRY, TempContractPeer::BANK_ACCOUNT, TempContractPeer::BANK_NAME, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'CONTRACT_NO', 'CONTRACT_DATE', 'CONTRACT_PLACE', 'EVENT_DESC', 'EVENT_DATE', 'EVENT_PLACE', 'EVENT_NAME', 'EVENT_ROLE', 'GROSS', 'INCOME_COST', 'TAX', 'NETTO', 'FIRST_NAME', 'LAST_NAME', 'PESEL', 'NIP', 'STREET', 'HOUSE', 'FLAT', 'CODE', 'CITY', 'DISTRICT', 'COUNTRY', 'BANK_ACCOUNT', 'BANK_NAME', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'contract_no', 'contract_date', 'contract_place', 'event_desc', 'event_date', 'event_place', 'event_name', 'event_role', 'gross', 'income_cost', 'tax', 'netto', 'first_name', 'last_name', 'PESEL', 'NIP', 'street', 'house', 'flat', 'code', 'city', 'district', 'country', 'bank_account', 'bank_name', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. TempContractPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'ContractNo' => 1, 'ContractDate' => 2, 'ContractPlace' => 3, 'EventDesc' => 4, 'EventDate' => 5, 'EventPlace' => 6, 'EventName' => 7, 'EventRole' => 8, 'Gross' => 9, 'IncomeCost' => 10, 'Tax' => 11, 'Netto' => 12, 'FirstName' => 13, 'LastName' => 14, 'Pesel' => 15, 'Nip' => 16, 'Street' => 17, 'House' => 18, 'Flat' => 19, 'Code' => 20, 'City' => 21, 'District' => 22, 'Country' => 23, 'BankAccount' => 24, 'BankName' => 25, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'contractNo' => 1, 'contractDate' => 2, 'contractPlace' => 3, 'eventDesc' => 4, 'eventDate' => 5, 'eventPlace' => 6, 'eventName' => 7, 'eventRole' => 8, 'gross' => 9, 'incomeCost' => 10, 'tax' => 11, 'netto' => 12, 'firstName' => 13, 'lastName' => 14, 'pesel' => 15, 'nip' => 16, 'street' => 17, 'house' => 18, 'flat' => 19, 'code' => 20, 'city' => 21, 'district' => 22, 'country' => 23, 'bankAccount' => 24, 'bankName' => 25, ),
        BasePeer::TYPE_COLNAME => array (TempContractPeer::ID => 0, TempContractPeer::CONTRACT_NO => 1, TempContractPeer::CONTRACT_DATE => 2, TempContractPeer::CONTRACT_PLACE => 3, TempContractPeer::EVENT_DESC => 4, TempContractPeer::EVENT_DATE => 5, TempContractPeer::EVENT_PLACE => 6, TempContractPeer::EVENT_NAME => 7, TempContractPeer::EVENT_ROLE => 8, TempContractPeer::GROSS => 9, TempContractPeer::INCOME_COST => 10, TempContractPeer::TAX => 11, TempContractPeer::NETTO => 12, TempContractPeer::FIRST_NAME => 13, TempContractPeer::LAST_NAME => 14, TempContractPeer::PESEL => 15, TempContractPeer::NIP => 16, TempContractPeer::STREET => 17, TempContractPeer::HOUSE => 18, TempContractPeer::FLAT => 19, TempContractPeer::CODE => 20, TempContractPeer::CITY => 21, TempContractPeer::DISTRICT => 22, TempContractPeer::COUNTRY => 23, TempContractPeer::BANK_ACCOUNT => 24, TempContractPeer::BANK_NAME => 25, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'CONTRACT_NO' => 1, 'CONTRACT_DATE' => 2, 'CONTRACT_PLACE' => 3, 'EVENT_DESC' => 4, 'EVENT_DATE' => 5, 'EVENT_PLACE' => 6, 'EVENT_NAME' => 7, 'EVENT_ROLE' => 8, 'GROSS' => 9, 'INCOME_COST' => 10, 'TAX' => 11, 'NETTO' => 12, 'FIRST_NAME' => 13, 'LAST_NAME' => 14, 'PESEL' => 15, 'NIP' => 16, 'STREET' => 17, 'HOUSE' => 18, 'FLAT' => 19, 'CODE' => 20, 'CITY' => 21, 'DISTRICT' => 22, 'COUNTRY' => 23, 'BANK_ACCOUNT' => 24, 'BANK_NAME' => 25, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'contract_no' => 1, 'contract_date' => 2, 'contract_place' => 3, 'event_desc' => 4, 'event_date' => 5, 'event_place' => 6, 'event_name' => 7, 'event_role' => 8, 'gross' => 9, 'income_cost' => 10, 'tax' => 11, 'netto' => 12, 'first_name' => 13, 'last_name' => 14, 'PESEL' => 15, 'NIP' => 16, 'street' => 17, 'house' => 18, 'flat' => 19, 'code' => 20, 'city' => 21, 'district' => 22, 'country' => 23, 'bank_account' => 24, 'bank_name' => 25, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = TempContractPeer::getFieldNames($toType);
        $key = isset(TempContractPeer::$fieldKeys[$fromType][$name]) ? TempContractPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(TempContractPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, TempContractPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return TempContractPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. TempContractPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(TempContractPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(TempContractPeer::ID);
            $criteria->addSelectColumn(TempContractPeer::CONTRACT_NO);
            $criteria->addSelectColumn(TempContractPeer::CONTRACT_DATE);
            $criteria->addSelectColumn(TempContractPeer::CONTRACT_PLACE);
            $criteria->addSelectColumn(TempContractPeer::EVENT_DESC);
            $criteria->addSelectColumn(TempContractPeer::EVENT_DATE);
            $criteria->addSelectColumn(TempContractPeer::EVENT_PLACE);
            $criteria->addSelectColumn(TempContractPeer::EVENT_NAME);
            $criteria->addSelectColumn(TempContractPeer::EVENT_ROLE);
            $criteria->addSelectColumn(TempContractPeer::GROSS);
            $criteria->addSelectColumn(TempContractPeer::INCOME_COST);
            $criteria->addSelectColumn(TempContractPeer::TAX);
            $criteria->addSelectColumn(TempContractPeer::NETTO);
            $criteria->addSelectColumn(TempContractPeer::FIRST_NAME);
            $criteria->addSelectColumn(TempContractPeer::LAST_NAME);
            $criteria->addSelectColumn(TempContractPeer::PESEL);
            $criteria->addSelectColumn(TempContractPeer::NIP);
            $criteria->addSelectColumn(TempContractPeer::STREET);
            $criteria->addSelectColumn(TempContractPeer::HOUSE);
            $criteria->addSelectColumn(TempContractPeer::FLAT);
            $criteria->addSelectColumn(TempContractPeer::CODE);
            $criteria->addSelectColumn(TempContractPeer::CITY);
            $criteria->addSelectColumn(TempContractPeer::DISTRICT);
            $criteria->addSelectColumn(TempContractPeer::COUNTRY);
            $criteria->addSelectColumn(TempContractPeer::BANK_ACCOUNT);
            $criteria->addSelectColumn(TempContractPeer::BANK_NAME);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.contract_no');
            $criteria->addSelectColumn($alias . '.contract_date');
            $criteria->addSelectColumn($alias . '.contract_place');
            $criteria->addSelectColumn($alias . '.event_desc');
            $criteria->addSelectColumn($alias . '.event_date');
            $criteria->addSelectColumn($alias . '.event_place');
            $criteria->addSelectColumn($alias . '.event_name');
            $criteria->addSelectColumn($alias . '.event_role');
            $criteria->addSelectColumn($alias . '.gross');
            $criteria->addSelectColumn($alias . '.income_cost');
            $criteria->addSelectColumn($alias . '.tax');
            $criteria->addSelectColumn($alias . '.netto');
            $criteria->addSelectColumn($alias . '.first_name');
            $criteria->addSelectColumn($alias . '.last_name');
            $criteria->addSelectColumn($alias . '.PESEL');
            $criteria->addSelectColumn($alias . '.NIP');
            $criteria->addSelectColumn($alias . '.street');
            $criteria->addSelectColumn($alias . '.house');
            $criteria->addSelectColumn($alias . '.flat');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.district');
            $criteria->addSelectColumn($alias . '.country');
            $criteria->addSelectColumn($alias . '.bank_account');
            $criteria->addSelectColumn($alias . '.bank_name');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(TempContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            TempContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(TempContractPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return TempContract
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = TempContractPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return TempContractPeer::populateObjects(TempContractPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            TempContractPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(TempContractPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param TempContract $obj A TempContract object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            TempContractPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A TempContract object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof TempContract) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or TempContract object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(TempContractPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return TempContract Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(TempContractPeer::$instances[$key])) {
                return TempContractPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (TempContractPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        TempContractPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to temp_contract
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = TempContractPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = TempContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = TempContractPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                TempContractPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (TempContract object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = TempContractPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = TempContractPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + TempContractPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = TempContractPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            TempContractPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(TempContractPeer::DATABASE_NAME)->getTable(TempContractPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseTempContractPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseTempContractPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Oppen\ProjectBundle\Model\map\TempContractTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return TempContractPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a TempContract or Criteria object.
     *
     * @param      mixed $values Criteria or TempContract object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from TempContract object
        }

        if ($criteria->containsKey(TempContractPeer::ID) && $criteria->keyContainsValue(TempContractPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.TempContractPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(TempContractPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a TempContract or Criteria object.
     *
     * @param      mixed $values Criteria or TempContract object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(TempContractPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(TempContractPeer::ID);
            $value = $criteria->remove(TempContractPeer::ID);
            if ($value) {
                $selectCriteria->add(TempContractPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(TempContractPeer::TABLE_NAME);
            }

        } else { // $values is TempContract object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(TempContractPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the temp_contract table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(TempContractPeer::TABLE_NAME, $con, TempContractPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            TempContractPeer::clearInstancePool();
            TempContractPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a TempContract or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or TempContract object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            TempContractPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof TempContract) { // it's a model object
            // invalidate the cache for this single object
            TempContractPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(TempContractPeer::DATABASE_NAME);
            $criteria->add(TempContractPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                TempContractPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(TempContractPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            TempContractPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given TempContract object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param TempContract $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(TempContractPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(TempContractPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(TempContractPeer::DATABASE_NAME, TempContractPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return TempContract
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = TempContractPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(TempContractPeer::DATABASE_NAME);
        $criteria->add(TempContractPeer::ID, $pk);

        $v = TempContractPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return TempContract[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(TempContractPeer::DATABASE_NAME);
            $criteria->add(TempContractPeer::ID, $pks, Criteria::IN);
            $objs = TempContractPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseTempContractPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseTempContractPeer::buildTableMap();

