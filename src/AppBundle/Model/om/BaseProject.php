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
use AppBundle\Model\Cost;
use AppBundle\Model\CostQuery;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\FileCatQuery;
use AppBundle\Model\FileQuery;
use AppBundle\Model\Income;
use AppBundle\Model\IncomeQuery;
use AppBundle\Model\Project;
use AppBundle\Model\ProjectPeer;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\Task;
use AppBundle\Model\TaskQuery;
use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;

abstract class BaseProject extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'AppBundle\\Model\\ProjectPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ProjectPeer
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
     * The value for the status field.
     * @var        int
     */
    protected $status;

    /**
     * The value for the desc field.
     * @var        string
     */
    protected $desc;

    /**
     * The value for the place field.
     * @var        string
     */
    protected $place;

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
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * The value for the year_id field.
     * @var        int
     */
    protected $year_id;

    /**
     * The value for the file_id field.
     * @var        int
     */
    protected $file_id;

    /**
     * The value for the file_cat_id field.
     * @var        int
     */
    protected $file_cat_id;

    /**
     * The value for the income_acc_id field.
     * @var        int
     */
    protected $income_acc_id;

    /**
     * The value for the cost_acc_id field.
     * @var        int
     */
    protected $cost_acc_id;

    /**
     * The value for the bank_acc_id field.
     * @var        int
     */
    protected $bank_acc_id;

    /**
     * @var        Year
     */
    protected $aYear;

    /**
     * @var        File
     */
    protected $aFile;

    /**
     * @var        FileCat
     */
    protected $aCostFileCat;

    /**
     * @var        Account
     */
    protected $aIncomeAcc;

    /**
     * @var        Account
     */
    protected $aCostAcc;

    /**
     * @var        Account
     */
    protected $aBankAcc;

    /**
     * @var        PropelObjectCollection|Bookk[] Collection to store aggregation of Bookk objects.
     */
    protected $collBookks;
    protected $collBookksPartial;

    /**
     * @var        PropelObjectCollection|Income[] Collection to store aggregation of Income objects.
     */
    protected $collIncomes;
    protected $collIncomesPartial;

    /**
     * @var        PropelObjectCollection|Cost[] Collection to store aggregation of Cost objects.
     */
    protected $collCosts;
    protected $collCostsPartial;

    /**
     * @var        PropelObjectCollection|Task[] Collection to store aggregation of Task objects.
     */
    protected $collTasks;
    protected $collTasksPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $bookksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $incomesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $tasksScheduledForDeletion = null;

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
     * Get the [status] column value.
     *
     * @return int
     */
    public function getStatus()
    {

        return $this->status;
    }

    /**
     * Get the [desc] column value.
     *
     * @return string
     */
    public function getDesc()
    {

        return $this->desc;
    }

    /**
     * Get the [place] column value.
     *
     * @return string
     */
    public function getPlace()
    {

        return $this->place;
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
     * Get the [comment] column value.
     *
     * @return string
     */
    public function getComment()
    {

        return $this->comment;
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
     * Get the [file_id] column value.
     *
     * @return int
     */
    public function getFileId()
    {

        return $this->file_id;
    }

    /**
     * Get the [file_cat_id] column value.
     *
     * @return int
     */
    public function getFileCatId()
    {

        return $this->file_cat_id;
    }

    /**
     * Get the [income_acc_id] column value.
     *
     * @return int
     */
    public function getIncomeAccId()
    {

        return $this->income_acc_id;
    }

    /**
     * Get the [cost_acc_id] column value.
     *
     * @return int
     */
    public function getCostAccId()
    {

        return $this->cost_acc_id;
    }

    /**
     * Get the [bank_acc_id] column value.
     *
     * @return int
     */
    public function getBankAccId()
    {

        return $this->bank_acc_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ProjectPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = ProjectPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [status] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setStatus($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->status !== $v) {
            $this->status = $v;
            $this->modifiedColumns[] = ProjectPeer::STATUS;
        }


        return $this;
    } // setStatus()

    /**
     * Set the value of [desc] column.
     *
     * @param  string $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->desc !== $v) {
            $this->desc = $v;
            $this->modifiedColumns[] = ProjectPeer::DESC;
        }


        return $this;
    } // setDesc()

    /**
     * Set the value of [place] column.
     *
     * @param  string $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->place !== $v) {
            $this->place = $v;
            $this->modifiedColumns[] = ProjectPeer::PLACE;
        }


        return $this;
    } // setPlace()

    /**
     * Sets the value of [from_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Project The current object (for fluent API support)
     */
    public function setFromDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->from_date !== null || $dt !== null) {
            $currentDateAsString = ($this->from_date !== null && $tmpDt = new DateTime($this->from_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->from_date = $newDateAsString;
                $this->modifiedColumns[] = ProjectPeer::FROM_DATE;
            }
        } // if either are not null


        return $this;
    } // setFromDate()

    /**
     * Sets the value of [to_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Project The current object (for fluent API support)
     */
    public function setToDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->to_date !== null || $dt !== null) {
            $currentDateAsString = ($this->to_date !== null && $tmpDt = new DateTime($this->to_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->to_date = $newDateAsString;
                $this->modifiedColumns[] = ProjectPeer::TO_DATE;
            }
        } // if either are not null


        return $this;
    } // setToDate()

    /**
     * Set the value of [comment] column.
     *
     * @param  string $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[] = ProjectPeer::COMMENT;
        }


        return $this;
    } // setComment()

    /**
     * Set the value of [year_id] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year_id !== $v) {
            $this->year_id = $v;
            $this->modifiedColumns[] = ProjectPeer::YEAR_ID;
        }

        if ($this->aYear !== null && $this->aYear->getId() !== $v) {
            $this->aYear = null;
        }


        return $this;
    } // setYearId()

    /**
     * Set the value of [file_id] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setFileId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_id !== $v) {
            $this->file_id = $v;
            $this->modifiedColumns[] = ProjectPeer::FILE_ID;
        }

        if ($this->aFile !== null && $this->aFile->getId() !== $v) {
            $this->aFile = null;
        }


        return $this;
    } // setFileId()

    /**
     * Set the value of [file_cat_id] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setFileCatId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_cat_id !== $v) {
            $this->file_cat_id = $v;
            $this->modifiedColumns[] = ProjectPeer::FILE_CAT_ID;
        }

        if ($this->aCostFileCat !== null && $this->aCostFileCat->getId() !== $v) {
            $this->aCostFileCat = null;
        }


        return $this;
    } // setFileCatId()

    /**
     * Set the value of [income_acc_id] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setIncomeAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->income_acc_id !== $v) {
            $this->income_acc_id = $v;
            $this->modifiedColumns[] = ProjectPeer::INCOME_ACC_ID;
        }

        if ($this->aIncomeAcc !== null && $this->aIncomeAcc->getId() !== $v) {
            $this->aIncomeAcc = null;
        }


        return $this;
    } // setIncomeAccId()

    /**
     * Set the value of [cost_acc_id] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setCostAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->cost_acc_id !== $v) {
            $this->cost_acc_id = $v;
            $this->modifiedColumns[] = ProjectPeer::COST_ACC_ID;
        }

        if ($this->aCostAcc !== null && $this->aCostAcc->getId() !== $v) {
            $this->aCostAcc = null;
        }


        return $this;
    } // setCostAccId()

    /**
     * Set the value of [bank_acc_id] column.
     *
     * @param  int $v new value
     * @return Project The current object (for fluent API support)
     */
    public function setBankAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bank_acc_id !== $v) {
            $this->bank_acc_id = $v;
            $this->modifiedColumns[] = ProjectPeer::BANK_ACC_ID;
        }

        if ($this->aBankAcc !== null && $this->aBankAcc->getId() !== $v) {
            $this->aBankAcc = null;
        }


        return $this;
    } // setBankAccId()

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
            $this->status = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->desc = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->place = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->from_date = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->to_date = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->comment = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->year_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->file_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->file_cat_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->income_acc_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->cost_acc_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->bank_acc_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 14; // 14 = ProjectPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Project object", $e);
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
        if ($this->aFile !== null && $this->file_id !== $this->aFile->getId()) {
            $this->aFile = null;
        }
        if ($this->aCostFileCat !== null && $this->file_cat_id !== $this->aCostFileCat->getId()) {
            $this->aCostFileCat = null;
        }
        if ($this->aIncomeAcc !== null && $this->income_acc_id !== $this->aIncomeAcc->getId()) {
            $this->aIncomeAcc = null;
        }
        if ($this->aCostAcc !== null && $this->cost_acc_id !== $this->aCostAcc->getId()) {
            $this->aCostAcc = null;
        }
        if ($this->aBankAcc !== null && $this->bank_acc_id !== $this->aBankAcc->getId()) {
            $this->aBankAcc = null;
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
            $con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ProjectPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aYear = null;
            $this->aFile = null;
            $this->aCostFileCat = null;
            $this->aIncomeAcc = null;
            $this->aCostAcc = null;
            $this->aBankAcc = null;
            $this->collBookks = null;

            $this->collIncomes = null;

            $this->collCosts = null;

            $this->collTasks = null;

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
            $con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ProjectQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
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
            $con = Propel::getConnection(ProjectPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
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
                ProjectPeer::addInstanceToPool($this);
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

            if ($this->aFile !== null) {
                if ($this->aFile->isModified() || $this->aFile->isNew()) {
                    $affectedRows += $this->aFile->save($con);
                }
                $this->setFile($this->aFile);
            }

            if ($this->aCostFileCat !== null) {
                if ($this->aCostFileCat->isModified() || $this->aCostFileCat->isNew()) {
                    $affectedRows += $this->aCostFileCat->save($con);
                }
                $this->setCostFileCat($this->aCostFileCat);
            }

            if ($this->aIncomeAcc !== null) {
                if ($this->aIncomeAcc->isModified() || $this->aIncomeAcc->isNew()) {
                    $affectedRows += $this->aIncomeAcc->save($con);
                }
                $this->setIncomeAcc($this->aIncomeAcc);
            }

            if ($this->aCostAcc !== null) {
                if ($this->aCostAcc->isModified() || $this->aCostAcc->isNew()) {
                    $affectedRows += $this->aCostAcc->save($con);
                }
                $this->setCostAcc($this->aCostAcc);
            }

            if ($this->aBankAcc !== null) {
                if ($this->aBankAcc->isModified() || $this->aBankAcc->isNew()) {
                    $affectedRows += $this->aBankAcc->save($con);
                }
                $this->setBankAcc($this->aBankAcc);
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

            if ($this->incomesScheduledForDeletion !== null) {
                if (!$this->incomesScheduledForDeletion->isEmpty()) {
                    IncomeQuery::create()
                        ->filterByPrimaryKeys($this->incomesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->incomesScheduledForDeletion = null;
                }
            }

            if ($this->collIncomes !== null) {
                foreach ($this->collIncomes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->costsScheduledForDeletion !== null) {
                if (!$this->costsScheduledForDeletion->isEmpty()) {
                    CostQuery::create()
                        ->filterByPrimaryKeys($this->costsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->costsScheduledForDeletion = null;
                }
            }

            if ($this->collCosts !== null) {
                foreach ($this->collCosts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->tasksScheduledForDeletion !== null) {
                if (!$this->tasksScheduledForDeletion->isEmpty()) {
                    TaskQuery::create()
                        ->filterByPrimaryKeys($this->tasksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->tasksScheduledForDeletion = null;
                }
            }

            if ($this->collTasks !== null) {
                foreach ($this->collTasks as $referrerFK) {
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

        $this->modifiedColumns[] = ProjectPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ProjectPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ProjectPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ProjectPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(ProjectPeer::STATUS)) {
            $modifiedColumns[':p' . $index++]  = '`status`';
        }
        if ($this->isColumnModified(ProjectPeer::DESC)) {
            $modifiedColumns[':p' . $index++]  = '`desc`';
        }
        if ($this->isColumnModified(ProjectPeer::PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`place`';
        }
        if ($this->isColumnModified(ProjectPeer::FROM_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`from_date`';
        }
        if ($this->isColumnModified(ProjectPeer::TO_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`to_date`';
        }
        if ($this->isColumnModified(ProjectPeer::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`comment`';
        }
        if ($this->isColumnModified(ProjectPeer::YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`year_id`';
        }
        if ($this->isColumnModified(ProjectPeer::FILE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_id`';
        }
        if ($this->isColumnModified(ProjectPeer::FILE_CAT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_cat_id`';
        }
        if ($this->isColumnModified(ProjectPeer::INCOME_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`income_acc_id`';
        }
        if ($this->isColumnModified(ProjectPeer::COST_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`cost_acc_id`';
        }
        if ($this->isColumnModified(ProjectPeer::BANK_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`bank_acc_id`';
        }

        $sql = sprintf(
            'INSERT INTO `project` (%s) VALUES (%s)',
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
                    case '`status`':
                        $stmt->bindValue($identifier, $this->status, PDO::PARAM_INT);
                        break;
                    case '`desc`':
                        $stmt->bindValue($identifier, $this->desc, PDO::PARAM_STR);
                        break;
                    case '`place`':
                        $stmt->bindValue($identifier, $this->place, PDO::PARAM_STR);
                        break;
                    case '`from_date`':
                        $stmt->bindValue($identifier, $this->from_date, PDO::PARAM_STR);
                        break;
                    case '`to_date`':
                        $stmt->bindValue($identifier, $this->to_date, PDO::PARAM_STR);
                        break;
                    case '`comment`':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                    case '`year_id`':
                        $stmt->bindValue($identifier, $this->year_id, PDO::PARAM_INT);
                        break;
                    case '`file_id`':
                        $stmt->bindValue($identifier, $this->file_id, PDO::PARAM_INT);
                        break;
                    case '`file_cat_id`':
                        $stmt->bindValue($identifier, $this->file_cat_id, PDO::PARAM_INT);
                        break;
                    case '`income_acc_id`':
                        $stmt->bindValue($identifier, $this->income_acc_id, PDO::PARAM_INT);
                        break;
                    case '`cost_acc_id`':
                        $stmt->bindValue($identifier, $this->cost_acc_id, PDO::PARAM_INT);
                        break;
                    case '`bank_acc_id`':
                        $stmt->bindValue($identifier, $this->bank_acc_id, PDO::PARAM_INT);
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

            if ($this->aFile !== null) {
                if (!$this->aFile->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFile->getValidationFailures());
                }
            }

            if ($this->aCostFileCat !== null) {
                if (!$this->aCostFileCat->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCostFileCat->getValidationFailures());
                }
            }

            if ($this->aIncomeAcc !== null) {
                if (!$this->aIncomeAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aIncomeAcc->getValidationFailures());
                }
            }

            if ($this->aCostAcc !== null) {
                if (!$this->aCostAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCostAcc->getValidationFailures());
                }
            }

            if ($this->aBankAcc !== null) {
                if (!$this->aBankAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBankAcc->getValidationFailures());
                }
            }


            if (($retval = ProjectPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBookks !== null) {
                    foreach ($this->collBookks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collIncomes !== null) {
                    foreach ($this->collIncomes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCosts !== null) {
                    foreach ($this->collCosts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collTasks !== null) {
                    foreach ($this->collTasks as $referrerFK) {
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
        $pos = ProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getStatus();
                break;
            case 3:
                return $this->getDesc();
                break;
            case 4:
                return $this->getPlace();
                break;
            case 5:
                return $this->getFromDate();
                break;
            case 6:
                return $this->getToDate();
                break;
            case 7:
                return $this->getComment();
                break;
            case 8:
                return $this->getYearId();
                break;
            case 9:
                return $this->getFileId();
                break;
            case 10:
                return $this->getFileCatId();
                break;
            case 11:
                return $this->getIncomeAccId();
                break;
            case 12:
                return $this->getCostAccId();
                break;
            case 13:
                return $this->getBankAccId();
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
        if (isset($alreadyDumpedObjects['Project'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Project'][$this->getPrimaryKey()] = true;
        $keys = ProjectPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getStatus(),
            $keys[3] => $this->getDesc(),
            $keys[4] => $this->getPlace(),
            $keys[5] => $this->getFromDate(),
            $keys[6] => $this->getToDate(),
            $keys[7] => $this->getComment(),
            $keys[8] => $this->getYearId(),
            $keys[9] => $this->getFileId(),
            $keys[10] => $this->getFileCatId(),
            $keys[11] => $this->getIncomeAccId(),
            $keys[12] => $this->getCostAccId(),
            $keys[13] => $this->getBankAccId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aYear) {
                $result['Year'] = $this->aYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFile) {
                $result['File'] = $this->aFile->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCostFileCat) {
                $result['CostFileCat'] = $this->aCostFileCat->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aIncomeAcc) {
                $result['IncomeAcc'] = $this->aIncomeAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCostAcc) {
                $result['CostAcc'] = $this->aCostAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aBankAcc) {
                $result['BankAcc'] = $this->aBankAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collBookks) {
                $result['Bookks'] = $this->collBookks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collIncomes) {
                $result['Incomes'] = $this->collIncomes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCosts) {
                $result['Costs'] = $this->collCosts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collTasks) {
                $result['Tasks'] = $this->collTasks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ProjectPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setStatus($value);
                break;
            case 3:
                $this->setDesc($value);
                break;
            case 4:
                $this->setPlace($value);
                break;
            case 5:
                $this->setFromDate($value);
                break;
            case 6:
                $this->setToDate($value);
                break;
            case 7:
                $this->setComment($value);
                break;
            case 8:
                $this->setYearId($value);
                break;
            case 9:
                $this->setFileId($value);
                break;
            case 10:
                $this->setFileCatId($value);
                break;
            case 11:
                $this->setIncomeAccId($value);
                break;
            case 12:
                $this->setCostAccId($value);
                break;
            case 13:
                $this->setBankAccId($value);
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
        $keys = ProjectPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setStatus($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDesc($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setPlace($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFromDate($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setToDate($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setComment($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setYearId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setFileId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setFileCatId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setIncomeAccId($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setCostAccId($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setBankAccId($arr[$keys[13]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ProjectPeer::DATABASE_NAME);

        if ($this->isColumnModified(ProjectPeer::ID)) $criteria->add(ProjectPeer::ID, $this->id);
        if ($this->isColumnModified(ProjectPeer::NAME)) $criteria->add(ProjectPeer::NAME, $this->name);
        if ($this->isColumnModified(ProjectPeer::STATUS)) $criteria->add(ProjectPeer::STATUS, $this->status);
        if ($this->isColumnModified(ProjectPeer::DESC)) $criteria->add(ProjectPeer::DESC, $this->desc);
        if ($this->isColumnModified(ProjectPeer::PLACE)) $criteria->add(ProjectPeer::PLACE, $this->place);
        if ($this->isColumnModified(ProjectPeer::FROM_DATE)) $criteria->add(ProjectPeer::FROM_DATE, $this->from_date);
        if ($this->isColumnModified(ProjectPeer::TO_DATE)) $criteria->add(ProjectPeer::TO_DATE, $this->to_date);
        if ($this->isColumnModified(ProjectPeer::COMMENT)) $criteria->add(ProjectPeer::COMMENT, $this->comment);
        if ($this->isColumnModified(ProjectPeer::YEAR_ID)) $criteria->add(ProjectPeer::YEAR_ID, $this->year_id);
        if ($this->isColumnModified(ProjectPeer::FILE_ID)) $criteria->add(ProjectPeer::FILE_ID, $this->file_id);
        if ($this->isColumnModified(ProjectPeer::FILE_CAT_ID)) $criteria->add(ProjectPeer::FILE_CAT_ID, $this->file_cat_id);
        if ($this->isColumnModified(ProjectPeer::INCOME_ACC_ID)) $criteria->add(ProjectPeer::INCOME_ACC_ID, $this->income_acc_id);
        if ($this->isColumnModified(ProjectPeer::COST_ACC_ID)) $criteria->add(ProjectPeer::COST_ACC_ID, $this->cost_acc_id);
        if ($this->isColumnModified(ProjectPeer::BANK_ACC_ID)) $criteria->add(ProjectPeer::BANK_ACC_ID, $this->bank_acc_id);

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
        $criteria = new Criteria(ProjectPeer::DATABASE_NAME);
        $criteria->add(ProjectPeer::ID, $this->id);

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
     * @param object $copyObj An object of Project (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setStatus($this->getStatus());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setPlace($this->getPlace());
        $copyObj->setFromDate($this->getFromDate());
        $copyObj->setToDate($this->getToDate());
        $copyObj->setComment($this->getComment());
        $copyObj->setYearId($this->getYearId());
        $copyObj->setFileId($this->getFileId());
        $copyObj->setFileCatId($this->getFileCatId());
        $copyObj->setIncomeAccId($this->getIncomeAccId());
        $copyObj->setCostAccId($this->getCostAccId());
        $copyObj->setBankAccId($this->getBankAccId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getBookks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookk($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getIncomes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIncome($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCosts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCost($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getTasks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addTask($relObj->copy($deepCopy));
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
     * @return Project Clone of current object.
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
     * @return ProjectPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ProjectPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Year object.
     *
     * @param                  Year $v
     * @return Project The current object (for fluent API support)
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
            $v->addProject($this);
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
                $this->aYear->addProjects($this);
             */
        }

        return $this->aYear;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return Project The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFile(File $v = null)
    {
        if ($v === null) {
            $this->setFileId(NULL);
        } else {
            $this->setFileId($v->getId());
        }

        $this->aFile = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the File object, it will not be re-added.
        if ($v !== null) {
            $v->addProject($this);
        }


        return $this;
    }


    /**
     * Get the associated File object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return File The associated File object.
     * @throws PropelException
     */
    public function getFile(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFile === null && ($this->file_id !== null) && $doQuery) {
            $this->aFile = FileQuery::create()->findPk($this->file_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFile->addProjects($this);
             */
        }

        return $this->aFile;
    }

    /**
     * Declares an association between this object and a FileCat object.
     *
     * @param                  FileCat $v
     * @return Project The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCostFileCat(FileCat $v = null)
    {
        if ($v === null) {
            $this->setFileCatId(NULL);
        } else {
            $this->setFileCatId($v->getId());
        }

        $this->aCostFileCat = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FileCat object, it will not be re-added.
        if ($v !== null) {
            $v->addProject($this);
        }


        return $this;
    }


    /**
     * Get the associated FileCat object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return FileCat The associated FileCat object.
     * @throws PropelException
     */
    public function getCostFileCat(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCostFileCat === null && ($this->file_cat_id !== null) && $doQuery) {
            $this->aCostFileCat = FileCatQuery::create()->findPk($this->file_cat_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCostFileCat->addProjects($this);
             */
        }

        return $this->aCostFileCat;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return Project The current object (for fluent API support)
     * @throws PropelException
     */
    public function setIncomeAcc(Account $v = null)
    {
        if ($v === null) {
            $this->setIncomeAccId(NULL);
        } else {
            $this->setIncomeAccId($v->getId());
        }

        $this->aIncomeAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addProjectRelatedByIncomeAccId($this);
        }


        return $this;
    }


    /**
     * Get the associated Account object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Account The associated Account object.
     * @throws PropelException
     */
    public function getIncomeAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aIncomeAcc === null && ($this->income_acc_id !== null) && $doQuery) {
            $this->aIncomeAcc = AccountQuery::create()->findPk($this->income_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aIncomeAcc->addProjectsRelatedByIncomeAccId($this);
             */
        }

        return $this->aIncomeAcc;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return Project The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCostAcc(Account $v = null)
    {
        if ($v === null) {
            $this->setCostAccId(NULL);
        } else {
            $this->setCostAccId($v->getId());
        }

        $this->aCostAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addProjectRelatedByCostAccId($this);
        }


        return $this;
    }


    /**
     * Get the associated Account object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Account The associated Account object.
     * @throws PropelException
     */
    public function getCostAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCostAcc === null && ($this->cost_acc_id !== null) && $doQuery) {
            $this->aCostAcc = AccountQuery::create()->findPk($this->cost_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCostAcc->addProjectsRelatedByCostAccId($this);
             */
        }

        return $this->aCostAcc;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return Project The current object (for fluent API support)
     * @throws PropelException
     */
    public function setBankAcc(Account $v = null)
    {
        if ($v === null) {
            $this->setBankAccId(NULL);
        } else {
            $this->setBankAccId($v->getId());
        }

        $this->aBankAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addProjectRelatedByBankAccId($this);
        }


        return $this;
    }


    /**
     * Get the associated Account object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Account The associated Account object.
     * @throws PropelException
     */
    public function getBankAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aBankAcc === null && ($this->bank_acc_id !== null) && $doQuery) {
            $this->aBankAcc = AccountQuery::create()->findPk($this->bank_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aBankAcc->addProjectsRelatedByBankAccId($this);
             */
        }

        return $this->aBankAcc;
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
        if ('Bookk' == $relationName) {
            $this->initBookks();
        }
        if ('Income' == $relationName) {
            $this->initIncomes();
        }
        if ('Cost' == $relationName) {
            $this->initCosts();
        }
        if ('Task' == $relationName) {
            $this->initTasks();
        }
    }

    /**
     * Clears out the collBookks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Project The current object (for fluent API support)
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
     * If this Project is new, it will return
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
                    ->filterByProject($this)
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
     * @return Project The current object (for fluent API support)
     */
    public function setBookks(PropelCollection $bookks, PropelPDO $con = null)
    {
        $bookksToDelete = $this->getBookks(new Criteria(), $con)->diff($bookks);


        $this->bookksScheduledForDeletion = $bookksToDelete;

        foreach ($bookksToDelete as $bookkRemoved) {
            $bookkRemoved->setProject(null);
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
                ->filterByProject($this)
                ->count($con);
        }

        return count($this->collBookks);
    }

    /**
     * Method called to associate a Bookk object to this object
     * through the Bookk foreign key attribute.
     *
     * @param    Bookk $l Bookk
     * @return Project The current object (for fluent API support)
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
        $bookk->setProject($this);
    }

    /**
     * @param	Bookk $bookk The bookk object to remove.
     * @return Project The current object (for fluent API support)
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
            $bookk->setProject(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Bookks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
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
     * Clears out the collIncomes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Project The current object (for fluent API support)
     * @see        addIncomes()
     */
    public function clearIncomes()
    {
        $this->collIncomes = null; // important to set this to null since that means it is uninitialized
        $this->collIncomesPartial = null;

        return $this;
    }

    /**
     * reset is the collIncomes collection loaded partially
     *
     * @return void
     */
    public function resetPartialIncomes($v = true)
    {
        $this->collIncomesPartial = $v;
    }

    /**
     * Initializes the collIncomes collection.
     *
     * By default this just sets the collIncomes collection to an empty array (like clearcollIncomes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIncomes($overrideExisting = true)
    {
        if (null !== $this->collIncomes && !$overrideExisting) {
            return;
        }
        $this->collIncomes = new PropelObjectCollection();
        $this->collIncomes->setModel('Income');
    }

    /**
     * Gets an array of Income objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Project is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Income[] List of Income objects
     * @throws PropelException
     */
    public function getIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIncomesPartial && !$this->isNew();
        if (null === $this->collIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIncomes) {
                // return empty collection
                $this->initIncomes();
            } else {
                $collIncomes = IncomeQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collIncomesPartial && count($collIncomes)) {
                      $this->initIncomes(false);

                      foreach ($collIncomes as $obj) {
                        if (false == $this->collIncomes->contains($obj)) {
                          $this->collIncomes->append($obj);
                        }
                      }

                      $this->collIncomesPartial = true;
                    }

                    $collIncomes->getInternalIterator()->rewind();

                    return $collIncomes;
                }

                if ($partial && $this->collIncomes) {
                    foreach ($this->collIncomes as $obj) {
                        if ($obj->isNew()) {
                            $collIncomes[] = $obj;
                        }
                    }
                }

                $this->collIncomes = $collIncomes;
                $this->collIncomesPartial = false;
            }
        }

        return $this->collIncomes;
    }

    /**
     * Sets a collection of Income objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $incomes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Project The current object (for fluent API support)
     */
    public function setIncomes(PropelCollection $incomes, PropelPDO $con = null)
    {
        $incomesToDelete = $this->getIncomes(new Criteria(), $con)->diff($incomes);


        $this->incomesScheduledForDeletion = $incomesToDelete;

        foreach ($incomesToDelete as $incomeRemoved) {
            $incomeRemoved->setProject(null);
        }

        $this->collIncomes = null;
        foreach ($incomes as $income) {
            $this->addIncome($income);
        }

        $this->collIncomes = $incomes;
        $this->collIncomesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Income objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Income objects.
     * @throws PropelException
     */
    public function countIncomes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collIncomesPartial && !$this->isNew();
        if (null === $this->collIncomes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIncomes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIncomes());
            }
            $query = IncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByProject($this)
                ->count($con);
        }

        return count($this->collIncomes);
    }

    /**
     * Method called to associate a Income object to this object
     * through the Income foreign key attribute.
     *
     * @param    Income $l Income
     * @return Project The current object (for fluent API support)
     */
    public function addIncome(Income $l)
    {
        if ($this->collIncomes === null) {
            $this->initIncomes();
            $this->collIncomesPartial = true;
        }

        if (!in_array($l, $this->collIncomes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIncome($l);

            if ($this->incomesScheduledForDeletion and $this->incomesScheduledForDeletion->contains($l)) {
                $this->incomesScheduledForDeletion->remove($this->incomesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Income $income The income object to add.
     */
    protected function doAddIncome($income)
    {
        $this->collIncomes[]= $income;
        $income->setProject($this);
    }

    /**
     * @param	Income $income The income object to remove.
     * @return Project The current object (for fluent API support)
     */
    public function removeIncome($income)
    {
        if ($this->getIncomes()->contains($income)) {
            $this->collIncomes->remove($this->collIncomes->search($income));
            if (null === $this->incomesScheduledForDeletion) {
                $this->incomesScheduledForDeletion = clone $this->collIncomes;
                $this->incomesScheduledForDeletion->clear();
            }
            $this->incomesScheduledForDeletion[]= $income;
            $income->setProject(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Incomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getIncomes($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Incomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesJoinBankAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('BankAcc', $join_behavior);

        return $this->getIncomes($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Incomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesJoinIncomeAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('IncomeAcc', $join_behavior);

        return $this->getIncomes($query, $con);
    }

    /**
     * Clears out the collCosts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Project The current object (for fluent API support)
     * @see        addCosts()
     */
    public function clearCosts()
    {
        $this->collCosts = null; // important to set this to null since that means it is uninitialized
        $this->collCostsPartial = null;

        return $this;
    }

    /**
     * reset is the collCosts collection loaded partially
     *
     * @return void
     */
    public function resetPartialCosts($v = true)
    {
        $this->collCostsPartial = $v;
    }

    /**
     * Initializes the collCosts collection.
     *
     * By default this just sets the collCosts collection to an empty array (like clearcollCosts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCosts($overrideExisting = true)
    {
        if (null !== $this->collCosts && !$overrideExisting) {
            return;
        }
        $this->collCosts = new PropelObjectCollection();
        $this->collCosts->setModel('Cost');
    }

    /**
     * Gets an array of Cost objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Project is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Cost[] List of Cost objects
     * @throws PropelException
     */
    public function getCosts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostsPartial && !$this->isNew();
        if (null === $this->collCosts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCosts) {
                // return empty collection
                $this->initCosts();
            } else {
                $collCosts = CostQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostsPartial && count($collCosts)) {
                      $this->initCosts(false);

                      foreach ($collCosts as $obj) {
                        if (false == $this->collCosts->contains($obj)) {
                          $this->collCosts->append($obj);
                        }
                      }

                      $this->collCostsPartial = true;
                    }

                    $collCosts->getInternalIterator()->rewind();

                    return $collCosts;
                }

                if ($partial && $this->collCosts) {
                    foreach ($this->collCosts as $obj) {
                        if ($obj->isNew()) {
                            $collCosts[] = $obj;
                        }
                    }
                }

                $this->collCosts = $collCosts;
                $this->collCostsPartial = false;
            }
        }

        return $this->collCosts;
    }

    /**
     * Sets a collection of Cost objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Project The current object (for fluent API support)
     */
    public function setCosts(PropelCollection $costs, PropelPDO $con = null)
    {
        $costsToDelete = $this->getCosts(new Criteria(), $con)->diff($costs);


        $this->costsScheduledForDeletion = $costsToDelete;

        foreach ($costsToDelete as $costRemoved) {
            $costRemoved->setProject(null);
        }

        $this->collCosts = null;
        foreach ($costs as $cost) {
            $this->addCost($cost);
        }

        $this->collCosts = $costs;
        $this->collCostsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Cost objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Cost objects.
     * @throws PropelException
     */
    public function countCosts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostsPartial && !$this->isNew();
        if (null === $this->collCosts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCosts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCosts());
            }
            $query = CostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByProject($this)
                ->count($con);
        }

        return count($this->collCosts);
    }

    /**
     * Method called to associate a Cost object to this object
     * through the Cost foreign key attribute.
     *
     * @param    Cost $l Cost
     * @return Project The current object (for fluent API support)
     */
    public function addCost(Cost $l)
    {
        if ($this->collCosts === null) {
            $this->initCosts();
            $this->collCostsPartial = true;
        }

        if (!in_array($l, $this->collCosts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCost($l);

            if ($this->costsScheduledForDeletion and $this->costsScheduledForDeletion->contains($l)) {
                $this->costsScheduledForDeletion->remove($this->costsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Cost $cost The cost object to add.
     */
    protected function doAddCost($cost)
    {
        $this->collCosts[]= $cost;
        $cost->setProject($this);
    }

    /**
     * @param	Cost $cost The cost object to remove.
     * @return Project The current object (for fluent API support)
     */
    public function removeCost($cost)
    {
        if ($this->getCosts()->contains($cost)) {
            $this->collCosts->remove($this->collCosts->search($cost));
            if (null === $this->costsScheduledForDeletion) {
                $this->costsScheduledForDeletion = clone $this->collCosts;
                $this->costsScheduledForDeletion->clear();
            }
            $this->costsScheduledForDeletion[]= $cost;
            $cost->setProject(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Costs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getCosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Costs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsJoinBankAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('BankAcc', $join_behavior);

        return $this->getCosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Costs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsJoinCostAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('CostAcc', $join_behavior);

        return $this->getCosts($query, $con);
    }

    /**
     * Clears out the collTasks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Project The current object (for fluent API support)
     * @see        addTasks()
     */
    public function clearTasks()
    {
        $this->collTasks = null; // important to set this to null since that means it is uninitialized
        $this->collTasksPartial = null;

        return $this;
    }

    /**
     * reset is the collTasks collection loaded partially
     *
     * @return void
     */
    public function resetPartialTasks($v = true)
    {
        $this->collTasksPartial = $v;
    }

    /**
     * Initializes the collTasks collection.
     *
     * By default this just sets the collTasks collection to an empty array (like clearcollTasks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initTasks($overrideExisting = true)
    {
        if (null !== $this->collTasks && !$overrideExisting) {
            return;
        }
        $this->collTasks = new PropelObjectCollection();
        $this->collTasks->setModel('Task');
    }

    /**
     * Gets an array of Task objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Project is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Task[] List of Task objects
     * @throws PropelException
     */
    public function getTasks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                // return empty collection
                $this->initTasks();
            } else {
                $collTasks = TaskQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTasksPartial && count($collTasks)) {
                      $this->initTasks(false);

                      foreach ($collTasks as $obj) {
                        if (false == $this->collTasks->contains($obj)) {
                          $this->collTasks->append($obj);
                        }
                      }

                      $this->collTasksPartial = true;
                    }

                    $collTasks->getInternalIterator()->rewind();

                    return $collTasks;
                }

                if ($partial && $this->collTasks) {
                    foreach ($this->collTasks as $obj) {
                        if ($obj->isNew()) {
                            $collTasks[] = $obj;
                        }
                    }
                }

                $this->collTasks = $collTasks;
                $this->collTasksPartial = false;
            }
        }

        return $this->collTasks;
    }

    /**
     * Sets a collection of Task objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $tasks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Project The current object (for fluent API support)
     */
    public function setTasks(PropelCollection $tasks, PropelPDO $con = null)
    {
        $tasksToDelete = $this->getTasks(new Criteria(), $con)->diff($tasks);


        $this->tasksScheduledForDeletion = $tasksToDelete;

        foreach ($tasksToDelete as $taskRemoved) {
            $taskRemoved->setProject(null);
        }

        $this->collTasks = null;
        foreach ($tasks as $task) {
            $this->addTask($task);
        }

        $this->collTasks = $tasks;
        $this->collTasksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Task objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Task objects.
     * @throws PropelException
     */
    public function countTasks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getTasks());
            }
            $query = TaskQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByProject($this)
                ->count($con);
        }

        return count($this->collTasks);
    }

    /**
     * Method called to associate a Task object to this object
     * through the Task foreign key attribute.
     *
     * @param    Task $l Task
     * @return Project The current object (for fluent API support)
     */
    public function addTask(Task $l)
    {
        if ($this->collTasks === null) {
            $this->initTasks();
            $this->collTasksPartial = true;
        }

        if (!in_array($l, $this->collTasks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddTask($l);

            if ($this->tasksScheduledForDeletion and $this->tasksScheduledForDeletion->contains($l)) {
                $this->tasksScheduledForDeletion->remove($this->tasksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Task $task The task object to add.
     */
    protected function doAddTask($task)
    {
        $this->collTasks[]= $task;
        $task->setProject($this);
    }

    /**
     * @param	Task $task The task object to remove.
     * @return Project The current object (for fluent API support)
     */
    public function removeTask($task)
    {
        if ($this->getTasks()->contains($task)) {
            $this->collTasks->remove($this->collTasks->search($task));
            if (null === $this->tasksScheduledForDeletion) {
                $this->tasksScheduledForDeletion = clone $this->collTasks;
                $this->tasksScheduledForDeletion->clear();
            }
            $this->tasksScheduledForDeletion[]= $task;
            $task->setProject(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Project is new, it will return
     * an empty collection; or if this Project has previously
     * been saved, it will retrieve related Tasks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Project.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Task[] List of Task objects
     */
    public function getTasksJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = TaskQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getTasks($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->status = null;
        $this->desc = null;
        $this->place = null;
        $this->from_date = null;
        $this->to_date = null;
        $this->comment = null;
        $this->year_id = null;
        $this->file_id = null;
        $this->file_cat_id = null;
        $this->income_acc_id = null;
        $this->cost_acc_id = null;
        $this->bank_acc_id = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
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
            if ($this->collBookks) {
                foreach ($this->collBookks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collIncomes) {
                foreach ($this->collIncomes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCosts) {
                foreach ($this->collCosts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collTasks) {
                foreach ($this->collTasks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aYear instanceof Persistent) {
              $this->aYear->clearAllReferences($deep);
            }
            if ($this->aFile instanceof Persistent) {
              $this->aFile->clearAllReferences($deep);
            }
            if ($this->aCostFileCat instanceof Persistent) {
              $this->aCostFileCat->clearAllReferences($deep);
            }
            if ($this->aIncomeAcc instanceof Persistent) {
              $this->aIncomeAcc->clearAllReferences($deep);
            }
            if ($this->aCostAcc instanceof Persistent) {
              $this->aCostAcc->clearAllReferences($deep);
            }
            if ($this->aBankAcc instanceof Persistent) {
              $this->aBankAcc->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collBookks instanceof PropelCollection) {
            $this->collBookks->clearIterator();
        }
        $this->collBookks = null;
        if ($this->collIncomes instanceof PropelCollection) {
            $this->collIncomes->clearIterator();
        }
        $this->collIncomes = null;
        if ($this->collCosts instanceof PropelCollection) {
            $this->collCosts->clearIterator();
        }
        $this->collCosts = null;
        if ($this->collTasks instanceof PropelCollection) {
            $this->collTasks->clearIterator();
        }
        $this->collTasks = null;
        $this->aYear = null;
        $this->aFile = null;
        $this->aCostFileCat = null;
        $this->aIncomeAcc = null;
        $this->aCostAcc = null;
        $this->aBankAcc = null;
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

}
