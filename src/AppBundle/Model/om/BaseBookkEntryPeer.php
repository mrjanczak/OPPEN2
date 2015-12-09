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
use AppBundle\Model\BookkEntry;
use AppBundle\Model\BookkEntryPeer;
use AppBundle\Model\BookkPeer;
use AppBundle\Model\FilePeer;
use AppBundle\Model\map\BookkEntryTableMap;

abstract class BaseBookkEntryPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'bookk_entry';

    /** the related Propel class for this table */
    const OM_CLASS = 'AppBundle\\Model\\BookkEntry';

    /** the related TableMap class for this table */
    const TM_CLASS = 'AppBundle\\Model\\map\\BookkEntryTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 9;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 9;

    /** the column name for the id field */
    const ID = 'bookk_entry.id';

    /** the column name for the acc_no field */
    const ACC_NO = 'bookk_entry.acc_no';

    /** the column name for the value field */
    const VALUE = 'bookk_entry.value';

    /** the column name for the side field */
    const SIDE = 'bookk_entry.side';

    /** the column name for the bookk_id field */
    const BOOKK_ID = 'bookk_entry.bookk_id';

    /** the column name for the account_id field */
    const ACCOUNT_ID = 'bookk_entry.account_id';

    /** the column name for the file_lev1_id field */
    const FILE_LEV1_ID = 'bookk_entry.file_lev1_id';

    /** the column name for the file_lev2_id field */
    const FILE_LEV2_ID = 'bookk_entry.file_lev2_id';

    /** the column name for the file_lev3_id field */
    const FILE_LEV3_ID = 'bookk_entry.file_lev3_id';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of BookkEntry objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array BookkEntry[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. BookkEntryPeer::$fieldNames[BookkEntryPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'AccNo', 'Value', 'Side', 'BookkId', 'AccountId', 'FileLev1Id', 'FileLev2Id', 'FileLev3Id', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'accNo', 'value', 'side', 'bookkId', 'accountId', 'fileLev1Id', 'fileLev2Id', 'fileLev3Id', ),
        BasePeer::TYPE_COLNAME => array (BookkEntryPeer::ID, BookkEntryPeer::ACC_NO, BookkEntryPeer::VALUE, BookkEntryPeer::SIDE, BookkEntryPeer::BOOKK_ID, BookkEntryPeer::ACCOUNT_ID, BookkEntryPeer::FILE_LEV1_ID, BookkEntryPeer::FILE_LEV2_ID, BookkEntryPeer::FILE_LEV3_ID, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'ACC_NO', 'VALUE', 'SIDE', 'BOOKK_ID', 'ACCOUNT_ID', 'FILE_LEV1_ID', 'FILE_LEV2_ID', 'FILE_LEV3_ID', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'acc_no', 'value', 'side', 'bookk_id', 'account_id', 'file_lev1_id', 'file_lev2_id', 'file_lev3_id', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. BookkEntryPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'AccNo' => 1, 'Value' => 2, 'Side' => 3, 'BookkId' => 4, 'AccountId' => 5, 'FileLev1Id' => 6, 'FileLev2Id' => 7, 'FileLev3Id' => 8, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'accNo' => 1, 'value' => 2, 'side' => 3, 'bookkId' => 4, 'accountId' => 5, 'fileLev1Id' => 6, 'fileLev2Id' => 7, 'fileLev3Id' => 8, ),
        BasePeer::TYPE_COLNAME => array (BookkEntryPeer::ID => 0, BookkEntryPeer::ACC_NO => 1, BookkEntryPeer::VALUE => 2, BookkEntryPeer::SIDE => 3, BookkEntryPeer::BOOKK_ID => 4, BookkEntryPeer::ACCOUNT_ID => 5, BookkEntryPeer::FILE_LEV1_ID => 6, BookkEntryPeer::FILE_LEV2_ID => 7, BookkEntryPeer::FILE_LEV3_ID => 8, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'ACC_NO' => 1, 'VALUE' => 2, 'SIDE' => 3, 'BOOKK_ID' => 4, 'ACCOUNT_ID' => 5, 'FILE_LEV1_ID' => 6, 'FILE_LEV2_ID' => 7, 'FILE_LEV3_ID' => 8, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'acc_no' => 1, 'value' => 2, 'side' => 3, 'bookk_id' => 4, 'account_id' => 5, 'file_lev1_id' => 6, 'file_lev2_id' => 7, 'file_lev3_id' => 8, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
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
        $toNames = BookkEntryPeer::getFieldNames($toType);
        $key = isset(BookkEntryPeer::$fieldKeys[$fromType][$name]) ? BookkEntryPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(BookkEntryPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, BookkEntryPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return BookkEntryPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. BookkEntryPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(BookkEntryPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(BookkEntryPeer::ID);
            $criteria->addSelectColumn(BookkEntryPeer::ACC_NO);
            $criteria->addSelectColumn(BookkEntryPeer::VALUE);
            $criteria->addSelectColumn(BookkEntryPeer::SIDE);
            $criteria->addSelectColumn(BookkEntryPeer::BOOKK_ID);
            $criteria->addSelectColumn(BookkEntryPeer::ACCOUNT_ID);
            $criteria->addSelectColumn(BookkEntryPeer::FILE_LEV1_ID);
            $criteria->addSelectColumn(BookkEntryPeer::FILE_LEV2_ID);
            $criteria->addSelectColumn(BookkEntryPeer::FILE_LEV3_ID);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.acc_no');
            $criteria->addSelectColumn($alias . '.value');
            $criteria->addSelectColumn($alias . '.side');
            $criteria->addSelectColumn($alias . '.bookk_id');
            $criteria->addSelectColumn($alias . '.account_id');
            $criteria->addSelectColumn($alias . '.file_lev1_id');
            $criteria->addSelectColumn($alias . '.file_lev2_id');
            $criteria->addSelectColumn($alias . '.file_lev3_id');
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
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return BookkEntry
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = BookkEntryPeer::doSelect($critcopy, $con);
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
        return BookkEntryPeer::populateObjects(BookkEntryPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            BookkEntryPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

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
     * @param BookkEntry $obj A BookkEntry object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            BookkEntryPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A BookkEntry object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof BookkEntry) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or BookkEntry object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(BookkEntryPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return BookkEntry Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(BookkEntryPeer::$instances[$key])) {
                return BookkEntryPeer::$instances[$key];
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
        foreach (BookkEntryPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        BookkEntryPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to bookk_entry
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
        $cls = BookkEntryPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = BookkEntryPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                BookkEntryPeer::addInstanceToPool($obj, $key);
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
     * @return array (BookkEntry object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = BookkEntryPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = BookkEntryPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + BookkEntryPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = BookkEntryPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            BookkEntryPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Bookk table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinBookk(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Account table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAccount(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileLev1 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileLev1(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileLev2 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileLev2(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileLev3 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileLev3(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);

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
     * Selects a collection of BookkEntry objects pre-filled with their Bookk objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinBookk(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol = BookkEntryPeer::NUM_HYDRATE_COLUMNS;
        BookkPeer::addSelectColumns($criteria);

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = BookkPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = BookkPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = BookkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    BookkPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (BookkEntry) to $obj2 (Bookk)
                $obj2->addBookkEntry($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with their Account objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAccount(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol = BookkEntryPeer::NUM_HYDRATE_COLUMNS;
        AccountPeer::addSelectColumns($criteria);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (BookkEntry) to $obj2 (Account)
                $obj2->addBookkEntry($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with their File objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileLev1(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol = BookkEntryPeer::NUM_HYDRATE_COLUMNS;
        FilePeer::addSelectColumns($criteria);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (BookkEntry) to $obj2 (File)
                $obj2->addBookkEntryRelatedByFileLev1Id($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with their File objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileLev2(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol = BookkEntryPeer::NUM_HYDRATE_COLUMNS;
        FilePeer::addSelectColumns($criteria);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (BookkEntry) to $obj2 (File)
                $obj2->addBookkEntryRelatedByFileLev2Id($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with their File objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileLev3(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol = BookkEntryPeer::NUM_HYDRATE_COLUMNS;
        FilePeer::addSelectColumns($criteria);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (BookkEntry) to $obj2 (File)
                $obj2->addBookkEntryRelatedByFileLev3Id($obj1);

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
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);

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
     * Selects a collection of BookkEntry objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol2 = BookkEntryPeer::NUM_HYDRATE_COLUMNS;

        BookkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + BookkPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FilePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + FilePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + FilePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Bookk rows

            $key2 = BookkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = BookkPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = BookkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    BookkPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj2 (Bookk)
                $obj2->addBookkEntry($obj1);
            } // if joined row not null

            // Add objects for joined Account rows

            $key3 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = AccountPeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = AccountPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    AccountPeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj3 (Account)
                $obj3->addBookkEntry($obj1);
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

                // Add the $obj1 (BookkEntry) to the collection in $obj4 (File)
                $obj4->addBookkEntryRelatedByFileLev1Id($obj1);
            } // if joined row not null

            // Add objects for joined File rows

            $key5 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = FilePeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = FilePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    FilePeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj5 (File)
                $obj5->addBookkEntryRelatedByFileLev2Id($obj1);
            } // if joined row not null

            // Add objects for joined File rows

            $key6 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = FilePeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = FilePeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    FilePeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj6 (File)
                $obj6->addBookkEntryRelatedByFileLev3Id($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related Bookk table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptBookk(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related Account table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptAccount(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileLev1 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileLev1(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileLev2 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileLev2(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related FileLev3 table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileLev3(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            BookkEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

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
     * Selects a collection of BookkEntry objects pre-filled with all related objects except Bookk.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptBookk(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol2 = BookkEntryPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + AccountPeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FilePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FilePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + FilePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Account rows

                $key2 = AccountPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = AccountPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = AccountPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    AccountPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj2 (Account)
                $obj2->addBookkEntry($obj1);

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

                // Add the $obj1 (BookkEntry) to the collection in $obj3 (File)
                $obj3->addBookkEntryRelatedByFileLev1Id($obj1);

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

                // Add the $obj1 (BookkEntry) to the collection in $obj4 (File)
                $obj4->addBookkEntryRelatedByFileLev2Id($obj1);

            } // if joined row is not null

                // Add objects for joined File rows

                $key5 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = FilePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = FilePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    FilePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj5 (File)
                $obj5->addBookkEntryRelatedByFileLev3Id($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with all related objects except Account.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptAccount(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol2 = BookkEntryPeer::NUM_HYDRATE_COLUMNS;

        BookkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + BookkPeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + FilePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + FilePeer::NUM_HYDRATE_COLUMNS;

        FilePeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + FilePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV1_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV2_ID, FilePeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::FILE_LEV3_ID, FilePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Bookk rows

                $key2 = BookkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = BookkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = BookkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    BookkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj2 (Bookk)
                $obj2->addBookkEntry($obj1);

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

                // Add the $obj1 (BookkEntry) to the collection in $obj3 (File)
                $obj3->addBookkEntryRelatedByFileLev1Id($obj1);

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

                // Add the $obj1 (BookkEntry) to the collection in $obj4 (File)
                $obj4->addBookkEntryRelatedByFileLev2Id($obj1);

            } // if joined row is not null

                // Add objects for joined File rows

                $key5 = FilePeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = FilePeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = FilePeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    FilePeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj5 (File)
                $obj5->addBookkEntryRelatedByFileLev3Id($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with all related objects except FileLev1.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileLev1(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol2 = BookkEntryPeer::NUM_HYDRATE_COLUMNS;

        BookkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + BookkPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Bookk rows

                $key2 = BookkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = BookkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = BookkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    BookkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj2 (Bookk)
                $obj2->addBookkEntry($obj1);

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

                // Add the $obj1 (BookkEntry) to the collection in $obj3 (Account)
                $obj3->addBookkEntry($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with all related objects except FileLev2.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileLev2(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol2 = BookkEntryPeer::NUM_HYDRATE_COLUMNS;

        BookkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + BookkPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Bookk rows

                $key2 = BookkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = BookkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = BookkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    BookkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj2 (Bookk)
                $obj2->addBookkEntry($obj1);

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

                // Add the $obj1 (BookkEntry) to the collection in $obj3 (Account)
                $obj3->addBookkEntry($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of BookkEntry objects pre-filled with all related objects except FileLev3.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of BookkEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileLev3(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);
        }

        BookkEntryPeer::addSelectColumns($criteria);
        $startcol2 = BookkEntryPeer::NUM_HYDRATE_COLUMNS;

        BookkPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + BookkPeer::NUM_HYDRATE_COLUMNS;

        AccountPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + AccountPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(BookkEntryPeer::BOOKK_ID, BookkPeer::ID, $join_behavior);

        $criteria->addJoin(BookkEntryPeer::ACCOUNT_ID, AccountPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = BookkEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = BookkEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = BookkEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                BookkEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined Bookk rows

                $key2 = BookkPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = BookkPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = BookkPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    BookkPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (BookkEntry) to the collection in $obj2 (Bookk)
                $obj2->addBookkEntry($obj1);

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

                // Add the $obj1 (BookkEntry) to the collection in $obj3 (Account)
                $obj3->addBookkEntry($obj1);

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
        return Propel::getDatabaseMap(BookkEntryPeer::DATABASE_NAME)->getTable(BookkEntryPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseBookkEntryPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseBookkEntryPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \AppBundle\Model\map\BookkEntryTableMap());
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
        return BookkEntryPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a BookkEntry or Criteria object.
     *
     * @param      mixed $values Criteria or BookkEntry object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from BookkEntry object
        }

        if ($criteria->containsKey(BookkEntryPeer::ID) && $criteria->keyContainsValue(BookkEntryPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.BookkEntryPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a BookkEntry or Criteria object.
     *
     * @param      mixed $values Criteria or BookkEntry object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(BookkEntryPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(BookkEntryPeer::ID);
            $value = $criteria->remove(BookkEntryPeer::ID);
            if ($value) {
                $selectCriteria->add(BookkEntryPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(BookkEntryPeer::TABLE_NAME);
            }

        } else { // $values is BookkEntry object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the bookk_entry table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(BookkEntryPeer::TABLE_NAME, $con, BookkEntryPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BookkEntryPeer::clearInstancePool();
            BookkEntryPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a BookkEntry or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or BookkEntry object or primary key or array of primary keys
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
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            BookkEntryPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof BookkEntry) { // it's a model object
            // invalidate the cache for this single object
            BookkEntryPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(BookkEntryPeer::DATABASE_NAME);
            $criteria->add(BookkEntryPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                BookkEntryPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(BookkEntryPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            BookkEntryPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given BookkEntry object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param BookkEntry $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(BookkEntryPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(BookkEntryPeer::TABLE_NAME);

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

        return BasePeer::doValidate(BookkEntryPeer::DATABASE_NAME, BookkEntryPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return BookkEntry
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = BookkEntryPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(BookkEntryPeer::DATABASE_NAME);
        $criteria->add(BookkEntryPeer::ID, $pk);

        $v = BookkEntryPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return BookkEntry[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(BookkEntryPeer::DATABASE_NAME);
            $criteria->add(BookkEntryPeer::ID, $pks, Criteria::IN);
            $objs = BookkEntryPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseBookkEntryPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseBookkEntryPeer::buildTableMap();

