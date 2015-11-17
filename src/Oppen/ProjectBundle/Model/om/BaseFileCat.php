<?php

namespace Oppen\ProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\FileCatPeer;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\ProjectQuery;
use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\YearQuery;

abstract class BaseFileCat extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\FileCatPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        FileCatPeer
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
     * The value for the symbol field.
     * @var        string
     */
    protected $symbol;

    /**
     * The value for the as_project field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_project;

    /**
     * The value for the as_income field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_income;

    /**
     * The value for the as_cost field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_cost;

    /**
     * The value for the as_contractor field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_contractor;

    /**
     * The value for the is_locked field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_locked;

    /**
     * The value for the year_id field.
     * @var        int
     */
    protected $year_id;

    /**
     * The value for the sub_file_cat_id field.
     * @var        int
     */
    protected $sub_file_cat_id;

    /**
     * @var        Year
     */
    protected $aYear;

    /**
     * @var        FileCat
     */
    protected $aSubFileCat;

    /**
     * @var        PropelObjectCollection|FileCat[] Collection to store aggregation of FileCat objects.
     */
    protected $collFileCats;
    protected $collFileCatsPartial;

    /**
     * @var        PropelObjectCollection|File[] Collection to store aggregation of File objects.
     */
    protected $collFiles;
    protected $collFilesPartial;

    /**
     * @var        PropelObjectCollection|DocCat[] Collection to store aggregation of DocCat objects.
     */
    protected $collDocCats;
    protected $collDocCatsPartial;

    /**
     * @var        PropelObjectCollection|Account[] Collection to store aggregation of Account objects.
     */
    protected $collAccountsRelatedByFileCatLev1Id;
    protected $collAccountsRelatedByFileCatLev1IdPartial;

    /**
     * @var        PropelObjectCollection|Account[] Collection to store aggregation of Account objects.
     */
    protected $collAccountsRelatedByFileCatLev2Id;
    protected $collAccountsRelatedByFileCatLev2IdPartial;

    /**
     * @var        PropelObjectCollection|Account[] Collection to store aggregation of Account objects.
     */
    protected $collAccountsRelatedByFileCatLev3Id;
    protected $collAccountsRelatedByFileCatLev3IdPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $fileCatsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $filesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $docCatsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $accountsRelatedByFileCatLev1IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $accountsRelatedByFileCatLev2IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $accountsRelatedByFileCatLev3IdScheduledForDeletion = null;

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
        $this->as_project = false;
        $this->as_income = false;
        $this->as_cost = false;
        $this->as_contractor = false;
        $this->is_locked = false;
    }

    /**
     * Initializes internal state of BaseFileCat object.
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
     * Get the [symbol] column value.
     *
     * @return string
     */
    public function getSymbol()
    {

        return $this->symbol;
    }

    /**
     * Get the [as_project] column value.
     *
     * @return boolean
     */
    public function getAsProject()
    {

        return $this->as_project;
    }

    /**
     * Get the [as_income] column value.
     *
     * @return boolean
     */
    public function getAsIncome()
    {

        return $this->as_income;
    }

    /**
     * Get the [as_cost] column value.
     *
     * @return boolean
     */
    public function getAsCost()
    {

        return $this->as_cost;
    }

    /**
     * Get the [as_contractor] column value.
     *
     * @return boolean
     */
    public function getAsContractor()
    {

        return $this->as_contractor;
    }

    /**
     * Get the [is_locked] column value.
     *
     * @return boolean
     */
    public function getIsLocked()
    {

        return $this->is_locked;
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
     * Get the [sub_file_cat_id] column value.
     *
     * @return int
     */
    public function getSubFileCatId()
    {

        return $this->sub_file_cat_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = FileCatPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = FileCatPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [symbol] column.
     *
     * @param  string $v new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setSymbol($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->symbol !== $v) {
            $this->symbol = $v;
            $this->modifiedColumns[] = FileCatPeer::SYMBOL;
        }


        return $this;
    } // setSymbol()

    /**
     * Sets the value of the [as_project] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setAsProject($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_project !== $v) {
            $this->as_project = $v;
            $this->modifiedColumns[] = FileCatPeer::AS_PROJECT;
        }


        return $this;
    } // setAsProject()

    /**
     * Sets the value of the [as_income] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setAsIncome($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_income !== $v) {
            $this->as_income = $v;
            $this->modifiedColumns[] = FileCatPeer::AS_INCOME;
        }


        return $this;
    } // setAsIncome()

    /**
     * Sets the value of the [as_cost] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setAsCost($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_cost !== $v) {
            $this->as_cost = $v;
            $this->modifiedColumns[] = FileCatPeer::AS_COST;
        }


        return $this;
    } // setAsCost()

    /**
     * Sets the value of the [as_contractor] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setAsContractor($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_contractor !== $v) {
            $this->as_contractor = $v;
            $this->modifiedColumns[] = FileCatPeer::AS_CONTRACTOR;
        }


        return $this;
    } // setAsContractor()

    /**
     * Sets the value of the [is_locked] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setIsLocked($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_locked !== $v) {
            $this->is_locked = $v;
            $this->modifiedColumns[] = FileCatPeer::IS_LOCKED;
        }


        return $this;
    } // setIsLocked()

    /**
     * Set the value of [year_id] column.
     *
     * @param  int $v new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year_id !== $v) {
            $this->year_id = $v;
            $this->modifiedColumns[] = FileCatPeer::YEAR_ID;
        }

        if ($this->aYear !== null && $this->aYear->getId() !== $v) {
            $this->aYear = null;
        }


        return $this;
    } // setYearId()

    /**
     * Set the value of [sub_file_cat_id] column.
     *
     * @param  int $v new value
     * @return FileCat The current object (for fluent API support)
     */
    public function setSubFileCatId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sub_file_cat_id !== $v) {
            $this->sub_file_cat_id = $v;
            $this->modifiedColumns[] = FileCatPeer::SUB_FILE_CAT_ID;
        }

        if ($this->aSubFileCat !== null && $this->aSubFileCat->getId() !== $v) {
            $this->aSubFileCat = null;
        }


        return $this;
    } // setSubFileCatId()

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
            if ($this->as_project !== false) {
                return false;
            }

            if ($this->as_income !== false) {
                return false;
            }

            if ($this->as_cost !== false) {
                return false;
            }

            if ($this->as_contractor !== false) {
                return false;
            }

            if ($this->is_locked !== false) {
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
            $this->symbol = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->as_project = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->as_income = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->as_cost = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->as_contractor = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->is_locked = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->year_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->sub_file_cat_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = FileCatPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating FileCat object", $e);
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
        if ($this->aSubFileCat !== null && $this->sub_file_cat_id !== $this->aSubFileCat->getId()) {
            $this->aSubFileCat = null;
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
            $con = Propel::getConnection(FileCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = FileCatPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aYear = null;
            $this->aSubFileCat = null;
            $this->collFileCats = null;

            $this->collFiles = null;

            $this->collDocCats = null;

            $this->collAccountsRelatedByFileCatLev1Id = null;

            $this->collAccountsRelatedByFileCatLev2Id = null;

            $this->collAccountsRelatedByFileCatLev3Id = null;

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
            $con = Propel::getConnection(FileCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = FileCatQuery::create()
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
            $con = Propel::getConnection(FileCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                FileCatPeer::addInstanceToPool($this);
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

            if ($this->aSubFileCat !== null) {
                if ($this->aSubFileCat->isModified() || $this->aSubFileCat->isNew()) {
                    $affectedRows += $this->aSubFileCat->save($con);
                }
                $this->setSubFileCat($this->aSubFileCat);
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

            if ($this->fileCatsScheduledForDeletion !== null) {
                if (!$this->fileCatsScheduledForDeletion->isEmpty()) {
                    foreach ($this->fileCatsScheduledForDeletion as $fileCat) {
                        // need to save related object because we set the relation to null
                        $fileCat->save($con);
                    }
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

            if ($this->filesScheduledForDeletion !== null) {
                if (!$this->filesScheduledForDeletion->isEmpty()) {
                    FileQuery::create()
                        ->filterByPrimaryKeys($this->filesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->filesScheduledForDeletion = null;
                }
            }

            if ($this->collFiles !== null) {
                foreach ($this->collFiles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->docCatsScheduledForDeletion !== null) {
                if (!$this->docCatsScheduledForDeletion->isEmpty()) {
                    foreach ($this->docCatsScheduledForDeletion as $docCat) {
                        // need to save related object because we set the relation to null
                        $docCat->save($con);
                    }
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

            if ($this->accountsRelatedByFileCatLev1IdScheduledForDeletion !== null) {
                if (!$this->accountsRelatedByFileCatLev1IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->accountsRelatedByFileCatLev1IdScheduledForDeletion as $accountRelatedByFileCatLev1Id) {
                        // need to save related object because we set the relation to null
                        $accountRelatedByFileCatLev1Id->save($con);
                    }
                    $this->accountsRelatedByFileCatLev1IdScheduledForDeletion = null;
                }
            }

            if ($this->collAccountsRelatedByFileCatLev1Id !== null) {
                foreach ($this->collAccountsRelatedByFileCatLev1Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->accountsRelatedByFileCatLev2IdScheduledForDeletion !== null) {
                if (!$this->accountsRelatedByFileCatLev2IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->accountsRelatedByFileCatLev2IdScheduledForDeletion as $accountRelatedByFileCatLev2Id) {
                        // need to save related object because we set the relation to null
                        $accountRelatedByFileCatLev2Id->save($con);
                    }
                    $this->accountsRelatedByFileCatLev2IdScheduledForDeletion = null;
                }
            }

            if ($this->collAccountsRelatedByFileCatLev2Id !== null) {
                foreach ($this->collAccountsRelatedByFileCatLev2Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->accountsRelatedByFileCatLev3IdScheduledForDeletion !== null) {
                if (!$this->accountsRelatedByFileCatLev3IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->accountsRelatedByFileCatLev3IdScheduledForDeletion as $accountRelatedByFileCatLev3Id) {
                        // need to save related object because we set the relation to null
                        $accountRelatedByFileCatLev3Id->save($con);
                    }
                    $this->accountsRelatedByFileCatLev3IdScheduledForDeletion = null;
                }
            }

            if ($this->collAccountsRelatedByFileCatLev3Id !== null) {
                foreach ($this->collAccountsRelatedByFileCatLev3Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->projectsScheduledForDeletion !== null) {
                if (!$this->projectsScheduledForDeletion->isEmpty()) {
                    foreach ($this->projectsScheduledForDeletion as $project) {
                        // need to save related object because we set the relation to null
                        $project->save($con);
                    }
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

        $this->modifiedColumns[] = FileCatPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FileCatPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FileCatPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(FileCatPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(FileCatPeer::SYMBOL)) {
            $modifiedColumns[':p' . $index++]  = '`symbol`';
        }
        if ($this->isColumnModified(FileCatPeer::AS_PROJECT)) {
            $modifiedColumns[':p' . $index++]  = '`as_project`';
        }
        if ($this->isColumnModified(FileCatPeer::AS_INCOME)) {
            $modifiedColumns[':p' . $index++]  = '`as_income`';
        }
        if ($this->isColumnModified(FileCatPeer::AS_COST)) {
            $modifiedColumns[':p' . $index++]  = '`as_cost`';
        }
        if ($this->isColumnModified(FileCatPeer::AS_CONTRACTOR)) {
            $modifiedColumns[':p' . $index++]  = '`as_contractor`';
        }
        if ($this->isColumnModified(FileCatPeer::IS_LOCKED)) {
            $modifiedColumns[':p' . $index++]  = '`is_locked`';
        }
        if ($this->isColumnModified(FileCatPeer::YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`year_id`';
        }
        if ($this->isColumnModified(FileCatPeer::SUB_FILE_CAT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`sub_file_cat_id`';
        }

        $sql = sprintf(
            'INSERT INTO `file_cat` (%s) VALUES (%s)',
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
                    case '`symbol`':
                        $stmt->bindValue($identifier, $this->symbol, PDO::PARAM_STR);
                        break;
                    case '`as_project`':
                        $stmt->bindValue($identifier, (int) $this->as_project, PDO::PARAM_INT);
                        break;
                    case '`as_income`':
                        $stmt->bindValue($identifier, (int) $this->as_income, PDO::PARAM_INT);
                        break;
                    case '`as_cost`':
                        $stmt->bindValue($identifier, (int) $this->as_cost, PDO::PARAM_INT);
                        break;
                    case '`as_contractor`':
                        $stmt->bindValue($identifier, (int) $this->as_contractor, PDO::PARAM_INT);
                        break;
                    case '`is_locked`':
                        $stmt->bindValue($identifier, (int) $this->is_locked, PDO::PARAM_INT);
                        break;
                    case '`year_id`':
                        $stmt->bindValue($identifier, $this->year_id, PDO::PARAM_INT);
                        break;
                    case '`sub_file_cat_id`':
                        $stmt->bindValue($identifier, $this->sub_file_cat_id, PDO::PARAM_INT);
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

            if ($this->aSubFileCat !== null) {
                if (!$this->aSubFileCat->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSubFileCat->getValidationFailures());
                }
            }


            if (($retval = FileCatPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collFileCats !== null) {
                    foreach ($this->collFileCats as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collFiles !== null) {
                    foreach ($this->collFiles as $referrerFK) {
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

                if ($this->collAccountsRelatedByFileCatLev1Id !== null) {
                    foreach ($this->collAccountsRelatedByFileCatLev1Id as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collAccountsRelatedByFileCatLev2Id !== null) {
                    foreach ($this->collAccountsRelatedByFileCatLev2Id as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collAccountsRelatedByFileCatLev3Id !== null) {
                    foreach ($this->collAccountsRelatedByFileCatLev3Id as $referrerFK) {
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
        $pos = FileCatPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getSymbol();
                break;
            case 3:
                return $this->getAsProject();
                break;
            case 4:
                return $this->getAsIncome();
                break;
            case 5:
                return $this->getAsCost();
                break;
            case 6:
                return $this->getAsContractor();
                break;
            case 7:
                return $this->getIsLocked();
                break;
            case 8:
                return $this->getYearId();
                break;
            case 9:
                return $this->getSubFileCatId();
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
        if (isset($alreadyDumpedObjects['FileCat'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['FileCat'][$this->getPrimaryKey()] = true;
        $keys = FileCatPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getSymbol(),
            $keys[3] => $this->getAsProject(),
            $keys[4] => $this->getAsIncome(),
            $keys[5] => $this->getAsCost(),
            $keys[6] => $this->getAsContractor(),
            $keys[7] => $this->getIsLocked(),
            $keys[8] => $this->getYearId(),
            $keys[9] => $this->getSubFileCatId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aYear) {
                $result['Year'] = $this->aYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSubFileCat) {
                $result['SubFileCat'] = $this->aSubFileCat->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collFileCats) {
                $result['FileCats'] = $this->collFileCats->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collFiles) {
                $result['Files'] = $this->collFiles->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDocCats) {
                $result['DocCats'] = $this->collDocCats->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAccountsRelatedByFileCatLev1Id) {
                $result['AccountsRelatedByFileCatLev1Id'] = $this->collAccountsRelatedByFileCatLev1Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAccountsRelatedByFileCatLev2Id) {
                $result['AccountsRelatedByFileCatLev2Id'] = $this->collAccountsRelatedByFileCatLev2Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collAccountsRelatedByFileCatLev3Id) {
                $result['AccountsRelatedByFileCatLev3Id'] = $this->collAccountsRelatedByFileCatLev3Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = FileCatPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setSymbol($value);
                break;
            case 3:
                $this->setAsProject($value);
                break;
            case 4:
                $this->setAsIncome($value);
                break;
            case 5:
                $this->setAsCost($value);
                break;
            case 6:
                $this->setAsContractor($value);
                break;
            case 7:
                $this->setIsLocked($value);
                break;
            case 8:
                $this->setYearId($value);
                break;
            case 9:
                $this->setSubFileCatId($value);
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
        $keys = FileCatPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSymbol($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setAsProject($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setAsIncome($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setAsCost($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAsContractor($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIsLocked($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setYearId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setSubFileCatId($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(FileCatPeer::DATABASE_NAME);

        if ($this->isColumnModified(FileCatPeer::ID)) $criteria->add(FileCatPeer::ID, $this->id);
        if ($this->isColumnModified(FileCatPeer::NAME)) $criteria->add(FileCatPeer::NAME, $this->name);
        if ($this->isColumnModified(FileCatPeer::SYMBOL)) $criteria->add(FileCatPeer::SYMBOL, $this->symbol);
        if ($this->isColumnModified(FileCatPeer::AS_PROJECT)) $criteria->add(FileCatPeer::AS_PROJECT, $this->as_project);
        if ($this->isColumnModified(FileCatPeer::AS_INCOME)) $criteria->add(FileCatPeer::AS_INCOME, $this->as_income);
        if ($this->isColumnModified(FileCatPeer::AS_COST)) $criteria->add(FileCatPeer::AS_COST, $this->as_cost);
        if ($this->isColumnModified(FileCatPeer::AS_CONTRACTOR)) $criteria->add(FileCatPeer::AS_CONTRACTOR, $this->as_contractor);
        if ($this->isColumnModified(FileCatPeer::IS_LOCKED)) $criteria->add(FileCatPeer::IS_LOCKED, $this->is_locked);
        if ($this->isColumnModified(FileCatPeer::YEAR_ID)) $criteria->add(FileCatPeer::YEAR_ID, $this->year_id);
        if ($this->isColumnModified(FileCatPeer::SUB_FILE_CAT_ID)) $criteria->add(FileCatPeer::SUB_FILE_CAT_ID, $this->sub_file_cat_id);

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
        $criteria = new Criteria(FileCatPeer::DATABASE_NAME);
        $criteria->add(FileCatPeer::ID, $this->id);

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
     * @param object $copyObj An object of FileCat (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setSymbol($this->getSymbol());
        $copyObj->setAsProject($this->getAsProject());
        $copyObj->setAsIncome($this->getAsIncome());
        $copyObj->setAsCost($this->getAsCost());
        $copyObj->setAsContractor($this->getAsContractor());
        $copyObj->setIsLocked($this->getIsLocked());
        $copyObj->setYearId($this->getYearId());
        $copyObj->setSubFileCatId($this->getSubFileCatId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getFileCats() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFileCat($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getFiles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFile($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDocCats() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDocCat($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAccountsRelatedByFileCatLev1Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAccountRelatedByFileCatLev1Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAccountsRelatedByFileCatLev2Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAccountRelatedByFileCatLev2Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getAccountsRelatedByFileCatLev3Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addAccountRelatedByFileCatLev3Id($relObj->copy($deepCopy));
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
     * @return FileCat Clone of current object.
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
     * @return FileCatPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new FileCatPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Year object.
     *
     * @param                  Year $v
     * @return FileCat The current object (for fluent API support)
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
            $v->addFileCat($this);
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
                $this->aYear->addFileCats($this);
             */
        }

        return $this->aYear;
    }

    /**
     * Declares an association between this object and a FileCat object.
     *
     * @param                  FileCat $v
     * @return FileCat The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSubFileCat(FileCat $v = null)
    {
        if ($v === null) {
            $this->setSubFileCatId(NULL);
        } else {
            $this->setSubFileCatId($v->getId());
        }

        $this->aSubFileCat = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FileCat object, it will not be re-added.
        if ($v !== null) {
            $v->addFileCat($this);
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
    public function getSubFileCat(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSubFileCat === null && ($this->sub_file_cat_id !== null) && $doQuery) {
            $this->aSubFileCat = FileCatQuery::create()->findPk($this->sub_file_cat_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSubFileCat->addFileCats($this);
             */
        }

        return $this->aSubFileCat;
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
        if ('FileCat' == $relationName) {
            $this->initFileCats();
        }
        if ('File' == $relationName) {
            $this->initFiles();
        }
        if ('DocCat' == $relationName) {
            $this->initDocCats();
        }
        if ('AccountRelatedByFileCatLev1Id' == $relationName) {
            $this->initAccountsRelatedByFileCatLev1Id();
        }
        if ('AccountRelatedByFileCatLev2Id' == $relationName) {
            $this->initAccountsRelatedByFileCatLev2Id();
        }
        if ('AccountRelatedByFileCatLev3Id' == $relationName) {
            $this->initAccountsRelatedByFileCatLev3Id();
        }
        if ('Project' == $relationName) {
            $this->initProjects();
        }
    }

    /**
     * Clears out the collFileCats collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return FileCat The current object (for fluent API support)
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
     * If this FileCat is new, it will return
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
                    ->filterBySubFileCat($this)
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
     * @return FileCat The current object (for fluent API support)
     */
    public function setFileCats(PropelCollection $fileCats, PropelPDO $con = null)
    {
        $fileCatsToDelete = $this->getFileCats(new Criteria(), $con)->diff($fileCats);


        $this->fileCatsScheduledForDeletion = $fileCatsToDelete;

        foreach ($fileCatsToDelete as $fileCatRemoved) {
            $fileCatRemoved->setSubFileCat(null);
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
                ->filterBySubFileCat($this)
                ->count($con);
        }

        return count($this->collFileCats);
    }

    /**
     * Method called to associate a FileCat object to this object
     * through the FileCat foreign key attribute.
     *
     * @param    FileCat $l FileCat
     * @return FileCat The current object (for fluent API support)
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
        $fileCat->setSubFileCat($this);
    }

    /**
     * @param	FileCat $fileCat The fileCat object to remove.
     * @return FileCat The current object (for fluent API support)
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
            $fileCat->setSubFileCat(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related FileCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|FileCat[] List of FileCat objects
     */
    public function getFileCatsJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FileCatQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getFileCats($query, $con);
    }

    /**
     * Clears out the collFiles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return FileCat The current object (for fluent API support)
     * @see        addFiles()
     */
    public function clearFiles()
    {
        $this->collFiles = null; // important to set this to null since that means it is uninitialized
        $this->collFilesPartial = null;

        return $this;
    }

    /**
     * reset is the collFiles collection loaded partially
     *
     * @return void
     */
    public function resetPartialFiles($v = true)
    {
        $this->collFilesPartial = $v;
    }

    /**
     * Initializes the collFiles collection.
     *
     * By default this just sets the collFiles collection to an empty array (like clearcollFiles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFiles($overrideExisting = true)
    {
        if (null !== $this->collFiles && !$overrideExisting) {
            return;
        }
        $this->collFiles = new PropelObjectCollection();
        $this->collFiles->setModel('File');
    }

    /**
     * Gets an array of File objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this FileCat is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|File[] List of File objects
     * @throws PropelException
     */
    public function getFiles($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                // return empty collection
                $this->initFiles();
            } else {
                $collFiles = FileQuery::create(null, $criteria)
                    ->filterByFileCat($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFilesPartial && count($collFiles)) {
                      $this->initFiles(false);

                      foreach ($collFiles as $obj) {
                        if (false == $this->collFiles->contains($obj)) {
                          $this->collFiles->append($obj);
                        }
                      }

                      $this->collFilesPartial = true;
                    }

                    $collFiles->getInternalIterator()->rewind();

                    return $collFiles;
                }

                if ($partial && $this->collFiles) {
                    foreach ($this->collFiles as $obj) {
                        if ($obj->isNew()) {
                            $collFiles[] = $obj;
                        }
                    }
                }

                $this->collFiles = $collFiles;
                $this->collFilesPartial = false;
            }
        }

        return $this->collFiles;
    }

    /**
     * Sets a collection of File objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $files A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return FileCat The current object (for fluent API support)
     */
    public function setFiles(PropelCollection $files, PropelPDO $con = null)
    {
        $filesToDelete = $this->getFiles(new Criteria(), $con)->diff($files);


        $this->filesScheduledForDeletion = $filesToDelete;

        foreach ($filesToDelete as $fileRemoved) {
            $fileRemoved->setFileCat(null);
        }

        $this->collFiles = null;
        foreach ($files as $file) {
            $this->addFile($file);
        }

        $this->collFiles = $files;
        $this->collFilesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related File objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related File objects.
     * @throws PropelException
     */
    public function countFiles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFiles());
            }
            $query = FileQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFileCat($this)
                ->count($con);
        }

        return count($this->collFiles);
    }

    /**
     * Method called to associate a File object to this object
     * through the File foreign key attribute.
     *
     * @param    File $l File
     * @return FileCat The current object (for fluent API support)
     */
    public function addFile(File $l)
    {
        if ($this->collFiles === null) {
            $this->initFiles();
            $this->collFilesPartial = true;
        }

        if (!in_array($l, $this->collFiles->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFile($l);

            if ($this->filesScheduledForDeletion and $this->filesScheduledForDeletion->contains($l)) {
                $this->filesScheduledForDeletion->remove($this->filesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	File $file The file object to add.
     */
    protected function doAddFile($file)
    {
        $this->collFiles[]= $file;
        $file->setFileCat($this);
    }

    /**
     * @param	File $file The file object to remove.
     * @return FileCat The current object (for fluent API support)
     */
    public function removeFile($file)
    {
        if ($this->getFiles()->contains($file)) {
            $this->collFiles->remove($this->collFiles->search($file));
            if (null === $this->filesScheduledForDeletion) {
                $this->filesScheduledForDeletion = clone $this->collFiles;
                $this->filesScheduledForDeletion->clear();
            }
            $this->filesScheduledForDeletion[]= $file;
            $file->setFileCat(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related Files from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|File[] List of File objects
     */
    public function getFilesJoinSubFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FileQuery::create(null, $criteria);
        $query->joinWith('SubFile', $join_behavior);

        return $this->getFiles($query, $con);
    }

    /**
     * Clears out the collDocCats collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return FileCat The current object (for fluent API support)
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
     * If this FileCat is new, it will return
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
                    ->filterByFileCat($this)
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
     * @return FileCat The current object (for fluent API support)
     */
    public function setDocCats(PropelCollection $docCats, PropelPDO $con = null)
    {
        $docCatsToDelete = $this->getDocCats(new Criteria(), $con)->diff($docCats);


        $this->docCatsScheduledForDeletion = $docCatsToDelete;

        foreach ($docCatsToDelete as $docCatRemoved) {
            $docCatRemoved->setFileCat(null);
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
                ->filterByFileCat($this)
                ->count($con);
        }

        return count($this->collDocCats);
    }

    /**
     * Method called to associate a DocCat object to this object
     * through the DocCat foreign key attribute.
     *
     * @param    DocCat $l DocCat
     * @return FileCat The current object (for fluent API support)
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
        $docCat->setFileCat($this);
    }

    /**
     * @param	DocCat $docCat The docCat object to remove.
     * @return FileCat The current object (for fluent API support)
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
            $docCat->setFileCat(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related DocCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getDocCats($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related DocCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
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
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related DocCats from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
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
     * Clears out the collAccountsRelatedByFileCatLev1Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return FileCat The current object (for fluent API support)
     * @see        addAccountsRelatedByFileCatLev1Id()
     */
    public function clearAccountsRelatedByFileCatLev1Id()
    {
        $this->collAccountsRelatedByFileCatLev1Id = null; // important to set this to null since that means it is uninitialized
        $this->collAccountsRelatedByFileCatLev1IdPartial = null;

        return $this;
    }

    /**
     * reset is the collAccountsRelatedByFileCatLev1Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialAccountsRelatedByFileCatLev1Id($v = true)
    {
        $this->collAccountsRelatedByFileCatLev1IdPartial = $v;
    }

    /**
     * Initializes the collAccountsRelatedByFileCatLev1Id collection.
     *
     * By default this just sets the collAccountsRelatedByFileCatLev1Id collection to an empty array (like clearcollAccountsRelatedByFileCatLev1Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAccountsRelatedByFileCatLev1Id($overrideExisting = true)
    {
        if (null !== $this->collAccountsRelatedByFileCatLev1Id && !$overrideExisting) {
            return;
        }
        $this->collAccountsRelatedByFileCatLev1Id = new PropelObjectCollection();
        $this->collAccountsRelatedByFileCatLev1Id->setModel('Account');
    }

    /**
     * Gets an array of Account objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this FileCat is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Account[] List of Account objects
     * @throws PropelException
     */
    public function getAccountsRelatedByFileCatLev1Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAccountsRelatedByFileCatLev1IdPartial && !$this->isNew();
        if (null === $this->collAccountsRelatedByFileCatLev1Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAccountsRelatedByFileCatLev1Id) {
                // return empty collection
                $this->initAccountsRelatedByFileCatLev1Id();
            } else {
                $collAccountsRelatedByFileCatLev1Id = AccountQuery::create(null, $criteria)
                    ->filterByFileCatLev1($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAccountsRelatedByFileCatLev1IdPartial && count($collAccountsRelatedByFileCatLev1Id)) {
                      $this->initAccountsRelatedByFileCatLev1Id(false);

                      foreach ($collAccountsRelatedByFileCatLev1Id as $obj) {
                        if (false == $this->collAccountsRelatedByFileCatLev1Id->contains($obj)) {
                          $this->collAccountsRelatedByFileCatLev1Id->append($obj);
                        }
                      }

                      $this->collAccountsRelatedByFileCatLev1IdPartial = true;
                    }

                    $collAccountsRelatedByFileCatLev1Id->getInternalIterator()->rewind();

                    return $collAccountsRelatedByFileCatLev1Id;
                }

                if ($partial && $this->collAccountsRelatedByFileCatLev1Id) {
                    foreach ($this->collAccountsRelatedByFileCatLev1Id as $obj) {
                        if ($obj->isNew()) {
                            $collAccountsRelatedByFileCatLev1Id[] = $obj;
                        }
                    }
                }

                $this->collAccountsRelatedByFileCatLev1Id = $collAccountsRelatedByFileCatLev1Id;
                $this->collAccountsRelatedByFileCatLev1IdPartial = false;
            }
        }

        return $this->collAccountsRelatedByFileCatLev1Id;
    }

    /**
     * Sets a collection of AccountRelatedByFileCatLev1Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $accountsRelatedByFileCatLev1Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return FileCat The current object (for fluent API support)
     */
    public function setAccountsRelatedByFileCatLev1Id(PropelCollection $accountsRelatedByFileCatLev1Id, PropelPDO $con = null)
    {
        $accountsRelatedByFileCatLev1IdToDelete = $this->getAccountsRelatedByFileCatLev1Id(new Criteria(), $con)->diff($accountsRelatedByFileCatLev1Id);


        $this->accountsRelatedByFileCatLev1IdScheduledForDeletion = $accountsRelatedByFileCatLev1IdToDelete;

        foreach ($accountsRelatedByFileCatLev1IdToDelete as $accountRelatedByFileCatLev1IdRemoved) {
            $accountRelatedByFileCatLev1IdRemoved->setFileCatLev1(null);
        }

        $this->collAccountsRelatedByFileCatLev1Id = null;
        foreach ($accountsRelatedByFileCatLev1Id as $accountRelatedByFileCatLev1Id) {
            $this->addAccountRelatedByFileCatLev1Id($accountRelatedByFileCatLev1Id);
        }

        $this->collAccountsRelatedByFileCatLev1Id = $accountsRelatedByFileCatLev1Id;
        $this->collAccountsRelatedByFileCatLev1IdPartial = false;

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
    public function countAccountsRelatedByFileCatLev1Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAccountsRelatedByFileCatLev1IdPartial && !$this->isNew();
        if (null === $this->collAccountsRelatedByFileCatLev1Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAccountsRelatedByFileCatLev1Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAccountsRelatedByFileCatLev1Id());
            }
            $query = AccountQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFileCatLev1($this)
                ->count($con);
        }

        return count($this->collAccountsRelatedByFileCatLev1Id);
    }

    /**
     * Method called to associate a Account object to this object
     * through the Account foreign key attribute.
     *
     * @param    Account $l Account
     * @return FileCat The current object (for fluent API support)
     */
    public function addAccountRelatedByFileCatLev1Id(Account $l)
    {
        if ($this->collAccountsRelatedByFileCatLev1Id === null) {
            $this->initAccountsRelatedByFileCatLev1Id();
            $this->collAccountsRelatedByFileCatLev1IdPartial = true;
        }

        if (!in_array($l, $this->collAccountsRelatedByFileCatLev1Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAccountRelatedByFileCatLev1Id($l);

            if ($this->accountsRelatedByFileCatLev1IdScheduledForDeletion and $this->accountsRelatedByFileCatLev1IdScheduledForDeletion->contains($l)) {
                $this->accountsRelatedByFileCatLev1IdScheduledForDeletion->remove($this->accountsRelatedByFileCatLev1IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	AccountRelatedByFileCatLev1Id $accountRelatedByFileCatLev1Id The accountRelatedByFileCatLev1Id object to add.
     */
    protected function doAddAccountRelatedByFileCatLev1Id($accountRelatedByFileCatLev1Id)
    {
        $this->collAccountsRelatedByFileCatLev1Id[]= $accountRelatedByFileCatLev1Id;
        $accountRelatedByFileCatLev1Id->setFileCatLev1($this);
    }

    /**
     * @param	AccountRelatedByFileCatLev1Id $accountRelatedByFileCatLev1Id The accountRelatedByFileCatLev1Id object to remove.
     * @return FileCat The current object (for fluent API support)
     */
    public function removeAccountRelatedByFileCatLev1Id($accountRelatedByFileCatLev1Id)
    {
        if ($this->getAccountsRelatedByFileCatLev1Id()->contains($accountRelatedByFileCatLev1Id)) {
            $this->collAccountsRelatedByFileCatLev1Id->remove($this->collAccountsRelatedByFileCatLev1Id->search($accountRelatedByFileCatLev1Id));
            if (null === $this->accountsRelatedByFileCatLev1IdScheduledForDeletion) {
                $this->accountsRelatedByFileCatLev1IdScheduledForDeletion = clone $this->collAccountsRelatedByFileCatLev1Id;
                $this->accountsRelatedByFileCatLev1IdScheduledForDeletion->clear();
            }
            $this->accountsRelatedByFileCatLev1IdScheduledForDeletion[]= $accountRelatedByFileCatLev1Id;
            $accountRelatedByFileCatLev1Id->setFileCatLev1(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related AccountsRelatedByFileCatLev1Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Account[] List of Account objects
     */
    public function getAccountsRelatedByFileCatLev1IdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AccountQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getAccountsRelatedByFileCatLev1Id($query, $con);
    }

    /**
     * Clears out the collAccountsRelatedByFileCatLev2Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return FileCat The current object (for fluent API support)
     * @see        addAccountsRelatedByFileCatLev2Id()
     */
    public function clearAccountsRelatedByFileCatLev2Id()
    {
        $this->collAccountsRelatedByFileCatLev2Id = null; // important to set this to null since that means it is uninitialized
        $this->collAccountsRelatedByFileCatLev2IdPartial = null;

        return $this;
    }

    /**
     * reset is the collAccountsRelatedByFileCatLev2Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialAccountsRelatedByFileCatLev2Id($v = true)
    {
        $this->collAccountsRelatedByFileCatLev2IdPartial = $v;
    }

    /**
     * Initializes the collAccountsRelatedByFileCatLev2Id collection.
     *
     * By default this just sets the collAccountsRelatedByFileCatLev2Id collection to an empty array (like clearcollAccountsRelatedByFileCatLev2Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAccountsRelatedByFileCatLev2Id($overrideExisting = true)
    {
        if (null !== $this->collAccountsRelatedByFileCatLev2Id && !$overrideExisting) {
            return;
        }
        $this->collAccountsRelatedByFileCatLev2Id = new PropelObjectCollection();
        $this->collAccountsRelatedByFileCatLev2Id->setModel('Account');
    }

    /**
     * Gets an array of Account objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this FileCat is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Account[] List of Account objects
     * @throws PropelException
     */
    public function getAccountsRelatedByFileCatLev2Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAccountsRelatedByFileCatLev2IdPartial && !$this->isNew();
        if (null === $this->collAccountsRelatedByFileCatLev2Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAccountsRelatedByFileCatLev2Id) {
                // return empty collection
                $this->initAccountsRelatedByFileCatLev2Id();
            } else {
                $collAccountsRelatedByFileCatLev2Id = AccountQuery::create(null, $criteria)
                    ->filterByFileCatLev2($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAccountsRelatedByFileCatLev2IdPartial && count($collAccountsRelatedByFileCatLev2Id)) {
                      $this->initAccountsRelatedByFileCatLev2Id(false);

                      foreach ($collAccountsRelatedByFileCatLev2Id as $obj) {
                        if (false == $this->collAccountsRelatedByFileCatLev2Id->contains($obj)) {
                          $this->collAccountsRelatedByFileCatLev2Id->append($obj);
                        }
                      }

                      $this->collAccountsRelatedByFileCatLev2IdPartial = true;
                    }

                    $collAccountsRelatedByFileCatLev2Id->getInternalIterator()->rewind();

                    return $collAccountsRelatedByFileCatLev2Id;
                }

                if ($partial && $this->collAccountsRelatedByFileCatLev2Id) {
                    foreach ($this->collAccountsRelatedByFileCatLev2Id as $obj) {
                        if ($obj->isNew()) {
                            $collAccountsRelatedByFileCatLev2Id[] = $obj;
                        }
                    }
                }

                $this->collAccountsRelatedByFileCatLev2Id = $collAccountsRelatedByFileCatLev2Id;
                $this->collAccountsRelatedByFileCatLev2IdPartial = false;
            }
        }

        return $this->collAccountsRelatedByFileCatLev2Id;
    }

    /**
     * Sets a collection of AccountRelatedByFileCatLev2Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $accountsRelatedByFileCatLev2Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return FileCat The current object (for fluent API support)
     */
    public function setAccountsRelatedByFileCatLev2Id(PropelCollection $accountsRelatedByFileCatLev2Id, PropelPDO $con = null)
    {
        $accountsRelatedByFileCatLev2IdToDelete = $this->getAccountsRelatedByFileCatLev2Id(new Criteria(), $con)->diff($accountsRelatedByFileCatLev2Id);


        $this->accountsRelatedByFileCatLev2IdScheduledForDeletion = $accountsRelatedByFileCatLev2IdToDelete;

        foreach ($accountsRelatedByFileCatLev2IdToDelete as $accountRelatedByFileCatLev2IdRemoved) {
            $accountRelatedByFileCatLev2IdRemoved->setFileCatLev2(null);
        }

        $this->collAccountsRelatedByFileCatLev2Id = null;
        foreach ($accountsRelatedByFileCatLev2Id as $accountRelatedByFileCatLev2Id) {
            $this->addAccountRelatedByFileCatLev2Id($accountRelatedByFileCatLev2Id);
        }

        $this->collAccountsRelatedByFileCatLev2Id = $accountsRelatedByFileCatLev2Id;
        $this->collAccountsRelatedByFileCatLev2IdPartial = false;

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
    public function countAccountsRelatedByFileCatLev2Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAccountsRelatedByFileCatLev2IdPartial && !$this->isNew();
        if (null === $this->collAccountsRelatedByFileCatLev2Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAccountsRelatedByFileCatLev2Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAccountsRelatedByFileCatLev2Id());
            }
            $query = AccountQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFileCatLev2($this)
                ->count($con);
        }

        return count($this->collAccountsRelatedByFileCatLev2Id);
    }

    /**
     * Method called to associate a Account object to this object
     * through the Account foreign key attribute.
     *
     * @param    Account $l Account
     * @return FileCat The current object (for fluent API support)
     */
    public function addAccountRelatedByFileCatLev2Id(Account $l)
    {
        if ($this->collAccountsRelatedByFileCatLev2Id === null) {
            $this->initAccountsRelatedByFileCatLev2Id();
            $this->collAccountsRelatedByFileCatLev2IdPartial = true;
        }

        if (!in_array($l, $this->collAccountsRelatedByFileCatLev2Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAccountRelatedByFileCatLev2Id($l);

            if ($this->accountsRelatedByFileCatLev2IdScheduledForDeletion and $this->accountsRelatedByFileCatLev2IdScheduledForDeletion->contains($l)) {
                $this->accountsRelatedByFileCatLev2IdScheduledForDeletion->remove($this->accountsRelatedByFileCatLev2IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	AccountRelatedByFileCatLev2Id $accountRelatedByFileCatLev2Id The accountRelatedByFileCatLev2Id object to add.
     */
    protected function doAddAccountRelatedByFileCatLev2Id($accountRelatedByFileCatLev2Id)
    {
        $this->collAccountsRelatedByFileCatLev2Id[]= $accountRelatedByFileCatLev2Id;
        $accountRelatedByFileCatLev2Id->setFileCatLev2($this);
    }

    /**
     * @param	AccountRelatedByFileCatLev2Id $accountRelatedByFileCatLev2Id The accountRelatedByFileCatLev2Id object to remove.
     * @return FileCat The current object (for fluent API support)
     */
    public function removeAccountRelatedByFileCatLev2Id($accountRelatedByFileCatLev2Id)
    {
        if ($this->getAccountsRelatedByFileCatLev2Id()->contains($accountRelatedByFileCatLev2Id)) {
            $this->collAccountsRelatedByFileCatLev2Id->remove($this->collAccountsRelatedByFileCatLev2Id->search($accountRelatedByFileCatLev2Id));
            if (null === $this->accountsRelatedByFileCatLev2IdScheduledForDeletion) {
                $this->accountsRelatedByFileCatLev2IdScheduledForDeletion = clone $this->collAccountsRelatedByFileCatLev2Id;
                $this->accountsRelatedByFileCatLev2IdScheduledForDeletion->clear();
            }
            $this->accountsRelatedByFileCatLev2IdScheduledForDeletion[]= $accountRelatedByFileCatLev2Id;
            $accountRelatedByFileCatLev2Id->setFileCatLev2(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related AccountsRelatedByFileCatLev2Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Account[] List of Account objects
     */
    public function getAccountsRelatedByFileCatLev2IdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AccountQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getAccountsRelatedByFileCatLev2Id($query, $con);
    }

    /**
     * Clears out the collAccountsRelatedByFileCatLev3Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return FileCat The current object (for fluent API support)
     * @see        addAccountsRelatedByFileCatLev3Id()
     */
    public function clearAccountsRelatedByFileCatLev3Id()
    {
        $this->collAccountsRelatedByFileCatLev3Id = null; // important to set this to null since that means it is uninitialized
        $this->collAccountsRelatedByFileCatLev3IdPartial = null;

        return $this;
    }

    /**
     * reset is the collAccountsRelatedByFileCatLev3Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialAccountsRelatedByFileCatLev3Id($v = true)
    {
        $this->collAccountsRelatedByFileCatLev3IdPartial = $v;
    }

    /**
     * Initializes the collAccountsRelatedByFileCatLev3Id collection.
     *
     * By default this just sets the collAccountsRelatedByFileCatLev3Id collection to an empty array (like clearcollAccountsRelatedByFileCatLev3Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initAccountsRelatedByFileCatLev3Id($overrideExisting = true)
    {
        if (null !== $this->collAccountsRelatedByFileCatLev3Id && !$overrideExisting) {
            return;
        }
        $this->collAccountsRelatedByFileCatLev3Id = new PropelObjectCollection();
        $this->collAccountsRelatedByFileCatLev3Id->setModel('Account');
    }

    /**
     * Gets an array of Account objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this FileCat is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Account[] List of Account objects
     * @throws PropelException
     */
    public function getAccountsRelatedByFileCatLev3Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collAccountsRelatedByFileCatLev3IdPartial && !$this->isNew();
        if (null === $this->collAccountsRelatedByFileCatLev3Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collAccountsRelatedByFileCatLev3Id) {
                // return empty collection
                $this->initAccountsRelatedByFileCatLev3Id();
            } else {
                $collAccountsRelatedByFileCatLev3Id = AccountQuery::create(null, $criteria)
                    ->filterByFileCatLev3($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collAccountsRelatedByFileCatLev3IdPartial && count($collAccountsRelatedByFileCatLev3Id)) {
                      $this->initAccountsRelatedByFileCatLev3Id(false);

                      foreach ($collAccountsRelatedByFileCatLev3Id as $obj) {
                        if (false == $this->collAccountsRelatedByFileCatLev3Id->contains($obj)) {
                          $this->collAccountsRelatedByFileCatLev3Id->append($obj);
                        }
                      }

                      $this->collAccountsRelatedByFileCatLev3IdPartial = true;
                    }

                    $collAccountsRelatedByFileCatLev3Id->getInternalIterator()->rewind();

                    return $collAccountsRelatedByFileCatLev3Id;
                }

                if ($partial && $this->collAccountsRelatedByFileCatLev3Id) {
                    foreach ($this->collAccountsRelatedByFileCatLev3Id as $obj) {
                        if ($obj->isNew()) {
                            $collAccountsRelatedByFileCatLev3Id[] = $obj;
                        }
                    }
                }

                $this->collAccountsRelatedByFileCatLev3Id = $collAccountsRelatedByFileCatLev3Id;
                $this->collAccountsRelatedByFileCatLev3IdPartial = false;
            }
        }

        return $this->collAccountsRelatedByFileCatLev3Id;
    }

    /**
     * Sets a collection of AccountRelatedByFileCatLev3Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $accountsRelatedByFileCatLev3Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return FileCat The current object (for fluent API support)
     */
    public function setAccountsRelatedByFileCatLev3Id(PropelCollection $accountsRelatedByFileCatLev3Id, PropelPDO $con = null)
    {
        $accountsRelatedByFileCatLev3IdToDelete = $this->getAccountsRelatedByFileCatLev3Id(new Criteria(), $con)->diff($accountsRelatedByFileCatLev3Id);


        $this->accountsRelatedByFileCatLev3IdScheduledForDeletion = $accountsRelatedByFileCatLev3IdToDelete;

        foreach ($accountsRelatedByFileCatLev3IdToDelete as $accountRelatedByFileCatLev3IdRemoved) {
            $accountRelatedByFileCatLev3IdRemoved->setFileCatLev3(null);
        }

        $this->collAccountsRelatedByFileCatLev3Id = null;
        foreach ($accountsRelatedByFileCatLev3Id as $accountRelatedByFileCatLev3Id) {
            $this->addAccountRelatedByFileCatLev3Id($accountRelatedByFileCatLev3Id);
        }

        $this->collAccountsRelatedByFileCatLev3Id = $accountsRelatedByFileCatLev3Id;
        $this->collAccountsRelatedByFileCatLev3IdPartial = false;

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
    public function countAccountsRelatedByFileCatLev3Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collAccountsRelatedByFileCatLev3IdPartial && !$this->isNew();
        if (null === $this->collAccountsRelatedByFileCatLev3Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collAccountsRelatedByFileCatLev3Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getAccountsRelatedByFileCatLev3Id());
            }
            $query = AccountQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFileCatLev3($this)
                ->count($con);
        }

        return count($this->collAccountsRelatedByFileCatLev3Id);
    }

    /**
     * Method called to associate a Account object to this object
     * through the Account foreign key attribute.
     *
     * @param    Account $l Account
     * @return FileCat The current object (for fluent API support)
     */
    public function addAccountRelatedByFileCatLev3Id(Account $l)
    {
        if ($this->collAccountsRelatedByFileCatLev3Id === null) {
            $this->initAccountsRelatedByFileCatLev3Id();
            $this->collAccountsRelatedByFileCatLev3IdPartial = true;
        }

        if (!in_array($l, $this->collAccountsRelatedByFileCatLev3Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddAccountRelatedByFileCatLev3Id($l);

            if ($this->accountsRelatedByFileCatLev3IdScheduledForDeletion and $this->accountsRelatedByFileCatLev3IdScheduledForDeletion->contains($l)) {
                $this->accountsRelatedByFileCatLev3IdScheduledForDeletion->remove($this->accountsRelatedByFileCatLev3IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	AccountRelatedByFileCatLev3Id $accountRelatedByFileCatLev3Id The accountRelatedByFileCatLev3Id object to add.
     */
    protected function doAddAccountRelatedByFileCatLev3Id($accountRelatedByFileCatLev3Id)
    {
        $this->collAccountsRelatedByFileCatLev3Id[]= $accountRelatedByFileCatLev3Id;
        $accountRelatedByFileCatLev3Id->setFileCatLev3($this);
    }

    /**
     * @param	AccountRelatedByFileCatLev3Id $accountRelatedByFileCatLev3Id The accountRelatedByFileCatLev3Id object to remove.
     * @return FileCat The current object (for fluent API support)
     */
    public function removeAccountRelatedByFileCatLev3Id($accountRelatedByFileCatLev3Id)
    {
        if ($this->getAccountsRelatedByFileCatLev3Id()->contains($accountRelatedByFileCatLev3Id)) {
            $this->collAccountsRelatedByFileCatLev3Id->remove($this->collAccountsRelatedByFileCatLev3Id->search($accountRelatedByFileCatLev3Id));
            if (null === $this->accountsRelatedByFileCatLev3IdScheduledForDeletion) {
                $this->accountsRelatedByFileCatLev3IdScheduledForDeletion = clone $this->collAccountsRelatedByFileCatLev3Id;
                $this->accountsRelatedByFileCatLev3IdScheduledForDeletion->clear();
            }
            $this->accountsRelatedByFileCatLev3IdScheduledForDeletion[]= $accountRelatedByFileCatLev3Id;
            $accountRelatedByFileCatLev3Id->setFileCatLev3(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related AccountsRelatedByFileCatLev3Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Account[] List of Account objects
     */
    public function getAccountsRelatedByFileCatLev3IdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = AccountQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getAccountsRelatedByFileCatLev3Id($query, $con);
    }

    /**
     * Clears out the collProjects collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return FileCat The current object (for fluent API support)
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
     * If this FileCat is new, it will return
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
                    ->filterByCostFileCat($this)
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
     * @return FileCat The current object (for fluent API support)
     */
    public function setProjects(PropelCollection $projects, PropelPDO $con = null)
    {
        $projectsToDelete = $this->getProjects(new Criteria(), $con)->diff($projects);


        $this->projectsScheduledForDeletion = $projectsToDelete;

        foreach ($projectsToDelete as $projectRemoved) {
            $projectRemoved->setCostFileCat(null);
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
                ->filterByCostFileCat($this)
                ->count($con);
        }

        return count($this->collProjects);
    }

    /**
     * Method called to associate a Project object to this object
     * through the Project foreign key attribute.
     *
     * @param    Project $l Project
     * @return FileCat The current object (for fluent API support)
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
        $project->setCostFileCat($this);
    }

    /**
     * @param	Project $project The project object to remove.
     * @return FileCat The current object (for fluent API support)
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
            $project->setCostFileCat(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
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
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
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
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
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
     * Otherwise if this FileCat is new, it will return
     * an empty collection; or if this FileCat has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in FileCat.
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
        $this->symbol = null;
        $this->as_project = null;
        $this->as_income = null;
        $this->as_cost = null;
        $this->as_contractor = null;
        $this->is_locked = null;
        $this->year_id = null;
        $this->sub_file_cat_id = null;
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
            if ($this->collFileCats) {
                foreach ($this->collFileCats as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collFiles) {
                foreach ($this->collFiles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDocCats) {
                foreach ($this->collDocCats as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAccountsRelatedByFileCatLev1Id) {
                foreach ($this->collAccountsRelatedByFileCatLev1Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAccountsRelatedByFileCatLev2Id) {
                foreach ($this->collAccountsRelatedByFileCatLev2Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collAccountsRelatedByFileCatLev3Id) {
                foreach ($this->collAccountsRelatedByFileCatLev3Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProjects) {
                foreach ($this->collProjects as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aYear instanceof Persistent) {
              $this->aYear->clearAllReferences($deep);
            }
            if ($this->aSubFileCat instanceof Persistent) {
              $this->aSubFileCat->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collFileCats instanceof PropelCollection) {
            $this->collFileCats->clearIterator();
        }
        $this->collFileCats = null;
        if ($this->collFiles instanceof PropelCollection) {
            $this->collFiles->clearIterator();
        }
        $this->collFiles = null;
        if ($this->collDocCats instanceof PropelCollection) {
            $this->collDocCats->clearIterator();
        }
        $this->collDocCats = null;
        if ($this->collAccountsRelatedByFileCatLev1Id instanceof PropelCollection) {
            $this->collAccountsRelatedByFileCatLev1Id->clearIterator();
        }
        $this->collAccountsRelatedByFileCatLev1Id = null;
        if ($this->collAccountsRelatedByFileCatLev2Id instanceof PropelCollection) {
            $this->collAccountsRelatedByFileCatLev2Id->clearIterator();
        }
        $this->collAccountsRelatedByFileCatLev2Id = null;
        if ($this->collAccountsRelatedByFileCatLev3Id instanceof PropelCollection) {
            $this->collAccountsRelatedByFileCatLev3Id->clearIterator();
        }
        $this->collAccountsRelatedByFileCatLev3Id = null;
        if ($this->collProjects instanceof PropelCollection) {
            $this->collProjects->clearIterator();
        }
        $this->collProjects = null;
        $this->aYear = null;
        $this->aSubFileCat = null;
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
