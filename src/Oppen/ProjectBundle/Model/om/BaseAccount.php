<?php

namespace Oppen\ProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \NestedSetRecursiveIterator;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\AccountPeer;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\BookkEntry;
use Oppen\ProjectBundle\Model\BookkEntryQuery;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostQuery;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\Income;
use Oppen\ProjectBundle\Model\IncomeQuery;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\ProjectQuery;
use Oppen\ProjectBundle\Model\Year;
use Oppen\ProjectBundle\Model\YearQuery;

abstract class BaseAccount extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\AccountPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        AccountPeer
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
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the report_side field.
     * @var        int
     */
    protected $report_side;

    /**
     * The value for the as_bank_acc field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_bank_acc;

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
     * The value for the inc_open_b field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $inc_open_b;

    /**
     * The value for the inc_close_b field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $inc_close_b;

    /**
     * The value for the as_close_b field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_close_b;

    /**
     * The value for the year_id field.
     * @var        int
     */
    protected $year_id;

    /**
     * The value for the file_cat_lev1_id field.
     * @var        int
     */
    protected $file_cat_lev1_id;

    /**
     * The value for the file_cat_lev2_id field.
     * @var        int
     */
    protected $file_cat_lev2_id;

    /**
     * The value for the file_cat_lev3_id field.
     * @var        int
     */
    protected $file_cat_lev3_id;

    /**
     * The value for the tree_left field.
     * @var        int
     */
    protected $tree_left;

    /**
     * The value for the tree_right field.
     * @var        int
     */
    protected $tree_right;

    /**
     * The value for the tree_level field.
     * @var        int
     */
    protected $tree_level;

    /**
     * @var        Year
     */
    protected $aYear;

    /**
     * @var        FileCat
     */
    protected $aFileCatLev1;

    /**
     * @var        FileCat
     */
    protected $aFileCatLev2;

    /**
     * @var        FileCat
     */
    protected $aFileCatLev3;

    /**
     * @var        PropelObjectCollection|DocCat[] Collection to store aggregation of DocCat objects.
     */
    protected $collDocCatsRelatedByCommitmentAccId;
    protected $collDocCatsRelatedByCommitmentAccIdPartial;

    /**
     * @var        PropelObjectCollection|DocCat[] Collection to store aggregation of DocCat objects.
     */
    protected $collDocCatsRelatedByTaxCommitmentAccId;
    protected $collDocCatsRelatedByTaxCommitmentAccIdPartial;

    /**
     * @var        PropelObjectCollection|BookkEntry[] Collection to store aggregation of BookkEntry objects.
     */
    protected $collBookkEntries;
    protected $collBookkEntriesPartial;

    /**
     * @var        PropelObjectCollection|Project[] Collection to store aggregation of Project objects.
     */
    protected $collProjectsRelatedByIncomeAccId;
    protected $collProjectsRelatedByIncomeAccIdPartial;

    /**
     * @var        PropelObjectCollection|Project[] Collection to store aggregation of Project objects.
     */
    protected $collProjectsRelatedByCostAccId;
    protected $collProjectsRelatedByCostAccIdPartial;

    /**
     * @var        PropelObjectCollection|Project[] Collection to store aggregation of Project objects.
     */
    protected $collProjectsRelatedByBankAccId;
    protected $collProjectsRelatedByBankAccIdPartial;

    /**
     * @var        PropelObjectCollection|Income[] Collection to store aggregation of Income objects.
     */
    protected $collIncomesRelatedByBankAccId;
    protected $collIncomesRelatedByBankAccIdPartial;

    /**
     * @var        PropelObjectCollection|Income[] Collection to store aggregation of Income objects.
     */
    protected $collIncomesRelatedByIncomeAccId;
    protected $collIncomesRelatedByIncomeAccIdPartial;

    /**
     * @var        PropelObjectCollection|Cost[] Collection to store aggregation of Cost objects.
     */
    protected $collCostsRelatedByBankAccId;
    protected $collCostsRelatedByBankAccIdPartial;

    /**
     * @var        PropelObjectCollection|Cost[] Collection to store aggregation of Cost objects.
     */
    protected $collCostsRelatedByCostAccId;
    protected $collCostsRelatedByCostAccIdPartial;

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

    // nested_set behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $nestedSetQueries = array();

    /**
     * Internal cache for children nodes
     * @var        null|PropelObjectCollection
     */
    protected $collNestedSetChildren = null;

    /**
     * Internal cache for parent node
     * @var        null|Account
     */
    protected $aNestedSetParent = null;


    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $docCatsRelatedByCommitmentAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $bookkEntriesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $projectsRelatedByIncomeAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $projectsRelatedByCostAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $projectsRelatedByBankAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $incomesRelatedByBankAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $incomesRelatedByIncomeAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costsRelatedByBankAccIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costsRelatedByCostAccIdScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->as_bank_acc = false;
        $this->as_income = false;
        $this->as_cost = false;
        $this->inc_open_b = false;
        $this->inc_close_b = false;
        $this->as_close_b = false;
    }

    /**
     * Initializes internal state of BaseAccount object.
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
     * Get the [acc_no] column value.
     *
     * @return string
     */
    public function getAccNo()
    {

        return $this->acc_no;
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
     * Get the [report_side] column value.
     *
     * @return int
     */
    public function getReportSide()
    {

        return $this->report_side;
    }

    /**
     * Get the [as_bank_acc] column value.
     *
     * @return boolean
     */
    public function getAsBankAcc()
    {

        return $this->as_bank_acc;
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
     * Get the [inc_open_b] column value.
     *
     * @return boolean
     */
    public function getIncOpenB()
    {

        return $this->inc_open_b;
    }

    /**
     * Get the [inc_close_b] column value.
     *
     * @return boolean
     */
    public function getIncCloseB()
    {

        return $this->inc_close_b;
    }

    /**
     * Get the [as_close_b] column value.
     *
     * @return boolean
     */
    public function getAsCloseB()
    {

        return $this->as_close_b;
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
     * Get the [file_cat_lev1_id] column value.
     *
     * @return int
     */
    public function getFileCatLev1Id()
    {

        return $this->file_cat_lev1_id;
    }

    /**
     * Get the [file_cat_lev2_id] column value.
     *
     * @return int
     */
    public function getFileCatLev2Id()
    {

        return $this->file_cat_lev2_id;
    }

    /**
     * Get the [file_cat_lev3_id] column value.
     *
     * @return int
     */
    public function getFileCatLev3Id()
    {

        return $this->file_cat_lev3_id;
    }

    /**
     * Get the [tree_left] column value.
     *
     * @return int
     */
    public function getTreeLeft()
    {

        return $this->tree_left;
    }

    /**
     * Get the [tree_right] column value.
     *
     * @return int
     */
    public function getTreeRight()
    {

        return $this->tree_right;
    }

    /**
     * Get the [tree_level] column value.
     *
     * @return int
     */
    public function getTreeLevel()
    {

        return $this->tree_level;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = AccountPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [acc_no] column.
     *
     * @param  string $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setAccNo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->acc_no !== $v) {
            $this->acc_no = $v;
            $this->modifiedColumns[] = AccountPeer::ACC_NO;
        }


        return $this;
    } // setAccNo()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = AccountPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [report_side] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setReportSide($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->report_side !== $v) {
            $this->report_side = $v;
            $this->modifiedColumns[] = AccountPeer::REPORT_SIDE;
        }


        return $this;
    } // setReportSide()

    /**
     * Sets the value of the [as_bank_acc] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Account The current object (for fluent API support)
     */
    public function setAsBankAcc($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_bank_acc !== $v) {
            $this->as_bank_acc = $v;
            $this->modifiedColumns[] = AccountPeer::AS_BANK_ACC;
        }


        return $this;
    } // setAsBankAcc()

    /**
     * Sets the value of the [as_income] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Account The current object (for fluent API support)
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
            $this->modifiedColumns[] = AccountPeer::AS_INCOME;
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
     * @return Account The current object (for fluent API support)
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
            $this->modifiedColumns[] = AccountPeer::AS_COST;
        }


        return $this;
    } // setAsCost()

    /**
     * Sets the value of the [inc_open_b] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Account The current object (for fluent API support)
     */
    public function setIncOpenB($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->inc_open_b !== $v) {
            $this->inc_open_b = $v;
            $this->modifiedColumns[] = AccountPeer::INC_OPEN_B;
        }


        return $this;
    } // setIncOpenB()

    /**
     * Sets the value of the [inc_close_b] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Account The current object (for fluent API support)
     */
    public function setIncCloseB($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->inc_close_b !== $v) {
            $this->inc_close_b = $v;
            $this->modifiedColumns[] = AccountPeer::INC_CLOSE_B;
        }


        return $this;
    } // setIncCloseB()

    /**
     * Sets the value of the [as_close_b] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Account The current object (for fluent API support)
     */
    public function setAsCloseB($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_close_b !== $v) {
            $this->as_close_b = $v;
            $this->modifiedColumns[] = AccountPeer::AS_CLOSE_B;
        }


        return $this;
    } // setAsCloseB()

    /**
     * Set the value of [year_id] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year_id !== $v) {
            $this->year_id = $v;
            $this->modifiedColumns[] = AccountPeer::YEAR_ID;
        }

        if ($this->aYear !== null && $this->aYear->getId() !== $v) {
            $this->aYear = null;
        }


        return $this;
    } // setYearId()

    /**
     * Set the value of [file_cat_lev1_id] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setFileCatLev1Id($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_cat_lev1_id !== $v) {
            $this->file_cat_lev1_id = $v;
            $this->modifiedColumns[] = AccountPeer::FILE_CAT_LEV1_ID;
        }

        if ($this->aFileCatLev1 !== null && $this->aFileCatLev1->getId() !== $v) {
            $this->aFileCatLev1 = null;
        }


        return $this;
    } // setFileCatLev1Id()

    /**
     * Set the value of [file_cat_lev2_id] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setFileCatLev2Id($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_cat_lev2_id !== $v) {
            $this->file_cat_lev2_id = $v;
            $this->modifiedColumns[] = AccountPeer::FILE_CAT_LEV2_ID;
        }

        if ($this->aFileCatLev2 !== null && $this->aFileCatLev2->getId() !== $v) {
            $this->aFileCatLev2 = null;
        }


        return $this;
    } // setFileCatLev2Id()

    /**
     * Set the value of [file_cat_lev3_id] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setFileCatLev3Id($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_cat_lev3_id !== $v) {
            $this->file_cat_lev3_id = $v;
            $this->modifiedColumns[] = AccountPeer::FILE_CAT_LEV3_ID;
        }

        if ($this->aFileCatLev3 !== null && $this->aFileCatLev3->getId() !== $v) {
            $this->aFileCatLev3 = null;
        }


        return $this;
    } // setFileCatLev3Id()

    /**
     * Set the value of [tree_left] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setTreeLeft($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_left !== $v) {
            $this->tree_left = $v;
            $this->modifiedColumns[] = AccountPeer::TREE_LEFT;
        }


        return $this;
    } // setTreeLeft()

    /**
     * Set the value of [tree_right] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setTreeRight($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_right !== $v) {
            $this->tree_right = $v;
            $this->modifiedColumns[] = AccountPeer::TREE_RIGHT;
        }


        return $this;
    } // setTreeRight()

    /**
     * Set the value of [tree_level] column.
     *
     * @param  int $v new value
     * @return Account The current object (for fluent API support)
     */
    public function setTreeLevel($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->tree_level !== $v) {
            $this->tree_level = $v;
            $this->modifiedColumns[] = AccountPeer::TREE_LEVEL;
        }


        return $this;
    } // setTreeLevel()

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
            if ($this->as_bank_acc !== false) {
                return false;
            }

            if ($this->as_income !== false) {
                return false;
            }

            if ($this->as_cost !== false) {
                return false;
            }

            if ($this->inc_open_b !== false) {
                return false;
            }

            if ($this->inc_close_b !== false) {
                return false;
            }

            if ($this->as_close_b !== false) {
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
            $this->acc_no = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->name = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->report_side = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->as_bank_acc = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->as_income = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->as_cost = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->inc_open_b = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->inc_close_b = ($row[$startcol + 8] !== null) ? (boolean) $row[$startcol + 8] : null;
            $this->as_close_b = ($row[$startcol + 9] !== null) ? (boolean) $row[$startcol + 9] : null;
            $this->year_id = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->file_cat_lev1_id = ($row[$startcol + 11] !== null) ? (int) $row[$startcol + 11] : null;
            $this->file_cat_lev2_id = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->file_cat_lev3_id = ($row[$startcol + 13] !== null) ? (int) $row[$startcol + 13] : null;
            $this->tree_left = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->tree_right = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->tree_level = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 17; // 17 = AccountPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Account object", $e);
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
        if ($this->aFileCatLev1 !== null && $this->file_cat_lev1_id !== $this->aFileCatLev1->getId()) {
            $this->aFileCatLev1 = null;
        }
        if ($this->aFileCatLev2 !== null && $this->file_cat_lev2_id !== $this->aFileCatLev2->getId()) {
            $this->aFileCatLev2 = null;
        }
        if ($this->aFileCatLev3 !== null && $this->file_cat_lev3_id !== $this->aFileCatLev3->getId()) {
            $this->aFileCatLev3 = null;
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
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = AccountPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aYear = null;
            $this->aFileCatLev1 = null;
            $this->aFileCatLev2 = null;
            $this->aFileCatLev3 = null;
            $this->collDocCatsRelatedByCommitmentAccId = null;

            $this->collDocCatsRelatedByTaxCommitmentAccId = null;

            $this->collBookkEntries = null;

            $this->collProjectsRelatedByIncomeAccId = null;

            $this->collProjectsRelatedByCostAccId = null;

            $this->collProjectsRelatedByBankAccId = null;

            $this->collIncomesRelatedByBankAccId = null;

            $this->collIncomesRelatedByIncomeAccId = null;

            $this->collCostsRelatedByBankAccId = null;

            $this->collCostsRelatedByCostAccId = null;

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
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = AccountQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // nested_set behavior
            if ($this->isRoot()) {
                throw new PropelException('Deletion of a root node is disabled for nested sets. Use AccountPeer::deleteTree($scope) instead to delete an entire tree');
            }

            if ($this->isInTree()) {
                $this->deleteDescendants($con);
            }

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // nested_set behavior
                if ($this->isInTree()) {
                    // fill up the room that was used by the node
                    AccountPeer::shiftRLValues(-2, $this->getRightValue() + 1, null, $this->getScopeValue(), $con);
                }

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
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // nested_set behavior
            if ($this->isNew() && $this->isRoot()) {
                // check if no other root exist in, the tree
                $nbRoots = AccountQuery::create()
                    ->addUsingAlias(AccountPeer::LEFT_COL, 1, Criteria::EQUAL)
                    ->addUsingAlias(AccountPeer::SCOPE_COL, $this->getScopeValue(), Criteria::EQUAL)
                    ->count($con);
                if ($nbRoots > 0) {
                        throw new PropelException(sprintf('A root node already exists in this tree with scope "%s".', $this->getScopeValue()));
                }
            }
            $this->processNestedSetQueries($con);
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
                AccountPeer::addInstanceToPool($this);
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

            if ($this->aFileCatLev1 !== null) {
                if ($this->aFileCatLev1->isModified() || $this->aFileCatLev1->isNew()) {
                    $affectedRows += $this->aFileCatLev1->save($con);
                }
                $this->setFileCatLev1($this->aFileCatLev1);
            }

            if ($this->aFileCatLev2 !== null) {
                if ($this->aFileCatLev2->isModified() || $this->aFileCatLev2->isNew()) {
                    $affectedRows += $this->aFileCatLev2->save($con);
                }
                $this->setFileCatLev2($this->aFileCatLev2);
            }

            if ($this->aFileCatLev3 !== null) {
                if ($this->aFileCatLev3->isModified() || $this->aFileCatLev3->isNew()) {
                    $affectedRows += $this->aFileCatLev3->save($con);
                }
                $this->setFileCatLev3($this->aFileCatLev3);
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

            if ($this->docCatsRelatedByCommitmentAccIdScheduledForDeletion !== null) {
                if (!$this->docCatsRelatedByCommitmentAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->docCatsRelatedByCommitmentAccIdScheduledForDeletion as $docCatRelatedByCommitmentAccId) {
                        // need to save related object because we set the relation to null
                        $docCatRelatedByCommitmentAccId->save($con);
                    }
                    $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collDocCatsRelatedByCommitmentAccId !== null) {
                foreach ($this->collDocCatsRelatedByCommitmentAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion !== null) {
                if (!$this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion as $docCatRelatedByTaxCommitmentAccId) {
                        // need to save related object because we set the relation to null
                        $docCatRelatedByTaxCommitmentAccId->save($con);
                    }
                    $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collDocCatsRelatedByTaxCommitmentAccId !== null) {
                foreach ($this->collDocCatsRelatedByTaxCommitmentAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->bookkEntriesScheduledForDeletion !== null) {
                if (!$this->bookkEntriesScheduledForDeletion->isEmpty()) {
                    foreach ($this->bookkEntriesScheduledForDeletion as $bookkEntry) {
                        // need to save related object because we set the relation to null
                        $bookkEntry->save($con);
                    }
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

            if ($this->projectsRelatedByIncomeAccIdScheduledForDeletion !== null) {
                if (!$this->projectsRelatedByIncomeAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->projectsRelatedByIncomeAccIdScheduledForDeletion as $projectRelatedByIncomeAccId) {
                        // need to save related object because we set the relation to null
                        $projectRelatedByIncomeAccId->save($con);
                    }
                    $this->projectsRelatedByIncomeAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collProjectsRelatedByIncomeAccId !== null) {
                foreach ($this->collProjectsRelatedByIncomeAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->projectsRelatedByCostAccIdScheduledForDeletion !== null) {
                if (!$this->projectsRelatedByCostAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->projectsRelatedByCostAccIdScheduledForDeletion as $projectRelatedByCostAccId) {
                        // need to save related object because we set the relation to null
                        $projectRelatedByCostAccId->save($con);
                    }
                    $this->projectsRelatedByCostAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collProjectsRelatedByCostAccId !== null) {
                foreach ($this->collProjectsRelatedByCostAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->projectsRelatedByBankAccIdScheduledForDeletion !== null) {
                if (!$this->projectsRelatedByBankAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->projectsRelatedByBankAccIdScheduledForDeletion as $projectRelatedByBankAccId) {
                        // need to save related object because we set the relation to null
                        $projectRelatedByBankAccId->save($con);
                    }
                    $this->projectsRelatedByBankAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collProjectsRelatedByBankAccId !== null) {
                foreach ($this->collProjectsRelatedByBankAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->incomesRelatedByBankAccIdScheduledForDeletion !== null) {
                if (!$this->incomesRelatedByBankAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->incomesRelatedByBankAccIdScheduledForDeletion as $incomeRelatedByBankAccId) {
                        // need to save related object because we set the relation to null
                        $incomeRelatedByBankAccId->save($con);
                    }
                    $this->incomesRelatedByBankAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collIncomesRelatedByBankAccId !== null) {
                foreach ($this->collIncomesRelatedByBankAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->incomesRelatedByIncomeAccIdScheduledForDeletion !== null) {
                if (!$this->incomesRelatedByIncomeAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->incomesRelatedByIncomeAccIdScheduledForDeletion as $incomeRelatedByIncomeAccId) {
                        // need to save related object because we set the relation to null
                        $incomeRelatedByIncomeAccId->save($con);
                    }
                    $this->incomesRelatedByIncomeAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collIncomesRelatedByIncomeAccId !== null) {
                foreach ($this->collIncomesRelatedByIncomeAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->costsRelatedByBankAccIdScheduledForDeletion !== null) {
                if (!$this->costsRelatedByBankAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->costsRelatedByBankAccIdScheduledForDeletion as $costRelatedByBankAccId) {
                        // need to save related object because we set the relation to null
                        $costRelatedByBankAccId->save($con);
                    }
                    $this->costsRelatedByBankAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collCostsRelatedByBankAccId !== null) {
                foreach ($this->collCostsRelatedByBankAccId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->costsRelatedByCostAccIdScheduledForDeletion !== null) {
                if (!$this->costsRelatedByCostAccIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->costsRelatedByCostAccIdScheduledForDeletion as $costRelatedByCostAccId) {
                        // need to save related object because we set the relation to null
                        $costRelatedByCostAccId->save($con);
                    }
                    $this->costsRelatedByCostAccIdScheduledForDeletion = null;
                }
            }

            if ($this->collCostsRelatedByCostAccId !== null) {
                foreach ($this->collCostsRelatedByCostAccId as $referrerFK) {
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

        $this->modifiedColumns[] = AccountPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . AccountPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(AccountPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(AccountPeer::ACC_NO)) {
            $modifiedColumns[':p' . $index++]  = '`acc_no`';
        }
        if ($this->isColumnModified(AccountPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(AccountPeer::REPORT_SIDE)) {
            $modifiedColumns[':p' . $index++]  = '`report_side`';
        }
        if ($this->isColumnModified(AccountPeer::AS_BANK_ACC)) {
            $modifiedColumns[':p' . $index++]  = '`as_bank_acc`';
        }
        if ($this->isColumnModified(AccountPeer::AS_INCOME)) {
            $modifiedColumns[':p' . $index++]  = '`as_income`';
        }
        if ($this->isColumnModified(AccountPeer::AS_COST)) {
            $modifiedColumns[':p' . $index++]  = '`as_cost`';
        }
        if ($this->isColumnModified(AccountPeer::INC_OPEN_B)) {
            $modifiedColumns[':p' . $index++]  = '`inc_open_b`';
        }
        if ($this->isColumnModified(AccountPeer::INC_CLOSE_B)) {
            $modifiedColumns[':p' . $index++]  = '`inc_close_b`';
        }
        if ($this->isColumnModified(AccountPeer::AS_CLOSE_B)) {
            $modifiedColumns[':p' . $index++]  = '`as_close_b`';
        }
        if ($this->isColumnModified(AccountPeer::YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`year_id`';
        }
        if ($this->isColumnModified(AccountPeer::FILE_CAT_LEV1_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_cat_lev1_id`';
        }
        if ($this->isColumnModified(AccountPeer::FILE_CAT_LEV2_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_cat_lev2_id`';
        }
        if ($this->isColumnModified(AccountPeer::FILE_CAT_LEV3_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_cat_lev3_id`';
        }
        if ($this->isColumnModified(AccountPeer::TREE_LEFT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_left`';
        }
        if ($this->isColumnModified(AccountPeer::TREE_RIGHT)) {
            $modifiedColumns[':p' . $index++]  = '`tree_right`';
        }
        if ($this->isColumnModified(AccountPeer::TREE_LEVEL)) {
            $modifiedColumns[':p' . $index++]  = '`tree_level`';
        }

        $sql = sprintf(
            'INSERT INTO `account` (%s) VALUES (%s)',
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
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`report_side`':
                        $stmt->bindValue($identifier, $this->report_side, PDO::PARAM_INT);
                        break;
                    case '`as_bank_acc`':
                        $stmt->bindValue($identifier, (int) $this->as_bank_acc, PDO::PARAM_INT);
                        break;
                    case '`as_income`':
                        $stmt->bindValue($identifier, (int) $this->as_income, PDO::PARAM_INT);
                        break;
                    case '`as_cost`':
                        $stmt->bindValue($identifier, (int) $this->as_cost, PDO::PARAM_INT);
                        break;
                    case '`inc_open_b`':
                        $stmt->bindValue($identifier, (int) $this->inc_open_b, PDO::PARAM_INT);
                        break;
                    case '`inc_close_b`':
                        $stmt->bindValue($identifier, (int) $this->inc_close_b, PDO::PARAM_INT);
                        break;
                    case '`as_close_b`':
                        $stmt->bindValue($identifier, (int) $this->as_close_b, PDO::PARAM_INT);
                        break;
                    case '`year_id`':
                        $stmt->bindValue($identifier, $this->year_id, PDO::PARAM_INT);
                        break;
                    case '`file_cat_lev1_id`':
                        $stmt->bindValue($identifier, $this->file_cat_lev1_id, PDO::PARAM_INT);
                        break;
                    case '`file_cat_lev2_id`':
                        $stmt->bindValue($identifier, $this->file_cat_lev2_id, PDO::PARAM_INT);
                        break;
                    case '`file_cat_lev3_id`':
                        $stmt->bindValue($identifier, $this->file_cat_lev3_id, PDO::PARAM_INT);
                        break;
                    case '`tree_left`':
                        $stmt->bindValue($identifier, $this->tree_left, PDO::PARAM_INT);
                        break;
                    case '`tree_right`':
                        $stmt->bindValue($identifier, $this->tree_right, PDO::PARAM_INT);
                        break;
                    case '`tree_level`':
                        $stmt->bindValue($identifier, $this->tree_level, PDO::PARAM_INT);
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

            if ($this->aFileCatLev1 !== null) {
                if (!$this->aFileCatLev1->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileCatLev1->getValidationFailures());
                }
            }

            if ($this->aFileCatLev2 !== null) {
                if (!$this->aFileCatLev2->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileCatLev2->getValidationFailures());
                }
            }

            if ($this->aFileCatLev3 !== null) {
                if (!$this->aFileCatLev3->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileCatLev3->getValidationFailures());
                }
            }


            if (($retval = AccountPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collDocCatsRelatedByCommitmentAccId !== null) {
                    foreach ($this->collDocCatsRelatedByCommitmentAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collDocCatsRelatedByTaxCommitmentAccId !== null) {
                    foreach ($this->collDocCatsRelatedByTaxCommitmentAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBookkEntries !== null) {
                    foreach ($this->collBookkEntries as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProjectsRelatedByIncomeAccId !== null) {
                    foreach ($this->collProjectsRelatedByIncomeAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProjectsRelatedByCostAccId !== null) {
                    foreach ($this->collProjectsRelatedByCostAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProjectsRelatedByBankAccId !== null) {
                    foreach ($this->collProjectsRelatedByBankAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collIncomesRelatedByBankAccId !== null) {
                    foreach ($this->collIncomesRelatedByBankAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collIncomesRelatedByIncomeAccId !== null) {
                    foreach ($this->collIncomesRelatedByIncomeAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCostsRelatedByBankAccId !== null) {
                    foreach ($this->collCostsRelatedByBankAccId as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCostsRelatedByCostAccId !== null) {
                    foreach ($this->collCostsRelatedByCostAccId as $referrerFK) {
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
        $pos = AccountPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getName();
                break;
            case 3:
                return $this->getReportSide();
                break;
            case 4:
                return $this->getAsBankAcc();
                break;
            case 5:
                return $this->getAsIncome();
                break;
            case 6:
                return $this->getAsCost();
                break;
            case 7:
                return $this->getIncOpenB();
                break;
            case 8:
                return $this->getIncCloseB();
                break;
            case 9:
                return $this->getAsCloseB();
                break;
            case 10:
                return $this->getYearId();
                break;
            case 11:
                return $this->getFileCatLev1Id();
                break;
            case 12:
                return $this->getFileCatLev2Id();
                break;
            case 13:
                return $this->getFileCatLev3Id();
                break;
            case 14:
                return $this->getTreeLeft();
                break;
            case 15:
                return $this->getTreeRight();
                break;
            case 16:
                return $this->getTreeLevel();
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
        if (isset($alreadyDumpedObjects['Account'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Account'][$this->getPrimaryKey()] = true;
        $keys = AccountPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getAccNo(),
            $keys[2] => $this->getName(),
            $keys[3] => $this->getReportSide(),
            $keys[4] => $this->getAsBankAcc(),
            $keys[5] => $this->getAsIncome(),
            $keys[6] => $this->getAsCost(),
            $keys[7] => $this->getIncOpenB(),
            $keys[8] => $this->getIncCloseB(),
            $keys[9] => $this->getAsCloseB(),
            $keys[10] => $this->getYearId(),
            $keys[11] => $this->getFileCatLev1Id(),
            $keys[12] => $this->getFileCatLev2Id(),
            $keys[13] => $this->getFileCatLev3Id(),
            $keys[14] => $this->getTreeLeft(),
            $keys[15] => $this->getTreeRight(),
            $keys[16] => $this->getTreeLevel(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aYear) {
                $result['Year'] = $this->aYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFileCatLev1) {
                $result['FileCatLev1'] = $this->aFileCatLev1->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFileCatLev2) {
                $result['FileCatLev2'] = $this->aFileCatLev2->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFileCatLev3) {
                $result['FileCatLev3'] = $this->aFileCatLev3->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collDocCatsRelatedByCommitmentAccId) {
                $result['DocCatsRelatedByCommitmentAccId'] = $this->collDocCatsRelatedByCommitmentAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDocCatsRelatedByTaxCommitmentAccId) {
                $result['DocCatsRelatedByTaxCommitmentAccId'] = $this->collDocCatsRelatedByTaxCommitmentAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBookkEntries) {
                $result['BookkEntries'] = $this->collBookkEntries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProjectsRelatedByIncomeAccId) {
                $result['ProjectsRelatedByIncomeAccId'] = $this->collProjectsRelatedByIncomeAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProjectsRelatedByCostAccId) {
                $result['ProjectsRelatedByCostAccId'] = $this->collProjectsRelatedByCostAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProjectsRelatedByBankAccId) {
                $result['ProjectsRelatedByBankAccId'] = $this->collProjectsRelatedByBankAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collIncomesRelatedByBankAccId) {
                $result['IncomesRelatedByBankAccId'] = $this->collIncomesRelatedByBankAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collIncomesRelatedByIncomeAccId) {
                $result['IncomesRelatedByIncomeAccId'] = $this->collIncomesRelatedByIncomeAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCostsRelatedByBankAccId) {
                $result['CostsRelatedByBankAccId'] = $this->collCostsRelatedByBankAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCostsRelatedByCostAccId) {
                $result['CostsRelatedByCostAccId'] = $this->collCostsRelatedByCostAccId->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = AccountPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setName($value);
                break;
            case 3:
                $this->setReportSide($value);
                break;
            case 4:
                $this->setAsBankAcc($value);
                break;
            case 5:
                $this->setAsIncome($value);
                break;
            case 6:
                $this->setAsCost($value);
                break;
            case 7:
                $this->setIncOpenB($value);
                break;
            case 8:
                $this->setIncCloseB($value);
                break;
            case 9:
                $this->setAsCloseB($value);
                break;
            case 10:
                $this->setYearId($value);
                break;
            case 11:
                $this->setFileCatLev1Id($value);
                break;
            case 12:
                $this->setFileCatLev2Id($value);
                break;
            case 13:
                $this->setFileCatLev3Id($value);
                break;
            case 14:
                $this->setTreeLeft($value);
                break;
            case 15:
                $this->setTreeRight($value);
                break;
            case 16:
                $this->setTreeLevel($value);
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
        $keys = AccountPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setAccNo($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setReportSide($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setAsBankAcc($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setAsIncome($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAsCost($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setIncOpenB($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setIncCloseB($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setAsCloseB($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setYearId($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setFileCatLev1Id($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setFileCatLev2Id($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setFileCatLev3Id($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setTreeLeft($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setTreeRight($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setTreeLevel($arr[$keys[16]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(AccountPeer::DATABASE_NAME);

        if ($this->isColumnModified(AccountPeer::ID)) $criteria->add(AccountPeer::ID, $this->id);
        if ($this->isColumnModified(AccountPeer::ACC_NO)) $criteria->add(AccountPeer::ACC_NO, $this->acc_no);
        if ($this->isColumnModified(AccountPeer::NAME)) $criteria->add(AccountPeer::NAME, $this->name);
        if ($this->isColumnModified(AccountPeer::REPORT_SIDE)) $criteria->add(AccountPeer::REPORT_SIDE, $this->report_side);
        if ($this->isColumnModified(AccountPeer::AS_BANK_ACC)) $criteria->add(AccountPeer::AS_BANK_ACC, $this->as_bank_acc);
        if ($this->isColumnModified(AccountPeer::AS_INCOME)) $criteria->add(AccountPeer::AS_INCOME, $this->as_income);
        if ($this->isColumnModified(AccountPeer::AS_COST)) $criteria->add(AccountPeer::AS_COST, $this->as_cost);
        if ($this->isColumnModified(AccountPeer::INC_OPEN_B)) $criteria->add(AccountPeer::INC_OPEN_B, $this->inc_open_b);
        if ($this->isColumnModified(AccountPeer::INC_CLOSE_B)) $criteria->add(AccountPeer::INC_CLOSE_B, $this->inc_close_b);
        if ($this->isColumnModified(AccountPeer::AS_CLOSE_B)) $criteria->add(AccountPeer::AS_CLOSE_B, $this->as_close_b);
        if ($this->isColumnModified(AccountPeer::YEAR_ID)) $criteria->add(AccountPeer::YEAR_ID, $this->year_id);
        if ($this->isColumnModified(AccountPeer::FILE_CAT_LEV1_ID)) $criteria->add(AccountPeer::FILE_CAT_LEV1_ID, $this->file_cat_lev1_id);
        if ($this->isColumnModified(AccountPeer::FILE_CAT_LEV2_ID)) $criteria->add(AccountPeer::FILE_CAT_LEV2_ID, $this->file_cat_lev2_id);
        if ($this->isColumnModified(AccountPeer::FILE_CAT_LEV3_ID)) $criteria->add(AccountPeer::FILE_CAT_LEV3_ID, $this->file_cat_lev3_id);
        if ($this->isColumnModified(AccountPeer::TREE_LEFT)) $criteria->add(AccountPeer::TREE_LEFT, $this->tree_left);
        if ($this->isColumnModified(AccountPeer::TREE_RIGHT)) $criteria->add(AccountPeer::TREE_RIGHT, $this->tree_right);
        if ($this->isColumnModified(AccountPeer::TREE_LEVEL)) $criteria->add(AccountPeer::TREE_LEVEL, $this->tree_level);

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
        $criteria = new Criteria(AccountPeer::DATABASE_NAME);
        $criteria->add(AccountPeer::ID, $this->id);

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
     * @param object $copyObj An object of Account (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setAccNo($this->getAccNo());
        $copyObj->setName($this->getName());
        $copyObj->setReportSide($this->getReportSide());
        $copyObj->setAsBankAcc($this->getAsBankAcc());
        $copyObj->setAsIncome($this->getAsIncome());
        $copyObj->setAsCost($this->getAsCost());
        $copyObj->setIncOpenB($this->getIncOpenB());
        $copyObj->setIncCloseB($this->getIncCloseB());
        $copyObj->setAsCloseB($this->getAsCloseB());
        $copyObj->setYearId($this->getYearId());
        $copyObj->setFileCatLev1Id($this->getFileCatLev1Id());
        $copyObj->setFileCatLev2Id($this->getFileCatLev2Id());
        $copyObj->setFileCatLev3Id($this->getFileCatLev3Id());
        $copyObj->setTreeLeft($this->getTreeLeft());
        $copyObj->setTreeRight($this->getTreeRight());
        $copyObj->setTreeLevel($this->getTreeLevel());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getDocCatsRelatedByCommitmentAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDocCatRelatedByCommitmentAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDocCatsRelatedByTaxCommitmentAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDocCatRelatedByTaxCommitmentAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBookkEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookkEntry($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProjectsRelatedByIncomeAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProjectRelatedByIncomeAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProjectsRelatedByCostAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProjectRelatedByCostAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProjectsRelatedByBankAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProjectRelatedByBankAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getIncomesRelatedByBankAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIncomeRelatedByBankAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getIncomesRelatedByIncomeAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIncomeRelatedByIncomeAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCostsRelatedByBankAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCostRelatedByBankAccId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCostsRelatedByCostAccId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCostRelatedByCostAccId($relObj->copy($deepCopy));
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
     * @return Account Clone of current object.
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
     * @return AccountPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new AccountPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Year object.
     *
     * @param                  Year $v
     * @return Account The current object (for fluent API support)
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
            $v->addAccount($this);
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
                $this->aYear->addAccounts($this);
             */
        }

        return $this->aYear;
    }

    /**
     * Declares an association between this object and a FileCat object.
     *
     * @param                  FileCat $v
     * @return Account The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileCatLev1(FileCat $v = null)
    {
        if ($v === null) {
            $this->setFileCatLev1Id(NULL);
        } else {
            $this->setFileCatLev1Id($v->getId());
        }

        $this->aFileCatLev1 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FileCat object, it will not be re-added.
        if ($v !== null) {
            $v->addAccountRelatedByFileCatLev1Id($this);
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
    public function getFileCatLev1(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileCatLev1 === null && ($this->file_cat_lev1_id !== null) && $doQuery) {
            $this->aFileCatLev1 = FileCatQuery::create()->findPk($this->file_cat_lev1_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileCatLev1->addAccountsRelatedByFileCatLev1Id($this);
             */
        }

        return $this->aFileCatLev1;
    }

    /**
     * Declares an association between this object and a FileCat object.
     *
     * @param                  FileCat $v
     * @return Account The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileCatLev2(FileCat $v = null)
    {
        if ($v === null) {
            $this->setFileCatLev2Id(NULL);
        } else {
            $this->setFileCatLev2Id($v->getId());
        }

        $this->aFileCatLev2 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FileCat object, it will not be re-added.
        if ($v !== null) {
            $v->addAccountRelatedByFileCatLev2Id($this);
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
    public function getFileCatLev2(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileCatLev2 === null && ($this->file_cat_lev2_id !== null) && $doQuery) {
            $this->aFileCatLev2 = FileCatQuery::create()->findPk($this->file_cat_lev2_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileCatLev2->addAccountsRelatedByFileCatLev2Id($this);
             */
        }

        return $this->aFileCatLev2;
    }

    /**
     * Declares an association between this object and a FileCat object.
     *
     * @param                  FileCat $v
     * @return Account The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileCatLev3(FileCat $v = null)
    {
        if ($v === null) {
            $this->setFileCatLev3Id(NULL);
        } else {
            $this->setFileCatLev3Id($v->getId());
        }

        $this->aFileCatLev3 = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FileCat object, it will not be re-added.
        if ($v !== null) {
            $v->addAccountRelatedByFileCatLev3Id($this);
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
    public function getFileCatLev3(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileCatLev3 === null && ($this->file_cat_lev3_id !== null) && $doQuery) {
            $this->aFileCatLev3 = FileCatQuery::create()->findPk($this->file_cat_lev3_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileCatLev3->addAccountsRelatedByFileCatLev3Id($this);
             */
        }

        return $this->aFileCatLev3;
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
        if ('DocCatRelatedByCommitmentAccId' == $relationName) {
            $this->initDocCatsRelatedByCommitmentAccId();
        }
        if ('DocCatRelatedByTaxCommitmentAccId' == $relationName) {
            $this->initDocCatsRelatedByTaxCommitmentAccId();
        }
        if ('BookkEntry' == $relationName) {
            $this->initBookkEntries();
        }
        if ('ProjectRelatedByIncomeAccId' == $relationName) {
            $this->initProjectsRelatedByIncomeAccId();
        }
        if ('ProjectRelatedByCostAccId' == $relationName) {
            $this->initProjectsRelatedByCostAccId();
        }
        if ('ProjectRelatedByBankAccId' == $relationName) {
            $this->initProjectsRelatedByBankAccId();
        }
        if ('IncomeRelatedByBankAccId' == $relationName) {
            $this->initIncomesRelatedByBankAccId();
        }
        if ('IncomeRelatedByIncomeAccId' == $relationName) {
            $this->initIncomesRelatedByIncomeAccId();
        }
        if ('CostRelatedByBankAccId' == $relationName) {
            $this->initCostsRelatedByBankAccId();
        }
        if ('CostRelatedByCostAccId' == $relationName) {
            $this->initCostsRelatedByCostAccId();
        }
    }

    /**
     * Clears out the collDocCatsRelatedByCommitmentAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addDocCatsRelatedByCommitmentAccId()
     */
    public function clearDocCatsRelatedByCommitmentAccId()
    {
        $this->collDocCatsRelatedByCommitmentAccId = null; // important to set this to null since that means it is uninitialized
        $this->collDocCatsRelatedByCommitmentAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collDocCatsRelatedByCommitmentAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialDocCatsRelatedByCommitmentAccId($v = true)
    {
        $this->collDocCatsRelatedByCommitmentAccIdPartial = $v;
    }

    /**
     * Initializes the collDocCatsRelatedByCommitmentAccId collection.
     *
     * By default this just sets the collDocCatsRelatedByCommitmentAccId collection to an empty array (like clearcollDocCatsRelatedByCommitmentAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDocCatsRelatedByCommitmentAccId($overrideExisting = true)
    {
        if (null !== $this->collDocCatsRelatedByCommitmentAccId && !$overrideExisting) {
            return;
        }
        $this->collDocCatsRelatedByCommitmentAccId = new PropelObjectCollection();
        $this->collDocCatsRelatedByCommitmentAccId->setModel('DocCat');
    }

    /**
     * Gets an array of DocCat objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     * @throws PropelException
     */
    public function getDocCatsRelatedByCommitmentAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDocCatsRelatedByCommitmentAccIdPartial && !$this->isNew();
        if (null === $this->collDocCatsRelatedByCommitmentAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDocCatsRelatedByCommitmentAccId) {
                // return empty collection
                $this->initDocCatsRelatedByCommitmentAccId();
            } else {
                $collDocCatsRelatedByCommitmentAccId = DocCatQuery::create(null, $criteria)
                    ->filterByCommitmentAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDocCatsRelatedByCommitmentAccIdPartial && count($collDocCatsRelatedByCommitmentAccId)) {
                      $this->initDocCatsRelatedByCommitmentAccId(false);

                      foreach ($collDocCatsRelatedByCommitmentAccId as $obj) {
                        if (false == $this->collDocCatsRelatedByCommitmentAccId->contains($obj)) {
                          $this->collDocCatsRelatedByCommitmentAccId->append($obj);
                        }
                      }

                      $this->collDocCatsRelatedByCommitmentAccIdPartial = true;
                    }

                    $collDocCatsRelatedByCommitmentAccId->getInternalIterator()->rewind();

                    return $collDocCatsRelatedByCommitmentAccId;
                }

                if ($partial && $this->collDocCatsRelatedByCommitmentAccId) {
                    foreach ($this->collDocCatsRelatedByCommitmentAccId as $obj) {
                        if ($obj->isNew()) {
                            $collDocCatsRelatedByCommitmentAccId[] = $obj;
                        }
                    }
                }

                $this->collDocCatsRelatedByCommitmentAccId = $collDocCatsRelatedByCommitmentAccId;
                $this->collDocCatsRelatedByCommitmentAccIdPartial = false;
            }
        }

        return $this->collDocCatsRelatedByCommitmentAccId;
    }

    /**
     * Sets a collection of DocCatRelatedByCommitmentAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $docCatsRelatedByCommitmentAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setDocCatsRelatedByCommitmentAccId(PropelCollection $docCatsRelatedByCommitmentAccId, PropelPDO $con = null)
    {
        $docCatsRelatedByCommitmentAccIdToDelete = $this->getDocCatsRelatedByCommitmentAccId(new Criteria(), $con)->diff($docCatsRelatedByCommitmentAccId);


        $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion = $docCatsRelatedByCommitmentAccIdToDelete;

        foreach ($docCatsRelatedByCommitmentAccIdToDelete as $docCatRelatedByCommitmentAccIdRemoved) {
            $docCatRelatedByCommitmentAccIdRemoved->setCommitmentAcc(null);
        }

        $this->collDocCatsRelatedByCommitmentAccId = null;
        foreach ($docCatsRelatedByCommitmentAccId as $docCatRelatedByCommitmentAccId) {
            $this->addDocCatRelatedByCommitmentAccId($docCatRelatedByCommitmentAccId);
        }

        $this->collDocCatsRelatedByCommitmentAccId = $docCatsRelatedByCommitmentAccId;
        $this->collDocCatsRelatedByCommitmentAccIdPartial = false;

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
    public function countDocCatsRelatedByCommitmentAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDocCatsRelatedByCommitmentAccIdPartial && !$this->isNew();
        if (null === $this->collDocCatsRelatedByCommitmentAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDocCatsRelatedByCommitmentAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDocCatsRelatedByCommitmentAccId());
            }
            $query = DocCatQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCommitmentAcc($this)
                ->count($con);
        }

        return count($this->collDocCatsRelatedByCommitmentAccId);
    }

    /**
     * Method called to associate a DocCat object to this object
     * through the DocCat foreign key attribute.
     *
     * @param    DocCat $l DocCat
     * @return Account The current object (for fluent API support)
     */
    public function addDocCatRelatedByCommitmentAccId(DocCat $l)
    {
        if ($this->collDocCatsRelatedByCommitmentAccId === null) {
            $this->initDocCatsRelatedByCommitmentAccId();
            $this->collDocCatsRelatedByCommitmentAccIdPartial = true;
        }

        if (!in_array($l, $this->collDocCatsRelatedByCommitmentAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDocCatRelatedByCommitmentAccId($l);

            if ($this->docCatsRelatedByCommitmentAccIdScheduledForDeletion and $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion->contains($l)) {
                $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion->remove($this->docCatsRelatedByCommitmentAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	DocCatRelatedByCommitmentAccId $docCatRelatedByCommitmentAccId The docCatRelatedByCommitmentAccId object to add.
     */
    protected function doAddDocCatRelatedByCommitmentAccId($docCatRelatedByCommitmentAccId)
    {
        $this->collDocCatsRelatedByCommitmentAccId[]= $docCatRelatedByCommitmentAccId;
        $docCatRelatedByCommitmentAccId->setCommitmentAcc($this);
    }

    /**
     * @param	DocCatRelatedByCommitmentAccId $docCatRelatedByCommitmentAccId The docCatRelatedByCommitmentAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeDocCatRelatedByCommitmentAccId($docCatRelatedByCommitmentAccId)
    {
        if ($this->getDocCatsRelatedByCommitmentAccId()->contains($docCatRelatedByCommitmentAccId)) {
            $this->collDocCatsRelatedByCommitmentAccId->remove($this->collDocCatsRelatedByCommitmentAccId->search($docCatRelatedByCommitmentAccId));
            if (null === $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion) {
                $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion = clone $this->collDocCatsRelatedByCommitmentAccId;
                $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion->clear();
            }
            $this->docCatsRelatedByCommitmentAccIdScheduledForDeletion[]= $docCatRelatedByCommitmentAccId;
            $docCatRelatedByCommitmentAccId->setCommitmentAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related DocCatsRelatedByCommitmentAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsRelatedByCommitmentAccIdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getDocCatsRelatedByCommitmentAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related DocCatsRelatedByCommitmentAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsRelatedByCommitmentAccIdJoinFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('FileCat', $join_behavior);

        return $this->getDocCatsRelatedByCommitmentAccId($query, $con);
    }

    /**
     * Clears out the collDocCatsRelatedByTaxCommitmentAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addDocCatsRelatedByTaxCommitmentAccId()
     */
    public function clearDocCatsRelatedByTaxCommitmentAccId()
    {
        $this->collDocCatsRelatedByTaxCommitmentAccId = null; // important to set this to null since that means it is uninitialized
        $this->collDocCatsRelatedByTaxCommitmentAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collDocCatsRelatedByTaxCommitmentAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialDocCatsRelatedByTaxCommitmentAccId($v = true)
    {
        $this->collDocCatsRelatedByTaxCommitmentAccIdPartial = $v;
    }

    /**
     * Initializes the collDocCatsRelatedByTaxCommitmentAccId collection.
     *
     * By default this just sets the collDocCatsRelatedByTaxCommitmentAccId collection to an empty array (like clearcollDocCatsRelatedByTaxCommitmentAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDocCatsRelatedByTaxCommitmentAccId($overrideExisting = true)
    {
        if (null !== $this->collDocCatsRelatedByTaxCommitmentAccId && !$overrideExisting) {
            return;
        }
        $this->collDocCatsRelatedByTaxCommitmentAccId = new PropelObjectCollection();
        $this->collDocCatsRelatedByTaxCommitmentAccId->setModel('DocCat');
    }

    /**
     * Gets an array of DocCat objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     * @throws PropelException
     */
    public function getDocCatsRelatedByTaxCommitmentAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDocCatsRelatedByTaxCommitmentAccIdPartial && !$this->isNew();
        if (null === $this->collDocCatsRelatedByTaxCommitmentAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDocCatsRelatedByTaxCommitmentAccId) {
                // return empty collection
                $this->initDocCatsRelatedByTaxCommitmentAccId();
            } else {
                $collDocCatsRelatedByTaxCommitmentAccId = DocCatQuery::create(null, $criteria)
                    ->filterByTaxCommitmentAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDocCatsRelatedByTaxCommitmentAccIdPartial && count($collDocCatsRelatedByTaxCommitmentAccId)) {
                      $this->initDocCatsRelatedByTaxCommitmentAccId(false);

                      foreach ($collDocCatsRelatedByTaxCommitmentAccId as $obj) {
                        if (false == $this->collDocCatsRelatedByTaxCommitmentAccId->contains($obj)) {
                          $this->collDocCatsRelatedByTaxCommitmentAccId->append($obj);
                        }
                      }

                      $this->collDocCatsRelatedByTaxCommitmentAccIdPartial = true;
                    }

                    $collDocCatsRelatedByTaxCommitmentAccId->getInternalIterator()->rewind();

                    return $collDocCatsRelatedByTaxCommitmentAccId;
                }

                if ($partial && $this->collDocCatsRelatedByTaxCommitmentAccId) {
                    foreach ($this->collDocCatsRelatedByTaxCommitmentAccId as $obj) {
                        if ($obj->isNew()) {
                            $collDocCatsRelatedByTaxCommitmentAccId[] = $obj;
                        }
                    }
                }

                $this->collDocCatsRelatedByTaxCommitmentAccId = $collDocCatsRelatedByTaxCommitmentAccId;
                $this->collDocCatsRelatedByTaxCommitmentAccIdPartial = false;
            }
        }

        return $this->collDocCatsRelatedByTaxCommitmentAccId;
    }

    /**
     * Sets a collection of DocCatRelatedByTaxCommitmentAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $docCatsRelatedByTaxCommitmentAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setDocCatsRelatedByTaxCommitmentAccId(PropelCollection $docCatsRelatedByTaxCommitmentAccId, PropelPDO $con = null)
    {
        $docCatsRelatedByTaxCommitmentAccIdToDelete = $this->getDocCatsRelatedByTaxCommitmentAccId(new Criteria(), $con)->diff($docCatsRelatedByTaxCommitmentAccId);


        $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion = $docCatsRelatedByTaxCommitmentAccIdToDelete;

        foreach ($docCatsRelatedByTaxCommitmentAccIdToDelete as $docCatRelatedByTaxCommitmentAccIdRemoved) {
            $docCatRelatedByTaxCommitmentAccIdRemoved->setTaxCommitmentAcc(null);
        }

        $this->collDocCatsRelatedByTaxCommitmentAccId = null;
        foreach ($docCatsRelatedByTaxCommitmentAccId as $docCatRelatedByTaxCommitmentAccId) {
            $this->addDocCatRelatedByTaxCommitmentAccId($docCatRelatedByTaxCommitmentAccId);
        }

        $this->collDocCatsRelatedByTaxCommitmentAccId = $docCatsRelatedByTaxCommitmentAccId;
        $this->collDocCatsRelatedByTaxCommitmentAccIdPartial = false;

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
    public function countDocCatsRelatedByTaxCommitmentAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDocCatsRelatedByTaxCommitmentAccIdPartial && !$this->isNew();
        if (null === $this->collDocCatsRelatedByTaxCommitmentAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDocCatsRelatedByTaxCommitmentAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDocCatsRelatedByTaxCommitmentAccId());
            }
            $query = DocCatQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTaxCommitmentAcc($this)
                ->count($con);
        }

        return count($this->collDocCatsRelatedByTaxCommitmentAccId);
    }

    /**
     * Method called to associate a DocCat object to this object
     * through the DocCat foreign key attribute.
     *
     * @param    DocCat $l DocCat
     * @return Account The current object (for fluent API support)
     */
    public function addDocCatRelatedByTaxCommitmentAccId(DocCat $l)
    {
        if ($this->collDocCatsRelatedByTaxCommitmentAccId === null) {
            $this->initDocCatsRelatedByTaxCommitmentAccId();
            $this->collDocCatsRelatedByTaxCommitmentAccIdPartial = true;
        }

        if (!in_array($l, $this->collDocCatsRelatedByTaxCommitmentAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDocCatRelatedByTaxCommitmentAccId($l);

            if ($this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion and $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion->contains($l)) {
                $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion->remove($this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	DocCatRelatedByTaxCommitmentAccId $docCatRelatedByTaxCommitmentAccId The docCatRelatedByTaxCommitmentAccId object to add.
     */
    protected function doAddDocCatRelatedByTaxCommitmentAccId($docCatRelatedByTaxCommitmentAccId)
    {
        $this->collDocCatsRelatedByTaxCommitmentAccId[]= $docCatRelatedByTaxCommitmentAccId;
        $docCatRelatedByTaxCommitmentAccId->setTaxCommitmentAcc($this);
    }

    /**
     * @param	DocCatRelatedByTaxCommitmentAccId $docCatRelatedByTaxCommitmentAccId The docCatRelatedByTaxCommitmentAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeDocCatRelatedByTaxCommitmentAccId($docCatRelatedByTaxCommitmentAccId)
    {
        if ($this->getDocCatsRelatedByTaxCommitmentAccId()->contains($docCatRelatedByTaxCommitmentAccId)) {
            $this->collDocCatsRelatedByTaxCommitmentAccId->remove($this->collDocCatsRelatedByTaxCommitmentAccId->search($docCatRelatedByTaxCommitmentAccId));
            if (null === $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion) {
                $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion = clone $this->collDocCatsRelatedByTaxCommitmentAccId;
                $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion->clear();
            }
            $this->docCatsRelatedByTaxCommitmentAccIdScheduledForDeletion[]= $docCatRelatedByTaxCommitmentAccId;
            $docCatRelatedByTaxCommitmentAccId->setTaxCommitmentAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related DocCatsRelatedByTaxCommitmentAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsRelatedByTaxCommitmentAccIdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getDocCatsRelatedByTaxCommitmentAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related DocCatsRelatedByTaxCommitmentAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|DocCat[] List of DocCat objects
     */
    public function getDocCatsRelatedByTaxCommitmentAccIdJoinFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocCatQuery::create(null, $criteria);
        $query->joinWith('FileCat', $join_behavior);

        return $this->getDocCatsRelatedByTaxCommitmentAccId($query, $con);
    }

    /**
     * Clears out the collBookkEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
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
     * If this Account is new, it will return
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
                    ->filterByAccount($this)
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
     * @return Account The current object (for fluent API support)
     */
    public function setBookkEntries(PropelCollection $bookkEntries, PropelPDO $con = null)
    {
        $bookkEntriesToDelete = $this->getBookkEntries(new Criteria(), $con)->diff($bookkEntries);


        $this->bookkEntriesScheduledForDeletion = $bookkEntriesToDelete;

        foreach ($bookkEntriesToDelete as $bookkEntryRemoved) {
            $bookkEntryRemoved->setAccount(null);
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
                ->filterByAccount($this)
                ->count($con);
        }

        return count($this->collBookkEntries);
    }

    /**
     * Method called to associate a BookkEntry object to this object
     * through the BookkEntry foreign key attribute.
     *
     * @param    BookkEntry $l BookkEntry
     * @return Account The current object (for fluent API support)
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
        $bookkEntry->setAccount($this);
    }

    /**
     * @param	BookkEntry $bookkEntry The bookkEntry object to remove.
     * @return Account The current object (for fluent API support)
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
            $bookkEntry->setAccount(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesJoinBookk($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Bookk', $join_behavior);

        return $this->getBookkEntries($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
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
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
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
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related BookkEntries from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
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
     * Clears out the collProjectsRelatedByIncomeAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addProjectsRelatedByIncomeAccId()
     */
    public function clearProjectsRelatedByIncomeAccId()
    {
        $this->collProjectsRelatedByIncomeAccId = null; // important to set this to null since that means it is uninitialized
        $this->collProjectsRelatedByIncomeAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collProjectsRelatedByIncomeAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialProjectsRelatedByIncomeAccId($v = true)
    {
        $this->collProjectsRelatedByIncomeAccIdPartial = $v;
    }

    /**
     * Initializes the collProjectsRelatedByIncomeAccId collection.
     *
     * By default this just sets the collProjectsRelatedByIncomeAccId collection to an empty array (like clearcollProjectsRelatedByIncomeAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProjectsRelatedByIncomeAccId($overrideExisting = true)
    {
        if (null !== $this->collProjectsRelatedByIncomeAccId && !$overrideExisting) {
            return;
        }
        $this->collProjectsRelatedByIncomeAccId = new PropelObjectCollection();
        $this->collProjectsRelatedByIncomeAccId->setModel('Project');
    }

    /**
     * Gets an array of Project objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Project[] List of Project objects
     * @throws PropelException
     */
    public function getProjectsRelatedByIncomeAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProjectsRelatedByIncomeAccIdPartial && !$this->isNew();
        if (null === $this->collProjectsRelatedByIncomeAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProjectsRelatedByIncomeAccId) {
                // return empty collection
                $this->initProjectsRelatedByIncomeAccId();
            } else {
                $collProjectsRelatedByIncomeAccId = ProjectQuery::create(null, $criteria)
                    ->filterByIncomeAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProjectsRelatedByIncomeAccIdPartial && count($collProjectsRelatedByIncomeAccId)) {
                      $this->initProjectsRelatedByIncomeAccId(false);

                      foreach ($collProjectsRelatedByIncomeAccId as $obj) {
                        if (false == $this->collProjectsRelatedByIncomeAccId->contains($obj)) {
                          $this->collProjectsRelatedByIncomeAccId->append($obj);
                        }
                      }

                      $this->collProjectsRelatedByIncomeAccIdPartial = true;
                    }

                    $collProjectsRelatedByIncomeAccId->getInternalIterator()->rewind();

                    return $collProjectsRelatedByIncomeAccId;
                }

                if ($partial && $this->collProjectsRelatedByIncomeAccId) {
                    foreach ($this->collProjectsRelatedByIncomeAccId as $obj) {
                        if ($obj->isNew()) {
                            $collProjectsRelatedByIncomeAccId[] = $obj;
                        }
                    }
                }

                $this->collProjectsRelatedByIncomeAccId = $collProjectsRelatedByIncomeAccId;
                $this->collProjectsRelatedByIncomeAccIdPartial = false;
            }
        }

        return $this->collProjectsRelatedByIncomeAccId;
    }

    /**
     * Sets a collection of ProjectRelatedByIncomeAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $projectsRelatedByIncomeAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setProjectsRelatedByIncomeAccId(PropelCollection $projectsRelatedByIncomeAccId, PropelPDO $con = null)
    {
        $projectsRelatedByIncomeAccIdToDelete = $this->getProjectsRelatedByIncomeAccId(new Criteria(), $con)->diff($projectsRelatedByIncomeAccId);


        $this->projectsRelatedByIncomeAccIdScheduledForDeletion = $projectsRelatedByIncomeAccIdToDelete;

        foreach ($projectsRelatedByIncomeAccIdToDelete as $projectRelatedByIncomeAccIdRemoved) {
            $projectRelatedByIncomeAccIdRemoved->setIncomeAcc(null);
        }

        $this->collProjectsRelatedByIncomeAccId = null;
        foreach ($projectsRelatedByIncomeAccId as $projectRelatedByIncomeAccId) {
            $this->addProjectRelatedByIncomeAccId($projectRelatedByIncomeAccId);
        }

        $this->collProjectsRelatedByIncomeAccId = $projectsRelatedByIncomeAccId;
        $this->collProjectsRelatedByIncomeAccIdPartial = false;

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
    public function countProjectsRelatedByIncomeAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProjectsRelatedByIncomeAccIdPartial && !$this->isNew();
        if (null === $this->collProjectsRelatedByIncomeAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProjectsRelatedByIncomeAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProjectsRelatedByIncomeAccId());
            }
            $query = ProjectQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByIncomeAcc($this)
                ->count($con);
        }

        return count($this->collProjectsRelatedByIncomeAccId);
    }

    /**
     * Method called to associate a Project object to this object
     * through the Project foreign key attribute.
     *
     * @param    Project $l Project
     * @return Account The current object (for fluent API support)
     */
    public function addProjectRelatedByIncomeAccId(Project $l)
    {
        if ($this->collProjectsRelatedByIncomeAccId === null) {
            $this->initProjectsRelatedByIncomeAccId();
            $this->collProjectsRelatedByIncomeAccIdPartial = true;
        }

        if (!in_array($l, $this->collProjectsRelatedByIncomeAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProjectRelatedByIncomeAccId($l);

            if ($this->projectsRelatedByIncomeAccIdScheduledForDeletion and $this->projectsRelatedByIncomeAccIdScheduledForDeletion->contains($l)) {
                $this->projectsRelatedByIncomeAccIdScheduledForDeletion->remove($this->projectsRelatedByIncomeAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProjectRelatedByIncomeAccId $projectRelatedByIncomeAccId The projectRelatedByIncomeAccId object to add.
     */
    protected function doAddProjectRelatedByIncomeAccId($projectRelatedByIncomeAccId)
    {
        $this->collProjectsRelatedByIncomeAccId[]= $projectRelatedByIncomeAccId;
        $projectRelatedByIncomeAccId->setIncomeAcc($this);
    }

    /**
     * @param	ProjectRelatedByIncomeAccId $projectRelatedByIncomeAccId The projectRelatedByIncomeAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeProjectRelatedByIncomeAccId($projectRelatedByIncomeAccId)
    {
        if ($this->getProjectsRelatedByIncomeAccId()->contains($projectRelatedByIncomeAccId)) {
            $this->collProjectsRelatedByIncomeAccId->remove($this->collProjectsRelatedByIncomeAccId->search($projectRelatedByIncomeAccId));
            if (null === $this->projectsRelatedByIncomeAccIdScheduledForDeletion) {
                $this->projectsRelatedByIncomeAccIdScheduledForDeletion = clone $this->collProjectsRelatedByIncomeAccId;
                $this->projectsRelatedByIncomeAccIdScheduledForDeletion->clear();
            }
            $this->projectsRelatedByIncomeAccIdScheduledForDeletion[]= $projectRelatedByIncomeAccId;
            $projectRelatedByIncomeAccId->setIncomeAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByIncomeAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByIncomeAccIdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getProjectsRelatedByIncomeAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByIncomeAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByIncomeAccIdJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getProjectsRelatedByIncomeAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByIncomeAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByIncomeAccIdJoinCostFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('CostFileCat', $join_behavior);

        return $this->getProjectsRelatedByIncomeAccId($query, $con);
    }

    /**
     * Clears out the collProjectsRelatedByCostAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addProjectsRelatedByCostAccId()
     */
    public function clearProjectsRelatedByCostAccId()
    {
        $this->collProjectsRelatedByCostAccId = null; // important to set this to null since that means it is uninitialized
        $this->collProjectsRelatedByCostAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collProjectsRelatedByCostAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialProjectsRelatedByCostAccId($v = true)
    {
        $this->collProjectsRelatedByCostAccIdPartial = $v;
    }

    /**
     * Initializes the collProjectsRelatedByCostAccId collection.
     *
     * By default this just sets the collProjectsRelatedByCostAccId collection to an empty array (like clearcollProjectsRelatedByCostAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProjectsRelatedByCostAccId($overrideExisting = true)
    {
        if (null !== $this->collProjectsRelatedByCostAccId && !$overrideExisting) {
            return;
        }
        $this->collProjectsRelatedByCostAccId = new PropelObjectCollection();
        $this->collProjectsRelatedByCostAccId->setModel('Project');
    }

    /**
     * Gets an array of Project objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Project[] List of Project objects
     * @throws PropelException
     */
    public function getProjectsRelatedByCostAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProjectsRelatedByCostAccIdPartial && !$this->isNew();
        if (null === $this->collProjectsRelatedByCostAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProjectsRelatedByCostAccId) {
                // return empty collection
                $this->initProjectsRelatedByCostAccId();
            } else {
                $collProjectsRelatedByCostAccId = ProjectQuery::create(null, $criteria)
                    ->filterByCostAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProjectsRelatedByCostAccIdPartial && count($collProjectsRelatedByCostAccId)) {
                      $this->initProjectsRelatedByCostAccId(false);

                      foreach ($collProjectsRelatedByCostAccId as $obj) {
                        if (false == $this->collProjectsRelatedByCostAccId->contains($obj)) {
                          $this->collProjectsRelatedByCostAccId->append($obj);
                        }
                      }

                      $this->collProjectsRelatedByCostAccIdPartial = true;
                    }

                    $collProjectsRelatedByCostAccId->getInternalIterator()->rewind();

                    return $collProjectsRelatedByCostAccId;
                }

                if ($partial && $this->collProjectsRelatedByCostAccId) {
                    foreach ($this->collProjectsRelatedByCostAccId as $obj) {
                        if ($obj->isNew()) {
                            $collProjectsRelatedByCostAccId[] = $obj;
                        }
                    }
                }

                $this->collProjectsRelatedByCostAccId = $collProjectsRelatedByCostAccId;
                $this->collProjectsRelatedByCostAccIdPartial = false;
            }
        }

        return $this->collProjectsRelatedByCostAccId;
    }

    /**
     * Sets a collection of ProjectRelatedByCostAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $projectsRelatedByCostAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setProjectsRelatedByCostAccId(PropelCollection $projectsRelatedByCostAccId, PropelPDO $con = null)
    {
        $projectsRelatedByCostAccIdToDelete = $this->getProjectsRelatedByCostAccId(new Criteria(), $con)->diff($projectsRelatedByCostAccId);


        $this->projectsRelatedByCostAccIdScheduledForDeletion = $projectsRelatedByCostAccIdToDelete;

        foreach ($projectsRelatedByCostAccIdToDelete as $projectRelatedByCostAccIdRemoved) {
            $projectRelatedByCostAccIdRemoved->setCostAcc(null);
        }

        $this->collProjectsRelatedByCostAccId = null;
        foreach ($projectsRelatedByCostAccId as $projectRelatedByCostAccId) {
            $this->addProjectRelatedByCostAccId($projectRelatedByCostAccId);
        }

        $this->collProjectsRelatedByCostAccId = $projectsRelatedByCostAccId;
        $this->collProjectsRelatedByCostAccIdPartial = false;

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
    public function countProjectsRelatedByCostAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProjectsRelatedByCostAccIdPartial && !$this->isNew();
        if (null === $this->collProjectsRelatedByCostAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProjectsRelatedByCostAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProjectsRelatedByCostAccId());
            }
            $query = ProjectQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCostAcc($this)
                ->count($con);
        }

        return count($this->collProjectsRelatedByCostAccId);
    }

    /**
     * Method called to associate a Project object to this object
     * through the Project foreign key attribute.
     *
     * @param    Project $l Project
     * @return Account The current object (for fluent API support)
     */
    public function addProjectRelatedByCostAccId(Project $l)
    {
        if ($this->collProjectsRelatedByCostAccId === null) {
            $this->initProjectsRelatedByCostAccId();
            $this->collProjectsRelatedByCostAccIdPartial = true;
        }

        if (!in_array($l, $this->collProjectsRelatedByCostAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProjectRelatedByCostAccId($l);

            if ($this->projectsRelatedByCostAccIdScheduledForDeletion and $this->projectsRelatedByCostAccIdScheduledForDeletion->contains($l)) {
                $this->projectsRelatedByCostAccIdScheduledForDeletion->remove($this->projectsRelatedByCostAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProjectRelatedByCostAccId $projectRelatedByCostAccId The projectRelatedByCostAccId object to add.
     */
    protected function doAddProjectRelatedByCostAccId($projectRelatedByCostAccId)
    {
        $this->collProjectsRelatedByCostAccId[]= $projectRelatedByCostAccId;
        $projectRelatedByCostAccId->setCostAcc($this);
    }

    /**
     * @param	ProjectRelatedByCostAccId $projectRelatedByCostAccId The projectRelatedByCostAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeProjectRelatedByCostAccId($projectRelatedByCostAccId)
    {
        if ($this->getProjectsRelatedByCostAccId()->contains($projectRelatedByCostAccId)) {
            $this->collProjectsRelatedByCostAccId->remove($this->collProjectsRelatedByCostAccId->search($projectRelatedByCostAccId));
            if (null === $this->projectsRelatedByCostAccIdScheduledForDeletion) {
                $this->projectsRelatedByCostAccIdScheduledForDeletion = clone $this->collProjectsRelatedByCostAccId;
                $this->projectsRelatedByCostAccIdScheduledForDeletion->clear();
            }
            $this->projectsRelatedByCostAccIdScheduledForDeletion[]= $projectRelatedByCostAccId;
            $projectRelatedByCostAccId->setCostAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByCostAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByCostAccIdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getProjectsRelatedByCostAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByCostAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByCostAccIdJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getProjectsRelatedByCostAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByCostAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByCostAccIdJoinCostFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('CostFileCat', $join_behavior);

        return $this->getProjectsRelatedByCostAccId($query, $con);
    }

    /**
     * Clears out the collProjectsRelatedByBankAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addProjectsRelatedByBankAccId()
     */
    public function clearProjectsRelatedByBankAccId()
    {
        $this->collProjectsRelatedByBankAccId = null; // important to set this to null since that means it is uninitialized
        $this->collProjectsRelatedByBankAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collProjectsRelatedByBankAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialProjectsRelatedByBankAccId($v = true)
    {
        $this->collProjectsRelatedByBankAccIdPartial = $v;
    }

    /**
     * Initializes the collProjectsRelatedByBankAccId collection.
     *
     * By default this just sets the collProjectsRelatedByBankAccId collection to an empty array (like clearcollProjectsRelatedByBankAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProjectsRelatedByBankAccId($overrideExisting = true)
    {
        if (null !== $this->collProjectsRelatedByBankAccId && !$overrideExisting) {
            return;
        }
        $this->collProjectsRelatedByBankAccId = new PropelObjectCollection();
        $this->collProjectsRelatedByBankAccId->setModel('Project');
    }

    /**
     * Gets an array of Project objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Project[] List of Project objects
     * @throws PropelException
     */
    public function getProjectsRelatedByBankAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProjectsRelatedByBankAccIdPartial && !$this->isNew();
        if (null === $this->collProjectsRelatedByBankAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProjectsRelatedByBankAccId) {
                // return empty collection
                $this->initProjectsRelatedByBankAccId();
            } else {
                $collProjectsRelatedByBankAccId = ProjectQuery::create(null, $criteria)
                    ->filterByBankAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProjectsRelatedByBankAccIdPartial && count($collProjectsRelatedByBankAccId)) {
                      $this->initProjectsRelatedByBankAccId(false);

                      foreach ($collProjectsRelatedByBankAccId as $obj) {
                        if (false == $this->collProjectsRelatedByBankAccId->contains($obj)) {
                          $this->collProjectsRelatedByBankAccId->append($obj);
                        }
                      }

                      $this->collProjectsRelatedByBankAccIdPartial = true;
                    }

                    $collProjectsRelatedByBankAccId->getInternalIterator()->rewind();

                    return $collProjectsRelatedByBankAccId;
                }

                if ($partial && $this->collProjectsRelatedByBankAccId) {
                    foreach ($this->collProjectsRelatedByBankAccId as $obj) {
                        if ($obj->isNew()) {
                            $collProjectsRelatedByBankAccId[] = $obj;
                        }
                    }
                }

                $this->collProjectsRelatedByBankAccId = $collProjectsRelatedByBankAccId;
                $this->collProjectsRelatedByBankAccIdPartial = false;
            }
        }

        return $this->collProjectsRelatedByBankAccId;
    }

    /**
     * Sets a collection of ProjectRelatedByBankAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $projectsRelatedByBankAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setProjectsRelatedByBankAccId(PropelCollection $projectsRelatedByBankAccId, PropelPDO $con = null)
    {
        $projectsRelatedByBankAccIdToDelete = $this->getProjectsRelatedByBankAccId(new Criteria(), $con)->diff($projectsRelatedByBankAccId);


        $this->projectsRelatedByBankAccIdScheduledForDeletion = $projectsRelatedByBankAccIdToDelete;

        foreach ($projectsRelatedByBankAccIdToDelete as $projectRelatedByBankAccIdRemoved) {
            $projectRelatedByBankAccIdRemoved->setBankAcc(null);
        }

        $this->collProjectsRelatedByBankAccId = null;
        foreach ($projectsRelatedByBankAccId as $projectRelatedByBankAccId) {
            $this->addProjectRelatedByBankAccId($projectRelatedByBankAccId);
        }

        $this->collProjectsRelatedByBankAccId = $projectsRelatedByBankAccId;
        $this->collProjectsRelatedByBankAccIdPartial = false;

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
    public function countProjectsRelatedByBankAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProjectsRelatedByBankAccIdPartial && !$this->isNew();
        if (null === $this->collProjectsRelatedByBankAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProjectsRelatedByBankAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProjectsRelatedByBankAccId());
            }
            $query = ProjectQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBankAcc($this)
                ->count($con);
        }

        return count($this->collProjectsRelatedByBankAccId);
    }

    /**
     * Method called to associate a Project object to this object
     * through the Project foreign key attribute.
     *
     * @param    Project $l Project
     * @return Account The current object (for fluent API support)
     */
    public function addProjectRelatedByBankAccId(Project $l)
    {
        if ($this->collProjectsRelatedByBankAccId === null) {
            $this->initProjectsRelatedByBankAccId();
            $this->collProjectsRelatedByBankAccIdPartial = true;
        }

        if (!in_array($l, $this->collProjectsRelatedByBankAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProjectRelatedByBankAccId($l);

            if ($this->projectsRelatedByBankAccIdScheduledForDeletion and $this->projectsRelatedByBankAccIdScheduledForDeletion->contains($l)) {
                $this->projectsRelatedByBankAccIdScheduledForDeletion->remove($this->projectsRelatedByBankAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ProjectRelatedByBankAccId $projectRelatedByBankAccId The projectRelatedByBankAccId object to add.
     */
    protected function doAddProjectRelatedByBankAccId($projectRelatedByBankAccId)
    {
        $this->collProjectsRelatedByBankAccId[]= $projectRelatedByBankAccId;
        $projectRelatedByBankAccId->setBankAcc($this);
    }

    /**
     * @param	ProjectRelatedByBankAccId $projectRelatedByBankAccId The projectRelatedByBankAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeProjectRelatedByBankAccId($projectRelatedByBankAccId)
    {
        if ($this->getProjectsRelatedByBankAccId()->contains($projectRelatedByBankAccId)) {
            $this->collProjectsRelatedByBankAccId->remove($this->collProjectsRelatedByBankAccId->search($projectRelatedByBankAccId));
            if (null === $this->projectsRelatedByBankAccIdScheduledForDeletion) {
                $this->projectsRelatedByBankAccIdScheduledForDeletion = clone $this->collProjectsRelatedByBankAccId;
                $this->projectsRelatedByBankAccIdScheduledForDeletion->clear();
            }
            $this->projectsRelatedByBankAccIdScheduledForDeletion[]= $projectRelatedByBankAccId;
            $projectRelatedByBankAccId->setBankAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByBankAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByBankAccIdJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getProjectsRelatedByBankAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByBankAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByBankAccIdJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getProjectsRelatedByBankAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related ProjectsRelatedByBankAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsRelatedByBankAccIdJoinCostFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('CostFileCat', $join_behavior);

        return $this->getProjectsRelatedByBankAccId($query, $con);
    }

    /**
     * Clears out the collIncomesRelatedByBankAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addIncomesRelatedByBankAccId()
     */
    public function clearIncomesRelatedByBankAccId()
    {
        $this->collIncomesRelatedByBankAccId = null; // important to set this to null since that means it is uninitialized
        $this->collIncomesRelatedByBankAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collIncomesRelatedByBankAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialIncomesRelatedByBankAccId($v = true)
    {
        $this->collIncomesRelatedByBankAccIdPartial = $v;
    }

    /**
     * Initializes the collIncomesRelatedByBankAccId collection.
     *
     * By default this just sets the collIncomesRelatedByBankAccId collection to an empty array (like clearcollIncomesRelatedByBankAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIncomesRelatedByBankAccId($overrideExisting = true)
    {
        if (null !== $this->collIncomesRelatedByBankAccId && !$overrideExisting) {
            return;
        }
        $this->collIncomesRelatedByBankAccId = new PropelObjectCollection();
        $this->collIncomesRelatedByBankAccId->setModel('Income');
    }

    /**
     * Gets an array of Income objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Income[] List of Income objects
     * @throws PropelException
     */
    public function getIncomesRelatedByBankAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIncomesRelatedByBankAccIdPartial && !$this->isNew();
        if (null === $this->collIncomesRelatedByBankAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIncomesRelatedByBankAccId) {
                // return empty collection
                $this->initIncomesRelatedByBankAccId();
            } else {
                $collIncomesRelatedByBankAccId = IncomeQuery::create(null, $criteria)
                    ->filterByBankAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collIncomesRelatedByBankAccIdPartial && count($collIncomesRelatedByBankAccId)) {
                      $this->initIncomesRelatedByBankAccId(false);

                      foreach ($collIncomesRelatedByBankAccId as $obj) {
                        if (false == $this->collIncomesRelatedByBankAccId->contains($obj)) {
                          $this->collIncomesRelatedByBankAccId->append($obj);
                        }
                      }

                      $this->collIncomesRelatedByBankAccIdPartial = true;
                    }

                    $collIncomesRelatedByBankAccId->getInternalIterator()->rewind();

                    return $collIncomesRelatedByBankAccId;
                }

                if ($partial && $this->collIncomesRelatedByBankAccId) {
                    foreach ($this->collIncomesRelatedByBankAccId as $obj) {
                        if ($obj->isNew()) {
                            $collIncomesRelatedByBankAccId[] = $obj;
                        }
                    }
                }

                $this->collIncomesRelatedByBankAccId = $collIncomesRelatedByBankAccId;
                $this->collIncomesRelatedByBankAccIdPartial = false;
            }
        }

        return $this->collIncomesRelatedByBankAccId;
    }

    /**
     * Sets a collection of IncomeRelatedByBankAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $incomesRelatedByBankAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setIncomesRelatedByBankAccId(PropelCollection $incomesRelatedByBankAccId, PropelPDO $con = null)
    {
        $incomesRelatedByBankAccIdToDelete = $this->getIncomesRelatedByBankAccId(new Criteria(), $con)->diff($incomesRelatedByBankAccId);


        $this->incomesRelatedByBankAccIdScheduledForDeletion = $incomesRelatedByBankAccIdToDelete;

        foreach ($incomesRelatedByBankAccIdToDelete as $incomeRelatedByBankAccIdRemoved) {
            $incomeRelatedByBankAccIdRemoved->setBankAcc(null);
        }

        $this->collIncomesRelatedByBankAccId = null;
        foreach ($incomesRelatedByBankAccId as $incomeRelatedByBankAccId) {
            $this->addIncomeRelatedByBankAccId($incomeRelatedByBankAccId);
        }

        $this->collIncomesRelatedByBankAccId = $incomesRelatedByBankAccId;
        $this->collIncomesRelatedByBankAccIdPartial = false;

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
    public function countIncomesRelatedByBankAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collIncomesRelatedByBankAccIdPartial && !$this->isNew();
        if (null === $this->collIncomesRelatedByBankAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIncomesRelatedByBankAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIncomesRelatedByBankAccId());
            }
            $query = IncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBankAcc($this)
                ->count($con);
        }

        return count($this->collIncomesRelatedByBankAccId);
    }

    /**
     * Method called to associate a Income object to this object
     * through the Income foreign key attribute.
     *
     * @param    Income $l Income
     * @return Account The current object (for fluent API support)
     */
    public function addIncomeRelatedByBankAccId(Income $l)
    {
        if ($this->collIncomesRelatedByBankAccId === null) {
            $this->initIncomesRelatedByBankAccId();
            $this->collIncomesRelatedByBankAccIdPartial = true;
        }

        if (!in_array($l, $this->collIncomesRelatedByBankAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIncomeRelatedByBankAccId($l);

            if ($this->incomesRelatedByBankAccIdScheduledForDeletion and $this->incomesRelatedByBankAccIdScheduledForDeletion->contains($l)) {
                $this->incomesRelatedByBankAccIdScheduledForDeletion->remove($this->incomesRelatedByBankAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	IncomeRelatedByBankAccId $incomeRelatedByBankAccId The incomeRelatedByBankAccId object to add.
     */
    protected function doAddIncomeRelatedByBankAccId($incomeRelatedByBankAccId)
    {
        $this->collIncomesRelatedByBankAccId[]= $incomeRelatedByBankAccId;
        $incomeRelatedByBankAccId->setBankAcc($this);
    }

    /**
     * @param	IncomeRelatedByBankAccId $incomeRelatedByBankAccId The incomeRelatedByBankAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeIncomeRelatedByBankAccId($incomeRelatedByBankAccId)
    {
        if ($this->getIncomesRelatedByBankAccId()->contains($incomeRelatedByBankAccId)) {
            $this->collIncomesRelatedByBankAccId->remove($this->collIncomesRelatedByBankAccId->search($incomeRelatedByBankAccId));
            if (null === $this->incomesRelatedByBankAccIdScheduledForDeletion) {
                $this->incomesRelatedByBankAccIdScheduledForDeletion = clone $this->collIncomesRelatedByBankAccId;
                $this->incomesRelatedByBankAccIdScheduledForDeletion->clear();
            }
            $this->incomesRelatedByBankAccIdScheduledForDeletion[]= $incomeRelatedByBankAccId;
            $incomeRelatedByBankAccId->setBankAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related IncomesRelatedByBankAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesRelatedByBankAccIdJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getIncomesRelatedByBankAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related IncomesRelatedByBankAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesRelatedByBankAccIdJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getIncomesRelatedByBankAccId($query, $con);
    }

    /**
     * Clears out the collIncomesRelatedByIncomeAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addIncomesRelatedByIncomeAccId()
     */
    public function clearIncomesRelatedByIncomeAccId()
    {
        $this->collIncomesRelatedByIncomeAccId = null; // important to set this to null since that means it is uninitialized
        $this->collIncomesRelatedByIncomeAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collIncomesRelatedByIncomeAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialIncomesRelatedByIncomeAccId($v = true)
    {
        $this->collIncomesRelatedByIncomeAccIdPartial = $v;
    }

    /**
     * Initializes the collIncomesRelatedByIncomeAccId collection.
     *
     * By default this just sets the collIncomesRelatedByIncomeAccId collection to an empty array (like clearcollIncomesRelatedByIncomeAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIncomesRelatedByIncomeAccId($overrideExisting = true)
    {
        if (null !== $this->collIncomesRelatedByIncomeAccId && !$overrideExisting) {
            return;
        }
        $this->collIncomesRelatedByIncomeAccId = new PropelObjectCollection();
        $this->collIncomesRelatedByIncomeAccId->setModel('Income');
    }

    /**
     * Gets an array of Income objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Income[] List of Income objects
     * @throws PropelException
     */
    public function getIncomesRelatedByIncomeAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIncomesRelatedByIncomeAccIdPartial && !$this->isNew();
        if (null === $this->collIncomesRelatedByIncomeAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIncomesRelatedByIncomeAccId) {
                // return empty collection
                $this->initIncomesRelatedByIncomeAccId();
            } else {
                $collIncomesRelatedByIncomeAccId = IncomeQuery::create(null, $criteria)
                    ->filterByIncomeAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collIncomesRelatedByIncomeAccIdPartial && count($collIncomesRelatedByIncomeAccId)) {
                      $this->initIncomesRelatedByIncomeAccId(false);

                      foreach ($collIncomesRelatedByIncomeAccId as $obj) {
                        if (false == $this->collIncomesRelatedByIncomeAccId->contains($obj)) {
                          $this->collIncomesRelatedByIncomeAccId->append($obj);
                        }
                      }

                      $this->collIncomesRelatedByIncomeAccIdPartial = true;
                    }

                    $collIncomesRelatedByIncomeAccId->getInternalIterator()->rewind();

                    return $collIncomesRelatedByIncomeAccId;
                }

                if ($partial && $this->collIncomesRelatedByIncomeAccId) {
                    foreach ($this->collIncomesRelatedByIncomeAccId as $obj) {
                        if ($obj->isNew()) {
                            $collIncomesRelatedByIncomeAccId[] = $obj;
                        }
                    }
                }

                $this->collIncomesRelatedByIncomeAccId = $collIncomesRelatedByIncomeAccId;
                $this->collIncomesRelatedByIncomeAccIdPartial = false;
            }
        }

        return $this->collIncomesRelatedByIncomeAccId;
    }

    /**
     * Sets a collection of IncomeRelatedByIncomeAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $incomesRelatedByIncomeAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setIncomesRelatedByIncomeAccId(PropelCollection $incomesRelatedByIncomeAccId, PropelPDO $con = null)
    {
        $incomesRelatedByIncomeAccIdToDelete = $this->getIncomesRelatedByIncomeAccId(new Criteria(), $con)->diff($incomesRelatedByIncomeAccId);


        $this->incomesRelatedByIncomeAccIdScheduledForDeletion = $incomesRelatedByIncomeAccIdToDelete;

        foreach ($incomesRelatedByIncomeAccIdToDelete as $incomeRelatedByIncomeAccIdRemoved) {
            $incomeRelatedByIncomeAccIdRemoved->setIncomeAcc(null);
        }

        $this->collIncomesRelatedByIncomeAccId = null;
        foreach ($incomesRelatedByIncomeAccId as $incomeRelatedByIncomeAccId) {
            $this->addIncomeRelatedByIncomeAccId($incomeRelatedByIncomeAccId);
        }

        $this->collIncomesRelatedByIncomeAccId = $incomesRelatedByIncomeAccId;
        $this->collIncomesRelatedByIncomeAccIdPartial = false;

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
    public function countIncomesRelatedByIncomeAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collIncomesRelatedByIncomeAccIdPartial && !$this->isNew();
        if (null === $this->collIncomesRelatedByIncomeAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIncomesRelatedByIncomeAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIncomesRelatedByIncomeAccId());
            }
            $query = IncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByIncomeAcc($this)
                ->count($con);
        }

        return count($this->collIncomesRelatedByIncomeAccId);
    }

    /**
     * Method called to associate a Income object to this object
     * through the Income foreign key attribute.
     *
     * @param    Income $l Income
     * @return Account The current object (for fluent API support)
     */
    public function addIncomeRelatedByIncomeAccId(Income $l)
    {
        if ($this->collIncomesRelatedByIncomeAccId === null) {
            $this->initIncomesRelatedByIncomeAccId();
            $this->collIncomesRelatedByIncomeAccIdPartial = true;
        }

        if (!in_array($l, $this->collIncomesRelatedByIncomeAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIncomeRelatedByIncomeAccId($l);

            if ($this->incomesRelatedByIncomeAccIdScheduledForDeletion and $this->incomesRelatedByIncomeAccIdScheduledForDeletion->contains($l)) {
                $this->incomesRelatedByIncomeAccIdScheduledForDeletion->remove($this->incomesRelatedByIncomeAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	IncomeRelatedByIncomeAccId $incomeRelatedByIncomeAccId The incomeRelatedByIncomeAccId object to add.
     */
    protected function doAddIncomeRelatedByIncomeAccId($incomeRelatedByIncomeAccId)
    {
        $this->collIncomesRelatedByIncomeAccId[]= $incomeRelatedByIncomeAccId;
        $incomeRelatedByIncomeAccId->setIncomeAcc($this);
    }

    /**
     * @param	IncomeRelatedByIncomeAccId $incomeRelatedByIncomeAccId The incomeRelatedByIncomeAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeIncomeRelatedByIncomeAccId($incomeRelatedByIncomeAccId)
    {
        if ($this->getIncomesRelatedByIncomeAccId()->contains($incomeRelatedByIncomeAccId)) {
            $this->collIncomesRelatedByIncomeAccId->remove($this->collIncomesRelatedByIncomeAccId->search($incomeRelatedByIncomeAccId));
            if (null === $this->incomesRelatedByIncomeAccIdScheduledForDeletion) {
                $this->incomesRelatedByIncomeAccIdScheduledForDeletion = clone $this->collIncomesRelatedByIncomeAccId;
                $this->incomesRelatedByIncomeAccIdScheduledForDeletion->clear();
            }
            $this->incomesRelatedByIncomeAccIdScheduledForDeletion[]= $incomeRelatedByIncomeAccId;
            $incomeRelatedByIncomeAccId->setIncomeAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related IncomesRelatedByIncomeAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesRelatedByIncomeAccIdJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getIncomesRelatedByIncomeAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related IncomesRelatedByIncomeAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesRelatedByIncomeAccIdJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getIncomesRelatedByIncomeAccId($query, $con);
    }

    /**
     * Clears out the collCostsRelatedByBankAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addCostsRelatedByBankAccId()
     */
    public function clearCostsRelatedByBankAccId()
    {
        $this->collCostsRelatedByBankAccId = null; // important to set this to null since that means it is uninitialized
        $this->collCostsRelatedByBankAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collCostsRelatedByBankAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialCostsRelatedByBankAccId($v = true)
    {
        $this->collCostsRelatedByBankAccIdPartial = $v;
    }

    /**
     * Initializes the collCostsRelatedByBankAccId collection.
     *
     * By default this just sets the collCostsRelatedByBankAccId collection to an empty array (like clearcollCostsRelatedByBankAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCostsRelatedByBankAccId($overrideExisting = true)
    {
        if (null !== $this->collCostsRelatedByBankAccId && !$overrideExisting) {
            return;
        }
        $this->collCostsRelatedByBankAccId = new PropelObjectCollection();
        $this->collCostsRelatedByBankAccId->setModel('Cost');
    }

    /**
     * Gets an array of Cost objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Cost[] List of Cost objects
     * @throws PropelException
     */
    public function getCostsRelatedByBankAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostsRelatedByBankAccIdPartial && !$this->isNew();
        if (null === $this->collCostsRelatedByBankAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostsRelatedByBankAccId) {
                // return empty collection
                $this->initCostsRelatedByBankAccId();
            } else {
                $collCostsRelatedByBankAccId = CostQuery::create(null, $criteria)
                    ->filterByBankAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostsRelatedByBankAccIdPartial && count($collCostsRelatedByBankAccId)) {
                      $this->initCostsRelatedByBankAccId(false);

                      foreach ($collCostsRelatedByBankAccId as $obj) {
                        if (false == $this->collCostsRelatedByBankAccId->contains($obj)) {
                          $this->collCostsRelatedByBankAccId->append($obj);
                        }
                      }

                      $this->collCostsRelatedByBankAccIdPartial = true;
                    }

                    $collCostsRelatedByBankAccId->getInternalIterator()->rewind();

                    return $collCostsRelatedByBankAccId;
                }

                if ($partial && $this->collCostsRelatedByBankAccId) {
                    foreach ($this->collCostsRelatedByBankAccId as $obj) {
                        if ($obj->isNew()) {
                            $collCostsRelatedByBankAccId[] = $obj;
                        }
                    }
                }

                $this->collCostsRelatedByBankAccId = $collCostsRelatedByBankAccId;
                $this->collCostsRelatedByBankAccIdPartial = false;
            }
        }

        return $this->collCostsRelatedByBankAccId;
    }

    /**
     * Sets a collection of CostRelatedByBankAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costsRelatedByBankAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setCostsRelatedByBankAccId(PropelCollection $costsRelatedByBankAccId, PropelPDO $con = null)
    {
        $costsRelatedByBankAccIdToDelete = $this->getCostsRelatedByBankAccId(new Criteria(), $con)->diff($costsRelatedByBankAccId);


        $this->costsRelatedByBankAccIdScheduledForDeletion = $costsRelatedByBankAccIdToDelete;

        foreach ($costsRelatedByBankAccIdToDelete as $costRelatedByBankAccIdRemoved) {
            $costRelatedByBankAccIdRemoved->setBankAcc(null);
        }

        $this->collCostsRelatedByBankAccId = null;
        foreach ($costsRelatedByBankAccId as $costRelatedByBankAccId) {
            $this->addCostRelatedByBankAccId($costRelatedByBankAccId);
        }

        $this->collCostsRelatedByBankAccId = $costsRelatedByBankAccId;
        $this->collCostsRelatedByBankAccIdPartial = false;

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
    public function countCostsRelatedByBankAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostsRelatedByBankAccIdPartial && !$this->isNew();
        if (null === $this->collCostsRelatedByBankAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCostsRelatedByBankAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCostsRelatedByBankAccId());
            }
            $query = CostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByBankAcc($this)
                ->count($con);
        }

        return count($this->collCostsRelatedByBankAccId);
    }

    /**
     * Method called to associate a Cost object to this object
     * through the Cost foreign key attribute.
     *
     * @param    Cost $l Cost
     * @return Account The current object (for fluent API support)
     */
    public function addCostRelatedByBankAccId(Cost $l)
    {
        if ($this->collCostsRelatedByBankAccId === null) {
            $this->initCostsRelatedByBankAccId();
            $this->collCostsRelatedByBankAccIdPartial = true;
        }

        if (!in_array($l, $this->collCostsRelatedByBankAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCostRelatedByBankAccId($l);

            if ($this->costsRelatedByBankAccIdScheduledForDeletion and $this->costsRelatedByBankAccIdScheduledForDeletion->contains($l)) {
                $this->costsRelatedByBankAccIdScheduledForDeletion->remove($this->costsRelatedByBankAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CostRelatedByBankAccId $costRelatedByBankAccId The costRelatedByBankAccId object to add.
     */
    protected function doAddCostRelatedByBankAccId($costRelatedByBankAccId)
    {
        $this->collCostsRelatedByBankAccId[]= $costRelatedByBankAccId;
        $costRelatedByBankAccId->setBankAcc($this);
    }

    /**
     * @param	CostRelatedByBankAccId $costRelatedByBankAccId The costRelatedByBankAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeCostRelatedByBankAccId($costRelatedByBankAccId)
    {
        if ($this->getCostsRelatedByBankAccId()->contains($costRelatedByBankAccId)) {
            $this->collCostsRelatedByBankAccId->remove($this->collCostsRelatedByBankAccId->search($costRelatedByBankAccId));
            if (null === $this->costsRelatedByBankAccIdScheduledForDeletion) {
                $this->costsRelatedByBankAccIdScheduledForDeletion = clone $this->collCostsRelatedByBankAccId;
                $this->costsRelatedByBankAccIdScheduledForDeletion->clear();
            }
            $this->costsRelatedByBankAccIdScheduledForDeletion[]= $costRelatedByBankAccId;
            $costRelatedByBankAccId->setBankAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related CostsRelatedByBankAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsRelatedByBankAccIdJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getCostsRelatedByBankAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related CostsRelatedByBankAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsRelatedByBankAccIdJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getCostsRelatedByBankAccId($query, $con);
    }

    /**
     * Clears out the collCostsRelatedByCostAccId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Account The current object (for fluent API support)
     * @see        addCostsRelatedByCostAccId()
     */
    public function clearCostsRelatedByCostAccId()
    {
        $this->collCostsRelatedByCostAccId = null; // important to set this to null since that means it is uninitialized
        $this->collCostsRelatedByCostAccIdPartial = null;

        return $this;
    }

    /**
     * reset is the collCostsRelatedByCostAccId collection loaded partially
     *
     * @return void
     */
    public function resetPartialCostsRelatedByCostAccId($v = true)
    {
        $this->collCostsRelatedByCostAccIdPartial = $v;
    }

    /**
     * Initializes the collCostsRelatedByCostAccId collection.
     *
     * By default this just sets the collCostsRelatedByCostAccId collection to an empty array (like clearcollCostsRelatedByCostAccId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCostsRelatedByCostAccId($overrideExisting = true)
    {
        if (null !== $this->collCostsRelatedByCostAccId && !$overrideExisting) {
            return;
        }
        $this->collCostsRelatedByCostAccId = new PropelObjectCollection();
        $this->collCostsRelatedByCostAccId->setModel('Cost');
    }

    /**
     * Gets an array of Cost objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Account is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Cost[] List of Cost objects
     * @throws PropelException
     */
    public function getCostsRelatedByCostAccId($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostsRelatedByCostAccIdPartial && !$this->isNew();
        if (null === $this->collCostsRelatedByCostAccId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostsRelatedByCostAccId) {
                // return empty collection
                $this->initCostsRelatedByCostAccId();
            } else {
                $collCostsRelatedByCostAccId = CostQuery::create(null, $criteria)
                    ->filterByCostAcc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostsRelatedByCostAccIdPartial && count($collCostsRelatedByCostAccId)) {
                      $this->initCostsRelatedByCostAccId(false);

                      foreach ($collCostsRelatedByCostAccId as $obj) {
                        if (false == $this->collCostsRelatedByCostAccId->contains($obj)) {
                          $this->collCostsRelatedByCostAccId->append($obj);
                        }
                      }

                      $this->collCostsRelatedByCostAccIdPartial = true;
                    }

                    $collCostsRelatedByCostAccId->getInternalIterator()->rewind();

                    return $collCostsRelatedByCostAccId;
                }

                if ($partial && $this->collCostsRelatedByCostAccId) {
                    foreach ($this->collCostsRelatedByCostAccId as $obj) {
                        if ($obj->isNew()) {
                            $collCostsRelatedByCostAccId[] = $obj;
                        }
                    }
                }

                $this->collCostsRelatedByCostAccId = $collCostsRelatedByCostAccId;
                $this->collCostsRelatedByCostAccIdPartial = false;
            }
        }

        return $this->collCostsRelatedByCostAccId;
    }

    /**
     * Sets a collection of CostRelatedByCostAccId objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costsRelatedByCostAccId A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Account The current object (for fluent API support)
     */
    public function setCostsRelatedByCostAccId(PropelCollection $costsRelatedByCostAccId, PropelPDO $con = null)
    {
        $costsRelatedByCostAccIdToDelete = $this->getCostsRelatedByCostAccId(new Criteria(), $con)->diff($costsRelatedByCostAccId);


        $this->costsRelatedByCostAccIdScheduledForDeletion = $costsRelatedByCostAccIdToDelete;

        foreach ($costsRelatedByCostAccIdToDelete as $costRelatedByCostAccIdRemoved) {
            $costRelatedByCostAccIdRemoved->setCostAcc(null);
        }

        $this->collCostsRelatedByCostAccId = null;
        foreach ($costsRelatedByCostAccId as $costRelatedByCostAccId) {
            $this->addCostRelatedByCostAccId($costRelatedByCostAccId);
        }

        $this->collCostsRelatedByCostAccId = $costsRelatedByCostAccId;
        $this->collCostsRelatedByCostAccIdPartial = false;

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
    public function countCostsRelatedByCostAccId(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostsRelatedByCostAccIdPartial && !$this->isNew();
        if (null === $this->collCostsRelatedByCostAccId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCostsRelatedByCostAccId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCostsRelatedByCostAccId());
            }
            $query = CostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCostAcc($this)
                ->count($con);
        }

        return count($this->collCostsRelatedByCostAccId);
    }

    /**
     * Method called to associate a Cost object to this object
     * through the Cost foreign key attribute.
     *
     * @param    Cost $l Cost
     * @return Account The current object (for fluent API support)
     */
    public function addCostRelatedByCostAccId(Cost $l)
    {
        if ($this->collCostsRelatedByCostAccId === null) {
            $this->initCostsRelatedByCostAccId();
            $this->collCostsRelatedByCostAccIdPartial = true;
        }

        if (!in_array($l, $this->collCostsRelatedByCostAccId->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCostRelatedByCostAccId($l);

            if ($this->costsRelatedByCostAccIdScheduledForDeletion and $this->costsRelatedByCostAccIdScheduledForDeletion->contains($l)) {
                $this->costsRelatedByCostAccIdScheduledForDeletion->remove($this->costsRelatedByCostAccIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CostRelatedByCostAccId $costRelatedByCostAccId The costRelatedByCostAccId object to add.
     */
    protected function doAddCostRelatedByCostAccId($costRelatedByCostAccId)
    {
        $this->collCostsRelatedByCostAccId[]= $costRelatedByCostAccId;
        $costRelatedByCostAccId->setCostAcc($this);
    }

    /**
     * @param	CostRelatedByCostAccId $costRelatedByCostAccId The costRelatedByCostAccId object to remove.
     * @return Account The current object (for fluent API support)
     */
    public function removeCostRelatedByCostAccId($costRelatedByCostAccId)
    {
        if ($this->getCostsRelatedByCostAccId()->contains($costRelatedByCostAccId)) {
            $this->collCostsRelatedByCostAccId->remove($this->collCostsRelatedByCostAccId->search($costRelatedByCostAccId));
            if (null === $this->costsRelatedByCostAccIdScheduledForDeletion) {
                $this->costsRelatedByCostAccIdScheduledForDeletion = clone $this->collCostsRelatedByCostAccId;
                $this->costsRelatedByCostAccIdScheduledForDeletion->clear();
            }
            $this->costsRelatedByCostAccIdScheduledForDeletion[]= $costRelatedByCostAccId;
            $costRelatedByCostAccId->setCostAcc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related CostsRelatedByCostAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsRelatedByCostAccIdJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getCostsRelatedByCostAccId($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Account is new, it will return
     * an empty collection; or if this Account has previously
     * been saved, it will retrieve related CostsRelatedByCostAccId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Account.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsRelatedByCostAccIdJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getCostsRelatedByCostAccId($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->acc_no = null;
        $this->name = null;
        $this->report_side = null;
        $this->as_bank_acc = null;
        $this->as_income = null;
        $this->as_cost = null;
        $this->inc_open_b = null;
        $this->inc_close_b = null;
        $this->as_close_b = null;
        $this->year_id = null;
        $this->file_cat_lev1_id = null;
        $this->file_cat_lev2_id = null;
        $this->file_cat_lev3_id = null;
        $this->tree_left = null;
        $this->tree_right = null;
        $this->tree_level = null;
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
            if ($this->collDocCatsRelatedByCommitmentAccId) {
                foreach ($this->collDocCatsRelatedByCommitmentAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDocCatsRelatedByTaxCommitmentAccId) {
                foreach ($this->collDocCatsRelatedByTaxCommitmentAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBookkEntries) {
                foreach ($this->collBookkEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProjectsRelatedByIncomeAccId) {
                foreach ($this->collProjectsRelatedByIncomeAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProjectsRelatedByCostAccId) {
                foreach ($this->collProjectsRelatedByCostAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProjectsRelatedByBankAccId) {
                foreach ($this->collProjectsRelatedByBankAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collIncomesRelatedByBankAccId) {
                foreach ($this->collIncomesRelatedByBankAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collIncomesRelatedByIncomeAccId) {
                foreach ($this->collIncomesRelatedByIncomeAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCostsRelatedByBankAccId) {
                foreach ($this->collCostsRelatedByBankAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCostsRelatedByCostAccId) {
                foreach ($this->collCostsRelatedByCostAccId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aYear instanceof Persistent) {
              $this->aYear->clearAllReferences($deep);
            }
            if ($this->aFileCatLev1 instanceof Persistent) {
              $this->aFileCatLev1->clearAllReferences($deep);
            }
            if ($this->aFileCatLev2 instanceof Persistent) {
              $this->aFileCatLev2->clearAllReferences($deep);
            }
            if ($this->aFileCatLev3 instanceof Persistent) {
              $this->aFileCatLev3->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        // nested_set behavior
        $this->collNestedSetChildren = null;
        $this->aNestedSetParent = null;
        if ($this->collDocCatsRelatedByCommitmentAccId instanceof PropelCollection) {
            $this->collDocCatsRelatedByCommitmentAccId->clearIterator();
        }
        $this->collDocCatsRelatedByCommitmentAccId = null;
        if ($this->collDocCatsRelatedByTaxCommitmentAccId instanceof PropelCollection) {
            $this->collDocCatsRelatedByTaxCommitmentAccId->clearIterator();
        }
        $this->collDocCatsRelatedByTaxCommitmentAccId = null;
        if ($this->collBookkEntries instanceof PropelCollection) {
            $this->collBookkEntries->clearIterator();
        }
        $this->collBookkEntries = null;
        if ($this->collProjectsRelatedByIncomeAccId instanceof PropelCollection) {
            $this->collProjectsRelatedByIncomeAccId->clearIterator();
        }
        $this->collProjectsRelatedByIncomeAccId = null;
        if ($this->collProjectsRelatedByCostAccId instanceof PropelCollection) {
            $this->collProjectsRelatedByCostAccId->clearIterator();
        }
        $this->collProjectsRelatedByCostAccId = null;
        if ($this->collProjectsRelatedByBankAccId instanceof PropelCollection) {
            $this->collProjectsRelatedByBankAccId->clearIterator();
        }
        $this->collProjectsRelatedByBankAccId = null;
        if ($this->collIncomesRelatedByBankAccId instanceof PropelCollection) {
            $this->collIncomesRelatedByBankAccId->clearIterator();
        }
        $this->collIncomesRelatedByBankAccId = null;
        if ($this->collIncomesRelatedByIncomeAccId instanceof PropelCollection) {
            $this->collIncomesRelatedByIncomeAccId->clearIterator();
        }
        $this->collIncomesRelatedByIncomeAccId = null;
        if ($this->collCostsRelatedByBankAccId instanceof PropelCollection) {
            $this->collCostsRelatedByBankAccId->clearIterator();
        }
        $this->collCostsRelatedByBankAccId = null;
        if ($this->collCostsRelatedByCostAccId instanceof PropelCollection) {
            $this->collCostsRelatedByCostAccId->clearIterator();
        }
        $this->collCostsRelatedByCostAccId = null;
        $this->aYear = null;
        $this->aFileCatLev1 = null;
        $this->aFileCatLev2 = null;
        $this->aFileCatLev3 = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'acc_no' column
     */
    public function __toString()
    {
        return (string) $this->getAccNo();
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

    // nested_set behavior

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processNestedSetQueries($con)
    {
        foreach ($this->nestedSetQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->nestedSetQueries = array();
    }

    /**
     * Proxy getter method for the left value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set left value
     */
    public function getLeftValue()
    {
        return $this->tree_left;
    }

    /**
     * Proxy getter method for the right value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set right value
     */
    public function getRightValue()
    {
        return $this->tree_right;
    }

    /**
     * Proxy getter method for the level value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set level value
     */
    public function getLevel()
    {
        return $this->tree_level;
    }

    /**
     * Proxy getter method for the scope value of the nested set model.
     * It provides a generic way to get the value, whatever the actual column name is.
     *
     * @return     int The nested set scope value
     */
    public function getScopeValue()
    {
        return $this->year_id;
    }

    /**
     * Proxy setter method for the left value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set left value
     * @return     Account The current object (for fluent API support)
     */
    public function setLeftValue($v)
    {
        return $this->setTreeLeft($v);
    }

    /**
     * Proxy setter method for the right value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set right value
     * @return     Account The current object (for fluent API support)
     */
    public function setRightValue($v)
    {
        return $this->setTreeRight($v);
    }

    /**
     * Proxy setter method for the level value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set level value
     * @return     Account The current object (for fluent API support)
     */
    public function setLevel($v)
    {
        return $this->setTreeLevel($v);
    }

    /**
     * Proxy setter method for the scope value of the nested set model.
     * It provides a generic way to set the value, whatever the actual column name is.
     *
     * @param      int $v The nested set scope value
     * @return     Account The current object (for fluent API support)
     */
    public function setScopeValue($v)
    {
        return $this->setYearId($v);
    }

    /**
     * Creates the supplied node as the root node.
     *
     * @return     Account The current object (for fluent API support)
     * @throws     PropelException
     */
    public function makeRoot()
    {
        if ($this->getLeftValue() || $this->getRightValue()) {
            throw new PropelException('Cannot turn an existing node into a root node.');
        }

        $this->setLeftValue(1);
        $this->setRightValue(2);
        $this->setLevel(0);

        return $this;
    }

    /**
     * Tests if onbject is a node, i.e. if it is inserted in the tree
     *
     * @return     bool
     */
    public function isInTree()
    {
        return $this->getLeftValue() > 0 && $this->getRightValue() > $this->getLeftValue();
    }

    /**
     * Tests if node is a root
     *
     * @return     bool
     */
    public function isRoot()
    {
        return $this->isInTree() && $this->getLeftValue() == 1;
    }

    /**
     * Tests if node is a leaf
     *
     * @return     bool
     */
    public function isLeaf()
    {
        return $this->isInTree() &&  ($this->getRightValue() - $this->getLeftValue()) == 1;
    }

    /**
     * Tests if node is a descendant of another node
     *
     * @param      Account $node Propel node object
     * @return     bool
     */
    public function isDescendantOf($parent)
    {
        if ($this->getScopeValue() !== $parent->getScopeValue()) {
            return false; //since the `this` and $parent are in different scopes, there's no way that `this` is be a descendant of $parent.
        }

        return $this->isInTree() && $this->getLeftValue() > $parent->getLeftValue() && $this->getRightValue() < $parent->getRightValue();
    }

    /**
     * Tests if node is a ancestor of another node
     *
     * @param      Account $node Propel node object
     * @return     bool
     */
    public function isAncestorOf($child)
    {
        return $child->isDescendantOf($this);
    }

    /**
     * Tests if object has an ancestor
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasParent(PropelPDO $con = null)
    {
        return $this->getLevel() > 0;
    }

    /**
     * Sets the cache for parent node of the current object.
     * Warning: this does not move the current object in the tree.
     * Use moveTofirstChildOf() or moveToLastChildOf() for that purpose
     *
     * @param      Account $parent
     * @return     Account The current object, for fluid interface
     */
    public function setParent($parent = null)
    {
        $this->aNestedSetParent = $parent;

        return $this;
    }

    /**
     * Gets parent node for the current object if it exists
     * The result is cached so further calls to the same method don't issue any queries
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getParent(PropelPDO $con = null)
    {
        if ($this->aNestedSetParent === null && $this->hasParent()) {
            $this->aNestedSetParent = AccountQuery::create()
                ->ancestorsOf($this)
                ->orderByLevel(true)
                ->findOne($con);
        }

        return $this->aNestedSetParent;
    }

    /**
     * Determines if the node has previous sibling
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasPrevSibling(PropelPDO $con = null)
    {
        if (!AccountPeer::isValid($this)) {
            return false;
        }

        return AccountQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->inTree($this->getScopeValue())
            ->count($con) > 0;
    }

    /**
     * Gets previous sibling for the given node if it exists
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getPrevSibling(PropelPDO $con = null)
    {
        return AccountQuery::create()
            ->filterByTreeRight($this->getLeftValue() - 1)
            ->inTree($this->getScopeValue())
            ->findOne($con);
    }

    /**
     * Determines if the node has next sibling
     *
     * @param      PropelPDO $con Connection to use.
     * @return     bool
     */
    public function hasNextSibling(PropelPDO $con = null)
    {
        if (!AccountPeer::isValid($this)) {
            return false;
        }

        return AccountQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->inTree($this->getScopeValue())
            ->count($con) > 0;
    }

    /**
     * Gets next sibling for the given node if it exists
     *
     * @param      PropelPDO $con Connection to use.
     * @return     mixed 		Propel object if exists else false
     */
    public function getNextSibling(PropelPDO $con = null)
    {
        return AccountQuery::create()
            ->filterByTreeLeft($this->getRightValue() + 1)
            ->inTree($this->getScopeValue())
            ->findOne($con);
    }

    /**
     * Clears out the $collNestedSetChildren collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return     void
     */
    public function clearNestedSetChildren()
    {
        $this->collNestedSetChildren = null;
    }

    /**
     * Initializes the $collNestedSetChildren collection.
     *
     * @return     void
     */
    public function initNestedSetChildren()
    {
        $this->collNestedSetChildren = new PropelObjectCollection();
        $this->collNestedSetChildren->setModel('Account');
    }

    /**
     * Adds an element to the internal $collNestedSetChildren collection.
     * Beware that this doesn't insert a node in the tree.
     * This method is only used to facilitate children hydration.
     *
     * @param      Account $account
     *
     * @return     void
     */
    public function addNestedSetChild($account)
    {
        if ($this->collNestedSetChildren === null) {
            $this->initNestedSetChildren();
        }
        if (!in_array($account, $this->collNestedSetChildren->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->collNestedSetChildren[]= $account;
            $account->setParent($this);
        }
    }

    /**
     * Tests if node has children
     *
     * @return     bool
     */
    public function hasChildren()
    {
        return ($this->getRightValue() - $this->getLeftValue()) > 1;
    }

    /**
     * Gets the children of the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array     List of Account objects
     */
    public function getChildren($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                // return empty collection
                $this->initNestedSetChildren();
            } else {
                $collNestedSetChildren = AccountQuery::create(null, $criteria)
                  ->childrenOf($this)
                  ->orderByBranch()
                    ->find($con);
                if (null !== $criteria) {
                    return $collNestedSetChildren;
                }
                $this->collNestedSetChildren = $collNestedSetChildren;
            }
        }

        return $this->collNestedSetChildren;
    }

    /**
     * Gets number of children for the given node
     *
     * @param      Criteria  $criteria Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     int       Number of children
     */
    public function countChildren($criteria = null, PropelPDO $con = null)
    {
        if (null === $this->collNestedSetChildren || null !== $criteria) {
            if ($this->isLeaf() || ($this->isNew() && null === $this->collNestedSetChildren)) {
                return 0;
            } else {
                return AccountQuery::create(null, $criteria)
                    ->childrenOf($this)
                    ->count($con);
            }
        } else {
            return count($this->collNestedSetChildren);
        }
    }

    /**
     * Gets the first child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Account objects
     */
    public function getFirstChild($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return AccountQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch()
                ->findOne($con);
        }
    }

    /**
     * Gets the last child of the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Account objects
     */
    public function getLastChild($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return AccountQuery::create(null, $query)
                ->childrenOf($this)
                ->orderByBranch(true)
                ->findOne($con);
        }
    }

    /**
     * Gets the siblings of the given node
     *
     * @param      bool			$includeNode Whether to include the current node or not
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     *
     * @return     array 		List of Account objects
     */
    public function getSiblings($includeNode = false, $query = null, PropelPDO $con = null)
    {
        if ($this->isRoot()) {
            return array();
        } else {
             $query = AccountQuery::create(null, $query)
                    ->childrenOf($this->getParent($con))
                    ->orderByBranch();
            if (!$includeNode) {
                $query->prune($this);
            }

            return $query->find($con);
        }
    }

    /**
     * Gets descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Account objects
     */
    public function getDescendants($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            return array();
        } else {
            return AccountQuery::create(null, $query)
                ->descendantsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Gets number of descendants for the given node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     int 		Number of descendants
     */
    public function countDescendants($query = null, PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return 0;
        } else {
            return AccountQuery::create(null, $query)
                ->descendantsOf($this)
                ->count($con);
        }
    }

    /**
     * Gets descendants for the given node, plus the current node
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Account objects
     */
    public function getBranch($query = null, PropelPDO $con = null)
    {
        return AccountQuery::create(null, $query)
            ->branchOf($this)
            ->orderByBranch()
            ->find($con);
    }

    /**
     * Gets ancestors for the given node, starting with the root node
     * Use it for breadcrumb paths for instance
     *
     * @param      Criteria $query Criteria to filter results.
     * @param      PropelPDO $con Connection to use.
     * @return     array 		List of Account objects
     */
    public function getAncestors($query = null, PropelPDO $con = null)
    {
        if ($this->isRoot()) {
            // save one query
            return array();
        } else {
            return AccountQuery::create(null, $query)
                ->ancestorsOf($this)
                ->orderByBranch()
                ->find($con);
        }
    }

    /**
     * Inserts the given $child node as first child of current
     * The modifications in the current object and the tree
     * are not persisted until the child object is saved.
     *
     * @param      Account $child	Propel object for child node
     *
     * @return     Account The current Propel object
     */
    public function addChild(Account $child)
    {
        if ($this->isNew()) {
            throw new PropelException('A Account object must not be new to accept children.');
        }
        $child->insertAsFirstChildOf($this);

        return $this;
    }

    /**
     * Inserts the current node as first child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Account $parent	Propel object for parent node
     *
     * @return     Account The current Propel object
     */
    public function insertAsFirstChildOf($parent)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Account object must not already be in the tree to be inserted. Use the moveToFirstChildOf() instead.');
        }
        $left = $parent->getLeftValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);
        $scope = $parent->getScopeValue();
        $this->setScopeValue($scope);
        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Oppen\ProjectBundle\Model\\AccountPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as last child of given $parent node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Account $parent	Propel object for parent node
     *
     * @return     Account The current Propel object
     */
    public function insertAsLastChildOf($parent)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Account object must not already be in the tree to be inserted. Use the moveToLastChildOf() instead.');
        }
        $left = $parent->getRightValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($parent->getLevel() + 1);
        $scope = $parent->getScopeValue();
        $this->setScopeValue($scope);
        // update the children collection of the parent
        $parent->addNestedSetChild($this);

        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Oppen\ProjectBundle\Model\\AccountPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as prev sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Account $sibling	Propel object for parent node
     *
     * @return     Account The current Propel object
     */
    public function insertAsPrevSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Account object must not already be in the tree to be inserted. Use the moveToPrevSiblingOf() instead.');
        }
        $left = $sibling->getLeftValue();
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        $scope = $sibling->getScopeValue();
        $this->setScopeValue($scope);
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Oppen\ProjectBundle\Model\\AccountPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Inserts the current node as next sibling given $sibling node
     * The modifications in the current object and the tree
     * are not persisted until the current object is saved.
     *
     * @param      Account $sibling	Propel object for parent node
     *
     * @return     Account The current Propel object
     */
    public function insertAsNextSiblingOf($sibling)
    {
        if ($this->isInTree()) {
            throw new PropelException('A Account object must not already be in the tree to be inserted. Use the moveToNextSiblingOf() instead.');
        }
        $left = $sibling->getRightValue() + 1;
        // Update node properties
        $this->setLeftValue($left);
        $this->setRightValue($left + 1);
        $this->setLevel($sibling->getLevel());
        $scope = $sibling->getScopeValue();
        $this->setScopeValue($scope);
        // Keep the tree modification query for the save() transaction
        $this->nestedSetQueries []= array(
            'callable'  => array('\\Oppen\ProjectBundle\Model\\AccountPeer', 'makeRoomForLeaf'),
            'arguments' => array($left, $scope, $this->isNew() ? null : $this)
        );

        return $this;
    }

    /**
     * Moves current node and its subtree to be the first child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      Account $parent	Propel object for parent node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Account The current Propel object
     */
    public function moveToFirstChildOf($parent, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Account object must be already in the tree to be moved. Use the insertAsFirstChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getLeftValue() + 1, $parent->getLevel() - $this->getLevel() + 1, $parent->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the last child of $parent
     * The modifications in the current object and the tree are immediate
     *
     * @param      Account $parent	Propel object for parent node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Account The current Propel object
     */
    public function moveToLastChildOf($parent, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Account object must be already in the tree to be moved. Use the insertAsLastChildOf() instead.');
        }
        if ($parent->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as child of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($parent->getRightValue(), $parent->getLevel() - $this->getLevel() + 1, $parent->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the previous sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      Account $sibling	Propel object for sibling node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Account The current Propel object
     */
    public function moveToPrevSiblingOf($sibling, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Account object must be already in the tree to be moved. Use the insertAsPrevSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to previous sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getLeftValue(), $sibling->getLevel() - $this->getLevel(), $sibling->getScopeValue(), $con);

        return $this;
    }

    /**
     * Moves current node and its subtree to be the next sibling of $sibling
     * The modifications in the current object and the tree are immediate
     *
     * @param      Account $sibling	Propel object for sibling node
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Account The current Propel object
     */
    public function moveToNextSiblingOf($sibling, PropelPDO $con = null)
    {
        if (!$this->isInTree()) {
            throw new PropelException('A Account object must be already in the tree to be moved. Use the insertAsNextSiblingOf() instead.');
        }
        if ($sibling->isRoot()) {
            throw new PropelException('Cannot move to next sibling of a root node.');
        }
        if ($sibling->isDescendantOf($this)) {
            throw new PropelException('Cannot move a node as sibling of one of its subtree nodes.');
        }

        $this->moveSubtreeTo($sibling->getRightValue() + 1, $sibling->getLevel() - $this->getLevel(), $sibling->getScopeValue(), $con);

        return $this;
    }

    /**
     * Move current node and its children to location $destLeft and updates rest of tree
     *
     * @param      int	$destLeft Destination left value
     * @param      int	$levelDelta Delta to add to the levels
     * @param      PropelPDO $con		Connection to use.
     */
    protected function moveSubtreeTo($destLeft, $levelDelta, $targetScope = null, PropelPDO $con = null)
    {
        $preventDefault = false;
        $left  = $this->getLeftValue();
        $right = $this->getRightValue();
        $scope = $this->getScopeValue();

        if ($targetScope === null) {
            $targetScope = $scope;
        }


        $treeSize = $right - $left +1;

        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {

            // make room next to the target for the subtree
            AccountPeer::shiftRLValues($treeSize, $destLeft, null, $targetScope, $con);



            if ($targetScope != $scope) {

                //move subtree to < 0, so the items are out of scope.
                AccountPeer::shiftRLValues(-$right, $left, $right, $scope, $con);

                //update scopes
                AccountPeer::setNegativeScope($targetScope, $con);

                //update levels
                AccountPeer::shiftLevel($levelDelta, $left - $right, 0, $targetScope, $con);

                //move the subtree to the target
                AccountPeer::shiftRLValues(($right - $left) + $destLeft, $left - $right, 0, $targetScope, $con);


                $preventDefault = true;
            }


            if (!$preventDefault) {


                if ($left >= $destLeft) { // src was shifted too?
                    $left += $treeSize;
                    $right += $treeSize;
                }

                if ($levelDelta) {
                    // update the levels of the subtree
                    AccountPeer::shiftLevel($levelDelta, $left, $right, $scope, $con);
                }

                // move the subtree to the target
                AccountPeer::shiftRLValues($destLeft - $left, $left, $right, $scope, $con);
            }

            // remove the empty room at the previous location of the subtree
            AccountPeer::shiftRLValues(-$treeSize, $right + 1, null, $scope, $con);

            // update all loaded nodes
            AccountPeer::updateLoadedNodes(null, $con);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Deletes all descendants for the given node
     * Instance pooling is wiped out by this command,
     * so existing Account instances are probably invalid (except for the current one)
     *
     * @param      PropelPDO $con Connection to use.
     *
     * @return     int 		number of deleted nodes
     */
    public function deleteDescendants(PropelPDO $con = null)
    {
        if ($this->isLeaf()) {
            // save one query
            return;
        }
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $left = $this->getLeftValue();
        $right = $this->getRightValue();
        $scope = $this->getScopeValue();
        $con->beginTransaction();
        try {
            // delete descendant nodes (will empty the instance pool)
            $ret = AccountQuery::create()
                ->descendantsOf($this)
                ->delete($con);

            // fill up the room that was used by descendants
            AccountPeer::shiftRLValues($left - $right + 1, $right, null, $scope, $con);

            // fix the right value for the current node, which is now a leaf
            $this->setRightValue($left + 1);

            $con->commit();
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }

        return $ret;
    }

    /**
     * Returns a pre-order iterator for this node and its children.
     *
     * @return     RecursiveIterator
     */
    public function getIterator()
    {
        return new NestedSetRecursiveIterator($this);
    }

}
