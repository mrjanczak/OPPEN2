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
use AppBundle\Model\Account;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\FileCat;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\Month;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\Project;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\Report;
use AppBundle\Model\ReportQuery;
use AppBundle\Model\Year;
use AppBundle\Model\YearPeer;
use AppBundle\Model\YearQuery;

abstract class BaseYear extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'AppBundle\\Model\\YearPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        YearPeer
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
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        PropelObjectCollection|Month[] Collection to store aggregation of Month objects.
     */
    protected $collMonths;
    protected $collMonthsPartial;

    /**
     * @var        PropelObjectCollection|FileCat[] Collection to store aggregation of FileCat objects.
     */
    protected $collFileCats;
    protected $collFileCatsPartial;

    /**
     * @var        PropelObjectCollection|DocCat[] Collection to store aggregation of DocCat objects.
     */
    protected $collDocCats;
    protected $collDocCatsPartial;

    /**
     * @var        PropelObjectCollection|Bookk[] Collection to store aggregation of Bookk objects.
     */
    protected $collBookks;
    protected $collBookksPartial;

    /**
     * @var        PropelObjectCollection|Account[] Collection to store aggregation of Account objects.
     */
    protected $collAccounts;
    protected $collAccountsPartial;

    /**
     * @var        PropelObjectCollection|Report[] Collection to store aggregation of Report objects.
     */
    protected $collReports;
    protected $collReportsPartial;

    /**
     * @var        PropelObjectCollection|Project[] Collection to store aggregation of Project objects.
     */
    protected $collProjects;
    protected $collProjectsPartial;

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
    protected $monthsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fileCatsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $docCatsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $bookksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $accountsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $reportsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $projectsScheduledForDeletion = null;

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
     * Initializes internal state of BaseYear object.
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
     * @return Year The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = YearPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Year The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = YearPeer::NAME;
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
     * @return Year The current object (for fluent API support)
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
            $this->modifiedColumns[] = YearPeer::IS_ACTIVE;
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
     * @return Year The current object (for fluent API support)
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
            $this->modifiedColumns[] = YearPeer::IS_CLOSED;
        }


        return $this;
    } // setIsClosed()

    /**
     * Sets the value of [from_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Year The current object (for fluent API support)
     */
    public function setFromDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->from_date !== null || $dt !== null) {
            $currentDateAsString = ($this->from_date !== null && $tmpDt = new DateTime($this->from_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->from_date = $newDateAsString;
                $this->modifiedColumns[] = YearPeer::FROM_DATE;
            }
        } // if either are not null


        return $this;
    } // setFromDate()

    /**
     * Sets the value of [to_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Year The current object (for fluent API support)
     */
    public function setToDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->to_date !== null || $dt !== null) {
            $currentDateAsString = ($this->to_date !== null && $tmpDt = new DateTime($this->to_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->to_date = $newDateAsString;
                $this->modifiedColumns[] = YearPeer::TO_DATE;
            }
        } // if either are not null


        return $this;
    } // setToDate()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Year The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = YearPeer::SORTABLE_RANK;
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
            $this->sortable_rank = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = YearPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Year object", $e);
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
            $con = Propel::getConnection(YearPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = YearPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collMonths = null;

            $this->collFileCats = null;

            $this->collDocCats = null;

            $this->collBookks = null;

            $this->collAccounts = null;

            $this->collReports = null;

            $this->collProjects = null;

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
            $con = Propel::getConnection(YearPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = YearQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            YearPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $con);
            YearPeer::clearInstancePool();

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
            $con = Propel::getConnection(YearPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(YearPeer::RANK_COL)) {
                    $this->setSortableRank(YearQuery::create()->getMaxRankArray($con) + 1);
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
                YearPeer::addInstanceToPool($this);
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

            if ($this->monthsScheduledForDeletion !== null) {
                if (!$this->monthsScheduledForDeletion->isEmpty()) {
                    MonthQuery::create()
                        ->filterByPrimaryKeys($this->monthsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->monthsScheduledForDeletion = null;
                }
            }

            if ($this->collMonths !== null) {
                foreach ($this->collMonths as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->fileCatsScheduledForDeletion !== null) {
                if (!$this->fileCatsScheduledForDeletion->isEmpty()) {
                    FileCatQuery::create()
                        ->filterByPrimaryKeys($this->fileCatsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->fileCatsScheduledForDeletion = null;
                }
            }

            if ($this->collFileCats !== null) {
                foreach ($this->collFileCats as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->docCatsScheduledForDeletion !== null) {
                if (!$this->docCatsScheduledForDeletion->isEmpty()) {
                    DocCatQuery::create()
                        ->filterByPrimaryKeys($this->docCatsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->docCatsScheduledForDeletion = null;
                }
            }

            if ($this->collDocCats !== null) {
                foreach ($this->collDocCats as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->bookksScheduledForDeletion !== null) {
                if (!$this->bookksScheduledForDeletion->isEmpty()) {
                    BookkQuery::create()
                        ->filterByPrimaryKeys($this->bookksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->bookksScheduledForDeletion = null;
                }
            }

            if ($this->collBookks !== null) {
                foreach ($this->collBookks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->accountsScheduledForDeletion !== null) {
                if (!$this->accountsScheduledForDeletion->isEmpty()) {
                    AccountQuery::create()
                        ->filterByPrimaryKeys($this->accountsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->accountsScheduledForDeletion = null;
                }
            }

            if ($this->collAccounts !== null) {
                foreach ($this->collAccounts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->reportsScheduledForDeletion !== null) {
                if (!$this->reportsScheduledForDeletion->isEmpty()) {
                    ReportQuery::create()
                        ->filterByPrimaryKeys($this->reportsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->reportsScheduledForDeletion = null;
                }
            }

            if ($this->collReports !== null) {
                foreach ($this->collReports as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->projectsScheduledForDeletion !== null) {
                if (!$this->projectsScheduledForDeletion->isEmpty()) {
                    ProjectQuery::create()
                        ->filterByPrimaryKeys($this->projectsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->projectsScheduledForDeletion = null;
                }
            }

            if ($this->collProjects !== null) {
                foreach ($this->collProjects as $referrerFK) {
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

        $this->modifiedColumns[] = YearPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . YearPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(YearPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(YearPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(YearPeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`is_active`';
        }
        if ($this->isColumnModified(YearPeer::IS_CLOSED)) {
            $modifiedColumns[':p' . $index++]  = '`is_closed`';
        }
        if ($this->isColumnModified(YearPeer::FROM_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`from_date`';
        }
        if ($this->isColumnModified(YearPeer::TO_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`to_date`';
        }
        if ($this->isColumnModified(YearPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `year` (%s) VALUES (%s)',
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


            if (($retval = YearPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collMonths !== null) {
                    foreach ($this->collMonths as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFileCats !== null) {
                    foreach ($this->collFileCats as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collDocCats !== null) {
                    foreach ($this->collDocCats as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBookks !== null) {
                    foreach ($this->collBookks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collAccounts !== null) {
                    foreach ($this->collAccounts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collReports !== null) {
                    foreach ($this->collReports as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProjects !== null) {
                    foreach ($this->collProjects as $referrerFK) {
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
        $pos = YearPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
        if (isset($alreadyDumpedObjects['Year'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Year'][$this->getPrimaryKey()] = true;
        $keys = YearPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getIsActive(),
            $keys[3] => $this->getIsClosed(),
            $keys[4] => $this->getFromDate(),
            $keys[5] => $this->getToDate(),
            $keys[6] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collMonths) {
                $result['Months'] = $this->collMonths->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFileCats) {
                $result['FileCats'] = $this->collFileCats->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDocCats) {
                $result['DocCats'] = $this->collDocCats->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBookks) {
                $result['Bookks'] = $this->collBookks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAccounts) {
                $result['Accounts'] = $this->collAccounts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collReports) {
                $result['Reports'] = $this->collReports->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProjects) {
                $result['Projects'] = $this->collProjects->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = YearPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
        $keys = YearPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setIsActive($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setIsClosed($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setFromDate($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setToDate($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSortableRank($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(YearPeer::DATABASE_NAME);

        if ($this->isColumnModified(YearPeer::ID)) $criteria->add(YearPeer::ID, $this->id);
        if ($this->isColumnModified(YearPeer::NAME)) $criteria->add(YearPeer::NAME, $this->name);
        if ($this->isColumnModified(YearPeer::IS_ACTIVE)) $criteria->add(YearPeer::IS_ACTIVE, $this->is_active);
        if ($this->isColumnModified(YearPeer::IS_CLOSED)) $criteria->add(YearPeer::IS_CLOSED, $this->is_closed);
        if ($this->isColumnModified(YearPeer::FROM_DATE)) $criteria->add(YearPeer::FROM_DATE, $this->from_date);
        if ($this->isColumnModified(YearPeer::TO_DATE)) $criteria->add(YearPeer::TO_DATE, $this->to_date);
        if ($this->isColumnModified(YearPeer::SORTABLE_RANK)) $criteria->add(YearPeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(YearPeer::DATABASE_NAME);
        $criteria->add(YearPeer::ID, $this->id);

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
     * @param object $copyObj An object of Year (or compatible) type.
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
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getMonths() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addMonth($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFileCats() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFileCat($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDocCats() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDocCat($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBookks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookk($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAccounts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAccount($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getReports() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addReport($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProjects() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProject($relObj->copy($deepCopy));
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
     * @return Year Clone of current object.
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
     * @return YearPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new YearPeer();
        }

        return self::$peer;
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
        if ('Month' == $relationName) {
            $this->initMonths();
        }
        if ('FileCat' == $relationName) {
            $this->initFileCats();
        }
        if ('DocCat' == $relationName) {
            $this->initDocCats();
        }
        if ('Bookk' == $relationName) {
            $this->initBookks();
        }
        if ('Account' == $relationName) {
            $this->initAccounts();
        }
        if ('Report' == $relationName) {
            $this->initReports();
        }
        if ('Project' == $relationName) {
            $this->initProjects();
        }
    }

    /**
     * Clears out the collMonths collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Year The current object (for fluent API support)
     * @see        addMonths()
     */
    public function clearMonths()
    {
        $this->collMonths = null; // important to set this to null since that means it is uninitialized
        $this->collMonthsPartial = null;

        return $this;
    }

    /**
     * reset is the collMonths collection loaded partially
     *
     * @return void
     */
    public function resetPartialMonths($v = true)
    {
        $this->collMonthsPartial = $v;
    }

    /**
     * Initializes the collMonths collection.
     *
     * By default this just sets the collMonths collection to an empty array (like clearcollMonths());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initMonths($overrideExisting = true)
    {
        if (null !== $this->collMonths && !$overrideExisting) {
            return;
        }
        $this->collMonths = new PropelObjectCollection();
        $this->collMonths->setModel('Month');
    }

    /**
     * Gets an array of Month objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Year is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Month[] List of Month objects
     * @throws PropelException
     */
    public function getMonths($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMonthsPartial && !$this->isNew();
        if (null === $this->collMonths || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMonths) {
                // return empty collection
                $this->initMonths();
            } else {
                $collMonths = MonthQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMonthsPartial && count($collMonths)) {
                      $this->initMonths(false);

                      foreach ($collMonths as $obj) {
                        if (false == $this->collMonths->contains($obj)) {
                          $this->collMonths->append($obj);
                        }
                      }

                      $this->collMonthsPartial = true;
                    }

                    $collMonths->getInternalIterator()->rewind();

                    return $collMonths;
                }

                if ($partial && $this->collMonths) {
                    foreach ($this->collMonths as $obj) {
                        if ($obj->isNew()) {
                            $collMonths[] = $obj;
                        }
                    }
                }

                $this->collMonths = $collMonths;
                $this->collMonthsPartial = false;
            }
        }

        return $this->collMonths;
    }

    /**
     * Sets a collection of Month objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $months A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Year The current object (for fluent API support)
     */
    public function setMonths(PropelCollection $months, PropelPDO $con = null)
    {
        $monthsToDelete = $this->getMonths(new Criteria(), $con)->diff($months);


        $this->monthsScheduledForDeletion = $monthsToDelete;

        foreach ($monthsToDelete as $monthRemoved) {
            $monthRemoved->setYear(null);
        }

        $this->collMonths = null;
        foreach ($months as $month) {
            $this->addMonth($month);
        }

        $this->collMonths = $months;
        $this->collMonthsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Month objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Month objects.
     * @throws PropelException
     */
    public function countMonths(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collMonthsPartial && !$this->isNew();
        if (null === $this->collMonths || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collMonths) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getMonths());
            }
            $query = MonthQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByYear($this)
                ->count($con);
        }

        return count($this->collMonths);
    }

    /**
     * Method called to associate a Month object to this object
     * through the Month foreign key attribute.
     *
     * @param    Month $l Month
     * @return Year The current object (for fluent API support)
     */
    public function addMonth(Month $l)
    {
        if ($this->collMonths === null) {
            $this->initMonths();
            $this->collMonthsPartial = true;
        }

        if (!in_array($l, $this->collMonths->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddMonth($l);

            if ($this->monthsScheduledForDeletion and $this->monthsScheduledForDeletion->contains($l)) {
                $this->monthsScheduledForDeletion->remove($this->monthsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Month $month The month object to add.
     */
    protected function doAddMonth($month)
    {
        $this->collMonths[]= $month;
        $month->setYear($this);
    }

    /**
     * @param	Month $month The month object to remove.
     * @return Year The current object (for fluent API support)
     */
    public function removeMonth($month)
    {
        if ($this->getMonths()->contains($month)) {
            $this->collMonths->remove($this->collMonths->search($month));
            if (null === $this->monthsScheduledForDeletion) {
                $this->monthsScheduledForDeletion = clone $this->collMonths;
                $this->monthsScheduledForDeletion->clear();
            }
            $this->monthsScheduledForDeletion[]= $month;
            $month->setYear(null);
        }

        return $this;
    }

    /**
     * Clears out the collFileCats collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Year The current object (for fluent API support)
     * @see        addFileCats()
     */
    public function clearFileCats()
    {
        $this->collFileCats = null; // important to set this to null since that means it is uninitialized
        $this->collFileCatsPartial = null;

        return $this;
    }

    /**
     * reset is the collFileCats collection loaded partially
     *
     * @return void
     */
    public function resetPartialFileCats($v = true)
    {
        $this->collFileCatsPartial = $v;
    }

    /**
     * Initializes the collFileCats collection.
     *
     * By default this just sets the collFileCats collection to an empty array (like clearcollFileCats());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFileCats($overrideExisting = true)
    {
        if (null !== $this->collFileCats && !$overrideExisting) {
            return;
        }
        $this->collFileCats = new PropelObjectCollection();
        $this->collFileCats->setModel('FileCat');
    }

    /**
     * Gets an array of FileCat objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Year is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|FileCat[] List of FileCat objects
     * @throws PropelException
     */
    public function getFileCats($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFileCatsPartial && !$this->isNew();
        if (null === $this->collFileCats || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFileCats) {
                // return empty collection
                $this->initFileCats();
            } else {
                $collFileCats = FileCatQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFileCatsPartial && count($collFileCats)) {
                      $this->initFileCats(false);

                      foreach ($collFileCats as $obj) {
                        if (false == $this->collFileCats->contains($obj)) {
                          $this->collFileCats->append($obj);
                        }
                      }

                      $this->collFileCatsPartial = true;
                    }

                    $collFileCats->getInternalIterator()->rewind();

                    return $collFileCats;
                }

                if ($partial && $this->collFileCats) {
                    foreach ($this->collFileCats as $obj) {
                        if ($obj->isNew()) {
                            $collFileCats[] = $obj;
                        }
                    }
                }

                $this->collFileCats = $collFileCats;
                $this->collFileCatsPartial = false;
            }
        }

        return $this->collFileCats;
    }

    /**
     * Sets a collection of FileCat objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $fileCats A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Year The current object (for fluent API support)
     */
    public function setFileCats(PropelCollection $fileCats, PropelPDO $con = null)
    {
        $fileCatsToDelete = $this->getFileCats(new Criteria(), $con)->diff($fileCats);


        $this->fileCatsScheduledForDeletion = $fileCatsToDelete;

        foreach ($fileCatsToDelete as $fileCatRemoved) {
            $fileCatRemoved->setYear(null);
        }

        $this->collFileCats = null;
        foreach ($fileCats as $fileCat) {
            $this->addFileCat($fileCat);
        }

        $this->collFileCats = $fileCats;
        $this->collFileCatsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related FileCat objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related FileCat objects.
     * @throws PropelException
     */
    public function countFileCats(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFileCatsPartial && !$this->isNew();
        if (null === $this->collFileCats || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFileCats) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFileCats());
            }
            $query = FileCatQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByYear($this)
                ->count($con);
        }

        return count($this->collFileCats);
    }

    /**
     * Method called to associate a FileCat object to this object
     * through the FileCat foreign key attribute.
     *
     * @param    FileCat $l FileCat
     * @return Year The current object (for fluent API support)
     */
    public function addFileCat(FileCat $l)
    {
        if ($this->collFileCats === null) {
            $this->initFileCats();
            $this->collFileCatsPartial = true;
        }

        if (!in_array($l, $this->collFileCats->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFileCat($l);

            if ($this->fileCatsScheduledForDeletion and $this->fileCatsScheduledForDeletion->contains($l)) {
                $this->fileCatsScheduledForDeletion->remove($this->fileCatsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	FileCat $fileCat The fileCat object to add.
     */
    protected function doAddFileCat($fileCat)
    {
        $this->collFileCats[]= $fileCat;
        $fileCat->setYear($this);
    }

    /**
     * @param	FileCat $fileCat The fileCat object to remove.
     * @return Year The current object (for fluent API support)
     */
    public function removeFileCat($fileCat)
    {
        if ($this->getFileCats()->contains($fileCat)) {
            $this->collFileCats->remove($this->collFileCats->search($fileCat));
            if (null === $this->fileCatsScheduledForDeletion) {
                $this->fileCatsScheduledForDeletion = clone $this->collFileCats;
                $this->fileCatsScheduledForDeletion->clear();
            }
            $this->fileCatsScheduledForDeletion[]= $fileCat;
            $fileCat->setYear(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related FileCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FileCat[] List of FileCat objects
     */
    public function getFileCatsJoinSubFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FileCatQuery::create(null, $criteria);
        $query->joinWith('SubFileCat', $join_behavior);

        return $this->getFileCats($query, $con);
    }

    /**
     * Clears out the collDocCats collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Year The current object (for fluent API support)
     * @see        addDocCats()
     */
    public function clearDocCats()
    {
        $this->collDocCats = null; // important to set this to null since that means it is uninitialized
        $this->collDocCatsPartial = null;

        return $this;
    }

    /**
     * reset is the collDocCats collection loaded partially
     *
     * @return void
     */
    public function resetPartialDocCats($v = true)
    {
        $this->collDocCatsPartial = $v;
    }

    /**
     * Initializes the collDocCats collection.
     *
     * By default this just sets the collDocCats collection to an empty array (like clearcollDocCats());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDocCats($overrideExisting = true)
    {
        if (null !== $this->collDocCats && !$overrideExisting) {
            return;
        }
        $this->collDocCats = new PropelObjectCollection();
        $this->collDocCats->setModel('DocCat');
    }

    /**
     * Gets an array of DocCat objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Year is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     * @throws PropelException
     */
    public function getDocCats($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDocCatsPartial && !$this->isNew();
        if (null === $this->collDocCats || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDocCats) {
                // return empty collection
                $this->initDocCats();
            } else {
                $collDocCats = DocCatQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDocCatsPartial && count($collDocCats)) {
                      $this->initDocCats(false);

                      foreach ($collDocCats as $obj) {
                        if (false == $this->collDocCats->contains($obj)) {
                          $this->collDocCats->append($obj);
                        }
                      }

                      $this->collDocCatsPartial = true;
                    }

                    $collDocCats->getInternalIterator()->rewind();

                    return $collDocCats;
                }

                if ($partial && $this->collDocCats) {
                    foreach ($this->collDocCats as $obj) {
                        if ($obj->isNew()) {
                            $collDocCats[] = $obj;
                        }
                    }
                }

                $this->collDocCats = $collDocCats;
                $this->collDocCatsPartial = false;
            }
        }

        return $this->collDocCats;
    }

    /**
     * Sets a collection of DocCat objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $docCats A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Year The current object (for fluent API support)
     */
    public function setDocCats(PropelCollection $docCats, PropelPDO $con = null)
    {
        $docCatsToDelete = $this->getDocCats(new Criteria(), $con)->diff($docCats);


        $this->docCatsScheduledForDeletion = $docCatsToDelete;

        foreach ($docCatsToDelete as $docCatRemoved) {
            $docCatRemoved->setYear(null);
        }

        $this->collDocCats = null;
        foreach ($docCats as $docCat) {
            $this->addDocCat($docCat);
        }

        $this->collDocCats = $docCats;
        $this->collDocCatsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related DocCat objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related DocCat objects.
     * @throws PropelException
     */
    public function countDocCats(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDocCatsPartial && !$this->isNew();
        if (null === $this->collDocCats || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDocCats) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDocCats());
            }
            $query = DocCatQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByYear($this)
                ->count($con);
        }

        return count($this->collDocCats);
    }

    /**
     * Method called to associate a DocCat object to this object
     * through the DocCat foreign key attribute.
     *
     * @param    DocCat $l DocCat
     * @return Year The current object (for fluent API support)
     */
    public function addDocCat(DocCat $l)
    {
        if ($this->collDocCats === null) {
            $this->initDocCats();
            $this->collDocCatsPartial = true;
        }

        if (!in_array($l, $this->collDocCats->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDocCat($l);

            if ($this->docCatsScheduledForDeletion and $this->docCatsScheduledForDeletion->contains($l)) {
                $this->docCatsScheduledForDeletion->remove($this->docCatsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	DocCat $docCat The docCat object to add.
     */
    protected function doAddDocCat($docCat)
    {
        $this->collDocCats[]= $docCat;
        $docCat->setYear($this);
    }

    /**
     * @param	DocCat $docCat The docCat object to remove.
     * @return Year The current object (for fluent API support)
     */
    public function removeDocCat($docCat)
    {
        if ($this->getDocCats()->contains($docCat)) {
            $this->collDocCats->remove($this->collDocCats->search($docCat));
            if (null === $this->docCatsScheduledForDeletion) {
                $this->docCatsScheduledForDeletion = clone $this->collDocCats;
                $this->docCatsScheduledForDeletion->clear();
            }
            $this->docCatsScheduledForDeletion[]= $docCat;
            $docCat->setYear(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related DocCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsJoinFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('FileCat', $join_behavior);

        return $this->getDocCats($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related DocCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsJoinCommitmentAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('CommitmentAcc', $join_behavior);

        return $this->getDocCats($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related DocCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsJoinTaxCommitmentAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('TaxCommitmentAcc', $join_behavior);

        return $this->getDocCats($query, $con);
    }

    /**
     * Clears out the collBookks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Year The current object (for fluent API support)
     * @see        addBookks()
     */
    public function clearBookks()
    {
        $this->collBookks = null; // important to set this to null since that means it is uninitialized
        $this->collBookksPartial = null;

        return $this;
    }

    /**
     * reset is the collBookks collection loaded partially
     *
     * @return void
     */
    public function resetPartialBookks($v = true)
    {
        $this->collBookksPartial = $v;
    }

    /**
     * Initializes the collBookks collection.
     *
     * By default this just sets the collBookks collection to an empty array (like clearcollBookks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBookks($overrideExisting = true)
    {
        if (null !== $this->collBookks && !$overrideExisting) {
            return;
        }
        $this->collBookks = new PropelObjectCollection();
        $this->collBookks->setModel('Bookk');
    }

    /**
     * Gets an array of Bookk objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Year is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Bookk[] List of Bookk objects
     * @throws PropelException
     */
    public function getBookks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookksPartial && !$this->isNew();
        if (null === $this->collBookks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookks) {
                // return empty collection
                $this->initBookks();
            } else {
                $collBookks = BookkQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookksPartial && count($collBookks)) {
                      $this->initBookks(false);

                      foreach ($collBookks as $obj) {
                        if (false == $this->collBookks->contains($obj)) {
                          $this->collBookks->append($obj);
                        }
                      }

                      $this->collBookksPartial = true;
                    }

                    $collBookks->getInternalIterator()->rewind();

                    return $collBookks;
                }

                if ($partial && $this->collBookks) {
                    foreach ($this->collBookks as $obj) {
                        if ($obj->isNew()) {
                            $collBookks[] = $obj;
                        }
                    }
                }

                $this->collBookks = $collBookks;
                $this->collBookksPartial = false;
            }
        }

        return $this->collBookks;
    }

    /**
     * Sets a collection of Bookk objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $bookks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Year The current object (for fluent API support)
     */
    public function setBookks(PropelCollection $bookks, PropelPDO $con = null)
    {
        $bookksToDelete = $this->getBookks(new Criteria(), $con)->diff($bookks);


        $this->bookksScheduledForDeletion = $bookksToDelete;

        foreach ($bookksToDelete as $bookkRemoved) {
            $bookkRemoved->setYear(null);
        }

        $this->collBookks = null;
        foreach ($bookks as $bookk) {
            $this->addBookk($bookk);
        }

        $this->collBookks = $bookks;
        $this->collBookksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Bookk objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Bookk objects.
     * @throws PropelException
     */
    public function countBookks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBookksPartial && !$this->isNew();
        if (null === $this->collBookks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBookks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBookks());
            }
            $query = BookkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByYear($this)
                ->count($con);
        }

        return count($this->collBookks);
    }

    /**
     * Method called to associate a Bookk object to this object
     * through the Bookk foreign key attribute.
     *
     * @param    Bookk $l Bookk
     * @return Year The current object (for fluent API support)
     */
    public function addBookk(Bookk $l)
    {
        if ($this->collBookks === null) {
            $this->initBookks();
            $this->collBookksPartial = true;
        }

        if (!in_array($l, $this->collBookks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBookk($l);

            if ($this->bookksScheduledForDeletion and $this->bookksScheduledForDeletion->contains($l)) {
                $this->bookksScheduledForDeletion->remove($this->bookksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Bookk $bookk The bookk object to add.
     */
    protected function doAddBookk($bookk)
    {
        $this->collBookks[]= $bookk;
        $bookk->setYear($this);
    }

    /**
     * @param	Bookk $bookk The bookk object to remove.
     * @return Year The current object (for fluent API support)
     */
    public function removeBookk($bookk)
    {
        if ($this->getBookks()->contains($bookk)) {
            $this->collBookks->remove($this->collBookks->search($bookk));
            if (null === $this->bookksScheduledForDeletion) {
                $this->bookksScheduledForDeletion = clone $this->collBookks;
                $this->bookksScheduledForDeletion->clear();
            }
            $this->bookksScheduledForDeletion[]= $bookk;
            $bookk->setYear(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Bookks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Bookk[] List of Bookk objects
     */
    public function getBookksJoinDoc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkQuery::create(null, $criteria);
        $query->joinWith('Doc', $join_behavior);

        return $this->getBookks($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Bookks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Bookk[] List of Bookk objects
     */
    public function getBookksJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getBookks($query, $con);
    }

    /**
     * Clears out the collAccounts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Year The current object (for fluent API support)
     * @see        addAccounts()
     */
    public function clearAccounts()
    {
        $this->collAccounts = null; // important to set this to null since that means it is uninitialized
        $this->collAccountsPartial = null;

        return $this;
    }

    /**
     * reset is the collAccounts collection loaded partially
     *
     * @return void
     */
    public function resetPartialAccounts($v = true)
    {
        $this->collAccountsPartial = $v;
    }

    /**
     * Initializes the collAccounts collection.
     *
     * By default this just sets the collAccounts collection to an empty array (like clearcollAccounts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAccounts($overrideExisting = true)
    {
        if (null !== $this->collAccounts && !$overrideExisting) {
            return;
        }
        $this->collAccounts = new PropelObjectCollection();
        $this->collAccounts->setModel('Account');
    }

    /**
     * Gets an array of Account objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Year is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Account[] List of Account objects
     * @throws PropelException
     */
    public function getAccounts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAccountsPartial && !$this->isNew();
        if (null === $this->collAccounts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAccounts) {
                // return empty collection
                $this->initAccounts();
            } else {
                $collAccounts = AccountQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAccountsPartial && count($collAccounts)) {
                      $this->initAccounts(false);

                      foreach ($collAccounts as $obj) {
                        if (false == $this->collAccounts->contains($obj)) {
                          $this->collAccounts->append($obj);
                        }
                      }

                      $this->collAccountsPartial = true;
                    }

                    $collAccounts->getInternalIterator()->rewind();

                    return $collAccounts;
                }

                if ($partial && $this->collAccounts) {
                    foreach ($this->collAccounts as $obj) {
                        if ($obj->isNew()) {
                            $collAccounts[] = $obj;
                        }
                    }
                }

                $this->collAccounts = $collAccounts;
                $this->collAccountsPartial = false;
            }
        }

        return $this->collAccounts;
    }

    /**
     * Sets a collection of Account objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $accounts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Year The current object (for fluent API support)
     */
    public function setAccounts(PropelCollection $accounts, PropelPDO $con = null)
    {
        $accountsToDelete = $this->getAccounts(new Criteria(), $con)->diff($accounts);


        $this->accountsScheduledForDeletion = $accountsToDelete;

        foreach ($accountsToDelete as $accountRemoved) {
            $accountRemoved->setYear(null);
        }

        $this->collAccounts = null;
        foreach ($accounts as $account) {
            $this->addAccount($account);
        }

        $this->collAccounts = $accounts;
        $this->collAccountsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Account objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Account objects.
     * @throws PropelException
     */
    public function countAccounts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAccountsPartial && !$this->isNew();
        if (null === $this->collAccounts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAccounts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAccounts());
            }
            $query = AccountQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByYear($this)
                ->count($con);
        }

        return count($this->collAccounts);
    }

    /**
     * Method called to associate a Account object to this object
     * through the Account foreign key attribute.
     *
     * @param    Account $l Account
     * @return Year The current object (for fluent API support)
     */
    public function addAccount(Account $l)
    {
        if ($this->collAccounts === null) {
            $this->initAccounts();
            $this->collAccountsPartial = true;
        }

        if (!in_array($l, $this->collAccounts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAccount($l);

            if ($this->accountsScheduledForDeletion and $this->accountsScheduledForDeletion->contains($l)) {
                $this->accountsScheduledForDeletion->remove($this->accountsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Account $account The account object to add.
     */
    protected function doAddAccount($account)
    {
        $this->collAccounts[]= $account;
        $account->setYear($this);
    }

    /**
     * @param	Account $account The account object to remove.
     * @return Year The current object (for fluent API support)
     */
    public function removeAccount($account)
    {
        if ($this->getAccounts()->contains($account)) {
            $this->collAccounts->remove($this->collAccounts->search($account));
            if (null === $this->accountsScheduledForDeletion) {
                $this->accountsScheduledForDeletion = clone $this->collAccounts;
                $this->accountsScheduledForDeletion->clear();
            }
            $this->accountsScheduledForDeletion[]= $account;
            $account->setYear(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Accounts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Account[] List of Account objects
     */
    public function getAccountsJoinFileCatLev1($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AccountQuery::create(null, $criteria);
        $query->joinWith('FileCatLev1', $join_behavior);

        return $this->getAccounts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Accounts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Account[] List of Account objects
     */
    public function getAccountsJoinFileCatLev2($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AccountQuery::create(null, $criteria);
        $query->joinWith('FileCatLev2', $join_behavior);

        return $this->getAccounts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Accounts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Account[] List of Account objects
     */
    public function getAccountsJoinFileCatLev3($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AccountQuery::create(null, $criteria);
        $query->joinWith('FileCatLev3', $join_behavior);

        return $this->getAccounts($query, $con);
    }

    /**
     * Clears out the collReports collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Year The current object (for fluent API support)
     * @see        addReports()
     */
    public function clearReports()
    {
        $this->collReports = null; // important to set this to null since that means it is uninitialized
        $this->collReportsPartial = null;

        return $this;
    }

    /**
     * reset is the collReports collection loaded partially
     *
     * @return void
     */
    public function resetPartialReports($v = true)
    {
        $this->collReportsPartial = $v;
    }

    /**
     * Initializes the collReports collection.
     *
     * By default this just sets the collReports collection to an empty array (like clearcollReports());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initReports($overrideExisting = true)
    {
        if (null !== $this->collReports && !$overrideExisting) {
            return;
        }
        $this->collReports = new PropelObjectCollection();
        $this->collReports->setModel('Report');
    }

    /**
     * Gets an array of Report objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Year is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Report[] List of Report objects
     * @throws PropelException
     */
    public function getReports($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collReportsPartial && !$this->isNew();
        if (null === $this->collReports || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collReports) {
                // return empty collection
                $this->initReports();
            } else {
                $collReports = ReportQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collReportsPartial && count($collReports)) {
                      $this->initReports(false);

                      foreach ($collReports as $obj) {
                        if (false == $this->collReports->contains($obj)) {
                          $this->collReports->append($obj);
                        }
                      }

                      $this->collReportsPartial = true;
                    }

                    $collReports->getInternalIterator()->rewind();

                    return $collReports;
                }

                if ($partial && $this->collReports) {
                    foreach ($this->collReports as $obj) {
                        if ($obj->isNew()) {
                            $collReports[] = $obj;
                        }
                    }
                }

                $this->collReports = $collReports;
                $this->collReportsPartial = false;
            }
        }

        return $this->collReports;
    }

    /**
     * Sets a collection of Report objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $reports A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Year The current object (for fluent API support)
     */
    public function setReports(PropelCollection $reports, PropelPDO $con = null)
    {
        $reportsToDelete = $this->getReports(new Criteria(), $con)->diff($reports);


        $this->reportsScheduledForDeletion = $reportsToDelete;

        foreach ($reportsToDelete as $reportRemoved) {
            $reportRemoved->setYear(null);
        }

        $this->collReports = null;
        foreach ($reports as $report) {
            $this->addReport($report);
        }

        $this->collReports = $reports;
        $this->collReportsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Report objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Report objects.
     * @throws PropelException
     */
    public function countReports(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collReportsPartial && !$this->isNew();
        if (null === $this->collReports || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collReports) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getReports());
            }
            $query = ReportQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByYear($this)
                ->count($con);
        }

        return count($this->collReports);
    }

    /**
     * Method called to associate a Report object to this object
     * through the Report foreign key attribute.
     *
     * @param    Report $l Report
     * @return Year The current object (for fluent API support)
     */
    public function addReport(Report $l)
    {
        if ($this->collReports === null) {
            $this->initReports();
            $this->collReportsPartial = true;
        }

        if (!in_array($l, $this->collReports->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddReport($l);

            if ($this->reportsScheduledForDeletion and $this->reportsScheduledForDeletion->contains($l)) {
                $this->reportsScheduledForDeletion->remove($this->reportsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Report $report The report object to add.
     */
    protected function doAddReport($report)
    {
        $this->collReports[]= $report;
        $report->setYear($this);
    }

    /**
     * @param	Report $report The report object to remove.
     * @return Year The current object (for fluent API support)
     */
    public function removeReport($report)
    {
        if ($this->getReports()->contains($report)) {
            $this->collReports->remove($this->collReports->search($report));
            if (null === $this->reportsScheduledForDeletion) {
                $this->reportsScheduledForDeletion = clone $this->collReports;
                $this->reportsScheduledForDeletion->clear();
            }
            $this->reportsScheduledForDeletion[]= $report;
            $report->setYear(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Reports from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Report[] List of Report objects
     */
    public function getReportsJoinTemplate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ReportQuery::create(null, $criteria);
        $query->joinWith('Template', $join_behavior);

        return $this->getReports($query, $con);
    }

    /**
     * Clears out the collProjects collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Year The current object (for fluent API support)
     * @see        addProjects()
     */
    public function clearProjects()
    {
        $this->collProjects = null; // important to set this to null since that means it is uninitialized
        $this->collProjectsPartial = null;

        return $this;
    }

    /**
     * reset is the collProjects collection loaded partially
     *
     * @return void
     */
    public function resetPartialProjects($v = true)
    {
        $this->collProjectsPartial = $v;
    }

    /**
     * Initializes the collProjects collection.
     *
     * By default this just sets the collProjects collection to an empty array (like clearcollProjects());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProjects($overrideExisting = true)
    {
        if (null !== $this->collProjects && !$overrideExisting) {
            return;
        }
        $this->collProjects = new PropelObjectCollection();
        $this->collProjects->setModel('Project');
    }

    /**
     * Gets an array of Project objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Year is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Project[] List of Project objects
     * @throws PropelException
     */
    public function getProjects($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProjectsPartial && !$this->isNew();
        if (null === $this->collProjects || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProjects) {
                // return empty collection
                $this->initProjects();
            } else {
                $collProjects = ProjectQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProjectsPartial && count($collProjects)) {
                      $this->initProjects(false);

                      foreach ($collProjects as $obj) {
                        if (false == $this->collProjects->contains($obj)) {
                          $this->collProjects->append($obj);
                        }
                      }

                      $this->collProjectsPartial = true;
                    }

                    $collProjects->getInternalIterator()->rewind();

                    return $collProjects;
                }

                if ($partial && $this->collProjects) {
                    foreach ($this->collProjects as $obj) {
                        if ($obj->isNew()) {
                            $collProjects[] = $obj;
                        }
                    }
                }

                $this->collProjects = $collProjects;
                $this->collProjectsPartial = false;
            }
        }

        return $this->collProjects;
    }

    /**
     * Sets a collection of Project objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $projects A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Year The current object (for fluent API support)
     */
    public function setProjects(PropelCollection $projects, PropelPDO $con = null)
    {
        $projectsToDelete = $this->getProjects(new Criteria(), $con)->diff($projects);


        $this->projectsScheduledForDeletion = $projectsToDelete;

        foreach ($projectsToDelete as $projectRemoved) {
            $projectRemoved->setYear(null);
        }

        $this->collProjects = null;
        foreach ($projects as $project) {
            $this->addProject($project);
        }

        $this->collProjects = $projects;
        $this->collProjectsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Project objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Project objects.
     * @throws PropelException
     */
    public function countProjects(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProjectsPartial && !$this->isNew();
        if (null === $this->collProjects || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProjects) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProjects());
            }
            $query = ProjectQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByYear($this)
                ->count($con);
        }

        return count($this->collProjects);
    }

    /**
     * Method called to associate a Project object to this object
     * through the Project foreign key attribute.
     *
     * @param    Project $l Project
     * @return Year The current object (for fluent API support)
     */
    public function addProject(Project $l)
    {
        if ($this->collProjects === null) {
            $this->initProjects();
            $this->collProjectsPartial = true;
        }

        if (!in_array($l, $this->collProjects->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProject($l);

            if ($this->projectsScheduledForDeletion and $this->projectsScheduledForDeletion->contains($l)) {
                $this->projectsScheduledForDeletion->remove($this->projectsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Project $project The project object to add.
     */
    protected function doAddProject($project)
    {
        $this->collProjects[]= $project;
        $project->setYear($this);
    }

    /**
     * @param	Project $project The project object to remove.
     * @return Year The current object (for fluent API support)
     */
    public function removeProject($project)
    {
        if ($this->getProjects()->contains($project)) {
            $this->collProjects->remove($this->collProjects->search($project));
            if (null === $this->projectsScheduledForDeletion) {
                $this->projectsScheduledForDeletion = clone $this->collProjects;
                $this->projectsScheduledForDeletion->clear();
            }
            $this->projectsScheduledForDeletion[]= $project;
            $project->setYear(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinCostFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('CostFileCat', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinIncomeAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('IncomeAcc', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinCostAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('CostAcc', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Year is new, it will return
     * an empty collection; or if this Year has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Year.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinBankAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('BankAcc', $join_behavior);

        return $this->getProjects($query, $con);
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
            if ($this->collMonths) {
                foreach ($this->collMonths as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFileCats) {
                foreach ($this->collFileCats as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDocCats) {
                foreach ($this->collDocCats as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBookks) {
                foreach ($this->collBookks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAccounts) {
                foreach ($this->collAccounts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collReports) {
                foreach ($this->collReports as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProjects) {
                foreach ($this->collProjects as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collMonths instanceof PropelCollection) {
            $this->collMonths->clearIterator();
        }
        $this->collMonths = null;
        if ($this->collFileCats instanceof PropelCollection) {
            $this->collFileCats->clearIterator();
        }
        $this->collFileCats = null;
        if ($this->collDocCats instanceof PropelCollection) {
            $this->collDocCats->clearIterator();
        }
        $this->collDocCats = null;
        if ($this->collBookks instanceof PropelCollection) {
            $this->collBookks->clearIterator();
        }
        $this->collBookks = null;
        if ($this->collAccounts instanceof PropelCollection) {
            $this->collAccounts->clearIterator();
        }
        $this->collAccounts = null;
        if ($this->collReports instanceof PropelCollection) {
            $this->collReports->clearIterator();
        }
        $this->collReports = null;
        if ($this->collProjects instanceof PropelCollection) {
            $this->collProjects->clearIterator();
        }
        $this->collProjects = null;
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
     * @return    Year
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
        return $this->getSortableRank() == YearQuery::create()->getMaxRankArray($con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Year
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = YearQuery::create();

        $query->filterByRank($this->getSortableRank() + 1);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Year
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = YearQuery::create();

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
     * @return    Year the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = YearQuery::create()->getMaxRankArray($con);
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
     * @return    Year the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(YearQuery::create()->getMaxRankArray($con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Year the current object
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
     * @return    Year the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(YearPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > YearQuery::create()->getMaxRankArray($con)) {
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
            YearPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $con);

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
     * @param     Year $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Year the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(YearPeer::DATABASE_NAME);
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
     * @return    Year the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(YearPeer::DATABASE_NAME);
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
     * @return    Year the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(YearPeer::DATABASE_NAME);
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
     * @return    Year the current object
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
            $con = Propel::getConnection(YearPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = YearQuery::create()->getMaxRankArray($con);
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
     * @return    Year the current object
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
