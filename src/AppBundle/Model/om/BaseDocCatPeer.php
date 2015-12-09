<?php

namespace AppBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use AppBundle\Model\AccountPeer;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocCatPeer;
use AppBundle\Model\DocPeer;
use AppBundle\Model\FileCatPeer;
use AppBundle\Model\YearPeer;
use AppBundle\Model\map\DocCatTableMap;

abstract class BaseDocCatPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'doc_cat';

    /** the related Propel class for this table */
    const OM_CLASS = 'AppBundle\\Model\\DocCat';

    /** the related TableMap class for this table */
    const TM_CLASS = 'AppBundle\\Model\\map\\DocCatTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 12;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 12;

    /** the column name for the id field */
    const ID = 'doc_cat.id';

    /** the column name for the name field */
    const NAME = 'doc_cat.name';

    /** the column name for the symbol field */
    const SYMBOL = 'doc_cat.symbol';

    /** the column name for the doc_no_tmp field */
    const DOC_NO_TMP = 'doc_cat.doc_no_tmp';

    /** the column name for the as_income field */
    const AS_INCOME = 'doc_cat.as_income';

    /** the column name for the as_cost field */
    const AS_COST = 'doc_cat.as_cost';

    /** the column name for the as_bill field */
    const AS_BILL = 'doc_cat.as_bill';

    /** the column name for the is_locked field */
    const IS_LOCKED = 'doc_cat.is_locked';

    /** the column name for the year_id field */
    const YEAR_ID = 'doc_cat.year_id';

    /** the column name for the file_cat_id field */
    const FILE_CAT_ID = 'doc_cat.file_cat_id';

    /** the column name for the commitment_acc_id field */
    const COMMITMENT_ACC_ID = 'doc_cat.commitment_acc_id';

    /** the column name for the tax_commitment_acc_id field */
    const TAX_COMMITMENT_ACC_ID = 'doc_cat.tax_commitment_acc_id';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of DocCat objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array DocCat[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. DocCatPeer::$fieldNames[DocCatPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Name', 'Symbol', 'DocNoTmp', 'AsIncome', 'AsCost', 'AsBill', 'IsLocked', 'YearId', 'FileCatId', 'CommitmentAccId', 'TaxCommitmentAccId', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'name', 'symbol', 'docNoTmp', 'asIncome', 'asCost', 'asBill', 'isLocked', 'yearId', 'fileCatId', 'commitmentAccId', 'taxCommitmentAccId', ),
        BasePeer::TYPE_COLNAME => array (DocCatPeer::ID, DocCatPeer::NAME, DocCatPeer::SYMBOL, DocCatPeer::DOC_NO_TMP, DocCatPeer::AS_INCOME, DocCatPeer::AS_COST, DocCatPeer::AS_BILL, DocCatPeer::IS_LOCKED, DocCatPeer::YEAR_ID, DocCatPeer::FILE_CAT_ID, DocCatPeer::COMMITMENT_ACC_ID, DocCatPeer::TAX_COMMITMENT_ACC_ID, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'NAME', 'SYMBOL', 'DOC_NO_TMP', 'AS_INCOME', 'AS_COST', 'AS_BILL', 'IS_LOCKED', 'YEAR_ID', 'FILE_CAT_ID', 'COMMITMENT_ACC_ID', 'TAX_COMMITMENT_ACC_ID', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'name', 'symbol', 'doc_no_tmp', 'as_income', 'as_cost', 'as_bill', 'is_locked', 'year_id', 'file_cat_id', 'commitment_acc_id', 'tax_commitment_acc_id', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. DocCatPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Name' => 1, 'Symbol' => 2, 'DocNoTmp' => 3, 'AsIncome' => 4, 'AsCost' => 5, 'AsBill' => 6, 'IsLocked' => 7, 'YearId' => 8, 'FileCatId' => 9, 'CommitmentAccId' => 10, 'TaxCommitmentAccId' => 11, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'name' => 1, 'symbol' => 2, 'docNoTmp' => 3, 'asIncome' => 4, 'asCost' => 5, 'asBill' => 6, 'isLocked' => 7, 'yearId' => 8, 'fileCatId' => 9, 'commitmentAccId' => 10, 'taxCommitmentAccId' => 11, ),
        BasePeer::TYPE_COLNAME => array (DocCatPeer::ID => 0, DocCatPeer::NAME => 1, DocCatPeer::SYMBOL => 2, DocCatPeer::DOC_NO_TMP => 3, DocCatPeer::AS_INCOME => 4, DocCatPeer::AS_COST => 5, DocCatPeer::AS_BILL => 6, DocCatPeer::IS_LOCKED => 7, DocCatPeer::YEAR_ID => 8, DocCatPeer::FILE_CAT_ID => 9, DocCatPeer::COMMITMENT_ACC_ID => 10, DocCatPeer::TAX_COMMITMENT_ACC_ID => 11, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'NAME' => 1, 'SYMBOL' => 2, 'DOC_NO_TMP' => 3, 'AS_INCOME' => 4, 'AS_COST' => 5, 'AS_BILL' => 6, 'IS_LOCKED' => 7, 'YEAR_ID' => 8, 'FILE_CAT_ID' => 9, 'COMMITMENT_ACC_ID' => 10, 'TAX_COMMITMENT_ACC_ID' => 11, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'name' => 1, 'symbol' => 2, 'doc_no_tmp' => 3, 'as_income' => 4, 'as_cost' => 5, 'as_bill' => 6, 'is_locked' => 7, 'year_id' => 8, 'file_cat_id' => 9, 'commitment_acc_id' => 10, 'tax_commitment_acc_id' => 11, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, )
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
        $toNames = DocCatPeer::getFieldNames($toType);
        $key = isset(DocCatPeer::$fieldKeys[$fromType][$name]) ? DocCatPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(DocCatPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, DocCatPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return DocCatPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. DocCatPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(DocCatPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(DocCatPeer::ID);
            $criteria->addSelectColumn(DocCatPeer::NAME);
            $criteria->addSelectColumn(DocCatPeer::SYMBOL);
            $criteria->addSelectColumn(DocCatPeer::DOC_NO_TMP);
            $criteria->addSelectColumn(DocCatPeer::AS_INCOME);
            $criteria->addSelectColumn(DocCatPeer::AS_COST);
            $criteria->addSelectColumn(DocCatPeer::AS_BILL);
            $criteria->addSelectColumn(DocCatPeer::IS_LOCKED);
            $criteria->addSelectColumn(DocCatPeer::YEAR_ID);
            $criteria->addSelectColumn(DocCatPeer::FILE_CAT_ID);
            $criteria->addSelectColumn(DocCatPeer::COMMITMENT_ACC_ID);
            $criteria->addSelectColumn(DocCatPeer::TAX_COMMITMENT_ACC_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.symbol');
            $criteria->addSelectColumn($alias . '.doc_no_tmp');
            $criteria->addSelectColumn($alias . '.as_income');
            $criteria->addSelectColumn($alias . '.as_cost');
            $criteria->addSelectColumn($alias . '.as_bill');
            $criteria->addSelectColumn($alias . '.is_locked');
            $criteria->addSelectColumn($alias . '.year_id');
            $criteria->addSelectColumn($alias . '.file_cat_id');
            $criteria->addSelectColumn($alias . '.commitment_acc_id');
            $criteria->addSelectColumn($alias . '.tax_commitment_acc_id');
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
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(DocCatPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return DocCat
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = DocCatPeer::doSelect($critcopy, $con);
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
        return DocCatPeer::populateObjects(DocCatPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            DocCatPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

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
     * @param DocCat $obj A DocCat object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            DocCatPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A DocCat object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof DocCat) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or DocCat object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(DocCatPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return DocCat Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(DocCatPeer::$instances[$key])) {
                return DocCatPeer::$instances[$key];
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
        foreach (DocCatPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        DocCatPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to doc_cat
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in DocPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        DocPeer::clearInstancePool();
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
        $cls = DocCatPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = DocCatPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                DocCatPeer::addInstanceToPool($obj, $key);
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
     * @return array (DocCat object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = DocCatPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = DocCatPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + DocCatPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = DocCatPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            DocCatPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Year table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinYear(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCat table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileCat(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related CommitmentAcc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinCommitmentAcc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related TaxCommitmentAcc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinTaxCommitmentAcc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

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
     * Selects a collection of DocCat objects pre-filled with their Year objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinYear(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol = DocCatPeer::NUM_HYDRATE_COLUMNS;
        YearPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = YearPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = YearPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = YearPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    YearPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DocCat) to $obj2 (Year)
                $obj2->addDocCat($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocCat objects pre-filled with their FileCat objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileCat(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol = DocCatPeer::NUM_HYDRATE_COLUMNS;
        FileCatPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = FileCatPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = FileCatPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    FileCatPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DocCat) to $obj2 (FileCat)
                $obj2->addDocCat($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocCat objects pre-filled with their Account objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinCommitmentAcc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol = DocCatPeer::NUM_HYDRATE_COLUMNS;
        AccountPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = AccountPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = AccountPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    AccountPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DocCat) to $obj2 (Account)
                $obj2->addDocCatRelatedByCommitmentAccId($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocCat objects pre-filled with their Account objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinTaxCommitmentAcc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol = DocCatPeer::NUM_HYDRATE_COLUMNS;
        AccountPeer::addSelectColumns($criteria);

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = AccountPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = AccountPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    AccountPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (DocCat) to $obj2 (Account)
                $obj2->addDocCatRelatedByTaxCommitmentAccId($obj1);

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
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

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
     * Selects a collection of DocCat objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol2 = DocCatPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Year rows

            $key2 = YearPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = YearPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = YearPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    YearPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (DocCat) to the collection in $obj2 (Year)
                $obj2->addDocCat($obj1);
            } // if joined row not null

            // Add objects for joined FileCat rows

            $key3 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = FileCatPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = FileCatPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    FileCatPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (DocCat) to the collection in $obj3 (FileCat)
                $obj3->addDocCat($obj1);
            } // if joined row not null

            // Add objects for joined Account rows

            $key4 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = AccountPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = AccountPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AccountPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (DocCat) to the collection in $obj4 (Account)
                $obj4->addDocCatRelatedByCommitmentAccId($obj1);
            } // if joined row not null

            // Add objects for joined Account rows

            $key5 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = AccountPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = AccountPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    AccountPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (DocCat) to the collection in $obj5 (Account)
                $obj5->addDocCatRelatedByTaxCommitmentAccId($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Year table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptYear(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCat table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileCat(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related CommitmentAcc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptCommitmentAcc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related TaxCommitmentAcc table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptTaxCommitmentAcc(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            DocCatPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

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
     * Selects a collection of DocCat objects pre-filled with all related objects except Year.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptYear(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol2 = DocCatPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined FileCat rows

                $key2 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = FileCatPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = FileCatPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    FileCatPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj2 (FileCat)
                $obj2->addDocCat($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key3 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = AccountPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = AccountPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AccountPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj3 (Account)
                $obj3->addDocCatRelatedByCommitmentAccId($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key4 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AccountPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AccountPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AccountPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj4 (Account)
                $obj4->addDocCatRelatedByTaxCommitmentAccId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocCat objects pre-filled with all related objects except FileCat.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileCat(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol2 = DocCatPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::TAX_COMMITMENT_ACC_ID, AccountPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Year rows

                $key2 = YearPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = YearPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = YearPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    YearPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj2 (Year)
                $obj2->addDocCat($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key3 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = AccountPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = AccountPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AccountPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj3 (Account)
                $obj3->addDocCatRelatedByCommitmentAccId($obj1);

            } // if joined row is not null

                // Add objects for joined Account rows

                $key4 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = AccountPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = AccountPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    AccountPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj4 (Account)
                $obj4->addDocCatRelatedByTaxCommitmentAccId($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocCat objects pre-filled with all related objects except CommitmentAcc.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptCommitmentAcc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol2 = DocCatPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Year rows

                $key2 = YearPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = YearPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = YearPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    YearPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj2 (Year)
                $obj2->addDocCat($obj1);

            } // if joined row is not null

                // Add objects for joined FileCat rows

                $key3 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = FileCatPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = FileCatPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    FileCatPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj3 (FileCat)
                $obj3->addDocCat($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of DocCat objects pre-filled with all related objects except TaxCommitmentAcc.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of DocCat objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptTaxCommitmentAcc(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(DocCatPeer::DATABASE_NAME);
        }

        DocCatPeer::addSelectColumns($criteria);
        $startcol2 = DocCatPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(DocCatPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(DocCatPeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = DocCatPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = DocCatPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = DocCatPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                DocCatPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Year rows

                $key2 = YearPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = YearPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = YearPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    YearPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj2 (Year)
                $obj2->addDocCat($obj1);

            } // if joined row is not null

                // Add objects for joined FileCat rows

                $key3 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = FileCatPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = FileCatPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    FileCatPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (DocCat) to the collection in $obj3 (FileCat)
                $obj3->addDocCat($obj1);

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
        return Propel::getDatabaseMap(DocCatPeer::DATABASE_NAME)->getTable(DocCatPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseDocCatPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseDocCatPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \AppBundle\Model\map\DocCatTableMap());
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
        return DocCatPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a DocCat or Criteria object.
     *
     * @param      mixed $values Criteria or DocCat object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from DocCat object
        }

        if ($criteria->containsKey(DocCatPeer::ID) && $criteria->keyContainsValue(DocCatPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.DocCatPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a DocCat or Criteria object.
     *
     * @param      mixed $values Criteria or DocCat object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(DocCatPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(DocCatPeer::ID);
            $value = $criteria->remove(DocCatPeer::ID);
            if ($value) {
                $selectCriteria->add(DocCatPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(DocCatPeer::TABLE_NAME);
            }

        } else { // $values is DocCat object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the doc_cat table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += DocCatPeer::doOnDeleteCascade(new Criteria(DocCatPeer::DATABASE_NAME), $con);
            $affectedRows += BasePeer::doDeleteAll(DocCatPeer::TABLE_NAME, $con, DocCatPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            DocCatPeer::clearInstancePool();
            DocCatPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a DocCat or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or DocCat object or primary key or array of primary keys
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
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof DocCat) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(DocCatPeer::DATABASE_NAME);
            $criteria->add(DocCatPeer::ID, (array) $values, Criteria::IN);
        }

        // Set the correct dbName
        $criteria->setDbName(DocCatPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            // cloning the Criteria in case it's modified by doSelect() or doSelectStmt()
            $c = clone $criteria;
            $affectedRows += DocCatPeer::doOnDeleteCascade($c, $con);

            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            if ($values instanceof Criteria) {
                DocCatPeer::clearInstancePool();
            } elseif ($values instanceof DocCat) { // it's a model object
                DocCatPeer::removeInstanceFromPool($values);
            } else { // it's a primary key, or an array of pks
                foreach ((array) $values as $singleval) {
                    DocCatPeer::removeInstanceFromPool($singleval);
                }
            }

            $affectedRows += BasePeer::doDelete($criteria, $con);
            DocCatPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * This is a method for emulating ON DELETE CASCADE for DBs that don't support this
     * feature (like MySQL or SQLite).
     *
     * This method is not very speedy because it must perform a query first to get
     * the implicated records and then perform the deletes by calling those Peer classes.
     *
     * This method should be used within a transaction if possible.
     *
     * @param      Criteria $criteria
     * @param      PropelPDO $con
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    protected static function doOnDeleteCascade(Criteria $criteria, PropelPDO $con)
    {
        // initialize var to track total num of affected rows
        $affectedRows = 0;

        // first find the objects that are implicated by the $criteria
        $objects = DocCatPeer::doSelect($criteria, $con);
        foreach ($objects as $obj) {


            // delete related Doc objects
            $criteria = new Criteria(DocPeer::DATABASE_NAME);

            $criteria->add(DocPeer::DOC_CAT_ID, $obj->getId());
            $affectedRows += DocPeer::doDelete($criteria, $con);
        }

        return $affectedRows;
    }

    /**
     * Validates all modified columns of given DocCat object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param DocCat $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(DocCatPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(DocCatPeer::TABLE_NAME);

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

        return BasePeer::doValidate(DocCatPeer::DATABASE_NAME, DocCatPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return DocCat
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = DocCatPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(DocCatPeer::DATABASE_NAME);
        $criteria->add(DocCatPeer::ID, $pk);

        $v = DocCatPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return DocCat[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(DocCatPeer::DATABASE_NAME);
            $criteria->add(DocCatPeer::ID, $pks, Criteria::IN);
            $objs = DocCatPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseDocCatPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseDocCatPeer::buildTableMap();

