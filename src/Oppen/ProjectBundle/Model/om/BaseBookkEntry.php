<?php

namespace Oppen\ProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelException;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\Bookk;
use Oppen\ProjectBundle\Model\BookkEntry;
use Oppen\ProjectBundle\Model\BookkEntryPeer;
use Oppen\ProjectBundle\Model\BookkEntryQuery;
use Oppen\ProjectBundle\Model\BookkQuery;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileQuery;

abstract class BaseBookkEntry extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\BookkEntryPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        BookkEntryPeer
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
     * The value for the acc_no field.
     * @var        string
     */
    protected $acc_no;

    /**
     * The value for the value field.
     * @var        double
     */
    protected $value;

    /**
     * The value for the side field.
     * @var        int
     */
    protected $side;

    /**
     * The value for the bookk_id field.
     * @var        int
     */
    protected $bookk_id;

    /**
     * The value for the account_id field.
     * @var        int
     */
    protected $account_id;

    /**
     * The value for the file_lev1_id field.
     * @var        int
     */
    protected $file_lev1_id;

    /**
     * The value for the file_lev2_id field.
     * @var        int
     */
    protected $file_lev2_id;

    /**
     * The value for the file_lev3_id field.
     * @var        int
     */
    protected $file_lev3_id;

    /**
     * @var        Bookk
     */
    protected $aBookk;

    /**
     * @var        Account
     */
    protected $aAccount;

    /**
     * @var        File
     */
    protected $aFileLev1;

    /**
     * @var        File
     */
    protected $aFileLev2;

    /**
     * @var        File
     */
    protected $aFileLev3;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [acc_no] column value.
     *
     * @return string
     */
    public function getAccNo()
    {

        return $this->acc_no;
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
     * Get the [side] column value.
     *
     * @return int
     */
    public function getSide()
    {

        return $this->side;
    }

    /**
     * Get the [bookk_id] column value.
     *
     * @return int
     */
    public function getBookkId()
    {

        return $this->bookk_id;
    }

    /**
     * Get the [account_id] column value.
     *
     * @return int
     */
    public function getAccountId()
    {

        return $this->account_id;
    }

    /**
     * Get the [file_lev1_id] column value.
     *
     * @return int
     */
    public function getFileLev1Id()
    {

        return $this->file_lev1_id;
    }

    /**
     * Get the [file_lev2_id] column value.
     *
     * @return int
     */
    public function getFileLev2Id()
    {

        return $this->file_lev2_id;
    }

    /**
     * Get the [file_lev3_id] column value.
     *
     * @return int
     */
    public function getFileLev3Id()
    {

        return $this->file_lev3_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = BookkEntryPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [acc_no] column.
     *
     * @param  string $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setAccNo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->acc_no !== $v) {
            $this->acc_no = $v;
            $this->modifiedColumns[] = BookkEntryPeer::ACC_NO;
        }


        return $this;
    } // setAccNo()

    /**
     * Set the value of [value] column.
     *
     * @param  double $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setValue($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->value !== $v) {
            $this->value = $v;
            $this->modifiedColumns[] = BookkEntryPeer::VALUE;
        }


        return $this;
    } // setValue()

    /**
     * Set the value of [side] column.
     *
     * @param  int $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setSide($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->side !== $v) {
            $this->side = $v;
            $this->modifiedColumns[] = BookkEntryPeer::SIDE;
        }


        return $this;
    } // setSide()

    /**
     * Set the value of [bookk_id] column.
     *
     * @param  int $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setBookkId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bookk_id !== $v) {
            $this->bookk_id = $v;
            $this->modifiedColumns[] = BookkEntryPeer::BOOKK_ID;
        }

        if ($this->aBookk !== null && $this->aBookk->getId() !== $v) {
            $this->aBookk = null;
        }


        return $this;
    } // setBookkId()

    /**
     * Set the value of [account_id] column.
     *
     * @param  int $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setAccountId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->account_id !== $v) {
            $this->account_id = $v;
            $this->modifiedColumns[] = BookkEntryPeer::ACCOUNT_ID;
        }

        if ($this->aAccount !== null && $this->aAccount->getId() !== $v) {
            $this->aAccount = null;
        }


        return $this;
    } // setAccountId()

    /**
     * Set the value of [file_lev1_id] column.
     *
     * @param  int $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setFileLev1Id($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_lev1_id !== $v) {
            $this->file_lev1_id = $v;
            $this->modifiedColumns[] = BookkEntryPeer::FILE_LEV1_ID;
        }

        if ($this->aFileLev1 !== null && $this->aFileLev1->getId() !== $v) {
            $this->aFileLev1 = null;
        }


        return $this;
    } // setFileLev1Id()

    /**
     * Set the value of [file_lev2_id] column.
     *
     * @param  int $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setFileLev2Id($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_lev2_id !== $v) {
            $this->file_lev2_id = $v;
            $this->modifiedColumns[] = BookkEntryPeer::FILE_LEV2_ID;
        }

        if ($this->aFileLev2 !== null && $this->aFileLev2->getId() !== $v) {
            $this->aFileLev2 = null;
        }


        return $this;
    } // setFileLev2Id()

    /**
     * Set the value of [file_lev3_id] column.
     *
     * @param  int $v new value
     * @return BookkEntry The current object (for fluent API support)
     */
    public function setFileLev3Id($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_lev3_id !== $v) {
            $this->file_lev3_id = $v;
            $this->modifiedColumns[] = BookkEntryPeer::FILE_LEV3_ID;
        }

        if ($this->aFileLev3 !== null && $this->aFileLev3->getId() !== $v) {
            $this->aFileLev3 = null;
        }


        return $this;
    } // setFileLev3Id()

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
            $this->acc_no = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->value = ($row[$startcol + 2] !== null) ? (double) $row[$startcol + 2] : null;
            $this->side = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->bookk_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->account_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->file_lev1_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->file_lev2_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->file_lev3_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = BookkEntryPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating BookkEntry object", $e);
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

        if ($this->aBookk !== null && $this->bookk_id !== $this->aBookk->getId()) {
            $this->aBookk = null;
        }
        if ($this->aAccount !== null && $this->account_id !== $this->aAccount->getId()) {
            $this->aAccount = null;
        }
        if ($this->aFileLev1 !== null && $this->file_lev1_id !== $this->aFileLev1->getId()) {
            $this->aFileLev1 = null;
        }
        if ($this->aFileLev2 !== null && $this->file_lev2_id !== $this->aFileLev2->getId()) {
            $this->aFileLev2 = null;
        }
        if ($this->aFileLev3 !== null && $this->file_lev3_id !== $this->aFileLev3->getId()) {
            $this->aFileLev3 = null;
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
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = BookkEntryPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aBookk = null;
            $this->aAccount = null;
            $this->aFileLev1 = null;
            $this->aFileLev2 = null;
            $this->aFileLev3 = null;
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
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = BookkEntryQuery::create()
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
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                BookkEntryPeer::addInstanceToPool($this);
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

            if ($this->aBookk !== null) {
                if ($this->aBookk->isModified() || $this->aBookk->isNew()) {
                    $affectedRows += $this->aBookk->save($con);
                }
                $this->setBookk($this->aBookk);
            }

            if ($this->aAccount !== null) {
                if ($this->aAccount->isModified() || $this->aAccount->isNew()) {
                    $affectedRows += $this->aAccount->save($con);
                }
                $this->setAccount($this->aAccount);
            }

            if ($this->aFileLev1 !== null) {
                if ($this->aFileLev1->isModified() || $this->aFileLev1->isNew()) {
                    $affectedRows += $this->aFileLev1->save($con);
                }
                $this->setFileLev1($this->aFileLev1);
            }

            if ($this->aFileLev2 !== null) {
                if ($this->aFileLev2->isModified() || $this->aFileLev2->isNew()) {
                    $affectedRows += $this->aFileLev2->save($con);
                }
                $this->setFileLev2($this->aFileLev2);
            }

            if ($this->aFileLev3 !== null) {
                if ($this->aFileLev3->isModified() || $this->aFileLev3->isNew()) {
                    $affectedRows += $this->aFileLev3->save($con);
                }
                $this->setFileLev3($this->aFileLev3);
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

        $this->modifiedColumns[] = BookkEntryPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . BookkEntryPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(BookkEntryPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(BookkEntryPeer::ACC_NO)) {
            $modifiedColumns[':p' . $index++]  = '`acc_no`';
        }
        if ($this->isColumnModified(BookkEntryPeer::VALUE)) {
            $modifiedColumns[':p' . $index++]  = '`value`';
        }
        if ($this->isColumnModified(BookkEntryPeer::SIDE)) {
            $modifiedColumns[':p' . $index++]  = '`side`';
        }
        if ($this->isColumnModified(BookkEntryPeer::BOOKK_ID)) {
            $modifiedColumns[':p' . $index++]  = '`bookk_id`';
        }
        if ($this->isColumnModified(BookkEntryPeer::ACCOUNT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`account_id`';
        }
        if ($this->isColumnModified(BookkEntryPeer::FILE_LEV1_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_lev1_id`';
        }
        if ($this->isColumnModified(BookkEntryPeer::FILE_LEV2_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_lev2_id`';
        }
        if ($this->isColumnModified(BookkEntryPeer::FILE_LEV3_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_lev3_id`';
        }

        $sql = sprintf(
            'INSERT INTO `bookk_entry` (%s) VALUES (%s)',
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
                    case '`acc_no`':
                        $stmt->bindValue($identifier, $this->acc_no, PDO::PARAM_STR);
                        break;
                    case '`value`':
                        $stmt->bindValue($identifier, $this->value, PDO::PARAM_STR);
                        break;
                    case '`side`':
                        $stmt->bindValue($identifier, $this->side, PDO::PARAM_INT);
                        break;
                    case '`bookk_id`':
                        $stmt->bindValue($identifier, $this->bookk_id, PDO::PARAM_INT);
                        break;
                    case '`account_id`':
                        $stmt->bindValue($identifier, $this->account_id, PDO::PARAM_INT);
                        break;
                    case '`file_lev1_id`':
                        $stmt->bindValue($identifier, $this->file_lev1_id, PDO::PARAM_INT);
                        break;
                    case '`file_lev2_id`':
                        $stmt->bindValue($identifier, $this->file_lev2_id, PDO::PARAM_INT);
                        break;
                    case '`file_lev3_id`':
                        $stmt->bindValue($identifier, $this->file_lev3_id, PDO::PARAM_INT);
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

            if ($this->aBookk !== null) {
                if (!$this->aBookk->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBookk->getValidationFailures());
                }
            }

            if ($this->aAccount !== null) {
                if (!$this->aAccount->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aAccount->getValidationFailures());
                }
            }

            if ($this->aFileLev1 !== null) {
                if (!$this->aFileLev1->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileLev1->getValidationFailures());
                }
            }

            if ($this->aFileLev2 !== null) {
                if (!$this->aFileLev2->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileLev2->getValidationFailures());
                }
            }

            if ($this->aFileLev3 !== null) {
                if (!$this->aFileLev3->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileLev3->getValidationFailures());
                }
            }


            if (($retval = BookkEntryPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
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
        $pos = BookkEntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAccNo();
                break;
            case 2:
                return $this->getValue();
                break;
            case 3:
                return $this->getSide();
                break;
            case 4:
                return $this->getBookkId();
                break;
            case 5:
                return $this->getAccountId();
                break;
            case 6:
                return $this->getFileLev1Id();
                break;
            case 7:
                return $this->getFileLev2Id();
                break;
            case 8:
                return $this->getFileLev3Id();
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
        if (isset($alreadyDumpedObjects['BookkEntry'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['BookkEntry'][$this->getPrimaryKey()] = true;
        $keys = BookkEntryPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAccNo(),
            $keys[2] => $this->getValue(),
            $keys[3] => $this->getSide(),
            $keys[4] => $this->getBookkId(),
            $keys[5] => $this->getAccountId(),
            $keys[6] => $this->getFileLev1Id(),
            $keys[7] => $this->getFileLev2Id(),
            $keys[8] => $this->getFileLev3Id(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aBookk) {
                $result['Bookk'] = $this->aBookk->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aAccount) {
                $result['Account'] = $this->aAccount->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFileLev1) {
                $result['FileLev1'] = $this->aFileLev1->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFileLev2) {
                $result['FileLev2'] = $this->aFileLev2->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFileLev3) {
                $result['FileLev3'] = $this->aFileLev3->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = BookkEntryPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setAccNo($value);
                break;
            case 2:
                $this->setValue($value);
                break;
            case 3:
                $this->setSide($value);
                break;
            case 4:
                $this->setBookkId($value);
                break;
            case 5:
                $this->setAccountId($value);
                break;
            case 6:
                $this->setFileLev1Id($value);
                break;
            case 7:
                $this->setFileLev2Id($value);
                break;
            case 8:
                $this->setFileLev3Id($value);
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
        $keys = BookkEntryPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setAccNo($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setValue($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setSide($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setBookkId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setAccountId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setFileLev1Id($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setFileLev2Id($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setFileLev3Id($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(BookkEntryPeer::DATABASE_NAME);

        if ($this->isColumnModified(BookkEntryPeer::ID)) $criteria->add(BookkEntryPeer::ID, $this->id);
        if ($this->isColumnModified(BookkEntryPeer::ACC_NO)) $criteria->add(BookkEntryPeer::ACC_NO, $this->acc_no);
        if ($this->isColumnModified(BookkEntryPeer::VALUE)) $criteria->add(BookkEntryPeer::VALUE, $this->value);
        if ($this->isColumnModified(BookkEntryPeer::SIDE)) $criteria->add(BookkEntryPeer::SIDE, $this->side);
        if ($this->isColumnModified(BookkEntryPeer::BOOKK_ID)) $criteria->add(BookkEntryPeer::BOOKK_ID, $this->bookk_id);
        if ($this->isColumnModified(BookkEntryPeer::ACCOUNT_ID)) $criteria->add(BookkEntryPeer::ACCOUNT_ID, $this->account_id);
        if ($this->isColumnModified(BookkEntryPeer::FILE_LEV1_ID)) $criteria->add(BookkEntryPeer::FILE_LEV1_ID, $this->file_lev1_id);
        if ($this->isColumnModified(BookkEntryPeer::FILE_LEV2_ID)) $criteria->add(BookkEntryPeer::FILE_LEV2_ID, $this->file_lev2_id);
        if ($this->isColumnModified(BookkEntryPeer::FILE_LEV3_ID)) $criteria->add(BookkEntryPeer::FILE_LEV3_ID, $this->file_lev3_id);

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
        $criteria = new Criteria(BookkEntryPeer::DATABASE_NAME);
        $criteria->add(BookkEntryPeer::ID, $this->id);

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
     * @param object $copyObj An object of BookkEntry (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAccNo($this->getAccNo());
        $copyObj->setValue($this->getValue());
        $copyObj->setSide($this->getSide());
        $copyObj->setBookkId($this->getBookkId());
        $copyObj->setAccountId($this->getAccountId());
        $copyObj->setFileLev1Id($this->getFileLev1Id());
        $copyObj->setFileLev2Id($this->getFileLev2Id());
        $copyObj->setFileLev3Id($this->getFileLev3Id());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

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
     * @return BookkEntry Clone of current object.
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
     * @return BookkEntryPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new BookkEntryPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Bookk object.
     *
     * @param                  Bookk $v
     * @return BookkEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setBookk(Bookk $v = null)
    {
        if ($v === null) {
            $this->setBookkId(NULL);
        } else {
            $this->setBookkId($v->getId());
        }

        $this->aBookk = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Bookk object, it will not be re-added.
        if ($v !== null) {
            $v->addBookkEntry($this);
        }


        return $this;
    }


    /**
     * Get the associated Bookk object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Bookk The associated Bookk object.
     * @throws PropelException
     */
    public function getBookk(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aBookk === null && ($this->bookk_id !== null) && $doQuery) {
            $this->aBookk = BookkQuery::create()->findPk($this->bookk_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aBookk->addBookkEntries($this);
             */
        }

        return $this->aBookk;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return BookkEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setAccount(Account $v = null)
    {
        if ($v === null) {
            $this->setAccountId(NULL);
        } else {
            $this->setAccountId($v->getId());
        }

        $this->aAccount = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addBookkEntry($this);
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
    public function getAccount(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aAccount === null && ($this->account_id !== null) && $doQuery) {
            $this->aAccount = AccountQuery::create()->findPk($this->account_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aAccount->addBookkEntries($this);
             */
        }

        return $this->aAccount;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return BookkEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileLev1(File $v = null)
    {
        if ($v === null) {
            $this->setFileLev1Id(NULL);
        } else {
            $this->setFileLev1Id($v->getId());
        }

        $this->aFileLev1 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the File object, it will not be re-added.
        if ($v !== null) {
            $v->addBookkEntryRelatedByFileLev1Id($this);
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
    public function getFileLev1(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileLev1 === null && ($this->file_lev1_id !== null) && $doQuery) {
            $this->aFileLev1 = FileQuery::create()->findPk($this->file_lev1_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileLev1->addBookkEntriesRelatedByFileLev1Id($this);
             */
        }

        return $this->aFileLev1;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return BookkEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileLev2(File $v = null)
    {
        if ($v === null) {
            $this->setFileLev2Id(NULL);
        } else {
            $this->setFileLev2Id($v->getId());
        }

        $this->aFileLev2 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the File object, it will not be re-added.
        if ($v !== null) {
            $v->addBookkEntryRelatedByFileLev2Id($this);
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
    public function getFileLev2(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileLev2 === null && ($this->file_lev2_id !== null) && $doQuery) {
            $this->aFileLev2 = FileQuery::create()->findPk($this->file_lev2_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileLev2->addBookkEntriesRelatedByFileLev2Id($this);
             */
        }

        return $this->aFileLev2;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return BookkEntry The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileLev3(File $v = null)
    {
        if ($v === null) {
            $this->setFileLev3Id(NULL);
        } else {
            $this->setFileLev3Id($v->getId());
        }

        $this->aFileLev3 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the File object, it will not be re-added.
        if ($v !== null) {
            $v->addBookkEntryRelatedByFileLev3Id($this);
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
    public function getFileLev3(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileLev3 === null && ($this->file_lev3_id !== null) && $doQuery) {
            $this->aFileLev3 = FileQuery::create()->findPk($this->file_lev3_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileLev3->addBookkEntriesRelatedByFileLev3Id($this);
             */
        }

        return $this->aFileLev3;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->acc_no = null;
        $this->value = null;
        $this->side = null;
        $this->bookk_id = null;
        $this->account_id = null;
        $this->file_lev1_id = null;
        $this->file_lev2_id = null;
        $this->file_lev3_id = null;
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
            if ($this->aBookk instanceof Persistent) {
              $this->aBookk->clearAllReferences($deep);
            }
            if ($this->aAccount instanceof Persistent) {
              $this->aAccount->clearAllReferences($deep);
            }
            if ($this->aFileLev1 instanceof Persistent) {
              $this->aFileLev1->clearAllReferences($deep);
            }
            if ($this->aFileLev2 instanceof Persistent) {
              $this->aFileLev2->clearAllReferences($deep);
            }
            if ($this->aFileLev3 instanceof Persistent) {
              $this->aFileLev3->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aBookk = null;
        $this->aAccount = null;
        $this->aFileLev1 = null;
        $this->aFileLev2 = null;
        $this->aFileLev3 = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(BookkEntryPeer::DEFAULT_STRING_FORMAT);
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
