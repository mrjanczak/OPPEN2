<?php

namespace AppBundle\Model\om;

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
use AppBundle\Model\Account;
use AppBundle\Model\AccountQuery;
use AppBundle\Model\CostDocIncome;
use AppBundle\Model\CostDocIncomeQuery;
use AppBundle\Model\CostIncome;
use AppBundle\Model\CostIncomeQuery;
use AppBundle\Model\File;
use AppBundle\Model\FileQuery;
use AppBundle\Model\Income;
use AppBundle\Model\IncomeDoc;
use AppBundle\Model\IncomeDocQuery;
use AppBundle\Model\IncomePeer;
use AppBundle\Model\IncomeQuery;
use AppBundle\Model\Project;
use AppBundle\Model\ProjectQuery;

abstract class BaseIncome extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'AppBundle\\Model\\IncomePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        IncomePeer
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
     * The value for the shortname field.
     * @var        string
     */
    protected $shortname;

    /**
     * The value for the value field.
     * @var        double
     */
    protected $value;

    /**
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * The value for the show field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $show;

    /**
     * The value for the project_id field.
     * @var        int
     */
    protected $project_id;

    /**
     * The value for the file_id field.
     * @var        int
     */
    protected $file_id;

    /**
     * The value for the bank_acc_id field.
     * @var        int
     */
    protected $bank_acc_id;

    /**
     * The value for the income_acc_id field.
     * @var        int
     */
    protected $income_acc_id;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        Project
     */
    protected $aProject;

    /**
     * @var        File
     */
    protected $aFile;

    /**
     * @var        Account
     */
    protected $aBankAcc;

    /**
     * @var        Account
     */
    protected $aIncomeAcc;

    /**
     * @var        PropelObjectCollection|IncomeDoc[] Collection to store aggregation of IncomeDoc objects.
     */
    protected $collIncomeDocs;
    protected $collIncomeDocsPartial;

    /**
     * @var        PropelObjectCollection|CostIncome[] Collection to store aggregation of CostIncome objects.
     */
    protected $collCostIncomes;
    protected $collCostIncomesPartial;

    /**
     * @var        PropelObjectCollection|CostDocIncome[] Collection to store aggregation of CostDocIncome objects.
     */
    protected $collCostDocIncomes;
    protected $collCostDocIncomesPartial;

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
     * The old scope value.
     * @var        int
     */
    protected $oldScope;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $incomeDocsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costIncomesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costDocIncomesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->show = true;
    }

    /**
     * Initializes internal state of BaseIncome object.
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
     * Get the [shortname] column value.
     *
     * @return string
     */
    public function getShortname()
    {

        return $this->shortname;
    }

    /**
     * Get the [value] column value.
     *
     * @return double
     */
    public function getValue()
    {

        return $this->value;
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
     * Get the [show] column value.
     *
     * @return boolean
     */
    public function getShow()
    {

        return $this->show;
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
     * Get the [file_id] column value.
     *
     * @return int
     */
    public function getFileId()
    {

        return $this->file_id;
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
     * Get the [income_acc_id] column value.
     *
     * @return int
     */
    public function getIncomeAccId()
    {

        return $this->income_acc_id;
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
     * @return Income The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = IncomePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = IncomePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [shortname] column.
     *
     * @param  string $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setShortname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shortname !== $v) {
            $this->shortname = $v;
            $this->modifiedColumns[] = IncomePeer::SHORTNAME;
        }


        return $this;
    } // setShortname()

    /**
     * Set the value of [value] column.
     *
     * @param  double $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setValue($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->value !== $v) {
            $this->value = $v;
            $this->modifiedColumns[] = IncomePeer::VALUE;
        }


        return $this;
    } // setValue()

    /**
     * Set the value of [comment] column.
     *
     * @param  string $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[] = IncomePeer::COMMENT;
        }


        return $this;
    } // setComment()

    /**
     * Sets the value of the [show] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Income The current object (for fluent API support)
     */
    public function setShow($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->show !== $v) {
            $this->show = $v;
            $this->modifiedColumns[] = IncomePeer::SHOW;
        }


        return $this;
    } // setShow()

    /**
     * Set the value of [project_id] column.
     *
     * @param  int $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setProjectId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->project_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->project_id;

            $this->project_id = $v;
            $this->modifiedColumns[] = IncomePeer::PROJECT_ID;
        }

        if ($this->aProject !== null && $this->aProject->getId() !== $v) {
            $this->aProject = null;
        }


        return $this;
    } // setProjectId()

    /**
     * Set the value of [file_id] column.
     *
     * @param  int $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setFileId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_id !== $v) {
            $this->file_id = $v;
            $this->modifiedColumns[] = IncomePeer::FILE_ID;
        }

        if ($this->aFile !== null && $this->aFile->getId() !== $v) {
            $this->aFile = null;
        }


        return $this;
    } // setFileId()

    /**
     * Set the value of [bank_acc_id] column.
     *
     * @param  int $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setBankAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bank_acc_id !== $v) {
            $this->bank_acc_id = $v;
            $this->modifiedColumns[] = IncomePeer::BANK_ACC_ID;
        }

        if ($this->aBankAcc !== null && $this->aBankAcc->getId() !== $v) {
            $this->aBankAcc = null;
        }


        return $this;
    } // setBankAccId()

    /**
     * Set the value of [income_acc_id] column.
     *
     * @param  int $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setIncomeAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->income_acc_id !== $v) {
            $this->income_acc_id = $v;
            $this->modifiedColumns[] = IncomePeer::INCOME_ACC_ID;
        }

        if ($this->aIncomeAcc !== null && $this->aIncomeAcc->getId() !== $v) {
            $this->aIncomeAcc = null;
        }


        return $this;
    } // setIncomeAccId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Income The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = IncomePeer::SORTABLE_RANK;
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
            if ($this->show !== true) {
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
            $this->shortname = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->value = ($row[$startcol + 3] !== null) ? (double) $row[$startcol + 3] : null;
            $this->comment = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->show = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->project_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->file_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->bank_acc_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->income_acc_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->sortable_rank = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 11; // 11 = IncomePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Income object", $e);
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

        if ($this->aProject !== null && $this->project_id !== $this->aProject->getId()) {
            $this->aProject = null;
        }
        if ($this->aFile !== null && $this->file_id !== $this->aFile->getId()) {
            $this->aFile = null;
        }
        if ($this->aBankAcc !== null && $this->bank_acc_id !== $this->aBankAcc->getId()) {
            $this->aBankAcc = null;
        }
        if ($this->aIncomeAcc !== null && $this->income_acc_id !== $this->aIncomeAcc->getId()) {
            $this->aIncomeAcc = null;
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
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = IncomePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aProject = null;
            $this->aFile = null;
            $this->aBankAcc = null;
            $this->aIncomeAcc = null;
            $this->collIncomeDocs = null;

            $this->collCostIncomes = null;

            $this->collCostDocIncomes = null;

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
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = IncomeQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            IncomePeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            IncomePeer::clearInstancePool();

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
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(IncomePeer::RANK_COL)) {
                    $this->setSortableRank(IncomeQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(IncomePeer::PROJECT_ID)) && !$this->isColumnModified(IncomePeer::RANK_COL)) { IncomePeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
                    $this->insertAtBottom($con);
                }

            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                IncomePeer::addInstanceToPool($this);
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

            if ($this->aProject !== null) {
                if ($this->aProject->isModified() || $this->aProject->isNew()) {
                    $affectedRows += $this->aProject->save($con);
                }
                $this->setProject($this->aProject);
            }

            if ($this->aFile !== null) {
                if ($this->aFile->isModified() || $this->aFile->isNew()) {
                    $affectedRows += $this->aFile->save($con);
                }
                $this->setFile($this->aFile);
            }

            if ($this->aBankAcc !== null) {
                if ($this->aBankAcc->isModified() || $this->aBankAcc->isNew()) {
                    $affectedRows += $this->aBankAcc->save($con);
                }
                $this->setBankAcc($this->aBankAcc);
            }

            if ($this->aIncomeAcc !== null) {
                if ($this->aIncomeAcc->isModified() || $this->aIncomeAcc->isNew()) {
                    $affectedRows += $this->aIncomeAcc->save($con);
                }
                $this->setIncomeAcc($this->aIncomeAcc);
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

            if ($this->incomeDocsScheduledForDeletion !== null) {
                if (!$this->incomeDocsScheduledForDeletion->isEmpty()) {
                    IncomeDocQuery::create()
                        ->filterByPrimaryKeys($this->incomeDocsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->incomeDocsScheduledForDeletion = null;
                }
            }

            if ($this->collIncomeDocs !== null) {
                foreach ($this->collIncomeDocs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->costIncomesScheduledForDeletion !== null) {
                if (!$this->costIncomesScheduledForDeletion->isEmpty()) {
                    CostIncomeQuery::create()
                        ->filterByPrimaryKeys($this->costIncomesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->costIncomesScheduledForDeletion = null;
                }
            }

            if ($this->collCostIncomes !== null) {
                foreach ($this->collCostIncomes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->costDocIncomesScheduledForDeletion !== null) {
                if (!$this->costDocIncomesScheduledForDeletion->isEmpty()) {
                    CostDocIncomeQuery::create()
                        ->filterByPrimaryKeys($this->costDocIncomesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->costDocIncomesScheduledForDeletion = null;
                }
            }

            if ($this->collCostDocIncomes !== null) {
                foreach ($this->collCostDocIncomes as $referrerFK) {
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

        $this->modifiedColumns[] = IncomePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . IncomePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(IncomePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(IncomePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(IncomePeer::SHORTNAME)) {
            $modifiedColumns[':p' . $index++]  = '`shortname`';
        }
        if ($this->isColumnModified(IncomePeer::VALUE)) {
            $modifiedColumns[':p' . $index++]  = '`value`';
        }
        if ($this->isColumnModified(IncomePeer::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`comment`';
        }
        if ($this->isColumnModified(IncomePeer::SHOW)) {
            $modifiedColumns[':p' . $index++]  = '`show`';
        }
        if ($this->isColumnModified(IncomePeer::PROJECT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`project_id`';
        }
        if ($this->isColumnModified(IncomePeer::FILE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_id`';
        }
        if ($this->isColumnModified(IncomePeer::BANK_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`bank_acc_id`';
        }
        if ($this->isColumnModified(IncomePeer::INCOME_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`income_acc_id`';
        }
        if ($this->isColumnModified(IncomePeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `income` (%s) VALUES (%s)',
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
                    case '`shortname`':
                        $stmt->bindValue($identifier, $this->shortname, PDO::PARAM_STR);
                        break;
                    case '`value`':
                        $stmt->bindValue($identifier, $this->value, PDO::PARAM_STR);
                        break;
                    case '`comment`':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                    case '`show`':
                        $stmt->bindValue($identifier, (int) $this->show, PDO::PARAM_INT);
                        break;
                    case '`project_id`':
                        $stmt->bindValue($identifier, $this->project_id, PDO::PARAM_INT);
                        break;
                    case '`file_id`':
                        $stmt->bindValue($identifier, $this->file_id, PDO::PARAM_INT);
                        break;
                    case '`bank_acc_id`':
                        $stmt->bindValue($identifier, $this->bank_acc_id, PDO::PARAM_INT);
                        break;
                    case '`income_acc_id`':
                        $stmt->bindValue($identifier, $this->income_acc_id, PDO::PARAM_INT);
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

            if ($this->aProject !== null) {
                if (!$this->aProject->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aProject->getValidationFailures());
                }
            }

            if ($this->aFile !== null) {
                if (!$this->aFile->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFile->getValidationFailures());
                }
            }

            if ($this->aBankAcc !== null) {
                if (!$this->aBankAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBankAcc->getValidationFailures());
                }
            }

            if ($this->aIncomeAcc !== null) {
                if (!$this->aIncomeAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aIncomeAcc->getValidationFailures());
                }
            }


            if (($retval = IncomePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collIncomeDocs !== null) {
                    foreach ($this->collIncomeDocs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCostIncomes !== null) {
                    foreach ($this->collCostIncomes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCostDocIncomes !== null) {
                    foreach ($this->collCostDocIncomes as $referrerFK) {
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
        $pos = IncomePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getShortname();
                break;
            case 3:
                return $this->getValue();
                break;
            case 4:
                return $this->getComment();
                break;
            case 5:
                return $this->getShow();
                break;
            case 6:
                return $this->getProjectId();
                break;
            case 7:
                return $this->getFileId();
                break;
            case 8:
                return $this->getBankAccId();
                break;
            case 9:
                return $this->getIncomeAccId();
                break;
            case 10:
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
        if (isset($alreadyDumpedObjects['Income'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Income'][$this->getPrimaryKey()] = true;
        $keys = IncomePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getShortname(),
            $keys[3] => $this->getValue(),
            $keys[4] => $this->getComment(),
            $keys[5] => $this->getShow(),
            $keys[6] => $this->getProjectId(),
            $keys[7] => $this->getFileId(),
            $keys[8] => $this->getBankAccId(),
            $keys[9] => $this->getIncomeAccId(),
            $keys[10] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aProject) {
                $result['Project'] = $this->aProject->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFile) {
                $result['File'] = $this->aFile->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aBankAcc) {
                $result['BankAcc'] = $this->aBankAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aIncomeAcc) {
                $result['IncomeAcc'] = $this->aIncomeAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collIncomeDocs) {
                $result['IncomeDocs'] = $this->collIncomeDocs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCostIncomes) {
                $result['CostIncomes'] = $this->collCostIncomes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCostDocIncomes) {
                $result['CostDocIncomes'] = $this->collCostDocIncomes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = IncomePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setShortname($value);
                break;
            case 3:
                $this->setValue($value);
                break;
            case 4:
                $this->setComment($value);
                break;
            case 5:
                $this->setShow($value);
                break;
            case 6:
                $this->setProjectId($value);
                break;
            case 7:
                $this->setFileId($value);
                break;
            case 8:
                $this->setBankAccId($value);
                break;
            case 9:
                $this->setIncomeAccId($value);
                break;
            case 10:
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
        $keys = IncomePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setShortname($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setValue($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setComment($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setShow($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setProjectId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setFileId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setBankAccId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setIncomeAccId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setSortableRank($arr[$keys[10]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(IncomePeer::DATABASE_NAME);

        if ($this->isColumnModified(IncomePeer::ID)) $criteria->add(IncomePeer::ID, $this->id);
        if ($this->isColumnModified(IncomePeer::NAME)) $criteria->add(IncomePeer::NAME, $this->name);
        if ($this->isColumnModified(IncomePeer::SHORTNAME)) $criteria->add(IncomePeer::SHORTNAME, $this->shortname);
        if ($this->isColumnModified(IncomePeer::VALUE)) $criteria->add(IncomePeer::VALUE, $this->value);
        if ($this->isColumnModified(IncomePeer::COMMENT)) $criteria->add(IncomePeer::COMMENT, $this->comment);
        if ($this->isColumnModified(IncomePeer::SHOW)) $criteria->add(IncomePeer::SHOW, $this->show);
        if ($this->isColumnModified(IncomePeer::PROJECT_ID)) $criteria->add(IncomePeer::PROJECT_ID, $this->project_id);
        if ($this->isColumnModified(IncomePeer::FILE_ID)) $criteria->add(IncomePeer::FILE_ID, $this->file_id);
        if ($this->isColumnModified(IncomePeer::BANK_ACC_ID)) $criteria->add(IncomePeer::BANK_ACC_ID, $this->bank_acc_id);
        if ($this->isColumnModified(IncomePeer::INCOME_ACC_ID)) $criteria->add(IncomePeer::INCOME_ACC_ID, $this->income_acc_id);
        if ($this->isColumnModified(IncomePeer::SORTABLE_RANK)) $criteria->add(IncomePeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(IncomePeer::DATABASE_NAME);
        $criteria->add(IncomePeer::ID, $this->id);

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
     * @param object $copyObj An object of Income (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setShortname($this->getShortname());
        $copyObj->setValue($this->getValue());
        $copyObj->setComment($this->getComment());
        $copyObj->setShow($this->getShow());
        $copyObj->setProjectId($this->getProjectId());
        $copyObj->setFileId($this->getFileId());
        $copyObj->setBankAccId($this->getBankAccId());
        $copyObj->setIncomeAccId($this->getIncomeAccId());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getIncomeDocs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIncomeDoc($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCostIncomes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCostIncome($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCostDocIncomes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCostDocIncome($relObj->copy($deepCopy));
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
     * @return Income Clone of current object.
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
     * @return IncomePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new IncomePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Project object.
     *
     * @param                  Project $v
     * @return Income The current object (for fluent API support)
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
            $v->addIncome($this);
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
                $this->aProject->addIncomes($this);
             */
        }

        return $this->aProject;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return Income The current object (for fluent API support)
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
            $v->addIncome($this);
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
                $this->aFile->addIncomes($this);
             */
        }

        return $this->aFile;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return Income The current object (for fluent API support)
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
            $v->addIncomeRelatedByBankAccId($this);
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
                $this->aBankAcc->addIncomesRelatedByBankAccId($this);
             */
        }

        return $this->aBankAcc;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return Income The current object (for fluent API support)
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
            $v->addIncomeRelatedByIncomeAccId($this);
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
                $this->aIncomeAcc->addIncomesRelatedByIncomeAccId($this);
             */
        }

        return $this->aIncomeAcc;
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
        if ('IncomeDoc' == $relationName) {
            $this->initIncomeDocs();
        }
        if ('CostIncome' == $relationName) {
            $this->initCostIncomes();
        }
        if ('CostDocIncome' == $relationName) {
            $this->initCostDocIncomes();
        }
    }

    /**
     * Clears out the collIncomeDocs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Income The current object (for fluent API support)
     * @see        addIncomeDocs()
     */
    public function clearIncomeDocs()
    {
        $this->collIncomeDocs = null; // important to set this to null since that means it is uninitialized
        $this->collIncomeDocsPartial = null;

        return $this;
    }

    /**
     * reset is the collIncomeDocs collection loaded partially
     *
     * @return void
     */
    public function resetPartialIncomeDocs($v = true)
    {
        $this->collIncomeDocsPartial = $v;
    }

    /**
     * Initializes the collIncomeDocs collection.
     *
     * By default this just sets the collIncomeDocs collection to an empty array (like clearcollIncomeDocs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIncomeDocs($overrideExisting = true)
    {
        if (null !== $this->collIncomeDocs && !$overrideExisting) {
            return;
        }
        $this->collIncomeDocs = new PropelObjectCollection();
        $this->collIncomeDocs->setModel('IncomeDoc');
    }

    /**
     * Gets an array of IncomeDoc objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Income is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|IncomeDoc[] List of IncomeDoc objects
     * @throws PropelException
     */
    public function getIncomeDocs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIncomeDocsPartial && !$this->isNew();
        if (null === $this->collIncomeDocs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIncomeDocs) {
                // return empty collection
                $this->initIncomeDocs();
            } else {
                $collIncomeDocs = IncomeDocQuery::create(null, $criteria)
                    ->filterByIncome($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collIncomeDocsPartial && count($collIncomeDocs)) {
                      $this->initIncomeDocs(false);

                      foreach ($collIncomeDocs as $obj) {
                        if (false == $this->collIncomeDocs->contains($obj)) {
                          $this->collIncomeDocs->append($obj);
                        }
                      }

                      $this->collIncomeDocsPartial = true;
                    }

                    $collIncomeDocs->getInternalIterator()->rewind();

                    return $collIncomeDocs;
                }

                if ($partial && $this->collIncomeDocs) {
                    foreach ($this->collIncomeDocs as $obj) {
                        if ($obj->isNew()) {
                            $collIncomeDocs[] = $obj;
                        }
                    }
                }

                $this->collIncomeDocs = $collIncomeDocs;
                $this->collIncomeDocsPartial = false;
            }
        }

        return $this->collIncomeDocs;
    }

    /**
     * Sets a collection of IncomeDoc objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $incomeDocs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Income The current object (for fluent API support)
     */
    public function setIncomeDocs(PropelCollection $incomeDocs, PropelPDO $con = null)
    {
        $incomeDocsToDelete = $this->getIncomeDocs(new Criteria(), $con)->diff($incomeDocs);


        $this->incomeDocsScheduledForDeletion = $incomeDocsToDelete;

        foreach ($incomeDocsToDelete as $incomeDocRemoved) {
            $incomeDocRemoved->setIncome(null);
        }

        $this->collIncomeDocs = null;
        foreach ($incomeDocs as $incomeDoc) {
            $this->addIncomeDoc($incomeDoc);
        }

        $this->collIncomeDocs = $incomeDocs;
        $this->collIncomeDocsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related IncomeDoc objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related IncomeDoc objects.
     * @throws PropelException
     */
    public function countIncomeDocs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collIncomeDocsPartial && !$this->isNew();
        if (null === $this->collIncomeDocs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIncomeDocs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIncomeDocs());
            }
            $query = IncomeDocQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByIncome($this)
                ->count($con);
        }

        return count($this->collIncomeDocs);
    }

    /**
     * Method called to associate a IncomeDoc object to this object
     * through the IncomeDoc foreign key attribute.
     *
     * @param    IncomeDoc $l IncomeDoc
     * @return Income The current object (for fluent API support)
     */
    public function addIncomeDoc(IncomeDoc $l)
    {
        if ($this->collIncomeDocs === null) {
            $this->initIncomeDocs();
            $this->collIncomeDocsPartial = true;
        }

        if (!in_array($l, $this->collIncomeDocs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIncomeDoc($l);

            if ($this->incomeDocsScheduledForDeletion and $this->incomeDocsScheduledForDeletion->contains($l)) {
                $this->incomeDocsScheduledForDeletion->remove($this->incomeDocsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	IncomeDoc $incomeDoc The incomeDoc object to add.
     */
    protected function doAddIncomeDoc($incomeDoc)
    {
        $this->collIncomeDocs[]= $incomeDoc;
        $incomeDoc->setIncome($this);
    }

    /**
     * @param	IncomeDoc $incomeDoc The incomeDoc object to remove.
     * @return Income The current object (for fluent API support)
     */
    public function removeIncomeDoc($incomeDoc)
    {
        if ($this->getIncomeDocs()->contains($incomeDoc)) {
            $this->collIncomeDocs->remove($this->collIncomeDocs->search($incomeDoc));
            if (null === $this->incomeDocsScheduledForDeletion) {
                $this->incomeDocsScheduledForDeletion = clone $this->collIncomeDocs;
                $this->incomeDocsScheduledForDeletion->clear();
            }
            $this->incomeDocsScheduledForDeletion[]= $incomeDoc;
            $incomeDoc->setIncome(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Income is new, it will return
     * an empty collection; or if this Income has previously
     * been saved, it will retrieve related IncomeDocs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Income.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|IncomeDoc[] List of IncomeDoc objects
     */
    public function getIncomeDocsJoinDoc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeDocQuery::create(null, $criteria);
        $query->joinWith('Doc', $join_behavior);

        return $this->getIncomeDocs($query, $con);
    }

    /**
     * Clears out the collCostIncomes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Income The current object (for fluent API support)
     * @see        addCostIncomes()
     */
    public function clearCostIncomes()
    {
        $this->collCostIncomes = null; // important to set this to null since that means it is uninitialized
        $this->collCostIncomesPartial = null;

        return $this;
    }

    /**
     * reset is the collCostIncomes collection loaded partially
     *
     * @return void
     */
    public function resetPartialCostIncomes($v = true)
    {
        $this->collCostIncomesPartial = $v;
    }

    /**
     * Initializes the collCostIncomes collection.
     *
     * By default this just sets the collCostIncomes collection to an empty array (like clearcollCostIncomes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCostIncomes($overrideExisting = true)
    {
        if (null !== $this->collCostIncomes && !$overrideExisting) {
            return;
        }
        $this->collCostIncomes = new PropelObjectCollection();
        $this->collCostIncomes->setModel('CostIncome');
    }

    /**
     * Gets an array of CostIncome objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Income is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CostIncome[] List of CostIncome objects
     * @throws PropelException
     */
    public function getCostIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostIncomesPartial && !$this->isNew();
        if (null === $this->collCostIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostIncomes) {
                // return empty collection
                $this->initCostIncomes();
            } else {
                $collCostIncomes = CostIncomeQuery::create(null, $criteria)
                    ->filterByIncome($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostIncomesPartial && count($collCostIncomes)) {
                      $this->initCostIncomes(false);

                      foreach ($collCostIncomes as $obj) {
                        if (false == $this->collCostIncomes->contains($obj)) {
                          $this->collCostIncomes->append($obj);
                        }
                      }

                      $this->collCostIncomesPartial = true;
                    }

                    $collCostIncomes->getInternalIterator()->rewind();

                    return $collCostIncomes;
                }

                if ($partial && $this->collCostIncomes) {
                    foreach ($this->collCostIncomes as $obj) {
                        if ($obj->isNew()) {
                            $collCostIncomes[] = $obj;
                        }
                    }
                }

                $this->collCostIncomes = $collCostIncomes;
                $this->collCostIncomesPartial = false;
            }
        }

        return $this->collCostIncomes;
    }

    /**
     * Sets a collection of CostIncome objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costIncomes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Income The current object (for fluent API support)
     */
    public function setCostIncomes(PropelCollection $costIncomes, PropelPDO $con = null)
    {
        $costIncomesToDelete = $this->getCostIncomes(new Criteria(), $con)->diff($costIncomes);


        $this->costIncomesScheduledForDeletion = $costIncomesToDelete;

        foreach ($costIncomesToDelete as $costIncomeRemoved) {
            $costIncomeRemoved->setIncome(null);
        }

        $this->collCostIncomes = null;
        foreach ($costIncomes as $costIncome) {
            $this->addCostIncome($costIncome);
        }

        $this->collCostIncomes = $costIncomes;
        $this->collCostIncomesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CostIncome objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CostIncome objects.
     * @throws PropelException
     */
    public function countCostIncomes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostIncomesPartial && !$this->isNew();
        if (null === $this->collCostIncomes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCostIncomes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCostIncomes());
            }
            $query = CostIncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByIncome($this)
                ->count($con);
        }

        return count($this->collCostIncomes);
    }

    /**
     * Method called to associate a CostIncome object to this object
     * through the CostIncome foreign key attribute.
     *
     * @param    CostIncome $l CostIncome
     * @return Income The current object (for fluent API support)
     */
    public function addCostIncome(CostIncome $l)
    {
        if ($this->collCostIncomes === null) {
            $this->initCostIncomes();
            $this->collCostIncomesPartial = true;
        }

        if (!in_array($l, $this->collCostIncomes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCostIncome($l);

            if ($this->costIncomesScheduledForDeletion and $this->costIncomesScheduledForDeletion->contains($l)) {
                $this->costIncomesScheduledForDeletion->remove($this->costIncomesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CostIncome $costIncome The costIncome object to add.
     */
    protected function doAddCostIncome($costIncome)
    {
        $this->collCostIncomes[]= $costIncome;
        $costIncome->setIncome($this);
    }

    /**
     * @param	CostIncome $costIncome The costIncome object to remove.
     * @return Income The current object (for fluent API support)
     */
    public function removeCostIncome($costIncome)
    {
        if ($this->getCostIncomes()->contains($costIncome)) {
            $this->collCostIncomes->remove($this->collCostIncomes->search($costIncome));
            if (null === $this->costIncomesScheduledForDeletion) {
                $this->costIncomesScheduledForDeletion = clone $this->collCostIncomes;
                $this->costIncomesScheduledForDeletion->clear();
            }
            $this->costIncomesScheduledForDeletion[]= $costIncome;
            $costIncome->setIncome(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Income is new, it will return
     * an empty collection; or if this Income has previously
     * been saved, it will retrieve related CostIncomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Income.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CostIncome[] List of CostIncome objects
     */
    public function getCostIncomesJoinCost($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostIncomeQuery::create(null, $criteria);
        $query->joinWith('Cost', $join_behavior);

        return $this->getCostIncomes($query, $con);
    }

    /**
     * Clears out the collCostDocIncomes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Income The current object (for fluent API support)
     * @see        addCostDocIncomes()
     */
    public function clearCostDocIncomes()
    {
        $this->collCostDocIncomes = null; // important to set this to null since that means it is uninitialized
        $this->collCostDocIncomesPartial = null;

        return $this;
    }

    /**
     * reset is the collCostDocIncomes collection loaded partially
     *
     * @return void
     */
    public function resetPartialCostDocIncomes($v = true)
    {
        $this->collCostDocIncomesPartial = $v;
    }

    /**
     * Initializes the collCostDocIncomes collection.
     *
     * By default this just sets the collCostDocIncomes collection to an empty array (like clearcollCostDocIncomes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCostDocIncomes($overrideExisting = true)
    {
        if (null !== $this->collCostDocIncomes && !$overrideExisting) {
            return;
        }
        $this->collCostDocIncomes = new PropelObjectCollection();
        $this->collCostDocIncomes->setModel('CostDocIncome');
    }

    /**
     * Gets an array of CostDocIncome objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Income is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CostDocIncome[] List of CostDocIncome objects
     * @throws PropelException
     */
    public function getCostDocIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostDocIncomesPartial && !$this->isNew();
        if (null === $this->collCostDocIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostDocIncomes) {
                // return empty collection
                $this->initCostDocIncomes();
            } else {
                $collCostDocIncomes = CostDocIncomeQuery::create(null, $criteria)
                    ->filterByIncome($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostDocIncomesPartial && count($collCostDocIncomes)) {
                      $this->initCostDocIncomes(false);

                      foreach ($collCostDocIncomes as $obj) {
                        if (false == $this->collCostDocIncomes->contains($obj)) {
                          $this->collCostDocIncomes->append($obj);
                        }
                      }

                      $this->collCostDocIncomesPartial = true;
                    }

                    $collCostDocIncomes->getInternalIterator()->rewind();

                    return $collCostDocIncomes;
                }

                if ($partial && $this->collCostDocIncomes) {
                    foreach ($this->collCostDocIncomes as $obj) {
                        if ($obj->isNew()) {
                            $collCostDocIncomes[] = $obj;
                        }
                    }
                }

                $this->collCostDocIncomes = $collCostDocIncomes;
                $this->collCostDocIncomesPartial = false;
            }
        }

        return $this->collCostDocIncomes;
    }

    /**
     * Sets a collection of CostDocIncome objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costDocIncomes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Income The current object (for fluent API support)
     */
    public function setCostDocIncomes(PropelCollection $costDocIncomes, PropelPDO $con = null)
    {
        $costDocIncomesToDelete = $this->getCostDocIncomes(new Criteria(), $con)->diff($costDocIncomes);


        $this->costDocIncomesScheduledForDeletion = $costDocIncomesToDelete;

        foreach ($costDocIncomesToDelete as $costDocIncomeRemoved) {
            $costDocIncomeRemoved->setIncome(null);
        }

        $this->collCostDocIncomes = null;
        foreach ($costDocIncomes as $costDocIncome) {
            $this->addCostDocIncome($costDocIncome);
        }

        $this->collCostDocIncomes = $costDocIncomes;
        $this->collCostDocIncomesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CostDocIncome objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CostDocIncome objects.
     * @throws PropelException
     */
    public function countCostDocIncomes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostDocIncomesPartial && !$this->isNew();
        if (null === $this->collCostDocIncomes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCostDocIncomes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCostDocIncomes());
            }
            $query = CostDocIncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByIncome($this)
                ->count($con);
        }

        return count($this->collCostDocIncomes);
    }

    /**
     * Method called to associate a CostDocIncome object to this object
     * through the CostDocIncome foreign key attribute.
     *
     * @param    CostDocIncome $l CostDocIncome
     * @return Income The current object (for fluent API support)
     */
    public function addCostDocIncome(CostDocIncome $l)
    {
        if ($this->collCostDocIncomes === null) {
            $this->initCostDocIncomes();
            $this->collCostDocIncomesPartial = true;
        }

        if (!in_array($l, $this->collCostDocIncomes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCostDocIncome($l);

            if ($this->costDocIncomesScheduledForDeletion and $this->costDocIncomesScheduledForDeletion->contains($l)) {
                $this->costDocIncomesScheduledForDeletion->remove($this->costDocIncomesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CostDocIncome $costDocIncome The costDocIncome object to add.
     */
    protected function doAddCostDocIncome($costDocIncome)
    {
        $this->collCostDocIncomes[]= $costDocIncome;
        $costDocIncome->setIncome($this);
    }

    /**
     * @param	CostDocIncome $costDocIncome The costDocIncome object to remove.
     * @return Income The current object (for fluent API support)
     */
    public function removeCostDocIncome($costDocIncome)
    {
        if ($this->getCostDocIncomes()->contains($costDocIncome)) {
            $this->collCostDocIncomes->remove($this->collCostDocIncomes->search($costDocIncome));
            if (null === $this->costDocIncomesScheduledForDeletion) {
                $this->costDocIncomesScheduledForDeletion = clone $this->collCostDocIncomes;
                $this->costDocIncomesScheduledForDeletion->clear();
            }
            $this->costDocIncomesScheduledForDeletion[]= $costDocIncome;
            $costDocIncome->setIncome(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Income is new, it will return
     * an empty collection; or if this Income has previously
     * been saved, it will retrieve related CostDocIncomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Income.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CostDocIncome[] List of CostDocIncome objects
     */
    public function getCostDocIncomesJoinCostDoc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostDocIncomeQuery::create(null, $criteria);
        $query->joinWith('CostDoc', $join_behavior);

        return $this->getCostDocIncomes($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->shortname = null;
        $this->value = null;
        $this->comment = null;
        $this->show = null;
        $this->project_id = null;
        $this->file_id = null;
        $this->bank_acc_id = null;
        $this->income_acc_id = null;
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
            if ($this->collIncomeDocs) {
                foreach ($this->collIncomeDocs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCostIncomes) {
                foreach ($this->collCostIncomes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCostDocIncomes) {
                foreach ($this->collCostDocIncomes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aProject instanceof Persistent) {
              $this->aProject->clearAllReferences($deep);
            }
            if ($this->aFile instanceof Persistent) {
              $this->aFile->clearAllReferences($deep);
            }
            if ($this->aBankAcc instanceof Persistent) {
              $this->aBankAcc->clearAllReferences($deep);
            }
            if ($this->aIncomeAcc instanceof Persistent) {
              $this->aIncomeAcc->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collIncomeDocs instanceof PropelCollection) {
            $this->collIncomeDocs->clearIterator();
        }
        $this->collIncomeDocs = null;
        if ($this->collCostIncomes instanceof PropelCollection) {
            $this->collCostIncomes->clearIterator();
        }
        $this->collCostIncomes = null;
        if ($this->collCostDocIncomes instanceof PropelCollection) {
            $this->collCostDocIncomes->clearIterator();
        }
        $this->collCostDocIncomes = null;
        $this->aProject = null;
        $this->aFile = null;
        $this->aBankAcc = null;
        $this->aIncomeAcc = null;
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
     * @return    Income
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }


    /**
     * Wrap the getter for scope value
     *
     * @param boolean $returnNulls If true and all scope values are null, this will return null instead of a array full with nulls
     *
     * @return    mixed A array or a native type
     */
    public function getScopeValue($returnNulls = true)
    {


        return $this->getProjectId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    Income
     */
    public function setScopeValue($v)
    {


        return $this->setProjectId($v);

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
        return $this->getSortableRank() == IncomeQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Income
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = IncomeQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Income
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = IncomeQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() - 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Income the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = IncomeQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, $this->getScopeValue())
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
     * @return    Income the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(IncomeQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Income the current object
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
     * @return    Income the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > IncomeQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
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
            IncomePeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

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
     * @param     Income $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Income the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldScope = $this->getScopeValue();
            $newScope = $object->getScopeValue();
            if ($oldScope != $newScope) {
                $this->setScopeValue($newScope);
                $object->setScopeValue($oldScope);
            }
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
     * @return    Income the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
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
     * @return    Income the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
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
     * @return    Income the current object
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
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = IncomeQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list (moves it to the null scope).
     * The modifications are not persisted until the object is saved.
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Income the current object
     */
    public function removeFromList(PropelPDO $con = null)
    {
        // check if object is already removed
        if ($this->getScopeValue() === null) {
            throw new PropelException('Object is already removed (has null scope)');
        }

        // move the object to the end of null scope
        $this->setScopeValue(null);
    //    $this->insertAtBottom($con);

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
