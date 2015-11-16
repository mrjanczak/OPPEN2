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
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\DocCatPeer;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\YearQuery;

abstract class BaseDocCat extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\DocCatPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DocCatPeer
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
     * The value for the doc_no_tmp field.
     * @var        string
     */
    protected $doc_no_tmp;

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
     * The value for the as_bill field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_bill;

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
     * The value for the file_cat_id field.
     * @var        int
     */
    protected $file_cat_id;

    /**
     * The value for the commitment_acc_id field.
     * @var        int
     */
    protected $commitment_acc_id;

    /**
     * The value for the tax_commitment_acc_id field.
     * @var        int
     */
    protected $tax_commitment_acc_id;

    /**
     * @var        Year
     */
    protected $aYear;

    /**
     * @var        FileCat
     */
    protected $aFileCat;

    /**
     * @var        Account
     */
    protected $aCommitmentAcc;

    /**
     * @var        Account
     */
    protected $aTaxCommitmentAcc;

    /**
     * @var        PropelObjectCollection|Doc[] Collection to store aggregation of Doc objects.
     */
    protected $collDocs;
    protected $collDocsPartial;

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
    protected $docsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->as_income = false;
        $this->as_cost = false;
        $this->as_bill = false;
        $this->is_locked = false;
    }

    /**
     * Initializes internal state of BaseDocCat object.
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
     * Get the [doc_no_tmp] column value.
     *
     * @return string
     */
    public function getDocNoTmp()
    {

        return $this->doc_no_tmp;
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
     * Get the [as_bill] column value.
     *
     * @return boolean
     */
    public function getAsBill()
    {

        return $this->as_bill;
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
     * Get the [file_cat_id] column value.
     *
     * @return int
     */
    public function getFileCatId()
    {

        return $this->file_cat_id;
    }

    /**
     * Get the [commitment_acc_id] column value.
     *
     * @return int
     */
    public function getCommitmentAccId()
    {

        return $this->commitment_acc_id;
    }

    /**
     * Get the [tax_commitment_acc_id] column value.
     *
     * @return int
     */
    public function getTaxCommitmentAccId()
    {

        return $this->tax_commitment_acc_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DocCatPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = DocCatPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [symbol] column.
     *
     * @param  string $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setSymbol($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->symbol !== $v) {
            $this->symbol = $v;
            $this->modifiedColumns[] = DocCatPeer::SYMBOL;
        }


        return $this;
    } // setSymbol()

    /**
     * Set the value of [doc_no_tmp] column.
     *
     * @param  string $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setDocNoTmp($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->doc_no_tmp !== $v) {
            $this->doc_no_tmp = $v;
            $this->modifiedColumns[] = DocCatPeer::DOC_NO_TMP;
        }


        return $this;
    } // setDocNoTmp()

    /**
     * Sets the value of the [as_income] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return DocCat The current object (for fluent API support)
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
            $this->modifiedColumns[] = DocCatPeer::AS_INCOME;
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
     * @return DocCat The current object (for fluent API support)
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
            $this->modifiedColumns[] = DocCatPeer::AS_COST;
        }


        return $this;
    } // setAsCost()

    /**
     * Sets the value of the [as_bill] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setAsBill($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_bill !== $v) {
            $this->as_bill = $v;
            $this->modifiedColumns[] = DocCatPeer::AS_BILL;
        }


        return $this;
    } // setAsBill()

    /**
     * Sets the value of the [is_locked] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return DocCat The current object (for fluent API support)
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
            $this->modifiedColumns[] = DocCatPeer::IS_LOCKED;
        }


        return $this;
    } // setIsLocked()

    /**
     * Set the value of [year_id] column.
     *
     * @param  int $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year_id !== $v) {
            $this->year_id = $v;
            $this->modifiedColumns[] = DocCatPeer::YEAR_ID;
        }

        if ($this->aYear !== null && $this->aYear->getId() !== $v) {
            $this->aYear = null;
        }


        return $this;
    } // setYearId()

    /**
     * Set the value of [file_cat_id] column.
     *
     * @param  int $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setFileCatId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_cat_id !== $v) {
            $this->file_cat_id = $v;
            $this->modifiedColumns[] = DocCatPeer::FILE_CAT_ID;
        }

        if ($this->aFileCat !== null && $this->aFileCat->getId() !== $v) {
            $this->aFileCat = null;
        }


        return $this;
    } // setFileCatId()

    /**
     * Set the value of [commitment_acc_id] column.
     *
     * @param  int $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setCommitmentAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->commitment_acc_id !== $v) {
            $this->commitment_acc_id = $v;
            $this->modifiedColumns[] = DocCatPeer::COMMITMENT_ACC_ID;
        }

        if ($this->aCommitmentAcc !== null && $this->aCommitmentAcc->getId() !== $v) {
            $this->aCommitmentAcc = null;
        }


        return $this;
    } // setCommitmentAccId()

    /**
     * Set the value of [tax_commitment_acc_id] column.
     *
     * @param  int $v new value
     * @return DocCat The current object (for fluent API support)
     */
    public function setTaxCommitmentAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tax_commitment_acc_id !== $v) {
            $this->tax_commitment_acc_id = $v;
            $this->modifiedColumns[] = DocCatPeer::TAX_COMMITMENT_ACC_ID;
        }

        if ($this->aTaxCommitmentAcc !== null && $this->aTaxCommitmentAcc->getId() !== $v) {
            $this->aTaxCommitmentAcc = null;
        }


        return $this;
    } // setTaxCommitmentAccId()

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
            if ($this->as_income !== false) {
                return false;
            }

            if ($this->as_cost !== false) {
                return false;
            }

            if ($this->as_bill !== false) {
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
            $this->doc_no_tmp = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->as_income = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->as_cost = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->as_bill = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->is_locked = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->year_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->file_cat_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->commitment_acc_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->tax_commitment_acc_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 12; // 12 = DocCatPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating DocCat object", $e);
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
        if ($this->aFileCat !== null && $this->file_cat_id !== $this->aFileCat->getId()) {
            $this->aFileCat = null;
        }
        if ($this->aCommitmentAcc !== null && $this->commitment_acc_id !== $this->aCommitmentAcc->getId()) {
            $this->aCommitmentAcc = null;
        }
        if ($this->aTaxCommitmentAcc !== null && $this->tax_commitment_acc_id !== $this->aTaxCommitmentAcc->getId()) {
            $this->aTaxCommitmentAcc = null;
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
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DocCatPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aYear = null;
            $this->aFileCat = null;
            $this->aCommitmentAcc = null;
            $this->aTaxCommitmentAcc = null;
            $this->collDocs = null;

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
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DocCatQuery::create()
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
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                DocCatPeer::addInstanceToPool($this);
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

            if ($this->aFileCat !== null) {
                if ($this->aFileCat->isModified() || $this->aFileCat->isNew()) {
                    $affectedRows += $this->aFileCat->save($con);
                }
                $this->setFileCat($this->aFileCat);
            }

            if ($this->aCommitmentAcc !== null) {
                if ($this->aCommitmentAcc->isModified() || $this->aCommitmentAcc->isNew()) {
                    $affectedRows += $this->aCommitmentAcc->save($con);
                }
                $this->setCommitmentAcc($this->aCommitmentAcc);
            }

            if ($this->aTaxCommitmentAcc !== null) {
                if ($this->aTaxCommitmentAcc->isModified() || $this->aTaxCommitmentAcc->isNew()) {
                    $affectedRows += $this->aTaxCommitmentAcc->save($con);
                }
                $this->setTaxCommitmentAcc($this->aTaxCommitmentAcc);
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

        $this->modifiedColumns[] = DocCatPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DocCatPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DocCatPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DocCatPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(DocCatPeer::SYMBOL)) {
            $modifiedColumns[':p' . $index++]  = '`symbol`';
        }
        if ($this->isColumnModified(DocCatPeer::DOC_NO_TMP)) {
            $modifiedColumns[':p' . $index++]  = '`doc_no_tmp`';
        }
        if ($this->isColumnModified(DocCatPeer::AS_INCOME)) {
            $modifiedColumns[':p' . $index++]  = '`as_income`';
        }
        if ($this->isColumnModified(DocCatPeer::AS_COST)) {
            $modifiedColumns[':p' . $index++]  = '`as_cost`';
        }
        if ($this->isColumnModified(DocCatPeer::AS_BILL)) {
            $modifiedColumns[':p' . $index++]  = '`as_bill`';
        }
        if ($this->isColumnModified(DocCatPeer::IS_LOCKED)) {
            $modifiedColumns[':p' . $index++]  = '`is_locked`';
        }
        if ($this->isColumnModified(DocCatPeer::YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`year_id`';
        }
        if ($this->isColumnModified(DocCatPeer::FILE_CAT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_cat_id`';
        }
        if ($this->isColumnModified(DocCatPeer::COMMITMENT_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`commitment_acc_id`';
        }
        if ($this->isColumnModified(DocCatPeer::TAX_COMMITMENT_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`tax_commitment_acc_id`';
        }

        $sql = sprintf(
            'INSERT INTO `doc_cat` (%s) VALUES (%s)',
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
                    case '`doc_no_tmp`':
                        $stmt->bindValue($identifier, $this->doc_no_tmp, PDO::PARAM_STR);
                        break;
                    case '`as_income`':
                        $stmt->bindValue($identifier, (int) $this->as_income, PDO::PARAM_INT);
                        break;
                    case '`as_cost`':
                        $stmt->bindValue($identifier, (int) $this->as_cost, PDO::PARAM_INT);
                        break;
                    case '`as_bill`':
                        $stmt->bindValue($identifier, (int) $this->as_bill, PDO::PARAM_INT);
                        break;
                    case '`is_locked`':
                        $stmt->bindValue($identifier, (int) $this->is_locked, PDO::PARAM_INT);
                        break;
                    case '`year_id`':
                        $stmt->bindValue($identifier, $this->year_id, PDO::PARAM_INT);
                        break;
                    case '`file_cat_id`':
                        $stmt->bindValue($identifier, $this->file_cat_id, PDO::PARAM_INT);
                        break;
                    case '`commitment_acc_id`':
                        $stmt->bindValue($identifier, $this->commitment_acc_id, PDO::PARAM_INT);
                        break;
                    case '`tax_commitment_acc_id`':
                        $stmt->bindValue($identifier, $this->tax_commitment_acc_id, PDO::PARAM_INT);
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

            if ($this->aFileCat !== null) {
                if (!$this->aFileCat->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileCat->getValidationFailures());
                }
            }

            if ($this->aCommitmentAcc !== null) {
                if (!$this->aCommitmentAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCommitmentAcc->getValidationFailures());
                }
            }

            if ($this->aTaxCommitmentAcc !== null) {
                if (!$this->aTaxCommitmentAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTaxCommitmentAcc->getValidationFailures());
                }
            }


            if (($retval = DocCatPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collDocs !== null) {
                    foreach ($this->collDocs as $referrerFK) {
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
        $pos = DocCatPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getDocNoTmp();
                break;
            case 4:
                return $this->getAsIncome();
                break;
            case 5:
                return $this->getAsCost();
                break;
            case 6:
                return $this->getAsBill();
                break;
            case 7:
                return $this->getIsLocked();
                break;
            case 8:
                return $this->getYearId();
                break;
            case 9:
                return $this->getFileCatId();
                break;
            case 10:
                return $this->getCommitmentAccId();
                break;
            case 11:
                return $this->getTaxCommitmentAccId();
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
        if (isset($alreadyDumpedObjects['DocCat'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['DocCat'][$this->getPrimaryKey()] = true;
        $keys = DocCatPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getSymbol(),
            $keys[3] => $this->getDocNoTmp(),
            $keys[4] => $this->getAsIncome(),
            $keys[5] => $this->getAsCost(),
            $keys[6] => $this->getAsBill(),
            $keys[7] => $this->getIsLocked(),
            $keys[8] => $this->getYearId(),
            $keys[9] => $this->getFileCatId(),
            $keys[10] => $this->getCommitmentAccId(),
            $keys[11] => $this->getTaxCommitmentAccId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aYear) {
                $result['Year'] = $this->aYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFileCat) {
                $result['FileCat'] = $this->aFileCat->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCommitmentAcc) {
                $result['CommitmentAcc'] = $this->aCommitmentAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTaxCommitmentAcc) {
                $result['TaxCommitmentAcc'] = $this->aTaxCommitmentAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDocs) {
                $result['Docs'] = $this->collDocs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = DocCatPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setDocNoTmp($value);
                break;
            case 4:
                $this->setAsIncome($value);
                break;
            case 5:
                $this->setAsCost($value);
                break;
            case 6:
                $this->setAsBill($value);
                break;
            case 7:
                $this->setIsLocked($value);
                break;
            case 8:
                $this->setYearId($value);
                break;
            case 9:
                $this->setFileCatId($value);
                break;
            case 10:
                $this->setCommitmentAccId($value);
                break;
            case 11:
                $this->setTaxCommitmentAccId($value);
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
        $keys = DocCatPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSymbol($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setDocNoTmp($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setAsIncome($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setAsCost($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAsBill($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIsLocked($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setYearId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setFileCatId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setCommitmentAccId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setTaxCommitmentAccId($arr[$keys[11]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DocCatPeer::DATABASE_NAME);

        if ($this->isColumnModified(DocCatPeer::ID)) $criteria->add(DocCatPeer::ID, $this->id);
        if ($this->isColumnModified(DocCatPeer::NAME)) $criteria->add(DocCatPeer::NAME, $this->name);
        if ($this->isColumnModified(DocCatPeer::SYMBOL)) $criteria->add(DocCatPeer::SYMBOL, $this->symbol);
        if ($this->isColumnModified(DocCatPeer::DOC_NO_TMP)) $criteria->add(DocCatPeer::DOC_NO_TMP, $this->doc_no_tmp);
        if ($this->isColumnModified(DocCatPeer::AS_INCOME)) $criteria->add(DocCatPeer::AS_INCOME, $this->as_income);
        if ($this->isColumnModified(DocCatPeer::AS_COST)) $criteria->add(DocCatPeer::AS_COST, $this->as_cost);
        if ($this->isColumnModified(DocCatPeer::AS_BILL)) $criteria->add(DocCatPeer::AS_BILL, $this->as_bill);
        if ($this->isColumnModified(DocCatPeer::IS_LOCKED)) $criteria->add(DocCatPeer::IS_LOCKED, $this->is_locked);
        if ($this->isColumnModified(DocCatPeer::YEAR_ID)) $criteria->add(DocCatPeer::YEAR_ID, $this->year_id);
        if ($this->isColumnModified(DocCatPeer::FILE_CAT_ID)) $criteria->add(DocCatPeer::FILE_CAT_ID, $this->file_cat_id);
        if ($this->isColumnModified(DocCatPeer::COMMITMENT_ACC_ID)) $criteria->add(DocCatPeer::COMMITMENT_ACC_ID, $this->commitment_acc_id);
        if ($this->isColumnModified(DocCatPeer::TAX_COMMITMENT_ACC_ID)) $criteria->add(DocCatPeer::TAX_COMMITMENT_ACC_ID, $this->tax_commitment_acc_id);

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
        $criteria = new Criteria(DocCatPeer::DATABASE_NAME);
        $criteria->add(DocCatPeer::ID, $this->id);

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
     * @param object $copyObj An object of DocCat (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setSymbol($this->getSymbol());
        $copyObj->setDocNoTmp($this->getDocNoTmp());
        $copyObj->setAsIncome($this->getAsIncome());
        $copyObj->setAsCost($this->getAsCost());
        $copyObj->setAsBill($this->getAsBill());
        $copyObj->setIsLocked($this->getIsLocked());
        $copyObj->setYearId($this->getYearId());
        $copyObj->setFileCatId($this->getFileCatId());
        $copyObj->setCommitmentAccId($this->getCommitmentAccId());
        $copyObj->setTaxCommitmentAccId($this->getTaxCommitmentAccId());

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
     * @return DocCat Clone of current object.
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
     * @return DocCatPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DocCatPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Year object.
     *
     * @param                  Year $v
     * @return DocCat The current object (for fluent API support)
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
            $v->addDocCat($this);
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
                $this->aYear->addDocCats($this);
             */
        }

        return $this->aYear;
    }

    /**
     * Declares an association between this object and a FileCat object.
     *
     * @param                  FileCat $v
     * @return DocCat The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileCat(FileCat $v = null)
    {
        if ($v === null) {
            $this->setFileCatId(NULL);
        } else {
            $this->setFileCatId($v->getId());
        }

        $this->aFileCat = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FileCat object, it will not be re-added.
        if ($v !== null) {
            $v->addDocCat($this);
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
    public function getFileCat(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileCat === null && ($this->file_cat_id !== null) && $doQuery) {
            $this->aFileCat = FileCatQuery::create()->findPk($this->file_cat_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileCat->addDocCats($this);
             */
        }

        return $this->aFileCat;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return DocCat The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCommitmentAcc(Account $v = null)
    {
        if ($v === null) {
            $this->setCommitmentAccId(NULL);
        } else {
            $this->setCommitmentAccId($v->getId());
        }

        $this->aCommitmentAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addDocCatRelatedByCommitmentAccId($this);
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
    public function getCommitmentAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCommitmentAcc === null && ($this->commitment_acc_id !== null) && $doQuery) {
            $this->aCommitmentAcc = AccountQuery::create()->findPk($this->commitment_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCommitmentAcc->addDocCatsRelatedByCommitmentAccId($this);
             */
        }

        return $this->aCommitmentAcc;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return DocCat The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTaxCommitmentAcc(Account $v = null)
    {
        if ($v === null) {
            $this->setTaxCommitmentAccId(NULL);
        } else {
            $this->setTaxCommitmentAccId($v->getId());
        }

        $this->aTaxCommitmentAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addDocCatRelatedByTaxCommitmentAccId($this);
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
    public function getTaxCommitmentAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTaxCommitmentAcc === null && ($this->tax_commitment_acc_id !== null) && $doQuery) {
            $this->aTaxCommitmentAcc = AccountQuery::create()->findPk($this->tax_commitment_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTaxCommitmentAcc->addDocCatsRelatedByTaxCommitmentAccId($this);
             */
        }

        return $this->aTaxCommitmentAcc;
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
    }

    /**
     * Clears out the collDocs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return DocCat The current object (for fluent API support)
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
     * If this DocCat is new, it will return
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
                    ->filterByDocCat($this)
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
     * @return DocCat The current object (for fluent API support)
     */
    public function setDocs(PropelCollection $docs, PropelPDO $con = null)
    {
        $docsToDelete = $this->getDocs(new Criteria(), $con)->diff($docs);


        $this->docsScheduledForDeletion = $docsToDelete;

        foreach ($docsToDelete as $docRemoved) {
            $docRemoved->setDocCat(null);
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
                ->filterByDocCat($this)
                ->count($con);
        }

        return count($this->collDocs);
    }

    /**
     * Method called to associate a Doc object to this object
     * through the Doc foreign key attribute.
     *
     * @param    Doc $l Doc
     * @return DocCat The current object (for fluent API support)
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
        $doc->setDocCat($this);
    }

    /**
     * @param	Doc $doc The doc object to remove.
     * @return DocCat The current object (for fluent API support)
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
            $doc->setDocCat(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DocCat is new, it will return
     * an empty collection; or if this DocCat has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DocCat.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Doc[] List of Doc objects
     */
    public function getDocsJoinMonth($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocQuery::create(null, $criteria);
        $query->joinWith('Month', $join_behavior);

        return $this->getDocs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this DocCat is new, it will return
     * an empty collection; or if this DocCat has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DocCat.
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
     * Otherwise if this DocCat is new, it will return
     * an empty collection; or if this DocCat has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in DocCat.
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
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->symbol = null;
        $this->doc_no_tmp = null;
        $this->as_income = null;
        $this->as_cost = null;
        $this->as_bill = null;
        $this->is_locked = null;
        $this->year_id = null;
        $this->file_cat_id = null;
        $this->commitment_acc_id = null;
        $this->tax_commitment_acc_id = null;
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
            if ($this->aYear instanceof Persistent) {
              $this->aYear->clearAllReferences($deep);
            }
            if ($this->aFileCat instanceof Persistent) {
              $this->aFileCat->clearAllReferences($deep);
            }
            if ($this->aCommitmentAcc instanceof Persistent) {
              $this->aCommitmentAcc->clearAllReferences($deep);
            }
            if ($this->aTaxCommitmentAcc instanceof Persistent) {
              $this->aTaxCommitmentAcc->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collDocs instanceof PropelCollection) {
            $this->collDocs->clearIterator();
        }
        $this->collDocs = null;
        $this->aYear = null;
        $this->aFileCat = null;
        $this->aCommitmentAcc = null;
        $this->aTaxCommitmentAcc = null;
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
