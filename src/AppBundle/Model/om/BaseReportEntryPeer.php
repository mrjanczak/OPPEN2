<?php

namespace AppBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use AppBundle\Model\ReportEntry;
use AppBundle\Model\ReportEntryPeer;
use AppBundle\Model\ReportPeer;
use AppBundle\Model\map\ReportEntryTableMap;

abstract class BaseReportEntryPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'report_entry';

    /** the related Propel class for this table */
    const OM_CLASS = 'AppBundle\\Model\\ReportEntry';

    /** the related TableMap class for this table */
    const TM_CLASS = 'AppBundle\\Model\\map\\ReportEntryTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 9;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 9;

    /** the column name for the id field */
    const ID = 'report_entry.id';

    /** the column name for the no field */
    const NO = 'report_entry.no';

    /** the column name for the name field */
    const NAME = 'report_entry.name';

    /** the column name for the symbol field */
    const SYMBOL = 'report_entry.symbol';

    /** the column name for the formula field */
    const FORMULA = 'report_entry.formula';

    /** the column name for the report_id field */
    const REPORT_ID = 'report_entry.report_id';

    /** the column name for the tree_left field */
    const TREE_LEFT = 'report_entry.tree_left';

    /** the column name for the tree_right field */
    const TREE_RIGHT = 'report_entry.tree_right';

    /** the column name for the tree_level field */
    const TREE_LEVEL = 'report_entry.tree_level';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of ReportEntry objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array ReportEntry[]
     */
    public static $instances = array();


    // nested_set behavior

    /**
     * Left column for the set
     */
    const LEFT_COL = 'report_entry.tree_left';

    /**
     * Right column for the set
     */
    const RIGHT_COL = 'report_entry.tree_right';

    /**
     * Level column for the set
     */
    const LEVEL_COL = 'report_entry.tree_level';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'report_entry.report_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. ReportEntryPeer::$fieldNames[ReportEntryPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'No', 'Name', 'Symbol', 'Formula', 'ReportId', 'TreeLeft', 'TreeRight', 'TreeLevel', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'no', 'name', 'symbol', 'formula', 'reportId', 'treeLeft', 'treeRight', 'treeLevel', ),
        BasePeer::TYPE_COLNAME => array (ReportEntryPeer::ID, ReportEntryPeer::NO, ReportEntryPeer::NAME, ReportEntryPeer::SYMBOL, ReportEntryPeer::FORMULA, ReportEntryPeer::REPORT_ID, ReportEntryPeer::TREE_LEFT, ReportEntryPeer::TREE_RIGHT, ReportEntryPeer::TREE_LEVEL, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'NO', 'NAME', 'SYMBOL', 'FORMULA', 'REPORT_ID', 'TREE_LEFT', 'TREE_RIGHT', 'TREE_LEVEL', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'no', 'name', 'symbol', 'formula', 'report_id', 'tree_left', 'tree_right', 'tree_level', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. ReportEntryPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'No' => 1, 'Name' => 2, 'Symbol' => 3, 'Formula' => 4, 'ReportId' => 5, 'TreeLeft' => 6, 'TreeRight' => 7, 'TreeLevel' => 8, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'no' => 1, 'name' => 2, 'symbol' => 3, 'formula' => 4, 'reportId' => 5, 'treeLeft' => 6, 'treeRight' => 7, 'treeLevel' => 8, ),
        BasePeer::TYPE_COLNAME => array (ReportEntryPeer::ID => 0, ReportEntryPeer::NO => 1, ReportEntryPeer::NAME => 2, ReportEntryPeer::SYMBOL => 3, ReportEntryPeer::FORMULA => 4, ReportEntryPeer::REPORT_ID => 5, ReportEntryPeer::TREE_LEFT => 6, ReportEntryPeer::TREE_RIGHT => 7, ReportEntryPeer::TREE_LEVEL => 8, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'NO' => 1, 'NAME' => 2, 'SYMBOL' => 3, 'FORMULA' => 4, 'REPORT_ID' => 5, 'TREE_LEFT' => 6, 'TREE_RIGHT' => 7, 'TREE_LEVEL' => 8, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'no' => 1, 'name' => 2, 'symbol' => 3, 'formula' => 4, 'report_id' => 5, 'tree_left' => 6, 'tree_right' => 7, 'tree_level' => 8, ),
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
        $toNames = ReportEntryPeer::getFieldNames($toType);
        $key = isset(ReportEntryPeer::$fieldKeys[$fromType][$name]) ? ReportEntryPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(ReportEntryPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, ReportEntryPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return ReportEntryPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. ReportEntryPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(ReportEntryPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(ReportEntryPeer::ID);
            $criteria->addSelectColumn(ReportEntryPeer::NO);
            $criteria->addSelectColumn(ReportEntryPeer::NAME);
            $criteria->addSelectColumn(ReportEntryPeer::SYMBOL);
            $criteria->addSelectColumn(ReportEntryPeer::FORMULA);
            $criteria->addSelectColumn(ReportEntryPeer::REPORT_ID);
            $criteria->addSelectColumn(ReportEntryPeer::TREE_LEFT);
            $criteria->addSelectColumn(ReportEntryPeer::TREE_RIGHT);
            $criteria->addSelectColumn(ReportEntryPeer::TREE_LEVEL);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.no');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.symbol');
            $criteria->addSelectColumn($alias . '.formula');
            $criteria->addSelectColumn($alias . '.report_id');
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
        $criteria->setPrimaryTableName(ReportEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ReportEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(ReportEntryPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return ReportEntry
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = ReportEntryPeer::doSelect($critcopy, $con);
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
        return ReportEntryPeer::populateObjects(ReportEntryPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            ReportEntryPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);

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
     * @param ReportEntry $obj A ReportEntry object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            ReportEntryPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A ReportEntry object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof ReportEntry) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or ReportEntry object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(ReportEntryPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return ReportEntry Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(ReportEntryPeer::$instances[$key])) {
                return ReportEntryPeer::$instances[$key];
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
        foreach (ReportEntryPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        ReportEntryPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to report_entry
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
        $cls = ReportEntryPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = ReportEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = ReportEntryPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                ReportEntryPeer::addInstanceToPool($obj, $key);
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
     * @return array (ReportEntry object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = ReportEntryPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = ReportEntryPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + ReportEntryPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = ReportEntryPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            ReportEntryPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related Report table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinReport(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(ReportEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ReportEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ReportEntryPeer::REPORT_ID, ReportPeer::ID, $join_behavior);

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
     * Selects a collection of ReportEntry objects pre-filled with their Report objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ReportEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinReport(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);
        }

        ReportEntryPeer::addSelectColumns($criteria);
        $startcol = ReportEntryPeer::NUM_HYDRATE_COLUMNS;
        ReportPeer::addSelectColumns($criteria);

        $criteria->addJoin(ReportEntryPeer::REPORT_ID, ReportPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ReportEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ReportEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = ReportEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ReportEntryPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = ReportPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = ReportPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ReportPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    ReportPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (ReportEntry) to $obj2 (Report)
                $obj2->addReportEntry($obj1);

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
        $criteria->setPrimaryTableName(ReportEntryPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            ReportEntryPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(ReportEntryPeer::REPORT_ID, ReportPeer::ID, $join_behavior);

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
     * Selects a collection of ReportEntry objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of ReportEntry objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);
        }

        ReportEntryPeer::addSelectColumns($criteria);
        $startcol2 = ReportEntryPeer::NUM_HYDRATE_COLUMNS;

        ReportPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + ReportPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(ReportEntryPeer::REPORT_ID, ReportPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = ReportEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = ReportEntryPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = ReportEntryPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                ReportEntryPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined Report rows

            $key2 = ReportPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = ReportPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = ReportPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    ReportPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (ReportEntry) to the collection in $obj2 (Report)
                $obj2->addReportEntry($obj1);
            } // if joined row not null

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
        return Propel::getDatabaseMap(ReportEntryPeer::DATABASE_NAME)->getTable(ReportEntryPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseReportEntryPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseReportEntryPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \AppBundle\Model\map\ReportEntryTableMap());
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
        return ReportEntryPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a ReportEntry or Criteria object.
     *
     * @param      mixed $values Criteria or ReportEntry object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from ReportEntry object
        }

        if ($criteria->containsKey(ReportEntryPeer::ID) && $criteria->keyContainsValue(ReportEntryPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.ReportEntryPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a ReportEntry or Criteria object.
     *
     * @param      mixed $values Criteria or ReportEntry object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(ReportEntryPeer::ID);
            $value = $criteria->remove(ReportEntryPeer::ID);
            if ($value) {
                $selectCriteria->add(ReportEntryPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(ReportEntryPeer::TABLE_NAME);
            }

        } else { // $values is ReportEntry object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the report_entry table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(ReportEntryPeer::TABLE_NAME, $con, ReportEntryPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ReportEntryPeer::clearInstancePool();
            ReportEntryPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a ReportEntry or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or ReportEntry object or primary key or array of primary keys
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
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            ReportEntryPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof ReportEntry) { // it's a model object
            // invalidate the cache for this single object
            ReportEntryPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
            $criteria->add(ReportEntryPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                ReportEntryPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(ReportEntryPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            ReportEntryPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given ReportEntry object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param ReportEntry $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(ReportEntryPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(ReportEntryPeer::TABLE_NAME);

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

        return BasePeer::doValidate(ReportEntryPeer::DATABASE_NAME, ReportEntryPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return ReportEntry
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = ReportEntryPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $criteria->add(ReportEntryPeer::ID, $pk);

        $v = ReportEntryPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return ReportEntry[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
            $criteria->add(ReportEntryPeer::ID, $pks, Criteria::IN);
            $objs = ReportEntryPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // nested_set behavior

    /**
     * Returns the root nodes for the tree
     *
     * @param      PropelPDO $con	Connection to use.
     * @return     ReportEntry			Propel object for root node
     */
    public static function retrieveRoots(Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        }
        $criteria->add(ReportEntryPeer::LEFT_COL, 1, Criteria::EQUAL);

        return ReportEntryPeer::doSelect($criteria, $con);
    }

    /**
     * Returns the root node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     * @return     ReportEntry			Propel object for root node
     */
    public static function retrieveRoot($scope = null, PropelPDO $con = null)
    {
        $c = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $c->add(ReportEntryPeer::LEFT_COL, 1, Criteria::EQUAL);
        $c->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return ReportEntryPeer::doSelectOne($c, $con);
    }

    /**
     * Returns the whole tree node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      Criteria $criteria	Optional Criteria to filter the query
     * @param      PropelPDO $con	Connection to use.
     * @return     ReportEntry			Propel object for root node
     */
    public static function retrieveTree($scope = null, Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        }
        $criteria->addAscendingOrderByColumn(ReportEntryPeer::LEFT_COL);
        $criteria->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return ReportEntryPeer::doSelect($criteria, $con);
    }

    /**
     * Tests if node is valid
     *
     * @param      ReportEntry $node	Propel object for src node
     * @return     bool
     */
    public static function isValid(ReportEntry $node = null)
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
        $c = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $c->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return ReportEntryPeer::doDelete($c, $con);
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
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        // Shift left column values
        $whereCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ReportEntryPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ReportEntryPeer::LEFT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $valuesCriteria->add(ReportEntryPeer::LEFT_COL, array('raw' => ReportEntryPeer::LEFT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);

        // Shift right column values
        $whereCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(ReportEntryPeer::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(ReportEntryPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $valuesCriteria->add(ReportEntryPeer::RIGHT_COL, array('raw' => ReportEntryPeer::RIGHT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

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
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $whereCriteria->add(ReportEntryPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        $whereCriteria->add(ReportEntryPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL);
        $whereCriteria->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $valuesCriteria->add(ReportEntryPeer::LEVEL_COL, array('raw' => ReportEntryPeer::LEVEL_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

    /**
     * Reload all already loaded nodes to sync them with updated db
     *
     * @param      ReportEntry $prune		Object to prune from the update
     * @param      PropelPDO $con		Connection to use.
     */
    public static function updateLoadedNodes($prune = null, PropelPDO $con = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            $keys = array();
            foreach (ReportEntryPeer::$instances as $obj) {
                if (!$prune || !$prune->equals($obj)) {
                    $keys[] = $obj->getPrimaryKey();
                }
            }

            if (!empty($keys)) {
                // We don't need to alter the object instance pool; we're just modifying these ones
                // already in the pool.
                $criteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
                $criteria->add(ReportEntryPeer::ID, $keys, Criteria::IN);
                $stmt = ReportEntryPeer::doSelectStmt($criteria, $con);
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $key = ReportEntryPeer::getPrimaryKeyHashFromRow($row, 0);
                    if (null !== ($object = ReportEntryPeer::getInstanceFromPool($key))) {
                        $object->setScopeValue($row[5]);
                        $object->setLeftValue($row[6]);
                        $object->setRightValue($row[7]);
                        $object->setLevel($row[8]);
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
        ReportEntryPeer::shiftRLValues(2, $left, null, $scope, $con);

        // Update all loaded nodes
        ReportEntryPeer::updateLoadedNodes($prune, $con);
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
        $c->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $c->addAscendingOrderByColumn(ReportEntryPeer::LEFT_COL);
        $stmt = ReportEntryPeer::doSelectStmt($c, $con);

        // set the class once to avoid overhead in the loop
        $cls = ReportEntryPeer::getOMClass(false);
        $level = null;
        // iterate over the statement
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {

            // hydrate object
            $key = ReportEntryPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null === ($obj = ReportEntryPeer::getInstanceFromPool($key))) {
                $obj = new $cls();
                $obj->hydrate($row);
                ReportEntryPeer::addInstanceToPool($obj, $key);
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
        $whereCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $whereCriteria->add(ReportEntryPeer::LEFT_COL, 0, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(ReportEntryPeer::DATABASE_NAME);
        $valuesCriteria->add(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

} // BaseReportEntryPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseReportEntryPeer::buildTableMap();

