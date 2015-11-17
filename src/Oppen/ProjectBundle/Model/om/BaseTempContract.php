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
use Oppen\ProjectBundle\Model\TempContract;
use Oppen\ProjectBundle\Model\TempContractPeer;
use Oppen\ProjectBundle\Model\TempContractQuery;

abstract class BaseTempContract extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\TempContractPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        TempContractPeer
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
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the pesel field.
     * @var        string
     */
    protected $pesel;

    /**
     * The value for the nip field.
     * @var        string
     */
    protected $nip;

    /**
     * The value for the street field.
     * @var        string
     */
    protected $street;

    /**
     * The value for the house field.
     * @var        string
     */
    protected $house;

    /**
     * The value for the flat field.
     * @var        string
     */
    protected $flat;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the district field.
     * @var        string
     */
    protected $district;

    /**
     * The value for the country field.
     * @var        string
     */
    protected $country;

    /**
     * The value for the bank_account field.
     * @var        string
     */
    protected $bank_account;

    /**
     * The value for the bank_name field.
     * @var        string
     */
    protected $bank_name;

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
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {

        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {

        return $this->last_name;
    }

    /**
     * Get the [pesel] column value.
     *
     * @return string
     */
    public function getPesel()
    {

        return $this->pesel;
    }

    /**
     * Get the [nip] column value.
     *
     * @return string
     */
    public function getNip()
    {

        return $this->nip;
    }

    /**
     * Get the [street] column value.
     *
     * @return string
     */
    public function getStreet()
    {

        return $this->street;
    }

    /**
     * Get the [house] column value.
     *
     * @return string
     */
    public function getHouse()
    {

        return $this->house;
    }

    /**
     * Get the [flat] column value.
     *
     * @return string
     */
    public function getFlat()
    {

        return $this->flat;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * Get the [district] column value.
     *
     * @return string
     */
    public function getDistrict()
    {

        return $this->district;
    }

    /**
     * Get the [country] column value.
     *
     * @return string
     */
    public function getCountry()
    {

        return $this->country;
    }

    /**
     * Get the [bank_account] column value.
     *
     * @return string
     */
    public function getBankAccount()
    {

        return $this->bank_account;
    }

    /**
     * Get the [bank_name] column value.
     *
     * @return string
     */
    public function getBankName()
    {

        return $this->bank_name;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = TempContractPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [contract_no] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setContractNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->contract_no !== $v) {
            $this->contract_no = $v;
            $this->modifiedColumns[] = TempContractPeer::CONTRACT_NO;
        }


        return $this;
    } // setContractNo()

    /**
     * Sets the value of [contract_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return TempContract The current object (for fluent API support)
     */
    public function setContractDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->contract_date !== null || $dt !== null) {
            $currentDateAsString = ($this->contract_date !== null && $tmpDt = new DateTime($this->contract_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->contract_date = $newDateAsString;
                $this->modifiedColumns[] = TempContractPeer::CONTRACT_DATE;
            }
        } // if either are not null


        return $this;
    } // setContractDate()

    /**
     * Set the value of [contract_place] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setContractPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->contract_place !== $v) {
            $this->contract_place = $v;
            $this->modifiedColumns[] = TempContractPeer::CONTRACT_PLACE;
        }


        return $this;
    } // setContractPlace()

    /**
     * Set the value of [event_desc] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setEventDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_desc !== $v) {
            $this->event_desc = $v;
            $this->modifiedColumns[] = TempContractPeer::EVENT_DESC;
        }


        return $this;
    } // setEventDesc()

    /**
     * Sets the value of [event_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return TempContract The current object (for fluent API support)
     */
    public function setEventDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->event_date !== null || $dt !== null) {
            $currentDateAsString = ($this->event_date !== null && $tmpDt = new DateTime($this->event_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->event_date = $newDateAsString;
                $this->modifiedColumns[] = TempContractPeer::EVENT_DATE;
            }
        } // if either are not null


        return $this;
    } // setEventDate()

    /**
     * Set the value of [event_place] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setEventPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_place !== $v) {
            $this->event_place = $v;
            $this->modifiedColumns[] = TempContractPeer::EVENT_PLACE;
        }


        return $this;
    } // setEventPlace()

    /**
     * Set the value of [event_name] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setEventName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_name !== $v) {
            $this->event_name = $v;
            $this->modifiedColumns[] = TempContractPeer::EVENT_NAME;
        }


        return $this;
    } // setEventName()

    /**
     * Set the value of [event_role] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setEventRole($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->event_role !== $v) {
            $this->event_role = $v;
            $this->modifiedColumns[] = TempContractPeer::EVENT_ROLE;
        }


        return $this;
    } // setEventRole()

    /**
     * Set the value of [gross] column.
     *
     * @param  double $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setGross($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->gross !== $v) {
            $this->gross = $v;
            $this->modifiedColumns[] = TempContractPeer::GROSS;
        }


        return $this;
    } // setGross()

    /**
     * Set the value of [income_cost] column.
     *
     * @param  double $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setIncomeCost($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->income_cost !== $v) {
            $this->income_cost = $v;
            $this->modifiedColumns[] = TempContractPeer::INCOME_COST;
        }


        return $this;
    } // setIncomeCost()

    /**
     * Set the value of [tax] column.
     *
     * @param  double $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setTax($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->tax !== $v) {
            $this->tax = $v;
            $this->modifiedColumns[] = TempContractPeer::TAX;
        }


        return $this;
    } // setTax()

    /**
     * Set the value of [netto] column.
     *
     * @param  double $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setNetto($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->netto !== $v) {
            $this->netto = $v;
            $this->modifiedColumns[] = TempContractPeer::NETTO;
        }


        return $this;
    } // setNetto()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[] = TempContractPeer::FIRST_NAME;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[] = TempContractPeer::LAST_NAME;
        }


        return $this;
    } // setLastName()

    /**
     * Set the value of [pesel] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setPesel($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->pesel !== $v) {
            $this->pesel = $v;
            $this->modifiedColumns[] = TempContractPeer::PESEL;
        }


        return $this;
    } // setPesel()

    /**
     * Set the value of [nip] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setNip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nip !== $v) {
            $this->nip = $v;
            $this->modifiedColumns[] = TempContractPeer::NIP;
        }


        return $this;
    } // setNip()

    /**
     * Set the value of [street] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setStreet($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->street !== $v) {
            $this->street = $v;
            $this->modifiedColumns[] = TempContractPeer::STREET;
        }


        return $this;
    } // setStreet()

    /**
     * Set the value of [house] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setHouse($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->house !== $v) {
            $this->house = $v;
            $this->modifiedColumns[] = TempContractPeer::HOUSE;
        }


        return $this;
    } // setHouse()

    /**
     * Set the value of [flat] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setFlat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->flat !== $v) {
            $this->flat = $v;
            $this->modifiedColumns[] = TempContractPeer::FLAT;
        }


        return $this;
    } // setFlat()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = TempContractPeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = TempContractPeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [district] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setDistrict($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->district !== $v) {
            $this->district = $v;
            $this->modifiedColumns[] = TempContractPeer::DISTRICT;
        }


        return $this;
    } // setDistrict()

    /**
     * Set the value of [country] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country !== $v) {
            $this->country = $v;
            $this->modifiedColumns[] = TempContractPeer::COUNTRY;
        }


        return $this;
    } // setCountry()

    /**
     * Set the value of [bank_account] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setBankAccount($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bank_account !== $v) {
            $this->bank_account = $v;
            $this->modifiedColumns[] = TempContractPeer::BANK_ACCOUNT;
        }


        return $this;
    } // setBankAccount()

    /**
     * Set the value of [bank_name] column.
     *
     * @param  string $v new value
     * @return TempContract The current object (for fluent API support)
     */
    public function setBankName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bank_name !== $v) {
            $this->bank_name = $v;
            $this->modifiedColumns[] = TempContractPeer::BANK_NAME;
        }


        return $this;
    } // setBankName()

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
            $this->first_name = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->last_name = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->pesel = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->nip = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->street = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->house = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->flat = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->code = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->city = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->district = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->country = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->bank_account = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->bank_name = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 26; // 26 = TempContractPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating TempContract object", $e);
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
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = TempContractPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

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
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = TempContractQuery::create()
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
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                TempContractPeer::addInstanceToPool($this);
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

        $this->modifiedColumns[] = TempContractPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TempContractPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TempContractPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(TempContractPeer::CONTRACT_NO)) {
            $modifiedColumns[':p' . $index++]  = '`contract_no`';
        }
        if ($this->isColumnModified(TempContractPeer::CONTRACT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`contract_date`';
        }
        if ($this->isColumnModified(TempContractPeer::CONTRACT_PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`contract_place`';
        }
        if ($this->isColumnModified(TempContractPeer::EVENT_DESC)) {
            $modifiedColumns[':p' . $index++]  = '`event_desc`';
        }
        if ($this->isColumnModified(TempContractPeer::EVENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`event_date`';
        }
        if ($this->isColumnModified(TempContractPeer::EVENT_PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`event_place`';
        }
        if ($this->isColumnModified(TempContractPeer::EVENT_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`event_name`';
        }
        if ($this->isColumnModified(TempContractPeer::EVENT_ROLE)) {
            $modifiedColumns[':p' . $index++]  = '`event_role`';
        }
        if ($this->isColumnModified(TempContractPeer::GROSS)) {
            $modifiedColumns[':p' . $index++]  = '`gross`';
        }
        if ($this->isColumnModified(TempContractPeer::INCOME_COST)) {
            $modifiedColumns[':p' . $index++]  = '`income_cost`';
        }
        if ($this->isColumnModified(TempContractPeer::TAX)) {
            $modifiedColumns[':p' . $index++]  = '`tax`';
        }
        if ($this->isColumnModified(TempContractPeer::NETTO)) {
            $modifiedColumns[':p' . $index++]  = '`netto`';
        }
        if ($this->isColumnModified(TempContractPeer::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`first_name`';
        }
        if ($this->isColumnModified(TempContractPeer::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`last_name`';
        }
        if ($this->isColumnModified(TempContractPeer::PESEL)) {
            $modifiedColumns[':p' . $index++]  = '`PESEL`';
        }
        if ($this->isColumnModified(TempContractPeer::NIP)) {
            $modifiedColumns[':p' . $index++]  = '`NIP`';
        }
        if ($this->isColumnModified(TempContractPeer::STREET)) {
            $modifiedColumns[':p' . $index++]  = '`street`';
        }
        if ($this->isColumnModified(TempContractPeer::HOUSE)) {
            $modifiedColumns[':p' . $index++]  = '`house`';
        }
        if ($this->isColumnModified(TempContractPeer::FLAT)) {
            $modifiedColumns[':p' . $index++]  = '`flat`';
        }
        if ($this->isColumnModified(TempContractPeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(TempContractPeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(TempContractPeer::DISTRICT)) {
            $modifiedColumns[':p' . $index++]  = '`district`';
        }
        if ($this->isColumnModified(TempContractPeer::COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = '`country`';
        }
        if ($this->isColumnModified(TempContractPeer::BANK_ACCOUNT)) {
            $modifiedColumns[':p' . $index++]  = '`bank_account`';
        }
        if ($this->isColumnModified(TempContractPeer::BANK_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`bank_name`';
        }

        $sql = sprintf(
            'INSERT INTO `temp_contract` (%s) VALUES (%s)',
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
                    case '`first_name`':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case '`last_name`':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case '`PESEL`':
                        $stmt->bindValue($identifier, $this->pesel, PDO::PARAM_STR);
                        break;
                    case '`NIP`':
                        $stmt->bindValue($identifier, $this->nip, PDO::PARAM_STR);
                        break;
                    case '`street`':
                        $stmt->bindValue($identifier, $this->street, PDO::PARAM_STR);
                        break;
                    case '`house`':
                        $stmt->bindValue($identifier, $this->house, PDO::PARAM_STR);
                        break;
                    case '`flat`':
                        $stmt->bindValue($identifier, $this->flat, PDO::PARAM_STR);
                        break;
                    case '`code`':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case '`city`':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case '`district`':
                        $stmt->bindValue($identifier, $this->district, PDO::PARAM_STR);
                        break;
                    case '`country`':
                        $stmt->bindValue($identifier, $this->country, PDO::PARAM_STR);
                        break;
                    case '`bank_account`':
                        $stmt->bindValue($identifier, $this->bank_account, PDO::PARAM_STR);
                        break;
                    case '`bank_name`':
                        $stmt->bindValue($identifier, $this->bank_name, PDO::PARAM_STR);
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


            if (($retval = TempContractPeer::doValidate($this, $columns)) !== true) {
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
        $pos = TempContractPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getFirstName();
                break;
            case 14:
                return $this->getLastName();
                break;
            case 15:
                return $this->getPesel();
                break;
            case 16:
                return $this->getNip();
                break;
            case 17:
                return $this->getStreet();
                break;
            case 18:
                return $this->getHouse();
                break;
            case 19:
                return $this->getFlat();
                break;
            case 20:
                return $this->getCode();
                break;
            case 21:
                return $this->getCity();
                break;
            case 22:
                return $this->getDistrict();
                break;
            case 23:
                return $this->getCountry();
                break;
            case 24:
                return $this->getBankAccount();
                break;
            case 25:
                return $this->getBankName();
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
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array())
    {
        if (isset($alreadyDumpedObjects['TempContract'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['TempContract'][$this->getPrimaryKey()] = true;
        $keys = TempContractPeer::getFieldNames($keyType);
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
            $keys[13] => $this->getFirstName(),
            $keys[14] => $this->getLastName(),
            $keys[15] => $this->getPesel(),
            $keys[16] => $this->getNip(),
            $keys[17] => $this->getStreet(),
            $keys[18] => $this->getHouse(),
            $keys[19] => $this->getFlat(),
            $keys[20] => $this->getCode(),
            $keys[21] => $this->getCity(),
            $keys[22] => $this->getDistrict(),
            $keys[23] => $this->getCountry(),
            $keys[24] => $this->getBankAccount(),
            $keys[25] => $this->getBankName(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
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
        $pos = TempContractPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setFirstName($value);
                break;
            case 14:
                $this->setLastName($value);
                break;
            case 15:
                $this->setPesel($value);
                break;
            case 16:
                $this->setNip($value);
                break;
            case 17:
                $this->setStreet($value);
                break;
            case 18:
                $this->setHouse($value);
                break;
            case 19:
                $this->setFlat($value);
                break;
            case 20:
                $this->setCode($value);
                break;
            case 21:
                $this->setCity($value);
                break;
            case 22:
                $this->setDistrict($value);
                break;
            case 23:
                $this->setCountry($value);
                break;
            case 24:
                $this->setBankAccount($value);
                break;
            case 25:
                $this->setBankName($value);
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
        $keys = TempContractPeer::getFieldNames($keyType);

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
        if (array_key_exists($keys[13], $arr)) $this->setFirstName($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setLastName($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setPesel($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setNip($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setStreet($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setHouse($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setFlat($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setCode($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setCity($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setDistrict($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setCountry($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setBankAccount($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setBankName($arr[$keys[25]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TempContractPeer::DATABASE_NAME);

        if ($this->isColumnModified(TempContractPeer::ID)) $criteria->add(TempContractPeer::ID, $this->id);
        if ($this->isColumnModified(TempContractPeer::CONTRACT_NO)) $criteria->add(TempContractPeer::CONTRACT_NO, $this->contract_no);
        if ($this->isColumnModified(TempContractPeer::CONTRACT_DATE)) $criteria->add(TempContractPeer::CONTRACT_DATE, $this->contract_date);
        if ($this->isColumnModified(TempContractPeer::CONTRACT_PLACE)) $criteria->add(TempContractPeer::CONTRACT_PLACE, $this->contract_place);
        if ($this->isColumnModified(TempContractPeer::EVENT_DESC)) $criteria->add(TempContractPeer::EVENT_DESC, $this->event_desc);
        if ($this->isColumnModified(TempContractPeer::EVENT_DATE)) $criteria->add(TempContractPeer::EVENT_DATE, $this->event_date);
        if ($this->isColumnModified(TempContractPeer::EVENT_PLACE)) $criteria->add(TempContractPeer::EVENT_PLACE, $this->event_place);
        if ($this->isColumnModified(TempContractPeer::EVENT_NAME)) $criteria->add(TempContractPeer::EVENT_NAME, $this->event_name);
        if ($this->isColumnModified(TempContractPeer::EVENT_ROLE)) $criteria->add(TempContractPeer::EVENT_ROLE, $this->event_role);
        if ($this->isColumnModified(TempContractPeer::GROSS)) $criteria->add(TempContractPeer::GROSS, $this->gross);
        if ($this->isColumnModified(TempContractPeer::INCOME_COST)) $criteria->add(TempContractPeer::INCOME_COST, $this->income_cost);
        if ($this->isColumnModified(TempContractPeer::TAX)) $criteria->add(TempContractPeer::TAX, $this->tax);
        if ($this->isColumnModified(TempContractPeer::NETTO)) $criteria->add(TempContractPeer::NETTO, $this->netto);
        if ($this->isColumnModified(TempContractPeer::FIRST_NAME)) $criteria->add(TempContractPeer::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(TempContractPeer::LAST_NAME)) $criteria->add(TempContractPeer::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(TempContractPeer::PESEL)) $criteria->add(TempContractPeer::PESEL, $this->pesel);
        if ($this->isColumnModified(TempContractPeer::NIP)) $criteria->add(TempContractPeer::NIP, $this->nip);
        if ($this->isColumnModified(TempContractPeer::STREET)) $criteria->add(TempContractPeer::STREET, $this->street);
        if ($this->isColumnModified(TempContractPeer::HOUSE)) $criteria->add(TempContractPeer::HOUSE, $this->house);
        if ($this->isColumnModified(TempContractPeer::FLAT)) $criteria->add(TempContractPeer::FLAT, $this->flat);
        if ($this->isColumnModified(TempContractPeer::CODE)) $criteria->add(TempContractPeer::CODE, $this->code);
        if ($this->isColumnModified(TempContractPeer::CITY)) $criteria->add(TempContractPeer::CITY, $this->city);
        if ($this->isColumnModified(TempContractPeer::DISTRICT)) $criteria->add(TempContractPeer::DISTRICT, $this->district);
        if ($this->isColumnModified(TempContractPeer::COUNTRY)) $criteria->add(TempContractPeer::COUNTRY, $this->country);
        if ($this->isColumnModified(TempContractPeer::BANK_ACCOUNT)) $criteria->add(TempContractPeer::BANK_ACCOUNT, $this->bank_account);
        if ($this->isColumnModified(TempContractPeer::BANK_NAME)) $criteria->add(TempContractPeer::BANK_NAME, $this->bank_name);

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
        $criteria = new Criteria(TempContractPeer::DATABASE_NAME);
        $criteria->add(TempContractPeer::ID, $this->id);

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
     * @param object $copyObj An object of TempContract (or compatible) type.
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
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setPesel($this->getPesel());
        $copyObj->setNip($this->getNip());
        $copyObj->setStreet($this->getStreet());
        $copyObj->setHouse($this->getHouse());
        $copyObj->setFlat($this->getFlat());
        $copyObj->setCode($this->getCode());
        $copyObj->setCity($this->getCity());
        $copyObj->setDistrict($this->getDistrict());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setBankAccount($this->getBankAccount());
        $copyObj->setBankName($this->getBankName());
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
     * @return TempContract Clone of current object.
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
     * @return TempContractPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new TempContractPeer();
        }

        return self::$peer;
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
        $this->first_name = null;
        $this->last_name = null;
        $this->pesel = null;
        $this->nip = null;
        $this->street = null;
        $this->house = null;
        $this->flat = null;
        $this->code = null;
        $this->city = null;
        $this->district = null;
        $this->country = null;
        $this->bank_account = null;
        $this->bank_name = null;
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

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(TempContractPeer::DEFAULT_STRING_FORMAT);
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
