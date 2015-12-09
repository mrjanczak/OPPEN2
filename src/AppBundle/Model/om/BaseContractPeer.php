<?php

namespace AppBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use AppBundle\Model\Contract;
use AppBundle\Model\ContractPeer;
use AppBundle\Model\ContractQuery;
use AppBundle\Model\CostPeer;
use AppBundle\Model\DocPeer;
use AppBundle\Model\FilePeer;
use AppBundle\Model\MonthPeer;
use AppBundle\Model\TemplatePeer;
use AppBundle\Model\map\ContractTableMap;

abstract class BaseContractPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'contract';

    /** the related Propel class for this table */
    const OM_CLASS = 'AppBundle\\Model\\Contract';

    /** the related TableMap class for this table */
    const TM_CLASS = 'AppBundle\\Model\\map\\ContractTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 24;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 24;

    /** the column name for the id field */
    const ID = 'contract.id';

    /** the column name for the contract_no field */
    const CONTRACT_NO = 'contract.contract_no';

    /** the column name for the contract_date field */
    const CONTRACT_DATE = 'contract.contract_date';

    /** the column name for the contract_place field */
    const CONTRACT_PLACE = 'contract.contract_place';

    /** the column name for the event_desc field */
    const EVENT_DESC = 'contract.event_desc';

    /** the column name for the event_date field */
    const EVENT_DATE = 'contract.event_date';

    /** the column name for the event_place field */
    const EVENT_PLACE = 'contract.event_place';

    /** the column name for the event_name field */
    const EVENT_NAME = 'contract.event_name';

    /** the column name for the event_role field */
    const EVENT_ROLE = 'contract.event_role';

    /** the column name for the gross field */
    const GROSS = 'contract.gross';

    /** the column name for the income_cost field */
    const INCOME_COST = 'contract.income_cost';

    /** the column name for the tax field */
    const TAX = 'contract.tax';

    /** the column name for the netto field */
    const NETTO = 'contract.netto';

    /** the column name for the tax_coef field */
    const TAX_COEF = 'contract.tax_coef';

    /** the column name for the cost_coef field */
    const COST_COEF = 'contract.cost_coef';

    /** the column name for the payment_period field */
    const PAYMENT_PERIOD = 'contract.payment_period';

    /** the column name for the payment_method field */
    const PAYMENT_METHOD = 'contract.payment_method';

    /** the column name for the comment field */
    const COMMENT = 'contract.comment';

    /** the column name for the cost_id field */
    const COST_ID = 'contract.cost_id';

    /** the column name for the template_id field */
    const TEMPLATE_ID = 'contract.template_id';

    /** the column name for the file_id field */
    const FILE_ID = 'contract.file_id';

    /** the column name for the doc_id field */
    const DOC_ID = 'contract.doc_id';

    /** the column name for the month_id field */
    const MONTH_ID = 'contract.month_id';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'contract.sortable_rank';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Contract objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Contract[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'contract.sortable_rank';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'contract.cost_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ContractPeer::$fieldNames[ContractPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'ContractNo', 'ContractDate', 'ContractPlace', 'EventDesc', 'EventDate', 'EventPlace', 'EventName', 'EventRole', 'Gross', 'IncomeCost', 'Tax', 'Netto', 'TaxCoef', 'CostCoef', 'PaymentPeriod', 'PaymentMethod', 'Comment', 'CostId', 'TemplateId', 'FileId', 'DocId', 'MonthId', 'SortableRank', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'contractNo', 'contractDate', 'contractPlace', 'eventDesc', 'eventDate', 'eventPlace', 'eventName', 'eventRole', 'gross', 'incomeCost', 'tax', 'netto', 'taxCoef', 'costCoef', 'paymentPeriod', 'paymentMethod', 'comment', 'costId', 'templateId', 'fileId', 'docId', 'monthId', 'sortableRank', ),
        BasePeer::TYPE_COLNAME => array (ContractPeer::ID, ContractPeer::CONTRACT_NO, ContractPeer::CONTRACT_DATE, ContractPeer::CONTRACT_PLACE, ContractPeer::EVENT_DESC, ContractPeer::EVENT_DATE, ContractPeer::EVENT_PLACE, ContractPeer::EVENT_NAME, ContractPeer::EVENT_ROLE, ContractPeer::GROSS, ContractPeer::INCOME_COST, ContractPeer::TAX, ContractPeer::NETTO, ContractPeer::TAX_COEF, ContractPeer::COST_COEF, ContractPeer::PAYMENT_PERIOD, ContractPeer::PAYMENT_METHOD, ContractPeer::COMMENT, ContractPeer::COST_ID, ContractPeer::TEMPLATE_ID, ContractPeer::FILE_ID, ContractPeer::DOC_ID, ContractPeer::MONTH_ID, ContractPeer::SORTABLE_RANK, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'CONTRACT_NO', 'CONTRACT_DATE', 'CONTRACT_PLACE', 'EVENT_DESC', 'EVENT_DATE', 'EVENT_PLACE', 'EVENT_NAME', 'EVENT_ROLE', 'GROSS', 'INCOME_COST', 'TAX', 'NETTO', 'TAX_COEF', 'COST_COEF', 'PAYMENT_PERIOD', 'PAYMENT_METHOD', 'COMMENT', 'COST_ID', 'TEMPLATE_ID', 'FILE_ID', 'DOC_ID', 'MONTH_ID', 'SORTABLE_RANK', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'contract_no', 'contract_date', 'contract_place', 'event_desc', 'event_date', 'event_place', 'event_name', 'event_role', 'gross', 'income_cost', 'tax', 'netto', 'tax_coef', 'cost_coef', 'payment_period', 'payment_method', 'comment', 'cost_id', 'template_id', 'file_id', 'doc_id', 'month_id', 'sortable_rank', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ContractPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'ContractNo' => 1, 'ContractDate' => 2, 'ContractPlace' => 3, 'EventDesc' => 4, 'EventDate' => 5, 'EventPlace' => 6, 'EventName' => 7, 'EventRole' => 8, 'Gross' => 9, 'IncomeCost' => 10, 'Tax' => 11, 'Netto' => 12, 'TaxCoef' => 13, 'CostCoef' => 14, 'PaymentPeriod' => 15, 'PaymentMethod' => 16, 'Comment' => 17, 'CostId' => 18, 'TemplateId' => 19, 'FileId' => 20, 'DocId' => 21, 'MonthId' => 22, 'SortableRank' => 23, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'contractNo' => 1, 'contractDate' => 2, 'contractPlace' => 3, 'eventDesc' => 4, 'eventDate' => 5, 'eventPlace' => 6, 'eventName' => 7, 'eventRole' => 8, 'gross' => 9, 'incomeCost' => 10, 'tax' => 11, 'netto' => 12, 'taxCoef' => 13, 'costCoef' => 14, 'paymentPeriod' => 15, 'paymentMethod' => 16, 'comment' => 17, 'costId' => 18, 'templateId' => 19, 'fileId' => 20, 'docId' => 21, 'monthId' => 22, 'sortableRank' => 23, ),
        BasePeer::TYPE_COLNAME => array (ContractPeer::ID => 0, ContractPeer::CONTRACT_NO => 1, ContractPeer::CONTRACT_DATE => 2, ContractPeer::CONTRACT_PLACE => 3, ContractPeer::EVENT_DESC => 4, ContractPeer::EVENT_DATE => 5, ContractPeer::EVENT_PLACE => 6, ContractPeer::EVENT_NAME => 7, ContractPeer::EVENT_ROLE => 8, ContractPeer::GROSS => 9, ContractPeer::INCOME_COST => 10, ContractPeer::TAX => 11, ContractPeer::NETTO => 12, ContractPeer::TAX_COEF => 13, ContractPeer::COST_COEF => 14, ContractPeer::PAYMENT_PERIOD => 15, ContractPeer::PAYMENT_METHOD => 16, ContractPeer::COMMENT => 17, ContractPeer::COST_ID => 18, ContractPeer::TEMPLATE_ID => 19, ContractPeer::FILE_ID => 20, ContractPeer::DOC_ID => 21, ContractPeer::MONTH_ID => 22, ContractPeer::SORTABLE_RANK => 23, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'CONTRACT_NO' => 1, 'CONTRACT_DATE' => 2, 'CONTRACT_PLACE' => 3, 'EVENT_DESC' => 4, 'EVENT_DATE' => 5, 'EVENT_PLACE' => 6, 'EVENT_NAME' => 7, 'EVENT_ROLE' => 8, 'GROSS' => 9, 'INCOME_COST' => 10, 'TAX' => 11, 'NETTO' => 12, 'TAX_COEF' => 13, 'COST_COEF' => 14, 'PAYMENT_PERIOD' => 15, 'PAYMENT_METHOD' => 16, 'COMMENT' => 17, 'COST_ID' => 18, 'TEMPLATE_ID' => 19, 'FILE_ID' => 20, 'DOC_ID' => 21, 'MONTH_ID' => 22, 'SORTABLE_RANK' => 23, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'contract_no' => 1, 'contract_date' => 2, 'contract_place' => 3, 'event_desc' => 4, 'event_date' => 5, 'event_place' => 6, 'event_name' => 7, 'event_role' => 8, 'gross' => 9, 'income_cost' => 10, 'tax' => 11, 'netto' => 12, 'tax_coef' => 13, 'cost_coef' => 14, 'payment_period' => 15, 'payment_method' => 16, 'comment' => 17, 'cost_id' => 18, 'template_id' => 19, 'file_id' => 20, 'doc_id' => 21, 'month_id' => 22, 'sortable_rank' => 23, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, )
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
        $toNames = ContractPeer::getFieldNames($toType);
        $key = isset(ContractPeer::$fieldKeys[$fromType][$name]) ? ContractPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ContractPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, ContractPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ContractPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. ContractPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ContractPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(ContractPeer::ID);
            $criteria->addSelectColumn(ContractPeer::CONTRACT_NO);
            $criteria->addSelectColumn(ContractPeer::CONTRACT_DATE);
            $criteria->addSelectColumn(ContractPeer::CONTRACT_PLACE);
            $criteria->addSelectColumn(ContractPeer::EVENT_DESC);
            $criteria->addSelectColumn(ContractPeer::EVENT_DATE);
            $criteria->addSelectColumn(ContractPeer::EVENT_PLACE);
            $criteria->addSelectColumn(ContractPeer::EVENT_NAME);
            $criteria->addSelectColumn(ContractPeer::EVENT_ROLE);
            $criteria->addSelectColumn(ContractPeer::GROSS);
            $criteria->addSelectColumn(ContractPeer::INCOME_COST);
            $criteria->addSelectColumn(ContractPeer::TAX);
            $criteria->addSelectColumn(ContractPeer::NETTO);
            $criteria->addSelectColumn(ContractPeer::TAX_COEF);
            $criteria->addSelectColumn(ContractPeer::COST_COEF);
            $criteria->addSelectColumn(ContractPeer::PAYMENT_PERIOD);
            $criteria->addSelectColumn(ContractPeer::PAYMENT_METHOD);
            $criteria->addSelectColumn(ContractPeer::COMMENT);
            $criteria->addSelectColumn(ContractPeer::COST_ID);
            $criteria->addSelectColumn(ContractPeer::TEMPLATE_ID);
            $criteria->addSelectColumn(ContractPeer::FILE_ID);
            $criteria->addSelectColumn(ContractPeer::DOC_ID);
            $criteria->addSelectColumn(ContractPeer::MONTH_ID);
            $criteria->addSelectColumn(ContractPeer::SORTABLE_RANK);
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
            $criteria->addSelectColumn($alias . '.tax_coef');
            $criteria->addSelectColumn($alias . '.cost_coef');
            $criteria->addSelectColumn($alias . '.payment_period');
            $criteria->addSelectColumn($alias . '.payment_method');
            $criteria->addSelectColumn($alias . '.comment');
            $criteria->addSelectColumn($alias . '.cost_id');
            $criteria->addSelectColumn($alias . '.template_id');
            $criteria->addSelectColumn($alias . '.file_id');
            $criteria->addSelectColumn($alias . '.doc_id');
            $criteria->addSelectColumn($alias . '.month_id');
            $criteria->addSelectColumn($alias . '.sortable_rank');
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
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ContractPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return Contract
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ContractPeer::doSelect($critcopy, $con);
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
        return ContractPeer::populateObjects(ContractPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ContractPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

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
     * @param Contract $obj A Contract object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            ContractPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Contract object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Contract) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Contract object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ContractPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Contract Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ContractPeer::$instances[$key])) {
                return ContractPeer::$instances[$key];
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
        foreach (ContractPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        ContractPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to contract
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
        $cls = ContractPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ContractPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ContractPeer::addInstanceToPool($obj, $key);
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
     * @return array (Contract object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ContractPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ContractPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ContractPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ContractPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ContractPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Cost table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCost(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Template table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinTemplate(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related File table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFile(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Doc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinDoc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Month table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinMonth(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

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
     * Selects a collection of Contract objects pre-filled with their Cost objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCost(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol = ContractPeer::NUM_HYDRATE_COLUMNS;
        CostPeer::addSelectColumns($criteria);

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = CostPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = CostPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CostPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    CostPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Contract) to $obj2 (Cost)
                $obj2->addContract($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with their Template objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinTemplate(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol = ContractPeer::NUM_HYDRATE_COLUMNS;
        TemplatePeer::addSelectColumns($criteria);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = TemplatePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = TemplatePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = TemplatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    TemplatePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Contract) to $obj2 (Template)
                $obj2->addContract($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with their File objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFile(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol = ContractPeer::NUM_HYDRATE_COLUMNS;
        FilePeer::addSelectColumns($criteria);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = FilePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = FilePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    FilePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Contract) to $obj2 (File)
                $obj2->addContract($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with their Doc objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinDoc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol = ContractPeer::NUM_HYDRATE_COLUMNS;
        DocPeer::addSelectColumns($criteria);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = DocPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = DocPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = DocPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    DocPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Contract) to $obj2 (Doc)
                $obj2->addContract($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with their Month objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinMonth(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol = ContractPeer::NUM_HYDRATE_COLUMNS;
        MonthPeer::addSelectColumns($criteria);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = MonthPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = MonthPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = MonthPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    MonthPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (Contract) to $obj2 (Month)
                $obj2->addContract($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

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
     * Selects a collection of Contract objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol2 = ContractPeer::NUM_HYDRATE_COLUMNS;

        CostPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CostPeer::NUM_HYDRATE_COLUMNS;

        TemplatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + TemplatePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FilePeer::NUM_HYDRATE_COLUMNS;

        DocPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DocPeer::NUM_HYDRATE_COLUMNS;

        MonthPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + MonthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Cost rows

            $key2 = CostPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = CostPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = CostPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CostPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (Contract) to the collection in $obj2 (Cost)
                $obj2->addContract($obj1);
            } // if joined row not null

            // Add objects for joined Template rows

            $key3 = TemplatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = TemplatePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = TemplatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    TemplatePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (Contract) to the collection in $obj3 (Template)
                $obj3->addContract($obj1);
            } // if joined row not null

            // Add objects for joined File rows

            $key4 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = FilePeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = FilePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    FilePeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Contract) to the collection in $obj4 (File)
                $obj4->addContract($obj1);
            } // if joined row not null

            // Add objects for joined Doc rows

            $key5 = DocPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = DocPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = DocPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DocPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Contract) to the collection in $obj5 (Doc)
                $obj5->addContract($obj1);
            } // if joined row not null

            // Add objects for joined Month rows

            $key6 = MonthPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = MonthPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = MonthPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    MonthPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (Contract) to the collection in $obj6 (Month)
                $obj6->addContract($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Cost table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCost(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Template table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptTemplate(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related File table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFile(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Doc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptDoc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Month table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptMonth(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ContractPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ContractPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

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
     * Selects a collection of Contract objects pre-filled with all related objects except Cost.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCost(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol2 = ContractPeer::NUM_HYDRATE_COLUMNS;

        TemplatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + TemplatePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FilePeer::NUM_HYDRATE_COLUMNS;

        DocPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DocPeer::NUM_HYDRATE_COLUMNS;

        MonthPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + MonthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Template rows

                $key2 = TemplatePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = TemplatePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = TemplatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    TemplatePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Contract) to the collection in $obj2 (Template)
                $obj2->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined File rows

                $key3 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = FilePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = FilePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    FilePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Contract) to the collection in $obj3 (File)
                $obj3->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Doc rows

                $key4 = DocPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DocPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DocPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DocPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Contract) to the collection in $obj4 (Doc)
                $obj4->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Month rows

                $key5 = MonthPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = MonthPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = MonthPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    MonthPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Contract) to the collection in $obj5 (Month)
                $obj5->addContract($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with all related objects except Template.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptTemplate(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol2 = ContractPeer::NUM_HYDRATE_COLUMNS;

        CostPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CostPeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FilePeer::NUM_HYDRATE_COLUMNS;

        DocPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DocPeer::NUM_HYDRATE_COLUMNS;

        MonthPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + MonthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Cost rows

                $key2 = CostPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CostPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CostPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CostPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Contract) to the collection in $obj2 (Cost)
                $obj2->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined File rows

                $key3 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = FilePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = FilePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    FilePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Contract) to the collection in $obj3 (File)
                $obj3->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Doc rows

                $key4 = DocPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DocPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DocPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DocPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Contract) to the collection in $obj4 (Doc)
                $obj4->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Month rows

                $key5 = MonthPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = MonthPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = MonthPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    MonthPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Contract) to the collection in $obj5 (Month)
                $obj5->addContract($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with all related objects except File.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFile(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol2 = ContractPeer::NUM_HYDRATE_COLUMNS;

        CostPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CostPeer::NUM_HYDRATE_COLUMNS;

        TemplatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + TemplatePeer::NUM_HYDRATE_COLUMNS;

        DocPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + DocPeer::NUM_HYDRATE_COLUMNS;

        MonthPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + MonthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Cost rows

                $key2 = CostPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CostPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CostPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CostPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Contract) to the collection in $obj2 (Cost)
                $obj2->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Template rows

                $key3 = TemplatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = TemplatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = TemplatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    TemplatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Contract) to the collection in $obj3 (Template)
                $obj3->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Doc rows

                $key4 = DocPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = DocPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = DocPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    DocPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Contract) to the collection in $obj4 (Doc)
                $obj4->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Month rows

                $key5 = MonthPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = MonthPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = MonthPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    MonthPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Contract) to the collection in $obj5 (Month)
                $obj5->addContract($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with all related objects except Doc.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptDoc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol2 = ContractPeer::NUM_HYDRATE_COLUMNS;

        CostPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CostPeer::NUM_HYDRATE_COLUMNS;

        TemplatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + TemplatePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FilePeer::NUM_HYDRATE_COLUMNS;

        MonthPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + MonthPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::MONTH_ID, MonthPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Cost rows

                $key2 = CostPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CostPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CostPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CostPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Contract) to the collection in $obj2 (Cost)
                $obj2->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Template rows

                $key3 = TemplatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = TemplatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = TemplatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    TemplatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Contract) to the collection in $obj3 (Template)
                $obj3->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined File rows

                $key4 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = FilePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = FilePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    FilePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Contract) to the collection in $obj4 (File)
                $obj4->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Month rows

                $key5 = MonthPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = MonthPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = MonthPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    MonthPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Contract) to the collection in $obj5 (Month)
                $obj5->addContract($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Contract objects pre-filled with all related objects except Month.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Contract objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptMonth(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ContractPeer::DATABASE_NAME);
        }

        ContractPeer::addSelectColumns($criteria);
        $startcol2 = ContractPeer::NUM_HYDRATE_COLUMNS;

        CostPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + CostPeer::NUM_HYDRATE_COLUMNS;

        TemplatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + TemplatePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FilePeer::NUM_HYDRATE_COLUMNS;

        DocPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + DocPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ContractPeer::COST_ID, CostPeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::TEMPLATE_ID, TemplatePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::FILE_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(ContractPeer::DOC_ID, DocPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ContractPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ContractPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ContractPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ContractPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Cost rows

                $key2 = CostPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = CostPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = CostPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    CostPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (Contract) to the collection in $obj2 (Cost)
                $obj2->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Template rows

                $key3 = TemplatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = TemplatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = TemplatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    TemplatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (Contract) to the collection in $obj3 (Template)
                $obj3->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined File rows

                $key4 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = FilePeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = FilePeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    FilePeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Contract) to the collection in $obj4 (File)
                $obj4->addContract($obj1);

            } // if joined row is not null

                // Add objects for joined Doc rows

                $key5 = DocPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = DocPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = DocPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    DocPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (Contract) to the collection in $obj5 (Doc)
                $obj5->addContract($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
        return Propel::getDatabaseMap(ContractPeer::DATABASE_NAME)->getTable(ContractPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseContractPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseContractPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \AppBundle\Model\map\ContractTableMap());
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
        return ContractPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Contract or Criteria object.
     *
     * @param      mixed $values Criteria or Contract object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Contract object
        }

        if ($criteria->containsKey(ContractPeer::ID) && $criteria->keyContainsValue(ContractPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ContractPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Contract or Criteria object.
     *
     * @param      mixed $values Criteria or Contract object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(ContractPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(ContractPeer::ID);
            $value = $criteria->remove(ContractPeer::ID);
            if ($value) {
                $selectCriteria->add(ContractPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(ContractPeer::TABLE_NAME);
            }

        } else { // $values is Contract object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the contract table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(ContractPeer::TABLE_NAME, $con, ContractPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ContractPeer::clearInstancePool();
            ContractPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Contract or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Contract object or primary key or array of primary keys
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
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            ContractPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Contract) { // it's a model object
            // invalidate the cache for this single object
            ContractPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ContractPeer::DATABASE_NAME);
            $criteria->add(ContractPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                ContractPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(ContractPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            ContractPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Contract object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Contract $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ContractPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ContractPeer::TABLE_NAME);

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

        return BasePeer::doValidate(ContractPeer::DATABASE_NAME, ContractPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Contract
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = ContractPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(ContractPeer::DATABASE_NAME);
        $criteria->add(ContractPeer::ID, $pk);

        $v = ContractPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Contract[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(ContractPeer::DATABASE_NAME);
            $criteria->add(ContractPeer::ID, $pks, Criteria::IN);
            $objs = ContractPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // sortable behavior

    /**
     * Get the highest rank
     *
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public static function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . ContractPeer::RANK_COL . ')');
        ContractPeer::sortableApplyScopeCriteria($c, $scope);
        $stmt = ContractPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return Contract
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(ContractPeer::RANK_COL, $rank);
        ContractPeer::sortableApplyScopeCriteria($c, $scope);

        return ContractPeer::doSelectOne($c, $con);
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public static function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = ContractPeer::retrieveByPKs($ids);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Return an array of sortable objects ordered by position
     *
     * @param     Criteria  $criteria  optional criteria object
     * @param     string    $order     sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con       optional connection
     *
     * @return    array list of sortable objects
     */
    public static function doSelectOrderByRank(Criteria $criteria = null, $order = Criteria::ASC, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(ContractPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(ContractPeer::RANK_COL);
        }

        return ContractPeer::doSelect($criteria, $con);
    }

    /**
     * Return an array of sortable objects in the given scope ordered by position
     *
     * @param     mixed     $scope  the scope of the list
     * @param     string    $order  sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function retrieveList($scope, $order = Criteria::ASC, PropelPDO $con = null)
    {
        $c = new Criteria();
        ContractPeer::sortableApplyScopeCriteria($c, $scope);

        return ContractPeer::doSelectOrderByRank($c, $order, $con);
    }

    /**
     * Return the number of sortable objects in the given scope
     *
     * @param     mixed     $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function countList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        ContractPeer::sortableApplyScopeCriteria($c, $scope);

        return ContractPeer::doCount($c, $con);
    }

    /**
     * Deletes the sortable objects in the given scope
     *
     * @param     mixed     $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    int number of deleted objects
     */
    public static function deleteList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        ContractPeer::sortableApplyScopeCriteria($c, $scope);

        return ContractPeer::doDelete($c, $con);
    }

    /**
     * Applies all scope fields to the given criteria.
     *
     * @param  Criteria $criteria Applies the values directly to this criteria.
     * @param  mixed    $scope    The scope value as scalar type or array($value1, ...).
     * @param  string   $method   The method we use to apply the values.
     *
     */
    public static function sortableApplyScopeCriteria(Criteria $criteria, $scope, $method = 'add')
    {

        $criteria->$method(ContractPeer::COST_ID, $scope, Criteria::EQUAL);

    }

    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      mixed $scope Scope to use for the shift. Scalar value (single scope) or array
     * @param      PropelPDO $con Connection to use.
     */
    public static function shiftRank($delta, $first = null, $last = null, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = ContractQuery::create();
        if (null !== $first) {
            $whereCriteria->add(ContractPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        }
        if (null !== $last) {
            $whereCriteria->addAnd(ContractPeer::RANK_COL, $last, Criteria::LESS_EQUAL);
        }
        ContractPeer::sortableApplyScopeCriteria($whereCriteria, $scope);

        $valuesCriteria = new Criteria(ContractPeer::DATABASE_NAME);
        $valuesCriteria->add(ContractPeer::RANK_COL, array('raw' => ContractPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        ContractPeer::clearInstancePool();
    }

} // BaseContractPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseContractPeer::buildTableMap();

