<?php

namespace Oppen\ProjectBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\AccountPeer;
use Oppen\ProjectBundle\Model\FileCatPeer;
use Oppen\ProjectBundle\Model\YearPeer;
use Oppen\ProjectBundle\Model\map\AccountTableMap;

abstract class BaseAccountPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'account';

    /** the related Propel class for this table */
    const OM_CLASS = 'Oppen\\ProjectBundle\\Model\\Account';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Oppen\\ProjectBundle\\Model\\map\\AccountTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 17;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 17;

    /** the column name for the id field */
    const ID = 'account.id';

    /** the column name for the acc_no field */
    const ACC_NO = 'account.acc_no';

    /** the column name for the name field */
    const NAME = 'account.name';

    /** the column name for the report_side field */
    const REPORT_SIDE = 'account.report_side';

    /** the column name for the as_bank_acc field */
    const AS_BANK_ACC = 'account.as_bank_acc';

    /** the column name for the as_income field */
    const AS_INCOME = 'account.as_income';

    /** the column name for the as_cost field */
    const AS_COST = 'account.as_cost';

    /** the column name for the inc_open_b field */
    const INC_OPEN_B = 'account.inc_open_b';

    /** the column name for the inc_close_b field */
    const INC_CLOSE_B = 'account.inc_close_b';

    /** the column name for the as_close_b field */
    const AS_CLOSE_B = 'account.as_close_b';

    /** the column name for the year_id field */
    const YEAR_ID = 'account.year_id';

    /** the column name for the file_cat_lev1_id field */
    const FILE_CAT_LEV1_ID = 'account.file_cat_lev1_id';

    /** the column name for the file_cat_lev2_id field */
    const FILE_CAT_LEV2_ID = 'account.file_cat_lev2_id';

    /** the column name for the file_cat_lev3_id field */
    const FILE_CAT_LEV3_ID = 'account.file_cat_lev3_id';

    /** the column name for the tree_left field */
    const TREE_LEFT = 'account.tree_left';

    /** the column name for the tree_right field */
    const TREE_RIGHT = 'account.tree_right';

    /** the column name for the tree_level field */
    const TREE_LEVEL = 'account.tree_level';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of Account objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array Account[]
     */
    public static $instances = array();


    // nested_set behavior

    /**
     * Left column for the set
     */
    const LEFT_COL = 'account.tree_left';

    /**
     * Right column for the set
     */
    const RIGHT_COL = 'account.tree_right';

    /**
     * Level column for the set
     */
    const LEVEL_COL = 'account.tree_level';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'account.year_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. AccountPeer::$fieldNames[AccountPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'AccNo', 'Name', 'ReportSide', 'AsBankAcc', 'AsIncome', 'AsCost', 'IncOpenB', 'IncCloseB', 'AsCloseB', 'YearId', 'FileCatLev1Id', 'FileCatLev2Id', 'FileCatLev3Id', 'TreeLeft', 'TreeRight', 'TreeLevel', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'accNo', 'name', 'reportSide', 'asBankAcc', 'asIncome', 'asCost', 'incOpenB', 'incCloseB', 'asCloseB', 'yearId', 'fileCatLev1Id', 'fileCatLev2Id', 'fileCatLev3Id', 'treeLeft', 'treeRight', 'treeLevel', ),
        BasePeer::TYPE_COLNAME => array (AccountPeer::ID, AccountPeer::ACC_NO, AccountPeer::NAME, AccountPeer::REPORT_SIDE, AccountPeer::AS_BANK_ACC, AccountPeer::AS_INCOME, AccountPeer::AS_COST, AccountPeer::INC_OPEN_B, AccountPeer::INC_CLOSE_B, AccountPeer::AS_CLOSE_B, AccountPeer::YEAR_ID, AccountPeer::FILE_CAT_LEV1_ID, AccountPeer::FILE_CAT_LEV2_ID, AccountPeer::FILE_CAT_LEV3_ID, AccountPeer::TREE_LEFT, AccountPeer::TREE_RIGHT, AccountPeer::TREE_LEVEL, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'ACC_NO', 'NAME', 'REPORT_SIDE', 'AS_BANK_ACC', 'AS_INCOME', 'AS_COST', 'INC_OPEN_B', 'INC_CLOSE_B', 'AS_CLOSE_B', 'YEAR_ID', 'FILE_CAT_LEV1_ID', 'FILE_CAT_LEV2_ID', 'FILE_CAT_LEV3_ID', 'TREE_LEFT', 'TREE_RIGHT', 'TREE_LEVEL', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'acc_no', 'name', 'report_side', 'as_bank_acc', 'as_income', 'as_cost', 'inc_open_b', 'inc_close_b', 'as_close_b', 'year_id', 'file_cat_lev1_id', 'file_cat_lev2_id', 'file_cat_lev3_id', 'tree_left', 'tree_right', 'tree_level', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. AccountPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'AccNo' => 1, 'Name' => 2, 'ReportSide' => 3, 'AsBankAcc' => 4, 'AsIncome' => 5, 'AsCost' => 6, 'IncOpenB' => 7, 'IncCloseB' => 8, 'AsCloseB' => 9, 'YearId' => 10, 'FileCatLev1Id' => 11, 'FileCatLev2Id' => 12, 'FileCatLev3Id' => 13, 'TreeLeft' => 14, 'TreeRight' => 15, 'TreeLevel' => 16, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'accNo' => 1, 'name' => 2, 'reportSide' => 3, 'asBankAcc' => 4, 'asIncome' => 5, 'asCost' => 6, 'incOpenB' => 7, 'incCloseB' => 8, 'asCloseB' => 9, 'yearId' => 10, 'fileCatLev1Id' => 11, 'fileCatLev2Id' => 12, 'fileCatLev3Id' => 13, 'treeLeft' => 14, 'treeRight' => 15, 'treeLevel' => 16, ),
        BasePeer::TYPE_COLNAME => array (AccountPeer::ID => 0, AccountPeer::ACC_NO => 1, AccountPeer::NAME => 2, AccountPeer::REPORT_SIDE => 3, AccountPeer::AS_BANK_ACC => 4, AccountPeer::AS_INCOME => 5, AccountPeer::AS_COST => 6, AccountPeer::INC_OPEN_B => 7, AccountPeer::INC_CLOSE_B => 8, AccountPeer::AS_CLOSE_B => 9, AccountPeer::YEAR_ID => 10, AccountPeer::FILE_CAT_LEV1_ID => 11, AccountPeer::FILE_CAT_LEV2_ID => 12, AccountPeer::FILE_CAT_LEV3_ID => 13, AccountPeer::TREE_LEFT => 14, AccountPeer::TREE_RIGHT => 15, AccountPeer::TREE_LEVEL => 16, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'ACC_NO' => 1, 'NAME' => 2, 'REPORT_SIDE' => 3, 'AS_BANK_ACC' => 4, 'AS_INCOME' => 5, 'AS_COST' => 6, 'INC_OPEN_B' => 7, 'INC_CLOSE_B' => 8, 'AS_CLOSE_B' => 9, 'YEAR_ID' => 10, 'FILE_CAT_LEV1_ID' => 11, 'FILE_CAT_LEV2_ID' => 12, 'FILE_CAT_LEV3_ID' => 13, 'TREE_LEFT' => 14, 'TREE_RIGHT' => 15, 'TREE_LEVEL' => 16, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'acc_no' => 1, 'name' => 2, 'report_side' => 3, 'as_bank_acc' => 4, 'as_income' => 5, 'as_cost' => 6, 'inc_open_b' => 7, 'inc_close_b' => 8, 'as_close_b' => 9, 'year_id' => 10, 'file_cat_lev1_id' => 11, 'file_cat_lev2_id' => 12, 'file_cat_lev3_id' => 13, 'tree_left' => 14, 'tree_right' => 15, 'tree_level' => 16, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, )
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
        $toNames = AccountPeer::getFieldNames($toType);
        $key = isset(AccountPeer::$fieldKeys[$fromType][$name]) ? AccountPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(AccountPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, AccountPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return AccountPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. AccountPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(AccountPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(AccountPeer::ID);
            $criteria->addSelectColumn(AccountPeer::ACC_NO);
            $criteria->addSelectColumn(AccountPeer::NAME);
            $criteria->addSelectColumn(AccountPeer::REPORT_SIDE);
            $criteria->addSelectColumn(AccountPeer::AS_BANK_ACC);
            $criteria->addSelectColumn(AccountPeer::AS_INCOME);
            $criteria->addSelectColumn(AccountPeer::AS_COST);
            $criteria->addSelectColumn(AccountPeer::INC_OPEN_B);
            $criteria->addSelectColumn(AccountPeer::INC_CLOSE_B);
            $criteria->addSelectColumn(AccountPeer::AS_CLOSE_B);
            $criteria->addSelectColumn(AccountPeer::YEAR_ID);
            $criteria->addSelectColumn(AccountPeer::FILE_CAT_LEV1_ID);
            $criteria->addSelectColumn(AccountPeer::FILE_CAT_LEV2_ID);
            $criteria->addSelectColumn(AccountPeer::FILE_CAT_LEV3_ID);
            $criteria->addSelectColumn(AccountPeer::TREE_LEFT);
            $criteria->addSelectColumn(AccountPeer::TREE_RIGHT);
            $criteria->addSelectColumn(AccountPeer::TREE_LEVEL);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.acc_no');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.report_side');
            $criteria->addSelectColumn($alias . '.as_bank_acc');
            $criteria->addSelectColumn($alias . '.as_income');
            $criteria->addSelectColumn($alias . '.as_cost');
            $criteria->addSelectColumn($alias . '.inc_open_b');
            $criteria->addSelectColumn($alias . '.inc_close_b');
            $criteria->addSelectColumn($alias . '.as_close_b');
            $criteria->addSelectColumn($alias . '.year_id');
            $criteria->addSelectColumn($alias . '.file_cat_lev1_id');
            $criteria->addSelectColumn($alias . '.file_cat_lev2_id');
            $criteria->addSelectColumn($alias . '.file_cat_lev3_id');
            $criteria->addSelectColumn($alias . '.tree_left');
            $criteria->addSelectColumn($alias . '.tree_right');
            $criteria->addSelectColumn($alias . '.tree_level');
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
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(AccountPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return Account
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = AccountPeer::doSelect($critcopy, $con);
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
        return AccountPeer::populateObjects(AccountPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            AccountPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

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
     * @param Account $obj A Account object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            AccountPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A Account object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof Account) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Account object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(AccountPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return Account Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(AccountPeer::$instances[$key])) {
                return AccountPeer::$instances[$key];
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
        foreach (AccountPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        AccountPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to account
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
        $cls = AccountPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = AccountPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AccountPeer::addInstanceToPool($obj, $key);
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
     * @return array (Account object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = AccountPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + AccountPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AccountPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            AccountPeer::addInstanceToPool($obj, $key);
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
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCatLev1 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileCatLev1(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV1_ID, FileCatPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCatLev2 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileCatLev2(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV2_ID, FileCatPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCatLev3 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileCatLev3(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV3_ID, FileCatPeer::ID, $join_behavior);

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
     * Selects a collection of Account objects pre-filled with their Year objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinYear(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol = AccountPeer::NUM_HYDRATE_COLUMNS;
        YearPeer::addSelectColumns($criteria);

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to $obj2 (Year)
                $obj2->addAccount($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Account objects pre-filled with their FileCat objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileCatLev1(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol = AccountPeer::NUM_HYDRATE_COLUMNS;
        FileCatPeer::addSelectColumns($criteria);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV1_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to $obj2 (FileCat)
                $obj2->addAccountRelatedByFileCatLev1Id($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Account objects pre-filled with their FileCat objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileCatLev2(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol = AccountPeer::NUM_HYDRATE_COLUMNS;
        FileCatPeer::addSelectColumns($criteria);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV2_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to $obj2 (FileCat)
                $obj2->addAccountRelatedByFileCatLev2Id($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Account objects pre-filled with their FileCat objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileCatLev3(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol = AccountPeer::NUM_HYDRATE_COLUMNS;
        FileCatPeer::addSelectColumns($criteria);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV3_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to $obj2 (FileCat)
                $obj2->addAccountRelatedByFileCatLev3Id($obj1);

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
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV1_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV2_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV3_ID, FileCatPeer::ID, $join_behavior);

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
     * Selects a collection of Account objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol2 = AccountPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV1_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV2_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV3_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to the collection in $obj2 (Year)
                $obj2->addAccount($obj1);
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

                // Add the $obj1 (Account) to the collection in $obj3 (FileCat)
                $obj3->addAccountRelatedByFileCatLev1Id($obj1);
            } // if joined row not null

            // Add objects for joined FileCat rows

            $key4 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = FileCatPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = FileCatPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    FileCatPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (Account) to the collection in $obj4 (FileCat)
                $obj4->addAccountRelatedByFileCatLev2Id($obj1);
            } // if joined row not null

            // Add objects for joined FileCat rows

            $key5 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = FileCatPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = FileCatPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    FileCatPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (Account) to the collection in $obj5 (FileCat)
                $obj5->addAccountRelatedByFileCatLev3Id($obj1);
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
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV1_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV2_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV3_ID, FileCatPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCatLev1 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileCatLev1(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCatLev2 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileCatLev2(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileCatLev3 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileCatLev3(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(AccountPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            AccountPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);

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
     * Selects a collection of Account objects pre-filled with all related objects except Year.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
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
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol2 = AccountPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV1_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV2_ID, FileCatPeer::ID, $join_behavior);

        $criteria->addJoin(AccountPeer::FILE_CAT_LEV3_ID, FileCatPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to the collection in $obj2 (FileCat)
                $obj2->addAccountRelatedByFileCatLev1Id($obj1);

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

                // Add the $obj1 (Account) to the collection in $obj3 (FileCat)
                $obj3->addAccountRelatedByFileCatLev2Id($obj1);

            } // if joined row is not null

                // Add objects for joined FileCat rows

                $key4 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = FileCatPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = FileCatPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    FileCatPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (Account) to the collection in $obj4 (FileCat)
                $obj4->addAccountRelatedByFileCatLev3Id($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Account objects pre-filled with all related objects except FileCatLev1.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileCatLev1(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol2 = AccountPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to the collection in $obj2 (Year)
                $obj2->addAccount($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Account objects pre-filled with all related objects except FileCatLev2.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileCatLev2(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol2 = AccountPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to the collection in $obj2 (Year)
                $obj2->addAccount($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of Account objects pre-filled with all related objects except FileCatLev3.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of Account objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileCatLev3(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(AccountPeer::DATABASE_NAME);
        }

        AccountPeer::addSelectColumns($criteria);
        $startcol2 = AccountPeer::NUM_HYDRATE_COLUMNS;

        YearPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + YearPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(AccountPeer::YEAR_ID, YearPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = AccountPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = AccountPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                AccountPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (Account) to the collection in $obj2 (Year)
                $obj2->addAccount($obj1);

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
        return Propel::getDatabaseMap(AccountPeer::DATABASE_NAME)->getTable(AccountPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseAccountPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseAccountPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Oppen\ProjectBundle\Model\map\AccountTableMap());
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
        return AccountPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a Account or Criteria object.
     *
     * @param      mixed $values Criteria or Account object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from Account object
        }

        if ($criteria->containsKey(AccountPeer::ID) && $criteria->keyContainsValue(AccountPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AccountPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a Account or Criteria object.
     *
     * @param      mixed $values Criteria or Account object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(AccountPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(AccountPeer::ID);
            $value = $criteria->remove(AccountPeer::ID);
            if ($value) {
                $selectCriteria->add(AccountPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(AccountPeer::TABLE_NAME);
            }

        } else { // $values is Account object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the account table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(AccountPeer::TABLE_NAME, $con, AccountPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            AccountPeer::clearInstancePool();
            AccountPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a Account or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or Account object or primary key or array of primary keys
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
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            AccountPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof Account) { // it's a model object
            // invalidate the cache for this single object
            AccountPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AccountPeer::DATABASE_NAME);
            $criteria->add(AccountPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                AccountPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(AccountPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            AccountPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given Account object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param Account $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(AccountPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(AccountPeer::TABLE_NAME);

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

        return BasePeer::doValidate(AccountPeer::DATABASE_NAME, AccountPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return Account
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = AccountPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(AccountPeer::DATABASE_NAME);
        $criteria->add(AccountPeer::ID, $pk);

        $v = AccountPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return Account[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(AccountPeer::DATABASE_NAME);
            $criteria->add(AccountPeer::ID, $pks, Criteria::IN);
            $objs = AccountPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // nested_set behavior

    /**
     * Returns the root nodes for the tree
     *
     * @param      PropelPDO $con	Connection to use.
     * @return     Account			Propel object for root node
     */
    public static function retrieveRoots(Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(AccountPeer::DATABASE_NAME);
        }
        $criteria->add(AccountPeer::LEFT_COL, 1, Criteria::EQUAL);

        return AccountPeer::doSelect($criteria, $con);
    }

    /**
     * Returns the root node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     * @return     Account			Propel object for root node
     */
    public static function retrieveRoot($scope = null, PropelPDO $con = null)
    {
        $c = new Criteria(AccountPeer::DATABASE_NAME);
        $c->add(AccountPeer::LEFT_COL, 1, Criteria::EQUAL);
        $c->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return AccountPeer::doSelectOne($c, $con);
    }

    /**
     * Returns the whole tree node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      Criteria $criteria	Optional Criteria to filter the query
     * @param      PropelPDO $con	Connection to use.
     * @return     Account			Propel object for root node
     */
    public static function retrieveTree($scope = null, Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(AccountPeer::DATABASE_NAME);
        }
        $criteria->addAscendingOrderByColumn(AccountPeer::LEFT_COL);
        $criteria->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return AccountPeer::doSelect($criteria, $con);
    }

    /**
     * Tests if node is valid
     *
     * @param      Account $node	Propel object for src node
     * @return     bool
     */
    public static function isValid(Account $node = null)
    {
        if (is_object($node) && $node->getRightValue() > $node->getLeftValue()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete an entire tree
     *
     * @param      int $scope		Scope to determine which tree to delete
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     int  The number of deleted nodes
     */
    public static function deleteTree($scope = null, PropelPDO $con = null)
    {
        $c = new Criteria(AccountPeer::DATABASE_NAME);
        $c->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return AccountPeer::doDelete($c, $con);
    }

    /**
     * Adds $delta to all L and R values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta		Value to be shifted by, can be negative
     * @param      int $first		First node to be shifted
     * @param      int $last			Last node to be shifted (optional)
     * @param      int $scope		Scope to use for the shift
     * @param      PropelPDO $con		Connection to use.
     */
    public static function shiftRLValues($delta, $first, $last = null, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        // Shift left column values
        $whereCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(AccountPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(AccountPeer::LEFT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $valuesCriteria->add(AccountPeer::LEFT_COL, array('raw' => AccountPeer::LEFT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);

        // Shift right column values
        $whereCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(AccountPeer::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(AccountPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $valuesCriteria->add(AccountPeer::RIGHT_COL, array('raw' => AccountPeer::RIGHT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

    /**
     * Adds $delta to level for nodes having left value >= $first and right value <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta		Value to be shifted by, can be negative
     * @param      int $first		First node to be shifted
     * @param      int $last			Last node to be shifted
     * @param      int $scope		Scope to use for the shift
     * @param      PropelPDO $con		Connection to use.
     */
    public static function shiftLevel($delta, $first, $last, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $whereCriteria->add(AccountPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        $whereCriteria->add(AccountPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL);
        $whereCriteria->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $valuesCriteria->add(AccountPeer::LEVEL_COL, array('raw' => AccountPeer::LEVEL_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

    /**
     * Reload all already loaded nodes to sync them with updated db
     *
     * @param      Account $prune		Object to prune from the update
     * @param      PropelPDO $con		Connection to use.
     */
    public static function updateLoadedNodes($prune = null, PropelPDO $con = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            $keys = array();
            foreach (AccountPeer::$instances as $obj) {
                if (!$prune || !$prune->equals($obj)) {
                    $keys[] = $obj->getPrimaryKey();
                }
            }

            if (!empty($keys)) {
                // We don't need to alter the object instance pool; we're just modifying these ones
                // already in the pool.
                $criteria = new Criteria(AccountPeer::DATABASE_NAME);
                $criteria->add(AccountPeer::ID, $keys, Criteria::IN);
                $stmt = AccountPeer::doSelectStmt($criteria, $con);
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $key = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
                    if (null !== ($object = AccountPeer::getInstanceFromPool($key))) {
                        $object->setScopeValue($row[10]);
                        $object->setLeftValue($row[14]);
                        $object->setRightValue($row[15]);
                        $object->setLevel($row[16]);
                        $object->clearNestedSetChildren();
                    }
                }
                $stmt->closeCursor();
            }
        }
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      int $left	left column value
     * @param      integer $scope	scope column value
     * @param      mixed $prune	Object to prune from the shift
     * @param      PropelPDO $con	Connection to use.
     */
    public static function makeRoomForLeaf($left, $scope, $prune = null, PropelPDO $con = null)
    {
        // Update database nodes
        AccountPeer::shiftRLValues(2, $left, null, $scope, $con);

        // Update all loaded nodes
        AccountPeer::updateLoadedNodes($prune, $con);
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      integer $scope	scope column value
     * @param      PropelPDO $con	Connection to use.
     */
    public static function fixLevels($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $c->addAscendingOrderByColumn(AccountPeer::LEFT_COL);
        $stmt = AccountPeer::doSelectStmt($c, $con);

        // set the class once to avoid overhead in the loop
        $cls = AccountPeer::getOMClass(false);
        $level = null;
        // iterate over the statement
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {

            // hydrate object
            $key = AccountPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null === ($obj = AccountPeer::getInstanceFromPool($key))) {
                $obj = new $cls();
                $obj->hydrate($row);
                AccountPeer::addInstanceToPool($obj, $key);
            }

            // compute level
            // Algorithm shamelessly stolen from sfPropelActAsNestedSetBehaviorPlugin
            // Probably authored by Tristan Rivoallan
            if ($level === null) {
                $level = 0;
                $i = 0;
                $prev = array($obj->getRightValue());
            } else {
                while ($obj->getRightValue() > $prev[$i]) {
                    $i--;
                }
                $level = ++$i;
                $prev[$i] = $obj->getRightValue();
            }

            // update level in node if necessary
            if ($obj->getLevel() !== $level) {
                $obj->setLevel($level);
                $obj->save($con);
            }
        }
        $stmt->closeCursor();
    }

    /**
     * Updates all scope values for items that has negative left (<=0) values.
     *
     * @param      mixed     $scope
     * @param      PropelPDO $con	Connection to use.
     */
    public static function setNegativeScope($scope, PropelPDO $con = null)
    {
        //adjust scope value to $scope
        $whereCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $whereCriteria->add(AccountPeer::LEFT_COL, 0, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(AccountPeer::DATABASE_NAME);
        $valuesCriteria->add(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

} // BaseAccountPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseAccountPeer::buildTableMap();

