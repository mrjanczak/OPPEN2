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
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;
use AppBundle\Model\BookkEntryQuery;
use AppBundle\Model\BookkPeer;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\Doc;
use AppBundle\Model\DocQuery;
use AppBundle\Model\Project;
use AppBundle\Model\ProjectQuery;
use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;

abstract class BaseBookk extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'AppBundle\\Model\\BookkPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BookkPeer
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
     * The value for the no field.
     * @var        int
     */
    protected $no;

    /**
     * The value for the desc field.
     * @var        string
     */
    protected $desc;

    /**
     * The value for the is_accepted field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_accepted;

    /**
     * The value for the bookking_date field.
     * @var        string
     */
    protected $bookking_date;

    /**
     * The value for the year_id field.
     * @var        int
     */
    protected $year_id;

    /**
     * The value for the doc_id field.
     * @var        int
     */
    protected $doc_id;

    /**
     * The value for the project_id field.
     * @var        int
     */
    protected $project_id;

    /**
     * @var        Year
     */
    protected $aYear;

    /**
     * @var        Doc
     */
    protected $aDoc;

    /**
     * @var        Project
     */
    protected $aProject;

    /**
     * @var        PropelObjectCollection|BookkEntry[] Collection to store aggregation of BookkEntry objects.
     */
    protected $collBookkEntries;
    protected $collBookkEntriesPartial;

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
    protected $bookkEntriesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_accepted = false;
    }

    /**
     * Initializes internal state of BaseBookk object.
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
     * Get the [no] column value.
     *
     * @return int
     */
    public function getNo()
    {

        return $this->no;
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
     * Get the [is_accepted] column value.
     *
     * @return boolean
     */
    public function getIsAccepted()
    {

        return $this->is_accepted;
    }

    /**
     * Get the [optionally formatted] temporal [bookking_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBookkingDate($format = null)
    {
        if ($this->bookking_date === null) {
            return null;
        }

        if ($this->bookking_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->bookking_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->bookking_date, true), $x);
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
     * Get the [doc_id] column value.
     *
     * @return int
     */
    public function getDocId()
    {

        return $this->doc_id;
    }

    /**
     * Get the [project_id] column value.
     *
     * @return int
     */
    public function getProjectId()
    {

        return $this->project_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Bookk The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = BookkPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [no] column.
     *
     * @param  int $v new value
     * @return Bookk The current object (for fluent API support)
     */
    public function setNo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->no !== $v) {
            $this->no = $v;
            $this->modifiedColumns[] = BookkPeer::NO;
        }


        return $this;
    } // setNo()

    /**
     * Set the value of [desc] column.
     *
     * @param  string $v new value
     * @return Bookk The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->desc !== $v) {
            $this->desc = $v;
            $this->modifiedColumns[] = BookkPeer::DESC;
        }


        return $this;
    } // setDesc()

    /**
     * Sets the value of the [is_accepted] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Bookk The current object (for fluent API support)
     */
    public function setIsAccepted($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_accepted !== $v) {
            $this->is_accepted = $v;
            $this->modifiedColumns[] = BookkPeer::IS_ACCEPTED;
        }


        return $this;
    } // setIsAccepted()

    /**
     * Sets the value of [bookking_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Bookk The current object (for fluent API support)
     */
    public function setBookkingDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->bookking_date !== null || $dt !== null) {
            $currentDateAsString = ($this->bookking_date !== null && $tmpDt = new DateTime($this->bookking_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->bookking_date = $newDateAsString;
                $this->modifiedColumns[] = BookkPeer::BOOKKING_DATE;
            }
        } // if either are not null


        return $this;
    } // setBookkingDate()

    /**
     * Set the value of [year_id] column.
     *
     * @param  int $v new value
     * @return Bookk The current object (for fluent API support)
     */
    public function setYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year_id !== $v) {
            $this->year_id = $v;
            $this->modifiedColumns[] = BookkPeer::YEAR_ID;
        }

        if ($this->aYear !== null && $this->aYear->getId() !== $v) {
            $this->aYear = null;
        }


        return $this;
    } // setYearId()

    /**
     * Set the value of [doc_id] column.
     *
     * @param  int $v new value
     * @return Bookk The current object (for fluent API support)
     */
    public function setDocId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->doc_id !== $v) {
            $this->doc_id = $v;
            $this->modifiedColumns[] = BookkPeer::DOC_ID;
        }

        if ($this->aDoc !== null && $this->aDoc->getId() !== $v) {
            $this->aDoc = null;
        }


        return $this;
    } // setDocId()

    /**
     * Set the value of [project_id] column.
     *
     * @param  int $v new value
     * @return Bookk The current object (for fluent API support)
     */
    public function setProjectId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->project_id !== $v) {
            $this->project_id = $v;
            $this->modifiedColumns[] = BookkPeer::PROJECT_ID;
        }

        if ($this->aProject !== null && $this->aProject->getId() !== $v) {
            $this->aProject = null;
        }


        return $this;
    } // setProjectId()

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
            if ($this->is_accepted !== false) {
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
            $this->no = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->desc = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->is_accepted = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->bookking_date = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->year_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->doc_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->project_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 8; // 8 = BookkPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Bookk object", $e);
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
        if ($this->aDoc !== null && $this->doc_id !== $this->aDoc->getId()) {
            $this->aDoc = null;
        }
        if ($this->aProject !== null && $this->project_id !== $this->aProject->getId()) {
            $this->aProject = null;
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
            $con = Propel::getConnection(BookkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = BookkPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aYear = null;
            $this->aDoc = null;
            $this->aProject = null;
            $this->collBookkEntries = null;

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
            $con = Propel::getConnection(BookkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = BookkQuery::create()
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
            $con = Propel::getConnection(BookkPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                BookkPeer::addInstanceToPool($this);
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

            if ($this->aDoc !== null) {
                if ($this->aDoc->isModified() || $this->aDoc->isNew()) {
                    $affectedRows += $this->aDoc->save($con);
                }
                $this->setDoc($this->aDoc);
            }

            if ($this->aProject !== null) {
                if ($this->aProject->isModified() || $this->aProject->isNew()) {
                    $affectedRows += $this->aProject->save($con);
                }
                $this->setProject($this->aProject);
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

            if ($this->bookkEntriesScheduledForDeletion !== null) {
                if (!$this->bookkEntriesScheduledForDeletion->isEmpty()) {
                    BookkEntryQuery::create()
                        ->filterByPrimaryKeys($this->bookkEntriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->bookkEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collBookkEntries !== null) {
                foreach ($this->collBookkEntries as $referrerFK) {
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

        $this->modifiedColumns[] = BookkPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BookkPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BookkPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(BookkPeer::NO)) {
            $modifiedColumns[':p' . $index++]  = '`no`';
        }
        if ($this->isColumnModified(BookkPeer::DESC)) {
            $modifiedColumns[':p' . $index++]  = '`desc`';
        }
        if ($this->isColumnModified(BookkPeer::IS_ACCEPTED)) {
            $modifiedColumns[':p' . $index++]  = '`is_accepted`';
        }
        if ($this->isColumnModified(BookkPeer::BOOKKING_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`bookking_date`';
        }
        if ($this->isColumnModified(BookkPeer::YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`year_id`';
        }
        if ($this->isColumnModified(BookkPeer::DOC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`doc_id`';
        }
        if ($this->isColumnModified(BookkPeer::PROJECT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`project_id`';
        }

        $sql = sprintf(
            'INSERT INTO `bookk` (%s) VALUES (%s)',
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
                    case '`no`':
                        $stmt->bindValue($identifier, $this->no, PDO::PARAM_INT);
                        break;
                    case '`desc`':
                        $stmt->bindValue($identifier, $this->desc, PDO::PARAM_STR);
                        break;
                    case '`is_accepted`':
                        $stmt->bindValue($identifier, (int) $this->is_accepted, PDO::PARAM_INT);
                        break;
                    case '`bookking_date`':
                        $stmt->bindValue($identifier, $this->bookking_date, PDO::PARAM_STR);
                        break;
                    case '`year_id`':
                        $stmt->bindValue($identifier, $this->year_id, PDO::PARAM_INT);
                        break;
                    case '`doc_id`':
                        $stmt->bindValue($identifier, $this->doc_id, PDO::PARAM_INT);
                        break;
                    case '`project_id`':
                        $stmt->bindValue($identifier, $this->project_id, PDO::PARAM_INT);
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

            if ($this->aDoc !== null) {
                if (!$this->aDoc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDoc->getValidationFailures());
                }
            }

            if ($this->aProject !== null) {
                if (!$this->aProject->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aProject->getValidationFailures());
                }
            }


            if (($retval = BookkPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBookkEntries !== null) {
                    foreach ($this->collBookkEntries as $referrerFK) {
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
        $pos = BookkPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getNo();
                break;
            case 2:
                return $this->getDesc();
                break;
            case 3:
                return $this->getIsAccepted();
                break;
            case 4:
                return $this->getBookkingDate();
                break;
            case 5:
                return $this->getYearId();
                break;
            case 6:
                return $this->getDocId();
                break;
            case 7:
                return $this->getProjectId();
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
        if (isset($alreadyDumpedObjects['Bookk'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Bookk'][$this->getPrimaryKey()] = true;
        $keys = BookkPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getNo(),
            $keys[2] => $this->getDesc(),
            $keys[3] => $this->getIsAccepted(),
            $keys[4] => $this->getBookkingDate(),
            $keys[5] => $this->getYearId(),
            $keys[6] => $this->getDocId(),
            $keys[7] => $this->getProjectId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aYear) {
                $result['Year'] = $this->aYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDoc) {
                $result['Doc'] = $this->aDoc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aProject) {
                $result['Project'] = $this->aProject->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collBookkEntries) {
                $result['BookkEntries'] = $this->collBookkEntries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = BookkPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setNo($value);
                break;
            case 2:
                $this->setDesc($value);
                break;
            case 3:
                $this->setIsAccepted($value);
                break;
            case 4:
                $this->setBookkingDate($value);
                break;
            case 5:
                $this->setYearId($value);
                break;
            case 6:
                $this->setDocId($value);
                break;
            case 7:
                $this->setProjectId($value);
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
        $keys = BookkPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setNo($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDesc($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setIsAccepted($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setBookkingDate($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setYearId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setDocId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setProjectId($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BookkPeer::DATABASE_NAME);

        if ($this->isColumnModified(BookkPeer::ID)) $criteria->add(BookkPeer::ID, $this->id);
        if ($this->isColumnModified(BookkPeer::NO)) $criteria->add(BookkPeer::NO, $this->no);
        if ($this->isColumnModified(BookkPeer::DESC)) $criteria->add(BookkPeer::DESC, $this->desc);
        if ($this->isColumnModified(BookkPeer::IS_ACCEPTED)) $criteria->add(BookkPeer::IS_ACCEPTED, $this->is_accepted);
        if ($this->isColumnModified(BookkPeer::BOOKKING_DATE)) $criteria->add(BookkPeer::BOOKKING_DATE, $this->bookking_date);
        if ($this->isColumnModified(BookkPeer::YEAR_ID)) $criteria->add(BookkPeer::YEAR_ID, $this->year_id);
        if ($this->isColumnModified(BookkPeer::DOC_ID)) $criteria->add(BookkPeer::DOC_ID, $this->doc_id);
        if ($this->isColumnModified(BookkPeer::PROJECT_ID)) $criteria->add(BookkPeer::PROJECT_ID, $this->project_id);

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
        $criteria = new Criteria(BookkPeer::DATABASE_NAME);
        $criteria->add(BookkPeer::ID, $this->id);

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
     * @param object $copyObj An object of Bookk (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setNo($this->getNo());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setIsAccepted($this->getIsAccepted());
        $copyObj->setBookkingDate($this->getBookkingDate());
        $copyObj->setYearId($this->getYearId());
        $copyObj->setDocId($this->getDocId());
        $copyObj->setProjectId($this->getProjectId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getBookkEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookkEntry($relObj->copy($deepCopy));
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
     * @return Bookk Clone of current object.
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
     * @return BookkPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BookkPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Year object.
     *
     * @param                  Year $v
     * @return Bookk The current object (for fluent API support)
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
            $v->addBookk($this);
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
                $this->aYear->addBookks($this);
             */
        }

        return $this->aYear;
    }

    /**
     * Declares an association between this object and a Doc object.
     *
     * @param                  Doc $v
     * @return Bookk The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDoc(Doc $v = null)
    {
        if ($v === null) {
            $this->setDocId(NULL);
        } else {
            $this->setDocId($v->getId());
        }

        $this->aDoc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Doc object, it will not be re-added.
        if ($v !== null) {
            $v->addBookk($this);
        }


        return $this;
    }


    /**
     * Get the associated Doc object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Doc The associated Doc object.
     * @throws PropelException
     */
    public function getDoc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDoc === null && ($this->doc_id !== null) && $doQuery) {
            $this->aDoc = DocQuery::create()->findPk($this->doc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDoc->addBookks($this);
             */
        }

        return $this->aDoc;
    }

    /**
     * Declares an association between this object and a Project object.
     *
     * @param                  Project $v
     * @return Bookk The current object (for fluent API support)
     * @throws PropelException
     */
    public function setProject(Project $v = null)
    {
        if ($v === null) {
            $this->setProjectId(NULL);
        } else {
            $this->setProjectId($v->getId());
        }

        $this->aProject = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Project object, it will not be re-added.
        if ($v !== null) {
            $v->addBookk($this);
        }


        return $this;
    }


    /**
     * Get the associated Project object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Project The associated Project object.
     * @throws PropelException
     */
    public function getProject(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aProject === null && ($this->project_id !== null) && $doQuery) {
            $this->aProject = ProjectQuery::create()->findPk($this->project_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aProject->addBookks($this);
             */
        }

        return $this->aProject;
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
        if ('BookkEntry' == $relationName) {
            $this->initBookkEntries();
        }
    }

    /**
     * Clears out the collBookkEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Bookk The current object (for fluent API support)
     * @see        addBookkEntries()
     */
    public function clearBookkEntries()
    {
        $this->collBookkEntries = null; // important to set this to null since that means it is uninitialized
        $this->collBookkEntriesPartial = null;

        return $this;
    }

    /**
     * reset is the collBookkEntries collection loaded partially
     *
     * @return void
     */
    public function resetPartialBookkEntries($v = true)
    {
        $this->collBookkEntriesPartial = $v;
    }

    /**
     * Initializes the collBookkEntries collection.
     *
     * By default this just sets the collBookkEntries collection to an empty array (like clearcollBookkEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBookkEntries($overrideExisting = true)
    {
        if (null !== $this->collBookkEntries && !$overrideExisting) {
            return;
        }
        $this->collBookkEntries = new PropelObjectCollection();
        $this->collBookkEntries->setModel('BookkEntry');
    }

    /**
     * Gets an array of BookkEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Bookk is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     * @throws PropelException
     */
    public function getBookkEntries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesPartial && !$this->isNew();
        if (null === $this->collBookkEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookkEntries) {
                // return empty collection
                $this->initBookkEntries();
            } else {
                $collBookkEntries = BookkEntryQuery::create(null, $criteria)
                    ->filterByBookk($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookkEntriesPartial && count($collBookkEntries)) {
                      $this->initBookkEntries(false);

                      foreach ($collBookkEntries as $obj) {
                        if (false == $this->collBookkEntries->contains($obj)) {
                          $this->collBookkEntries->append($obj);
                        }
                      }

                      $this->collBookkEntriesPartial = true;
                    }

                    $collBookkEntries->getInternalIterator()->rewind();

                    return $collBookkEntries;
                }

                if ($partial && $this->collBookkEntries) {
                    foreach ($this->collBookkEntries as $obj) {
                        if ($obj->isNew()) {
                            $collBookkEntries[] = $obj;
                        }
                    }
                }

                $this->collBookkEntries = $collBookkEntries;
                $this->collBookkEntriesPartial = false;
            }
        }

        return $this->collBookkEntries;
    }

    /**
     * Sets a collection of BookkEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $bookkEntries A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Bookk The current object (for fluent API support)
     */
    public function setBookkEntries(PropelCollection $bookkEntries, PropelPDO $con = null)
    {
        $bookkEntriesToDelete = $this->getBookkEntries(new Criteria(), $con)->diff($bookkEntries);


        $this->bookkEntriesScheduledForDeletion = $bookkEntriesToDelete;

        foreach ($bookkEntriesToDelete as $bookkEntryRemoved) {
            $bookkEntryRemoved->setBookk(null);
        }

        $this->collBookkEntries = null;
        foreach ($bookkEntries as $bookkEntry) {
            $this->addBookkEntry($bookkEntry);
        }

        $this->collBookkEntries = $bookkEntries;
        $this->collBookkEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BookkEntry objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BookkEntry objects.
     * @throws PropelException
     */
    public function countBookkEntries(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesPartial && !$this->isNew();
        if (null === $this->collBookkEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBookkEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBookkEntries());
            }
            $query = BookkEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBookk($this)
                ->count($con);
        }

        return count($this->collBookkEntries);
    }

    /**
     * Method called to associate a BookkEntry object to this object
     * through the BookkEntry foreign key attribute.
     *
     * @param    BookkEntry $l BookkEntry
     * @return Bookk The current object (for fluent API support)
     */
    public function addBookkEntry(BookkEntry $l)
    {
        if ($this->collBookkEntries === null) {
            $this->initBookkEntries();
            $this->collBookkEntriesPartial = true;
        }

        if (!in_array($l, $this->collBookkEntries->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBookkEntry($l);

            if ($this->bookkEntriesScheduledForDeletion and $this->bookkEntriesScheduledForDeletion->contains($l)) {
                $this->bookkEntriesScheduledForDeletion->remove($this->bookkEntriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BookkEntry $bookkEntry The bookkEntry object to add.
     */
    protected function doAddBookkEntry($bookkEntry)
    {
        $this->collBookkEntries[]= $bookkEntry;
        $bookkEntry->setBookk($this);
    }

    /**
     * @param	BookkEntry $bookkEntry The bookkEntry object to remove.
     * @return Bookk The current object (for fluent API support)
     */
    public function removeBookkEntry($bookkEntry)
    {
        if ($this->getBookkEntries()->contains($bookkEntry)) {
            $this->collBookkEntries->remove($this->collBookkEntries->search($bookkEntry));
            if (null === $this->bookkEntriesScheduledForDeletion) {
                $this->bookkEntriesScheduledForDeletion = clone $this->collBookkEntries;
                $this->bookkEntriesScheduledForDeletion->clear();
            }
            $this->bookkEntriesScheduledForDeletion[]= $bookkEntry;
            $bookkEntry->setBookk(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Bookk is new, it will return
     * an empty collection; or if this Bookk has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Bookk.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesJoinAccount($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Account', $join_behavior);

        return $this->getBookkEntries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Bookk is new, it will return
     * an empty collection; or if this Bookk has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Bookk.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesJoinFileLev1($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('FileLev1', $join_behavior);

        return $this->getBookkEntries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Bookk is new, it will return
     * an empty collection; or if this Bookk has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Bookk.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesJoinFileLev2($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('FileLev2', $join_behavior);

        return $this->getBookkEntries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Bookk is new, it will return
     * an empty collection; or if this Bookk has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Bookk.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesJoinFileLev3($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('FileLev3', $join_behavior);

        return $this->getBookkEntries($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->no = null;
        $this->desc = null;
        $this->is_accepted = null;
        $this->bookking_date = null;
        $this->year_id = null;
        $this->doc_id = null;
        $this->project_id = null;
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
            if ($this->collBookkEntries) {
                foreach ($this->collBookkEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aYear instanceof Persistent) {
              $this->aYear->clearAllReferences($deep);
            }
            if ($this->aDoc instanceof Persistent) {
              $this->aDoc->clearAllReferences($deep);
            }
            if ($this->aProject instanceof Persistent) {
              $this->aProject->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collBookkEntries instanceof PropelCollection) {
            $this->collBookkEntries->clearIterator();
        }
        $this->collBookkEntries = null;
        $this->aYear = null;
        $this->aDoc = null;
        $this->aProject = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'desc' column
     */
    public function __toString()
    {
        return (string) $this->getDesc();
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
