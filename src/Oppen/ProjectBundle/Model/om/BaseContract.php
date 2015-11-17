<?php

namespace Oppen\ProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\ContractPeer;
use Oppen\ProjectBundle\Model\ContractQuery;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostQuery;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\MonthQuery;
use Oppen\ProjectBundle\Model\Template;
use Oppen\ProjectBundle\Model\TemplateQuery;

abstract class BaseContract extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\ContractPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ContractPeer
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
     * The value for the contract_no field.
     * @var        string
     */
    protected $contract_no;

    /**
     * The value for the contract_date field.
     * @var        string
     */
    protected $contract_date;

    /**
     * The value for the contract_place field.
     * @var        string
     */
    protected $contract_place;

    /**
     * The value for the event_desc field.
     * @var        string
     */
    protected $event_desc;

    /**
     * The value for the event_date field.
     * @var        string
     */
    protected $event_date;

    /**
     * The value for the event_place field.
     * @var        string
     */
    protected $event_place;

    /**
     * The value for the event_name field.
     * @var        string
     */
    protected $event_name;

    /**
     * The value for the event_role field.
     * @var        string
     */
    protected $event_role;

    /**
     * The value for the gross field.
     * @var        double
     */
    protected $gross;

    /**
     * The value for the income_cost field.
     * @var        double
     */
    protected $income_cost;

    /**
     * The value for the tax field.
     * @var        double
     */
    protected $tax;

    /**
     * The value for the netto field.
     * @var        double
     */
    protected $netto;

    /**
     * The value for the tax_coef field.
     * @var        double
     */
    protected $tax_coef;

    /**
     * The value for the cost_coef field.
     * @var        double
     */
    protected $cost_coef;

    /**
     * The value for the payment_period field.
     * @var        string
     */
    protected $payment_period;

    /**
     * The value for the payment_method field.
     * @var        int
     */
    protected $payment_method;

    /**
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * The value for the cost_id field.
     * @var        int
     */
    protected $cost_id;

    /**
     * The value for the template_id field.
     * @var        int
     */
    protected $template_id;

    /**
     * The value for the file_id field.
     * @var        int
     */
    protected $file_id;

    /**
     * The value for the doc_id field.
     * @var        int
     */
    protected $doc_id;

    /**
     * The value for the month_id field.
     * @var        int
     */
    protected $month_id;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        Cost
     */
    protected $aCost;

    /**
     * @var        Template
     */
    protected $aTemplate;

    /**
     * @var        File
     */
    protected $aFile;

    /**
     * @var        Doc
     */
    protected $aDoc;

    /**
     * @var        Month
     */
    protected $aMonth;

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
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [contract_no] column value.
     *
     * @return string
     */
    public function getContractNo()
    {

        return $this->contract_no;
    }

    /**
     * Get the [optionally formatted] temporal [contract_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getContractDate($format = null)
    {
        if ($this->contract_date === null) {
            return null;
        }

        if ($this->contract_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->contract_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->contract_date, true), $x);
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
     * Get the [contract_place] column value.
     *
     * @return string
     */
    public function getContractPlace()
    {

        return $this->contract_place;
    }

    /**
     * Get the [event_desc] column value.
     *
     * @return string
     */
    public function getEventDesc()
    {

        return $this->event_desc;
    }

    /**
     * Get the [optionally formatted] temporal [event_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getEventDate($format = null)
    {
        if ($this->event_date === null) {
            return null;
        }

        if ($this->event_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->event_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->event_date, true), $x);
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
     * Get the [event_place] column value.
     *
     * @return string
     */
    public function getEventPlace()
    {

        return $this->event_place;
    }

    /**
     * Get the [event_name] column value.
     *
     * @return string
     */
    public function getEventName()
    {

        return $this->event_name;
    }

    /**
     * Get the [event_role] column value.
     *
     * @return string
     */
    public function getEventRole()
    {

        return $this->event_role;
    }

    /**
     * Get the [gross] column value.
     *
     * @return double
     */
    public function getGross()
    {

        return $this->gross;
    }

    /**
     * Get the [income_cost] column value.
     *
     * @return double
     */
    public function getIncomeCost()
    {

        return $this->income_cost;
    }

    /**
     * Get the [tax] column value.
     *
     * @return double
     */
    public function getTax()
    {

        return $this->tax;
    }

    /**
     * Get the [netto] column value.
     *
     * @return double
     */
    public function getNetto()
    {

        return $this->netto;
    }

    /**
     * Get the [tax_coef] column value.
     *
     * @return double
     */
    public function getTaxCoef()
    {

        return $this->tax_coef;
    }

    /**
     * Get the [cost_coef] column value.
     *
     * @return double
     */
    public function getCostCoef()
    {

        return $this->cost_coef;
    }

    /**
     * Get the [payment_period] column value.
     *
     * @return string
     */
    public function getPaymentPeriod()
    {

        return $this->payment_period;
    }

    /**
     * Get the [payment_method] column value.
     *
     * @return int
     */
    public function getPaymentMethod()
    {

        return $this->payment_method;
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
     * Get the [cost_id] column value.
     *
     * @return int
     */
    public function getCostId()
    {

        return $this->cost_id;
    }

    /**
     * Get the [template_id] column value.
     *
     * @return int
     */
    public function getTemplateId()
    {

        return $this->template_id;
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
     * Get the [doc_id] column value.
     *
     * @return int
     */
    public function getDocId()
    {

        return $this->doc_id;
    }

    /**
     * Get the [month_id] column value.
     *
     * @return int
     */
    public function getMonthId()
    {

        return $this->month_id;
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
     * @return Contract The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ContractPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [contract_no] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setContractNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->contract_no !== $v) {
            $this->contract_no = $v;
            $this->modifiedColumns[] = ContractPeer::CONTRACT_NO;
        }


        return $this;
    } // setContractNo()

    /**
     * Sets the value of [contract_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Contract The current object (for fluent API support)
     */
    public function setContractDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->contract_date !== null || $dt !== null) {
            $currentDateAsString = ($this->contract_date !== null && $tmpDt = new DateTime($this->contract_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->contract_date = $newDateAsString;
                $this->modifiedColumns[] = ContractPeer::CONTRACT_DATE;
            }
        } // if either are not null


        return $this;
    } // setContractDate()

    /**
     * Set the value of [contract_place] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setContractPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->contract_place !== $v) {
            $this->contract_place = $v;
            $this->modifiedColumns[] = ContractPeer::CONTRACT_PLACE;
        }


        return $this;
    } // setContractPlace()

    /**
     * Set the value of [event_desc] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setEventDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_desc !== $v) {
            $this->event_desc = $v;
            $this->modifiedColumns[] = ContractPeer::EVENT_DESC;
        }


        return $this;
    } // setEventDesc()

    /**
     * Sets the value of [event_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Contract The current object (for fluent API support)
     */
    public function setEventDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_date !== null || $dt !== null) {
            $currentDateAsString = ($this->event_date !== null && $tmpDt = new DateTime($this->event_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->event_date = $newDateAsString;
                $this->modifiedColumns[] = ContractPeer::EVENT_DATE;
            }
        } // if either are not null


        return $this;
    } // setEventDate()

    /**
     * Set the value of [event_place] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setEventPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_place !== $v) {
            $this->event_place = $v;
            $this->modifiedColumns[] = ContractPeer::EVENT_PLACE;
        }


        return $this;
    } // setEventPlace()

    /**
     * Set the value of [event_name] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setEventName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_name !== $v) {
            $this->event_name = $v;
            $this->modifiedColumns[] = ContractPeer::EVENT_NAME;
        }


        return $this;
    } // setEventName()

    /**
     * Set the value of [event_role] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setEventRole($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_role !== $v) {
            $this->event_role = $v;
            $this->modifiedColumns[] = ContractPeer::EVENT_ROLE;
        }


        return $this;
    } // setEventRole()

    /**
     * Set the value of [gross] column.
     *
     * @param  double $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setGross($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->gross !== $v) {
            $this->gross = $v;
            $this->modifiedColumns[] = ContractPeer::GROSS;
        }


        return $this;
    } // setGross()

    /**
     * Set the value of [income_cost] column.
     *
     * @param  double $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setIncomeCost($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->income_cost !== $v) {
            $this->income_cost = $v;
            $this->modifiedColumns[] = ContractPeer::INCOME_COST;
        }


        return $this;
    } // setIncomeCost()

    /**
     * Set the value of [tax] column.
     *
     * @param  double $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setTax($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->tax !== $v) {
            $this->tax = $v;
            $this->modifiedColumns[] = ContractPeer::TAX;
        }


        return $this;
    } // setTax()

    /**
     * Set the value of [netto] column.
     *
     * @param  double $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setNetto($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->netto !== $v) {
            $this->netto = $v;
            $this->modifiedColumns[] = ContractPeer::NETTO;
        }


        return $this;
    } // setNetto()

    /**
     * Set the value of [tax_coef] column.
     *
     * @param  double $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setTaxCoef($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->tax_coef !== $v) {
            $this->tax_coef = $v;
            $this->modifiedColumns[] = ContractPeer::TAX_COEF;
        }


        return $this;
    } // setTaxCoef()

    /**
     * Set the value of [cost_coef] column.
     *
     * @param  double $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setCostCoef($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->cost_coef !== $v) {
            $this->cost_coef = $v;
            $this->modifiedColumns[] = ContractPeer::COST_COEF;
        }


        return $this;
    } // setCostCoef()

    /**
     * Set the value of [payment_period] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setPaymentPeriod($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->payment_period !== $v) {
            $this->payment_period = $v;
            $this->modifiedColumns[] = ContractPeer::PAYMENT_PERIOD;
        }


        return $this;
    } // setPaymentPeriod()

    /**
     * Set the value of [payment_method] column.
     *
     * @param  int $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setPaymentMethod($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->payment_method !== $v) {
            $this->payment_method = $v;
            $this->modifiedColumns[] = ContractPeer::PAYMENT_METHOD;
        }


        return $this;
    } // setPaymentMethod()

    /**
     * Set the value of [comment] column.
     *
     * @param  string $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[] = ContractPeer::COMMENT;
        }


        return $this;
    } // setComment()

    /**
     * Set the value of [cost_id] column.
     *
     * @param  int $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setCostId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->cost_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->cost_id;

            $this->cost_id = $v;
            $this->modifiedColumns[] = ContractPeer::COST_ID;
        }

        if ($this->aCost !== null && $this->aCost->getId() !== $v) {
            $this->aCost = null;
        }


        return $this;
    } // setCostId()

    /**
     * Set the value of [template_id] column.
     *
     * @param  int $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setTemplateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->template_id !== $v) {
            $this->template_id = $v;
            $this->modifiedColumns[] = ContractPeer::TEMPLATE_ID;
        }

        if ($this->aTemplate !== null && $this->aTemplate->getId() !== $v) {
            $this->aTemplate = null;
        }


        return $this;
    } // setTemplateId()

    /**
     * Set the value of [file_id] column.
     *
     * @param  int $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setFileId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_id !== $v) {
            $this->file_id = $v;
            $this->modifiedColumns[] = ContractPeer::FILE_ID;
        }

        if ($this->aFile !== null && $this->aFile->getId() !== $v) {
            $this->aFile = null;
        }


        return $this;
    } // setFileId()

    /**
     * Set the value of [doc_id] column.
     *
     * @param  int $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setDocId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->doc_id !== $v) {
            $this->doc_id = $v;
            $this->modifiedColumns[] = ContractPeer::DOC_ID;
        }

        if ($this->aDoc !== null && $this->aDoc->getId() !== $v) {
            $this->aDoc = null;
        }


        return $this;
    } // setDocId()

    /**
     * Set the value of [month_id] column.
     *
     * @param  int $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setMonthId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->month_id !== $v) {
            $this->month_id = $v;
            $this->modifiedColumns[] = ContractPeer::MONTH_ID;
        }

        if ($this->aMonth !== null && $this->aMonth->getId() !== $v) {
            $this->aMonth = null;
        }


        return $this;
    } // setMonthId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Contract The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = ContractPeer::SORTABLE_RANK;
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
            $this->contract_no = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->contract_date = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->contract_place = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->event_desc = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->event_date = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->event_place = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->event_name = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->event_role = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->gross = ($row[$startcol + 9] !== null) ? (double) $row[$startcol + 9] : null;
            $this->income_cost = ($row[$startcol + 10] !== null) ? (double) $row[$startcol + 10] : null;
            $this->tax = ($row[$startcol + 11] !== null) ? (double) $row[$startcol + 11] : null;
            $this->netto = ($row[$startcol + 12] !== null) ? (double) $row[$startcol + 12] : null;
            $this->tax_coef = ($row[$startcol + 13] !== null) ? (double) $row[$startcol + 13] : null;
            $this->cost_coef = ($row[$startcol + 14] !== null) ? (double) $row[$startcol + 14] : null;
            $this->payment_period = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->payment_method = ($row[$startcol + 16] !== null) ? (int) $row[$startcol + 16] : null;
            $this->comment = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->cost_id = ($row[$startcol + 18] !== null) ? (int) $row[$startcol + 18] : null;
            $this->template_id = ($row[$startcol + 19] !== null) ? (int) $row[$startcol + 19] : null;
            $this->file_id = ($row[$startcol + 20] !== null) ? (int) $row[$startcol + 20] : null;
            $this->doc_id = ($row[$startcol + 21] !== null) ? (int) $row[$startcol + 21] : null;
            $this->month_id = ($row[$startcol + 22] !== null) ? (int) $row[$startcol + 22] : null;
            $this->sortable_rank = ($row[$startcol + 23] !== null) ? (int) $row[$startcol + 23] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 24; // 24 = ContractPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Contract object", $e);
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

        if ($this->aCost !== null && $this->cost_id !== $this->aCost->getId()) {
            $this->aCost = null;
        }
        if ($this->aTemplate !== null && $this->template_id !== $this->aTemplate->getId()) {
            $this->aTemplate = null;
        }
        if ($this->aFile !== null && $this->file_id !== $this->aFile->getId()) {
            $this->aFile = null;
        }
        if ($this->aDoc !== null && $this->doc_id !== $this->aDoc->getId()) {
            $this->aDoc = null;
        }
        if ($this->aMonth !== null && $this->month_id !== $this->aMonth->getId()) {
            $this->aMonth = null;
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
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ContractPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCost = null;
            $this->aTemplate = null;
            $this->aFile = null;
            $this->aDoc = null;
            $this->aMonth = null;
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
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ContractQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            ContractPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            ContractPeer::clearInstancePool();

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
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(ContractPeer::RANK_COL)) {
                    $this->setSortableRank(ContractQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(ContractPeer::COST_ID)) && !$this->isColumnModified(ContractPeer::RANK_COL)) { ContractPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
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
                ContractPeer::addInstanceToPool($this);
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

            if ($this->aCost !== null) {
                if ($this->aCost->isModified() || $this->aCost->isNew()) {
                    $affectedRows += $this->aCost->save($con);
                }
                $this->setCost($this->aCost);
            }

            if ($this->aTemplate !== null) {
                if ($this->aTemplate->isModified() || $this->aTemplate->isNew()) {
                    $affectedRows += $this->aTemplate->save($con);
                }
                $this->setTemplate($this->aTemplate);
            }

            if ($this->aFile !== null) {
                if ($this->aFile->isModified() || $this->aFile->isNew()) {
                    $affectedRows += $this->aFile->save($con);
                }
                $this->setFile($this->aFile);
            }

            if ($this->aDoc !== null) {
                if ($this->aDoc->isModified() || $this->aDoc->isNew()) {
                    $affectedRows += $this->aDoc->save($con);
                }
                $this->setDoc($this->aDoc);
            }

            if ($this->aMonth !== null) {
                if ($this->aMonth->isModified() || $this->aMonth->isNew()) {
                    $affectedRows += $this->aMonth->save($con);
                }
                $this->setMonth($this->aMonth);
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

        $this->modifiedColumns[] = ContractPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ContractPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ContractPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ContractPeer::CONTRACT_NO)) {
            $modifiedColumns[':p' . $index++]  = '`contract_no`';
        }
        if ($this->isColumnModified(ContractPeer::CONTRACT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`contract_date`';
        }
        if ($this->isColumnModified(ContractPeer::CONTRACT_PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`contract_place`';
        }
        if ($this->isColumnModified(ContractPeer::EVENT_DESC)) {
            $modifiedColumns[':p' . $index++]  = '`event_desc`';
        }
        if ($this->isColumnModified(ContractPeer::EVENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`event_date`';
        }
        if ($this->isColumnModified(ContractPeer::EVENT_PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`event_place`';
        }
        if ($this->isColumnModified(ContractPeer::EVENT_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`event_name`';
        }
        if ($this->isColumnModified(ContractPeer::EVENT_ROLE)) {
            $modifiedColumns[':p' . $index++]  = '`event_role`';
        }
        if ($this->isColumnModified(ContractPeer::GROSS)) {
            $modifiedColumns[':p' . $index++]  = '`gross`';
        }
        if ($this->isColumnModified(ContractPeer::INCOME_COST)) {
            $modifiedColumns[':p' . $index++]  = '`income_cost`';
        }
        if ($this->isColumnModified(ContractPeer::TAX)) {
            $modifiedColumns[':p' . $index++]  = '`tax`';
        }
        if ($this->isColumnModified(ContractPeer::NETTO)) {
            $modifiedColumns[':p' . $index++]  = '`netto`';
        }
        if ($this->isColumnModified(ContractPeer::TAX_COEF)) {
            $modifiedColumns[':p' . $index++]  = '`tax_coef`';
        }
        if ($this->isColumnModified(ContractPeer::COST_COEF)) {
            $modifiedColumns[':p' . $index++]  = '`cost_coef`';
        }
        if ($this->isColumnModified(ContractPeer::PAYMENT_PERIOD)) {
            $modifiedColumns[':p' . $index++]  = '`payment_period`';
        }
        if ($this->isColumnModified(ContractPeer::PAYMENT_METHOD)) {
            $modifiedColumns[':p' . $index++]  = '`payment_method`';
        }
        if ($this->isColumnModified(ContractPeer::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`comment`';
        }
        if ($this->isColumnModified(ContractPeer::COST_ID)) {
            $modifiedColumns[':p' . $index++]  = '`cost_id`';
        }
        if ($this->isColumnModified(ContractPeer::TEMPLATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`template_id`';
        }
        if ($this->isColumnModified(ContractPeer::FILE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_id`';
        }
        if ($this->isColumnModified(ContractPeer::DOC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`doc_id`';
        }
        if ($this->isColumnModified(ContractPeer::MONTH_ID)) {
            $modifiedColumns[':p' . $index++]  = '`month_id`';
        }
        if ($this->isColumnModified(ContractPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `contract` (%s) VALUES (%s)',
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
                    case '`contract_no`':
                        $stmt->bindValue($identifier, $this->contract_no, PDO::PARAM_STR);
                        break;
                    case '`contract_date`':
                        $stmt->bindValue($identifier, $this->contract_date, PDO::PARAM_STR);
                        break;
                    case '`contract_place`':
                        $stmt->bindValue($identifier, $this->contract_place, PDO::PARAM_STR);
                        break;
                    case '`event_desc`':
                        $stmt->bindValue($identifier, $this->event_desc, PDO::PARAM_STR);
                        break;
                    case '`event_date`':
                        $stmt->bindValue($identifier, $this->event_date, PDO::PARAM_STR);
                        break;
                    case '`event_place`':
                        $stmt->bindValue($identifier, $this->event_place, PDO::PARAM_STR);
                        break;
                    case '`event_name`':
                        $stmt->bindValue($identifier, $this->event_name, PDO::PARAM_STR);
                        break;
                    case '`event_role`':
                        $stmt->bindValue($identifier, $this->event_role, PDO::PARAM_STR);
                        break;
                    case '`gross`':
                        $stmt->bindValue($identifier, $this->gross, PDO::PARAM_STR);
                        break;
                    case '`income_cost`':
                        $stmt->bindValue($identifier, $this->income_cost, PDO::PARAM_STR);
                        break;
                    case '`tax`':
                        $stmt->bindValue($identifier, $this->tax, PDO::PARAM_STR);
                        break;
                    case '`netto`':
                        $stmt->bindValue($identifier, $this->netto, PDO::PARAM_STR);
                        break;
                    case '`tax_coef`':
                        $stmt->bindValue($identifier, $this->tax_coef, PDO::PARAM_STR);
                        break;
                    case '`cost_coef`':
                        $stmt->bindValue($identifier, $this->cost_coef, PDO::PARAM_STR);
                        break;
                    case '`payment_period`':
                        $stmt->bindValue($identifier, $this->payment_period, PDO::PARAM_STR);
                        break;
                    case '`payment_method`':
                        $stmt->bindValue($identifier, $this->payment_method, PDO::PARAM_INT);
                        break;
                    case '`comment`':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                    case '`cost_id`':
                        $stmt->bindValue($identifier, $this->cost_id, PDO::PARAM_INT);
                        break;
                    case '`template_id`':
                        $stmt->bindValue($identifier, $this->template_id, PDO::PARAM_INT);
                        break;
                    case '`file_id`':
                        $stmt->bindValue($identifier, $this->file_id, PDO::PARAM_INT);
                        break;
                    case '`doc_id`':
                        $stmt->bindValue($identifier, $this->doc_id, PDO::PARAM_INT);
                        break;
                    case '`month_id`':
                        $stmt->bindValue($identifier, $this->month_id, PDO::PARAM_INT);
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

            if ($this->aCost !== null) {
                if (!$this->aCost->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCost->getValidationFailures());
                }
            }

            if ($this->aTemplate !== null) {
                if (!$this->aTemplate->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTemplate->getValidationFailures());
                }
            }

            if ($this->aFile !== null) {
                if (!$this->aFile->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFile->getValidationFailures());
                }
            }

            if ($this->aDoc !== null) {
                if (!$this->aDoc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDoc->getValidationFailures());
                }
            }

            if ($this->aMonth !== null) {
                if (!$this->aMonth->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aMonth->getValidationFailures());
                }
            }


            if (($retval = ContractPeer::doValidate($this, $columns)) !== true) {
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
        $pos = ContractPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getContractNo();
                break;
            case 2:
                return $this->getContractDate();
                break;
            case 3:
                return $this->getContractPlace();
                break;
            case 4:
                return $this->getEventDesc();
                break;
            case 5:
                return $this->getEventDate();
                break;
            case 6:
                return $this->getEventPlace();
                break;
            case 7:
                return $this->getEventName();
                break;
            case 8:
                return $this->getEventRole();
                break;
            case 9:
                return $this->getGross();
                break;
            case 10:
                return $this->getIncomeCost();
                break;
            case 11:
                return $this->getTax();
                break;
            case 12:
                return $this->getNetto();
                break;
            case 13:
                return $this->getTaxCoef();
                break;
            case 14:
                return $this->getCostCoef();
                break;
            case 15:
                return $this->getPaymentPeriod();
                break;
            case 16:
                return $this->getPaymentMethod();
                break;
            case 17:
                return $this->getComment();
                break;
            case 18:
                return $this->getCostId();
                break;
            case 19:
                return $this->getTemplateId();
                break;
            case 20:
                return $this->getFileId();
                break;
            case 21:
                return $this->getDocId();
                break;
            case 22:
                return $this->getMonthId();
                break;
            case 23:
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
        if (isset($alreadyDumpedObjects['Contract'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Contract'][$this->getPrimaryKey()] = true;
        $keys = ContractPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getContractNo(),
            $keys[2] => $this->getContractDate(),
            $keys[3] => $this->getContractPlace(),
            $keys[4] => $this->getEventDesc(),
            $keys[5] => $this->getEventDate(),
            $keys[6] => $this->getEventPlace(),
            $keys[7] => $this->getEventName(),
            $keys[8] => $this->getEventRole(),
            $keys[9] => $this->getGross(),
            $keys[10] => $this->getIncomeCost(),
            $keys[11] => $this->getTax(),
            $keys[12] => $this->getNetto(),
            $keys[13] => $this->getTaxCoef(),
            $keys[14] => $this->getCostCoef(),
            $keys[15] => $this->getPaymentPeriod(),
            $keys[16] => $this->getPaymentMethod(),
            $keys[17] => $this->getComment(),
            $keys[18] => $this->getCostId(),
            $keys[19] => $this->getTemplateId(),
            $keys[20] => $this->getFileId(),
            $keys[21] => $this->getDocId(),
            $keys[22] => $this->getMonthId(),
            $keys[23] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCost) {
                $result['Cost'] = $this->aCost->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTemplate) {
                $result['Template'] = $this->aTemplate->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFile) {
                $result['File'] = $this->aFile->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDoc) {
                $result['Doc'] = $this->aDoc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aMonth) {
                $result['Month'] = $this->aMonth->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = ContractPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setContractNo($value);
                break;
            case 2:
                $this->setContractDate($value);
                break;
            case 3:
                $this->setContractPlace($value);
                break;
            case 4:
                $this->setEventDesc($value);
                break;
            case 5:
                $this->setEventDate($value);
                break;
            case 6:
                $this->setEventPlace($value);
                break;
            case 7:
                $this->setEventName($value);
                break;
            case 8:
                $this->setEventRole($value);
                break;
            case 9:
                $this->setGross($value);
                break;
            case 10:
                $this->setIncomeCost($value);
                break;
            case 11:
                $this->setTax($value);
                break;
            case 12:
                $this->setNetto($value);
                break;
            case 13:
                $this->setTaxCoef($value);
                break;
            case 14:
                $this->setCostCoef($value);
                break;
            case 15:
                $this->setPaymentPeriod($value);
                break;
            case 16:
                $this->setPaymentMethod($value);
                break;
            case 17:
                $this->setComment($value);
                break;
            case 18:
                $this->setCostId($value);
                break;
            case 19:
                $this->setTemplateId($value);
                break;
            case 20:
                $this->setFileId($value);
                break;
            case 21:
                $this->setDocId($value);
                break;
            case 22:
                $this->setMonthId($value);
                break;
            case 23:
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
        $keys = ContractPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setContractNo($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setContractDate($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setContractPlace($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setEventDesc($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setEventDate($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setEventPlace($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setEventName($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setEventRole($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setGross($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setIncomeCost($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setTax($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setNetto($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setTaxCoef($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setCostCoef($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setPaymentPeriod($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setPaymentMethod($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setComment($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setCostId($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setTemplateId($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setFileId($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setDocId($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setMonthId($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setSortableRank($arr[$keys[23]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ContractPeer::DATABASE_NAME);

        if ($this->isColumnModified(ContractPeer::ID)) $criteria->add(ContractPeer::ID, $this->id);
        if ($this->isColumnModified(ContractPeer::CONTRACT_NO)) $criteria->add(ContractPeer::CONTRACT_NO, $this->contract_no);
        if ($this->isColumnModified(ContractPeer::CONTRACT_DATE)) $criteria->add(ContractPeer::CONTRACT_DATE, $this->contract_date);
        if ($this->isColumnModified(ContractPeer::CONTRACT_PLACE)) $criteria->add(ContractPeer::CONTRACT_PLACE, $this->contract_place);
        if ($this->isColumnModified(ContractPeer::EVENT_DESC)) $criteria->add(ContractPeer::EVENT_DESC, $this->event_desc);
        if ($this->isColumnModified(ContractPeer::EVENT_DATE)) $criteria->add(ContractPeer::EVENT_DATE, $this->event_date);
        if ($this->isColumnModified(ContractPeer::EVENT_PLACE)) $criteria->add(ContractPeer::EVENT_PLACE, $this->event_place);
        if ($this->isColumnModified(ContractPeer::EVENT_NAME)) $criteria->add(ContractPeer::EVENT_NAME, $this->event_name);
        if ($this->isColumnModified(ContractPeer::EVENT_ROLE)) $criteria->add(ContractPeer::EVENT_ROLE, $this->event_role);
        if ($this->isColumnModified(ContractPeer::GROSS)) $criteria->add(ContractPeer::GROSS, $this->gross);
        if ($this->isColumnModified(ContractPeer::INCOME_COST)) $criteria->add(ContractPeer::INCOME_COST, $this->income_cost);
        if ($this->isColumnModified(ContractPeer::TAX)) $criteria->add(ContractPeer::TAX, $this->tax);
        if ($this->isColumnModified(ContractPeer::NETTO)) $criteria->add(ContractPeer::NETTO, $this->netto);
        if ($this->isColumnModified(ContractPeer::TAX_COEF)) $criteria->add(ContractPeer::TAX_COEF, $this->tax_coef);
        if ($this->isColumnModified(ContractPeer::COST_COEF)) $criteria->add(ContractPeer::COST_COEF, $this->cost_coef);
        if ($this->isColumnModified(ContractPeer::PAYMENT_PERIOD)) $criteria->add(ContractPeer::PAYMENT_PERIOD, $this->payment_period);
        if ($this->isColumnModified(ContractPeer::PAYMENT_METHOD)) $criteria->add(ContractPeer::PAYMENT_METHOD, $this->payment_method);
        if ($this->isColumnModified(ContractPeer::COMMENT)) $criteria->add(ContractPeer::COMMENT, $this->comment);
        if ($this->isColumnModified(ContractPeer::COST_ID)) $criteria->add(ContractPeer::COST_ID, $this->cost_id);
        if ($this->isColumnModified(ContractPeer::TEMPLATE_ID)) $criteria->add(ContractPeer::TEMPLATE_ID, $this->template_id);
        if ($this->isColumnModified(ContractPeer::FILE_ID)) $criteria->add(ContractPeer::FILE_ID, $this->file_id);
        if ($this->isColumnModified(ContractPeer::DOC_ID)) $criteria->add(ContractPeer::DOC_ID, $this->doc_id);
        if ($this->isColumnModified(ContractPeer::MONTH_ID)) $criteria->add(ContractPeer::MONTH_ID, $this->month_id);
        if ($this->isColumnModified(ContractPeer::SORTABLE_RANK)) $criteria->add(ContractPeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(ContractPeer::DATABASE_NAME);
        $criteria->add(ContractPeer::ID, $this->id);

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
     * @param object $copyObj An object of Contract (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setContractNo($this->getContractNo());
        $copyObj->setContractDate($this->getContractDate());
        $copyObj->setContractPlace($this->getContractPlace());
        $copyObj->setEventDesc($this->getEventDesc());
        $copyObj->setEventDate($this->getEventDate());
        $copyObj->setEventPlace($this->getEventPlace());
        $copyObj->setEventName($this->getEventName());
        $copyObj->setEventRole($this->getEventRole());
        $copyObj->setGross($this->getGross());
        $copyObj->setIncomeCost($this->getIncomeCost());
        $copyObj->setTax($this->getTax());
        $copyObj->setNetto($this->getNetto());
        $copyObj->setTaxCoef($this->getTaxCoef());
        $copyObj->setCostCoef($this->getCostCoef());
        $copyObj->setPaymentPeriod($this->getPaymentPeriod());
        $copyObj->setPaymentMethod($this->getPaymentMethod());
        $copyObj->setComment($this->getComment());
        $copyObj->setCostId($this->getCostId());
        $copyObj->setTemplateId($this->getTemplateId());
        $copyObj->setFileId($this->getFileId());
        $copyObj->setDocId($this->getDocId());
        $copyObj->setMonthId($this->getMonthId());
        $copyObj->setSortableRank($this->getSortableRank());

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
     * @return Contract Clone of current object.
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
     * @return ContractPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ContractPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Cost object.
     *
     * @param                  Cost $v
     * @return Contract The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCost(Cost $v = null)
    {
        if ($v === null) {
            $this->setCostId(NULL);
        } else {
            $this->setCostId($v->getId());
        }

        $this->aCost = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Cost object, it will not be re-added.
        if ($v !== null) {
            $v->addContract($this);
        }


        return $this;
    }


    /**
     * Get the associated Cost object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Cost The associated Cost object.
     * @throws PropelException
     */
    public function getCost(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCost === null && ($this->cost_id !== null) && $doQuery) {
            $this->aCost = CostQuery::create()->findPk($this->cost_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCost->addContracts($this);
             */
        }

        return $this->aCost;
    }

    /**
     * Declares an association between this object and a Template object.
     *
     * @param                  Template $v
     * @return Contract The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTemplate(Template $v = null)
    {
        if ($v === null) {
            $this->setTemplateId(NULL);
        } else {
            $this->setTemplateId($v->getId());
        }

        $this->aTemplate = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Template object, it will not be re-added.
        if ($v !== null) {
            $v->addContract($this);
        }


        return $this;
    }


    /**
     * Get the associated Template object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Template The associated Template object.
     * @throws PropelException
     */
    public function getTemplate(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTemplate === null && ($this->template_id !== null) && $doQuery) {
            $this->aTemplate = TemplateQuery::create()->findPk($this->template_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTemplate->addContracts($this);
             */
        }

        return $this->aTemplate;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return Contract The current object (for fluent API support)
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
            $v->addContract($this);
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
                $this->aFile->addContracts($this);
             */
        }

        return $this->aFile;
    }

    /**
     * Declares an association between this object and a Doc object.
     *
     * @param                  Doc $v
     * @return Contract The current object (for fluent API support)
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
            $v->addContract($this);
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
                $this->aDoc->addContracts($this);
             */
        }

        return $this->aDoc;
    }

    /**
     * Declares an association between this object and a Month object.
     *
     * @param                  Month $v
     * @return Contract The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMonth(Month $v = null)
    {
        if ($v === null) {
            $this->setMonthId(NULL);
        } else {
            $this->setMonthId($v->getId());
        }

        $this->aMonth = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Month object, it will not be re-added.
        if ($v !== null) {
            $v->addContract($this);
        }


        return $this;
    }


    /**
     * Get the associated Month object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Month The associated Month object.
     * @throws PropelException
     */
    public function getMonth(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aMonth === null && ($this->month_id !== null) && $doQuery) {
            $this->aMonth = MonthQuery::create()->findPk($this->month_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMonth->addContracts($this);
             */
        }

        return $this->aMonth;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->contract_no = null;
        $this->contract_date = null;
        $this->contract_place = null;
        $this->event_desc = null;
        $this->event_date = null;
        $this->event_place = null;
        $this->event_name = null;
        $this->event_role = null;
        $this->gross = null;
        $this->income_cost = null;
        $this->tax = null;
        $this->netto = null;
        $this->tax_coef = null;
        $this->cost_coef = null;
        $this->payment_period = null;
        $this->payment_method = null;
        $this->comment = null;
        $this->cost_id = null;
        $this->template_id = null;
        $this->file_id = null;
        $this->doc_id = null;
        $this->month_id = null;
        $this->sortable_rank = null;
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
            if ($this->aCost instanceof Persistent) {
              $this->aCost->clearAllReferences($deep);
            }
            if ($this->aTemplate instanceof Persistent) {
              $this->aTemplate->clearAllReferences($deep);
            }
            if ($this->aFile instanceof Persistent) {
              $this->aFile->clearAllReferences($deep);
            }
            if ($this->aDoc instanceof Persistent) {
              $this->aDoc->clearAllReferences($deep);
            }
            if ($this->aMonth instanceof Persistent) {
              $this->aMonth->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aCost = null;
        $this->aTemplate = null;
        $this->aFile = null;
        $this->aDoc = null;
        $this->aMonth = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(ContractPeer::DEFAULT_STRING_FORMAT);
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
     * @return    Contract
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


        return $this->getCostId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    Contract
     */
    public function setScopeValue($v)
    {


        return $this->setCostId($v);

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
        return $this->getSortableRank() == ContractQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Contract
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = ContractQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Contract
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = ContractQuery::create();

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
     * @return    Contract the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = ContractQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    Contract the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(ContractQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Contract the current object
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
     * @return    Contract the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > ContractQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
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
            ContractPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

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
     * @param     Contract $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Contract the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
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
     * @return    Contract the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
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
     * @return    Contract the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
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
     * @return    Contract the current object
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
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = ContractQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    Contract the current object
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
