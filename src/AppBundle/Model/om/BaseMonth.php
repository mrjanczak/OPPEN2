<?php

namespace AppBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use AppBundle\Model\Contract;
use AppBundle\Model\ContractQuery;
use AppBundle\Model\Doc;
use AppBundle\Model\DocQuery;
use AppBundle\Model\Month;
use AppBundle\Model\MonthPeer;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;

abstract class BaseMonth extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'AppBundle\\Model\\MonthPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        MonthPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the is_active field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_active;

    /**
     * The value for the is_closed field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_closed;

    /**
     * The value for the from_date field.
     * @var        string
     */
    protected $from_date;

    /**
     * The value for the to_date field.
     * @var        string
     */
    protected $to_date;

    /**
     * The value for the year_id field.
     * @var        int
     */
    protected $year_id;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        Year
     */
    protected $aYear;

    /**
     * @var        PropelObjectCollection|Doc[] Collection to store aggregation of Doc objects.
     */
    protected $collDocs;
    protected $collDocsPartial;

    /**
     * @var        PropelObjectCollection|Contract[] Collection to store aggregation of Contract objects.
     */
    protected $collContracts;
    protected $collContractsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $docsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $contractsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_active = false;
        $this->is_closed = false;
    }

    /**
     * Initializes internal state of BaseMonth object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [is_active] column value.
     *
     * @return boolean
     */
    public function getIsActive()
    {

        return $this->is_active;
    }

    /**
     * Get the [is_closed] column value.
     *
     * @return boolean
     */
    public function getIsClosed()
    {

        return $this->is_closed;
    }

    /**
     * Get the [optionally formatted] temporal [from_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getFromDate($format = null)
    {
        if ($this->from_date === null) {
            return null;
        }

        if ($this->from_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->from_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->from_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [to_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getToDate($format = null)
    {
        if ($this->to_date === null) {
            return null;
        }

        if ($this->to_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->to_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->to_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [year_id] column value.
     *
     * @return int
     */
    public function getYearId()
    {

        return $this->year_id;
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {

        return $this->sortable_rank;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Month The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = MonthPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Month The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = MonthPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Sets the value of the [is_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Month The current object (for fluent API support)
     */
    public function setIsActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_active !== $v) {
            $this->is_active = $v;
            $this->modifiedColumns[] = MonthPeer::IS_ACTIVE;
        }


        return $this;
    } // setIsActive()

    /**
     * Sets the value of the [is_closed] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Month The current object (for fluent API support)
     */
    public function setIsClosed($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_closed !== $v) {
            $this->is_closed = $v;
            $this->modifiedColumns[] = MonthPeer::IS_CLOSED;
        }


        return $this;
    } // setIsClosed()

    /**
     * Sets the value of [from_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Month The current object (for fluent API support)
     */
    public function setFromDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->from_date !== null || $dt !== null) {
            $currentDateAsString = ($this->from_date !== null && $tmpDt = new DateTime($this->from_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->from_date = $newDateAsString;
                $this->modifiedColumns[] = MonthPeer::FROM_DATE;
            }
        } // if either are not null


        return $this;
    } // setFromDate()

    /**
     * Sets the value of [to_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Month The current object (for fluent API support)
     */
    public function setToDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->to_date !== null || $dt !== null) {
            $currentDateAsString = ($this->to_date !== null && $tmpDt = new DateTime($this->to_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->to_date = $newDateAsString;
                $this->modifiedColumns[] = MonthPeer::TO_DATE;
            }
        } // if either are not null


        return $this;
    } // setToDate()

    /**
     * Set the value of [year_id] column.
     *
     * @param  int $v new value
     * @return Month The current object (for fluent API support)
     */
    public function setYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year_id !== $v) {
            $this->year_id = $v;
            $this->modifiedColumns[] = MonthPeer::YEAR_ID;
        }

        if ($this->aYear !== null && $this->aYear->getId() !== $v) {
            $this->aYear = null;
        }


        return $this;
    } // setYearId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Month The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = MonthPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->is_active !== false) {
                return false;
            }

            if ($this->is_closed !== false) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->is_active = ($row[$startcol + 2] !== null) ? (boolean) $row[$startcol + 2] : null;
            $this->is_closed = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->from_date = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->to_date = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->year_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->sortable_rank = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 8; // 8 = MonthPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Month object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aYear !== null && $this->year_id !== $this->aYear->getId()) {
            $this->aYear = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = MonthPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aYear = null;
            $this->collDocs = null;

            $this->collContracts = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = MonthQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            MonthPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $con);
            MonthPeer::clearInstancePool();

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(MonthPeer::RANK_COL)) {
                    $this->setSortableRank(MonthQuery::create()->getMaxRankArray($con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                MonthPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aYear !== null) {
                if ($this->aYear->isModified() || $this->aYear->isNew()) {
                    $affectedRows += $this->aYear->save($con);
                }
                $this->setYear($this->aYear);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->docsScheduledForDeletion !== null) {
                if (!$this->docsScheduledForDeletion->isEmpty()) {
                    DocQuery::create()
                        ->filterByPrimaryKeys($this->docsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->docsScheduledForDeletion = null;
                }
            }

            if ($this->collDocs !== null) {
                foreach ($this->collDocs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->contractsScheduledForDeletion !== null) {
                if (!$this->contractsScheduledForDeletion->isEmpty()) {
                    ContractQuery::create()
                        ->filterByPrimaryKeys($this->contractsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->contractsScheduledForDeletion = null;
                }
            }

            if ($this->collContracts !== null) {
                foreach ($this->collContracts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = MonthPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . MonthPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(MonthPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(MonthPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(MonthPeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`is_active`';
        }
        if ($this->isColumnModified(MonthPeer::IS_CLOSED)) {
            $modifiedColumns[':p' . $index++]  = '`is_closed`';
        }
        if ($this->isColumnModified(MonthPeer::FROM_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`from_date`';
        }
        if ($this->isColumnModified(MonthPeer::TO_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`to_date`';
        }
        if ($this->isColumnModified(MonthPeer::YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`year_id`';
        }
        if ($this->isColumnModified(MonthPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `month` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`is_active`':
                        $stmt->bindValue($identifier, (int) $this->is_active, PDO::PARAM_INT);
                        break;
                    case '`is_closed`':
                        $stmt->bindValue($identifier, (int) $this->is_closed, PDO::PARAM_INT);
                        break;
                    case '`from_date`':
                        $stmt->bindValue($identifier, $this->from_date, PDO::PARAM_STR);
                        break;
                    case '`to_date`':
                        $stmt->bindValue($identifier, $this->to_date, PDO::PARAM_STR);
                        break;
                    case '`year_id`':
                        $stmt->bindValue($identifier, $this->year_id, PDO::PARAM_INT);
                        break;
                    case '`sortable_rank`':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aYear !== null) {
                if (!$this->aYear->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aYear->getValidationFailures());
                }
            }


            if (($retval = MonthPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collDocs !== null) {
                    foreach ($this->collDocs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collContracts !== null) {
                    foreach ($this->collContracts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = MonthPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getIsActive();
                break;
            case 3:
                return $this->getIsClosed();
                break;
            case 4:
                return $this->getFromDate();
                break;
            case 5:
                return $this->getToDate();
                break;
            case 6:
                return $this->getYearId();
                break;
            case 7:
                return $this->getSortableRank();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Month'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Month'][$this->getPrimaryKey()] = true;
        $keys = MonthPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getIsActive(),
            $keys[3] => $this->getIsClosed(),
            $keys[4] => $this->getFromDate(),
            $keys[5] => $this->getToDate(),
            $keys[6] => $this->getYearId(),
            $keys[7] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aYear) {
                $result['Year'] = $this->aYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDocs) {
                $result['Docs'] = $this->collDocs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collContracts) {
                $result['Contracts'] = $this->collContracts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = MonthPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setIsActive($value);
                break;
            case 3:
                $this->setIsClosed($value);
                break;
            case 4:
                $this->setFromDate($value);
                break;
            case 5:
                $this->setToDate($value);
                break;
            case 6:
                $this->setYearId($value);
                break;
            case 7:
                $this->setSortableRank($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = MonthPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setIsActive($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setIsClosed($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFromDate($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setToDate($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setYearId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setSortableRank($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(MonthPeer::DATABASE_NAME);

        if ($this->isColumnModified(MonthPeer::ID)) $criteria->add(MonthPeer::ID, $this->id);
        if ($this->isColumnModified(MonthPeer::NAME)) $criteria->add(MonthPeer::NAME, $this->name);
        if ($this->isColumnModified(MonthPeer::IS_ACTIVE)) $criteria->add(MonthPeer::IS_ACTIVE, $this->is_active);
        if ($this->isColumnModified(MonthPeer::IS_CLOSED)) $criteria->add(MonthPeer::IS_CLOSED, $this->is_closed);
        if ($this->isColumnModified(MonthPeer::FROM_DATE)) $criteria->add(MonthPeer::FROM_DATE, $this->from_date);
        if ($this->isColumnModified(MonthPeer::TO_DATE)) $criteria->add(MonthPeer::TO_DATE, $this->to_date);
        if ($this->isColumnModified(MonthPeer::YEAR_ID)) $criteria->add(MonthPeer::YEAR_ID, $this->year_id);
        if ($this->isColumnModified(MonthPeer::SORTABLE_RANK)) $criteria->add(MonthPeer::SORTABLE_RANK, $this->sortable_rank);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(MonthPeer::DATABASE_NAME);
        $criteria->add(MonthPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Month (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setIsActive($this->getIsActive());
        $copyObj->setIsClosed($this->getIsClosed());
        $copyObj->setFromDate($this->getFromDate());
        $copyObj->setToDate($this->getToDate());
        $copyObj->setYearId($this->getYearId());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getDocs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDoc($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getContracts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addContract($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Month Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return MonthPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new MonthPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Year object.
     *
     * @param                  Year $v
     * @return Month The current object (for fluent API support)
     * @throws PropelException
     */
    public function setYear(Year $v = null)
    {
        if ($v === null) {
            $this->setYearId(NULL);
        } else {
            $this->setYearId($v->getId());
        }

        $this->aYear = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Year object, it will not be re-added.
        if ($v !== null) {
            $v->addMonth($this);
        }


        return $this;
    }


    /**
     * Get the associated Year object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Year The associated Year object.
     * @throws PropelException
     */
    public function getYear(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aYear === null && ($this->year_id !== null) && $doQuery) {
            $this->aYear = YearQuery::create()->findPk($this->year_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aYear->addMonths($this);
             */
        }

        return $this->aYear;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('Doc' == $relationName) {
            $this->initDocs();
        }
        if ('Contract' == $relationName) {
            $this->initContracts();
        }
    }

    /**
     * Clears out the collDocs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Month The current object (for fluent API support)
     * @see        addDocs()
     */
    public function clearDocs()
    {
        $this->collDocs = null; // important to set this to null since that means it is uninitialized
        $this->collDocsPartial = null;

        return $this;
    }

    /**
     * reset is the collDocs collection loaded partially
     *
     * @return void
     */
    public function resetPartialDocs($v = true)
    {
        $this->collDocsPartial = $v;
    }

    /**
     * Initializes the collDocs collection.
     *
     * By default this just sets the collDocs collection to an empty array (like clearcollDocs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDocs($overrideExisting = true)
    {
        if (null !== $this->collDocs && !$overrideExisting) {
            return;
        }
        $this->collDocs = new PropelObjectCollection();
        $this->collDocs->setModel('Doc');
    }

    /**
     * Gets an array of Doc objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Month is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Doc[] List of Doc objects
     * @throws PropelException
     */
    public function getDocs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDocsPartial && !$this->isNew();
        if (null === $this->collDocs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDocs) {
                // return empty collection
                $this->initDocs();
            } else {
                $collDocs = DocQuery::create(null, $criteria)
                    ->filterByMonth($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDocsPartial && count($collDocs)) {
                      $this->initDocs(false);

                      foreach ($collDocs as $obj) {
                        if (false == $this->collDocs->contains($obj)) {
                          $this->collDocs->append($obj);
                        }
                      }

                      $this->collDocsPartial = true;
                    }

                    $collDocs->getInternalIterator()->rewind();

                    return $collDocs;
                }

                if ($partial && $this->collDocs) {
                    foreach ($this->collDocs as $obj) {
                        if ($obj->isNew()) {
                            $collDocs[] = $obj;
                        }
                    }
                }

                $this->collDocs = $collDocs;
                $this->collDocsPartial = false;
            }
        }

        return $this->collDocs;
    }

    /**
     * Sets a collection of Doc objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $docs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Month The current object (for fluent API support)
     */
    public function setDocs(PropelCollection $docs, PropelPDO $con = null)
    {
        $docsToDelete = $this->getDocs(new Criteria(), $con)->diff($docs);


        $this->docsScheduledForDeletion = $docsToDelete;

        foreach ($docsToDelete as $docRemoved) {
            $docRemoved->setMonth(null);
        }

        $this->collDocs = null;
        foreach ($docs as $doc) {
            $this->addDoc($doc);
        }

        $this->collDocs = $docs;
        $this->collDocsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Doc objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Doc objects.
     * @throws PropelException
     */
    public function countDocs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDocsPartial && !$this->isNew();
        if (null === $this->collDocs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDocs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDocs());
            }
            $query = DocQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMonth($this)
                ->count($con);
        }

        return count($this->collDocs);
    }

    /**
     * Method called to associate a Doc object to this object
     * through the Doc foreign key attribute.
     *
     * @param    Doc $l Doc
     * @return Month The current object (for fluent API support)
     */
    public function addDoc(Doc $l)
    {
        if ($this->collDocs === null) {
            $this->initDocs();
            $this->collDocsPartial = true;
        }

        if (!in_array($l, $this->collDocs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDoc($l);

            if ($this->docsScheduledForDeletion and $this->docsScheduledForDeletion->contains($l)) {
                $this->docsScheduledForDeletion->remove($this->docsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Doc $doc The doc object to add.
     */
    protected function doAddDoc($doc)
    {
        $this->collDocs[]= $doc;
        $doc->setMonth($this);
    }

    /**
     * @param	Doc $doc The doc object to remove.
     * @return Month The current object (for fluent API support)
     */
    public function removeDoc($doc)
    {
        if ($this->getDocs()->contains($doc)) {
            $this->collDocs->remove($this->collDocs->search($doc));
            if (null === $this->docsScheduledForDeletion) {
                $this->docsScheduledForDeletion = clone $this->collDocs;
                $this->docsScheduledForDeletion->clear();
            }
            $this->docsScheduledForDeletion[]= $doc;
            $doc->setMonth(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Month is new, it will return
     * an empty collection; or if this Month has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Month.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Doc[] List of Doc objects
     */
    public function getDocsJoinDocCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocQuery::create(null, $criteria);
        $query->joinWith('DocCat', $join_behavior);

        return $this->getDocs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Month is new, it will return
     * an empty collection; or if this Month has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Month.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Doc[] List of Doc objects
     */
    public function getDocsJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getDocs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Month is new, it will return
     * an empty collection; or if this Month has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Month.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Doc[] List of Doc objects
     */
    public function getDocsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getDocs($query, $con);
    }

    /**
     * Clears out the collContracts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Month The current object (for fluent API support)
     * @see        addContracts()
     */
    public function clearContracts()
    {
        $this->collContracts = null; // important to set this to null since that means it is uninitialized
        $this->collContractsPartial = null;

        return $this;
    }

    /**
     * reset is the collContracts collection loaded partially
     *
     * @return void
     */
    public function resetPartialContracts($v = true)
    {
        $this->collContractsPartial = $v;
    }

    /**
     * Initializes the collContracts collection.
     *
     * By default this just sets the collContracts collection to an empty array (like clearcollContracts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initContracts($overrideExisting = true)
    {
        if (null !== $this->collContracts && !$overrideExisting) {
            return;
        }
        $this->collContracts = new PropelObjectCollection();
        $this->collContracts->setModel('Contract');
    }

    /**
     * Gets an array of Contract objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Month is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Contract[] List of Contract objects
     * @throws PropelException
     */
    public function getContracts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collContractsPartial && !$this->isNew();
        if (null === $this->collContracts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collContracts) {
                // return empty collection
                $this->initContracts();
            } else {
                $collContracts = ContractQuery::create(null, $criteria)
                    ->filterByMonth($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collContractsPartial && count($collContracts)) {
                      $this->initContracts(false);

                      foreach ($collContracts as $obj) {
                        if (false == $this->collContracts->contains($obj)) {
                          $this->collContracts->append($obj);
                        }
                      }

                      $this->collContractsPartial = true;
                    }

                    $collContracts->getInternalIterator()->rewind();

                    return $collContracts;
                }

                if ($partial && $this->collContracts) {
                    foreach ($this->collContracts as $obj) {
                        if ($obj->isNew()) {
                            $collContracts[] = $obj;
                        }
                    }
                }

                $this->collContracts = $collContracts;
                $this->collContractsPartial = false;
            }
        }

        return $this->collContracts;
    }

    /**
     * Sets a collection of Contract objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $contracts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Month The current object (for fluent API support)
     */
    public function setContracts(PropelCollection $contracts, PropelPDO $con = null)
    {
        $contractsToDelete = $this->getContracts(new Criteria(), $con)->diff($contracts);


        $this->contractsScheduledForDeletion = $contractsToDelete;

        foreach ($contractsToDelete as $contractRemoved) {
            $contractRemoved->setMonth(null);
        }

        $this->collContracts = null;
        foreach ($contracts as $contract) {
            $this->addContract($contract);
        }

        $this->collContracts = $contracts;
        $this->collContractsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Contract objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Contract objects.
     * @throws PropelException
     */
    public function countContracts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collContractsPartial && !$this->isNew();
        if (null === $this->collContracts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collContracts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getContracts());
            }
            $query = ContractQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByMonth($this)
                ->count($con);
        }

        return count($this->collContracts);
    }

    /**
     * Method called to associate a Contract object to this object
     * through the Contract foreign key attribute.
     *
     * @param    Contract $l Contract
     * @return Month The current object (for fluent API support)
     */
    public function addContract(Contract $l)
    {
        if ($this->collContracts === null) {
            $this->initContracts();
            $this->collContractsPartial = true;
        }

        if (!in_array($l, $this->collContracts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddContract($l);

            if ($this->contractsScheduledForDeletion and $this->contractsScheduledForDeletion->contains($l)) {
                $this->contractsScheduledForDeletion->remove($this->contractsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Contract $contract The contract object to add.
     */
    protected function doAddContract($contract)
    {
        $this->collContracts[]= $contract;
        $contract->setMonth($this);
    }

    /**
     * @param	Contract $contract The contract object to remove.
     * @return Month The current object (for fluent API support)
     */
    public function removeContract($contract)
    {
        if ($this->getContracts()->contains($contract)) {
            $this->collContracts->remove($this->collContracts->search($contract));
            if (null === $this->contractsScheduledForDeletion) {
                $this->contractsScheduledForDeletion = clone $this->collContracts;
                $this->contractsScheduledForDeletion->clear();
            }
            $this->contractsScheduledForDeletion[]= $contract;
            $contract->setMonth(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Month is new, it will return
     * an empty collection; or if this Month has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Month.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinCost($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('Cost', $join_behavior);

        return $this->getContracts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Month is new, it will return
     * an empty collection; or if this Month has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Month.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinTemplate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('Template', $join_behavior);

        return $this->getContracts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Month is new, it will return
     * an empty collection; or if this Month has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Month.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getContracts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Month is new, it will return
     * an empty collection; or if this Month has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Month.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinDoc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('Doc', $join_behavior);

        return $this->getContracts($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->is_active = null;
        $this->is_closed = null;
        $this->from_date = null;
        $this->to_date = null;
        $this->year_id = null;
        $this->sortable_rank = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collDocs) {
                foreach ($this->collDocs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContracts) {
                foreach ($this->collContracts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aYear instanceof Persistent) {
              $this->aYear->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collDocs instanceof PropelCollection) {
            $this->collDocs->clearIterator();
        }
        $this->collDocs = null;
        if ($this->collContracts instanceof PropelCollection) {
            $this->collContracts->clearIterator();
        }
        $this->collContracts = null;
        $this->aYear = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'name' column
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // sortable behavior

    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    Month
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }

    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }

    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(PropelPDO $con = null)
    {
        return $this->getSortableRank() == MonthQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Month
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = MonthQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Month
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = MonthQuery::create();

        $query->filterByRank($this->getSortableRank() - 1);


        return $query->findOne($con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Month the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = MonthQuery::create()->getMaxRankArray($con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, )
            );
        }

        return $this;
    }

    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param PropelPDO $con optional connection
     *
     * @return    Month the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(MonthQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Month the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }

    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     PropelPDO $con optional connection
     *
     * @return    Month the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > MonthQuery::create()->getMaxRankArray($con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->beginTransaction();
        try {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            MonthPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);

            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     Month $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Month the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();
            $this->setSortableRank($newRank);
            $this->save($con);
            $object->setSortableRank($oldRank);
            $object->save($con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Month the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Month the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object to the top of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Month the current object
     */
    public function moveToTop(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }

        return $this->moveToRank(1, $con);
    }

    /**
     * Move the object to the bottom of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = MonthQuery::create()->getMaxRankArray($con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list.
     * The modifications are not persisted until the object is saved.
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Month the current object
     */
    public function removeFromList(PropelPDO $con = null)
    {
        // Keep the list modification query for the save() transaction
        $this->sortableQueries []= array(
            'callable'  => array(self::PEER, 'shiftRank'),
            'arguments' => array(-1, $this->getSortableRank() + 1, null)
        );
        // remove the object from the list
        $this->setSortableRank(null);

        return $this;
    }

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
    }

}
