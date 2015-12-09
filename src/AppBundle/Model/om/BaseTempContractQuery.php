<?php

namespace AppBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use AppBundle\Model\TempContract;
use AppBundle\Model\TempContractPeer;
use AppBundle\Model\TempContractQuery;

/**
 * @method TempContractQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TempContractQuery orderByContractNo($order = Criteria::ASC) Order by the contract_no column
 * @method TempContractQuery orderByContractDate($order = Criteria::ASC) Order by the contract_date column
 * @method TempContractQuery orderByContractPlace($order = Criteria::ASC) Order by the contract_place column
 * @method TempContractQuery orderByEventDesc($order = Criteria::ASC) Order by the event_desc column
 * @method TempContractQuery orderByEventDate($order = Criteria::ASC) Order by the event_date column
 * @method TempContractQuery orderByEventPlace($order = Criteria::ASC) Order by the event_place column
 * @method TempContractQuery orderByEventName($order = Criteria::ASC) Order by the event_name column
 * @method TempContractQuery orderByEventRole($order = Criteria::ASC) Order by the event_role column
 * @method TempContractQuery orderByGross($order = Criteria::ASC) Order by the gross column
 * @method TempContractQuery orderByIncomeCost($order = Criteria::ASC) Order by the income_cost column
 * @method TempContractQuery orderByTax($order = Criteria::ASC) Order by the tax column
 * @method TempContractQuery orderByNetto($order = Criteria::ASC) Order by the netto column
 * @method TempContractQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method TempContractQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method TempContractQuery orderByPesel($order = Criteria::ASC) Order by the PESEL column
 * @method TempContractQuery orderByNip($order = Criteria::ASC) Order by the NIP column
 * @method TempContractQuery orderByStreet($order = Criteria::ASC) Order by the street column
 * @method TempContractQuery orderByHouse($order = Criteria::ASC) Order by the house column
 * @method TempContractQuery orderByFlat($order = Criteria::ASC) Order by the flat column
 * @method TempContractQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method TempContractQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method TempContractQuery orderByDistrict($order = Criteria::ASC) Order by the district column
 * @method TempContractQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method TempContractQuery orderByBankAccount($order = Criteria::ASC) Order by the bank_account column
 * @method TempContractQuery orderByBankName($order = Criteria::ASC) Order by the bank_name column
 *
 * @method TempContractQuery groupById() Group by the id column
 * @method TempContractQuery groupByContractNo() Group by the contract_no column
 * @method TempContractQuery groupByContractDate() Group by the contract_date column
 * @method TempContractQuery groupByContractPlace() Group by the contract_place column
 * @method TempContractQuery groupByEventDesc() Group by the event_desc column
 * @method TempContractQuery groupByEventDate() Group by the event_date column
 * @method TempContractQuery groupByEventPlace() Group by the event_place column
 * @method TempContractQuery groupByEventName() Group by the event_name column
 * @method TempContractQuery groupByEventRole() Group by the event_role column
 * @method TempContractQuery groupByGross() Group by the gross column
 * @method TempContractQuery groupByIncomeCost() Group by the income_cost column
 * @method TempContractQuery groupByTax() Group by the tax column
 * @method TempContractQuery groupByNetto() Group by the netto column
 * @method TempContractQuery groupByFirstName() Group by the first_name column
 * @method TempContractQuery groupByLastName() Group by the last_name column
 * @method TempContractQuery groupByPesel() Group by the PESEL column
 * @method TempContractQuery groupByNip() Group by the NIP column
 * @method TempContractQuery groupByStreet() Group by the street column
 * @method TempContractQuery groupByHouse() Group by the house column
 * @method TempContractQuery groupByFlat() Group by the flat column
 * @method TempContractQuery groupByCode() Group by the code column
 * @method TempContractQuery groupByCity() Group by the city column
 * @method TempContractQuery groupByDistrict() Group by the district column
 * @method TempContractQuery groupByCountry() Group by the country column
 * @method TempContractQuery groupByBankAccount() Group by the bank_account column
 * @method TempContractQuery groupByBankName() Group by the bank_name column
 *
 * @method TempContractQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TempContractQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TempContractQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TempContract findOne(PropelPDO $con = null) Return the first TempContract matching the query
 * @method TempContract findOneOrCreate(PropelPDO $con = null) Return the first TempContract matching the query, or a new TempContract object populated from the query conditions when no match is found
 *
 * @method TempContract findOneByContractNo(string $contract_no) Return the first TempContract filtered by the contract_no column
 * @method TempContract findOneByContractDate(string $contract_date) Return the first TempContract filtered by the contract_date column
 * @method TempContract findOneByContractPlace(string $contract_place) Return the first TempContract filtered by the contract_place column
 * @method TempContract findOneByEventDesc(string $event_desc) Return the first TempContract filtered by the event_desc column
 * @method TempContract findOneByEventDate(string $event_date) Return the first TempContract filtered by the event_date column
 * @method TempContract findOneByEventPlace(string $event_place) Return the first TempContract filtered by the event_place column
 * @method TempContract findOneByEventName(string $event_name) Return the first TempContract filtered by the event_name column
 * @method TempContract findOneByEventRole(string $event_role) Return the first TempContract filtered by the event_role column
 * @method TempContract findOneByGross(double $gross) Return the first TempContract filtered by the gross column
 * @method TempContract findOneByIncomeCost(double $income_cost) Return the first TempContract filtered by the income_cost column
 * @method TempContract findOneByTax(double $tax) Return the first TempContract filtered by the tax column
 * @method TempContract findOneByNetto(double $netto) Return the first TempContract filtered by the netto column
 * @method TempContract findOneByFirstName(string $first_name) Return the first TempContract filtered by the first_name column
 * @method TempContract findOneByLastName(string $last_name) Return the first TempContract filtered by the last_name column
 * @method TempContract findOneByPesel(string $PESEL) Return the first TempContract filtered by the PESEL column
 * @method TempContract findOneByNip(string $NIP) Return the first TempContract filtered by the NIP column
 * @method TempContract findOneByStreet(string $street) Return the first TempContract filtered by the street column
 * @method TempContract findOneByHouse(string $house) Return the first TempContract filtered by the house column
 * @method TempContract findOneByFlat(string $flat) Return the first TempContract filtered by the flat column
 * @method TempContract findOneByCode(string $code) Return the first TempContract filtered by the code column
 * @method TempContract findOneByCity(string $city) Return the first TempContract filtered by the city column
 * @method TempContract findOneByDistrict(string $district) Return the first TempContract filtered by the district column
 * @method TempContract findOneByCountry(string $country) Return the first TempContract filtered by the country column
 * @method TempContract findOneByBankAccount(string $bank_account) Return the first TempContract filtered by the bank_account column
 * @method TempContract findOneByBankName(string $bank_name) Return the first TempContract filtered by the bank_name column
 *
 * @method array findById(int $id) Return TempContract objects filtered by the id column
 * @method array findByContractNo(string $contract_no) Return TempContract objects filtered by the contract_no column
 * @method array findByContractDate(string $contract_date) Return TempContract objects filtered by the contract_date column
 * @method array findByContractPlace(string $contract_place) Return TempContract objects filtered by the contract_place column
 * @method array findByEventDesc(string $event_desc) Return TempContract objects filtered by the event_desc column
 * @method array findByEventDate(string $event_date) Return TempContract objects filtered by the event_date column
 * @method array findByEventPlace(string $event_place) Return TempContract objects filtered by the event_place column
 * @method array findByEventName(string $event_name) Return TempContract objects filtered by the event_name column
 * @method array findByEventRole(string $event_role) Return TempContract objects filtered by the event_role column
 * @method array findByGross(double $gross) Return TempContract objects filtered by the gross column
 * @method array findByIncomeCost(double $income_cost) Return TempContract objects filtered by the income_cost column
 * @method array findByTax(double $tax) Return TempContract objects filtered by the tax column
 * @method array findByNetto(double $netto) Return TempContract objects filtered by the netto column
 * @method array findByFirstName(string $first_name) Return TempContract objects filtered by the first_name column
 * @method array findByLastName(string $last_name) Return TempContract objects filtered by the last_name column
 * @method array findByPesel(string $PESEL) Return TempContract objects filtered by the PESEL column
 * @method array findByNip(string $NIP) Return TempContract objects filtered by the NIP column
 * @method array findByStreet(string $street) Return TempContract objects filtered by the street column
 * @method array findByHouse(string $house) Return TempContract objects filtered by the house column
 * @method array findByFlat(string $flat) Return TempContract objects filtered by the flat column
 * @method array findByCode(string $code) Return TempContract objects filtered by the code column
 * @method array findByCity(string $city) Return TempContract objects filtered by the city column
 * @method array findByDistrict(string $district) Return TempContract objects filtered by the district column
 * @method array findByCountry(string $country) Return TempContract objects filtered by the country column
 * @method array findByBankAccount(string $bank_account) Return TempContract objects filtered by the bank_account column
 * @method array findByBankName(string $bank_name) Return TempContract objects filtered by the bank_name column
 */
abstract class BaseTempContractQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTempContractQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'AppBundle\\Model\\TempContract';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TempContractQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   TempContractQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TempContractQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TempContractQuery) {
            return $criteria;
        }
        $query = new TempContractQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   TempContract|TempContract[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TempContractPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TempContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 TempContract A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 TempContract A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `contract_no`, `contract_date`, `contract_place`, `event_desc`, `event_date`, `event_place`, `event_name`, `event_role`, `gross`, `income_cost`, `tax`, `netto`, `first_name`, `last_name`, `PESEL`, `NIP`, `street`, `house`, `flat`, `code`, `city`, `district`, `country`, `bank_account`, `bank_name` FROM `temp_contract` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new TempContract();
            $obj->hydrate($row);
            TempContractPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return TempContract|TempContract[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|TempContract[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TempContractPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TempContractPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TempContractPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TempContractPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TempContractPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the contract_no column
     *
     * Example usage:
     * <code>
     * $query->filterByContractNo('fooValue');   // WHERE contract_no = 'fooValue'
     * $query->filterByContractNo('%fooValue%'); // WHERE contract_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contractNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByContractNo($contractNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contractNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $contractNo)) {
                $contractNo = str_replace('*', '%', $contractNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::CONTRACT_NO, $contractNo, $comparison);
    }

    /**
     * Filter the query on the contract_date column
     *
     * Example usage:
     * <code>
     * $query->filterByContractDate('2011-03-14'); // WHERE contract_date = '2011-03-14'
     * $query->filterByContractDate('now'); // WHERE contract_date = '2011-03-14'
     * $query->filterByContractDate(array('max' => 'yesterday')); // WHERE contract_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $contractDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByContractDate($contractDate = null, $comparison = null)
    {
        if (is_array($contractDate)) {
            $useMinMax = false;
            if (isset($contractDate['min'])) {
                $this->addUsingAlias(TempContractPeer::CONTRACT_DATE, $contractDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($contractDate['max'])) {
                $this->addUsingAlias(TempContractPeer::CONTRACT_DATE, $contractDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TempContractPeer::CONTRACT_DATE, $contractDate, $comparison);
    }

    /**
     * Filter the query on the contract_place column
     *
     * Example usage:
     * <code>
     * $query->filterByContractPlace('fooValue');   // WHERE contract_place = 'fooValue'
     * $query->filterByContractPlace('%fooValue%'); // WHERE contract_place LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contractPlace The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByContractPlace($contractPlace = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contractPlace)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $contractPlace)) {
                $contractPlace = str_replace('*', '%', $contractPlace);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::CONTRACT_PLACE, $contractPlace, $comparison);
    }

    /**
     * Filter the query on the event_desc column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDesc('fooValue');   // WHERE event_desc = 'fooValue'
     * $query->filterByEventDesc('%fooValue%'); // WHERE event_desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventDesc The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByEventDesc($eventDesc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventDesc)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventDesc)) {
                $eventDesc = str_replace('*', '%', $eventDesc);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::EVENT_DESC, $eventDesc, $comparison);
    }

    /**
     * Filter the query on the event_date column
     *
     * Example usage:
     * <code>
     * $query->filterByEventDate('2011-03-14'); // WHERE event_date = '2011-03-14'
     * $query->filterByEventDate('now'); // WHERE event_date = '2011-03-14'
     * $query->filterByEventDate(array('max' => 'yesterday')); // WHERE event_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $eventDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByEventDate($eventDate = null, $comparison = null)
    {
        if (is_array($eventDate)) {
            $useMinMax = false;
            if (isset($eventDate['min'])) {
                $this->addUsingAlias(TempContractPeer::EVENT_DATE, $eventDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventDate['max'])) {
                $this->addUsingAlias(TempContractPeer::EVENT_DATE, $eventDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TempContractPeer::EVENT_DATE, $eventDate, $comparison);
    }

    /**
     * Filter the query on the event_place column
     *
     * Example usage:
     * <code>
     * $query->filterByEventPlace('fooValue');   // WHERE event_place = 'fooValue'
     * $query->filterByEventPlace('%fooValue%'); // WHERE event_place LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventPlace The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByEventPlace($eventPlace = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventPlace)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventPlace)) {
                $eventPlace = str_replace('*', '%', $eventPlace);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::EVENT_PLACE, $eventPlace, $comparison);
    }

    /**
     * Filter the query on the event_name column
     *
     * Example usage:
     * <code>
     * $query->filterByEventName('fooValue');   // WHERE event_name = 'fooValue'
     * $query->filterByEventName('%fooValue%'); // WHERE event_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByEventName($eventName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventName)) {
                $eventName = str_replace('*', '%', $eventName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::EVENT_NAME, $eventName, $comparison);
    }

    /**
     * Filter the query on the event_role column
     *
     * Example usage:
     * <code>
     * $query->filterByEventRole('fooValue');   // WHERE event_role = 'fooValue'
     * $query->filterByEventRole('%fooValue%'); // WHERE event_role LIKE '%fooValue%'
     * </code>
     *
     * @param     string $eventRole The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByEventRole($eventRole = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($eventRole)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $eventRole)) {
                $eventRole = str_replace('*', '%', $eventRole);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::EVENT_ROLE, $eventRole, $comparison);
    }

    /**
     * Filter the query on the gross column
     *
     * Example usage:
     * <code>
     * $query->filterByGross(1234); // WHERE gross = 1234
     * $query->filterByGross(array(12, 34)); // WHERE gross IN (12, 34)
     * $query->filterByGross(array('min' => 12)); // WHERE gross >= 12
     * $query->filterByGross(array('max' => 12)); // WHERE gross <= 12
     * </code>
     *
     * @param     mixed $gross The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByGross($gross = null, $comparison = null)
    {
        if (is_array($gross)) {
            $useMinMax = false;
            if (isset($gross['min'])) {
                $this->addUsingAlias(TempContractPeer::GROSS, $gross['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gross['max'])) {
                $this->addUsingAlias(TempContractPeer::GROSS, $gross['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TempContractPeer::GROSS, $gross, $comparison);
    }

    /**
     * Filter the query on the income_cost column
     *
     * Example usage:
     * <code>
     * $query->filterByIncomeCost(1234); // WHERE income_cost = 1234
     * $query->filterByIncomeCost(array(12, 34)); // WHERE income_cost IN (12, 34)
     * $query->filterByIncomeCost(array('min' => 12)); // WHERE income_cost >= 12
     * $query->filterByIncomeCost(array('max' => 12)); // WHERE income_cost <= 12
     * </code>
     *
     * @param     mixed $incomeCost The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByIncomeCost($incomeCost = null, $comparison = null)
    {
        if (is_array($incomeCost)) {
            $useMinMax = false;
            if (isset($incomeCost['min'])) {
                $this->addUsingAlias(TempContractPeer::INCOME_COST, $incomeCost['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($incomeCost['max'])) {
                $this->addUsingAlias(TempContractPeer::INCOME_COST, $incomeCost['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TempContractPeer::INCOME_COST, $incomeCost, $comparison);
    }

    /**
     * Filter the query on the tax column
     *
     * Example usage:
     * <code>
     * $query->filterByTax(1234); // WHERE tax = 1234
     * $query->filterByTax(array(12, 34)); // WHERE tax IN (12, 34)
     * $query->filterByTax(array('min' => 12)); // WHERE tax >= 12
     * $query->filterByTax(array('max' => 12)); // WHERE tax <= 12
     * </code>
     *
     * @param     mixed $tax The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByTax($tax = null, $comparison = null)
    {
        if (is_array($tax)) {
            $useMinMax = false;
            if (isset($tax['min'])) {
                $this->addUsingAlias(TempContractPeer::TAX, $tax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tax['max'])) {
                $this->addUsingAlias(TempContractPeer::TAX, $tax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TempContractPeer::TAX, $tax, $comparison);
    }

    /**
     * Filter the query on the netto column
     *
     * Example usage:
     * <code>
     * $query->filterByNetto(1234); // WHERE netto = 1234
     * $query->filterByNetto(array(12, 34)); // WHERE netto IN (12, 34)
     * $query->filterByNetto(array('min' => 12)); // WHERE netto >= 12
     * $query->filterByNetto(array('max' => 12)); // WHERE netto <= 12
     * </code>
     *
     * @param     mixed $netto The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByNetto($netto = null, $comparison = null)
    {
        if (is_array($netto)) {
            $useMinMax = false;
            if (isset($netto['min'])) {
                $this->addUsingAlias(TempContractPeer::NETTO, $netto['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($netto['max'])) {
                $this->addUsingAlias(TempContractPeer::NETTO, $netto['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TempContractPeer::NETTO, $netto, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the PESEL column
     *
     * Example usage:
     * <code>
     * $query->filterByPesel('fooValue');   // WHERE PESEL = 'fooValue'
     * $query->filterByPesel('%fooValue%'); // WHERE PESEL LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pesel The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByPesel($pesel = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pesel)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $pesel)) {
                $pesel = str_replace('*', '%', $pesel);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::PESEL, $pesel, $comparison);
    }

    /**
     * Filter the query on the NIP column
     *
     * Example usage:
     * <code>
     * $query->filterByNip('fooValue');   // WHERE NIP = 'fooValue'
     * $query->filterByNip('%fooValue%'); // WHERE NIP LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nip The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByNip($nip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nip)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nip)) {
                $nip = str_replace('*', '%', $nip);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::NIP, $nip, $comparison);
    }

    /**
     * Filter the query on the street column
     *
     * Example usage:
     * <code>
     * $query->filterByStreet('fooValue');   // WHERE street = 'fooValue'
     * $query->filterByStreet('%fooValue%'); // WHERE street LIKE '%fooValue%'
     * </code>
     *
     * @param     string $street The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByStreet($street = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($street)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $street)) {
                $street = str_replace('*', '%', $street);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::STREET, $street, $comparison);
    }

    /**
     * Filter the query on the house column
     *
     * Example usage:
     * <code>
     * $query->filterByHouse('fooValue');   // WHERE house = 'fooValue'
     * $query->filterByHouse('%fooValue%'); // WHERE house LIKE '%fooValue%'
     * </code>
     *
     * @param     string $house The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByHouse($house = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($house)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $house)) {
                $house = str_replace('*', '%', $house);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::HOUSE, $house, $comparison);
    }

    /**
     * Filter the query on the flat column
     *
     * Example usage:
     * <code>
     * $query->filterByFlat('fooValue');   // WHERE flat = 'fooValue'
     * $query->filterByFlat('%fooValue%'); // WHERE flat LIKE '%fooValue%'
     * </code>
     *
     * @param     string $flat The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByFlat($flat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($flat)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $flat)) {
                $flat = str_replace('*', '%', $flat);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::FLAT, $flat, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $city)) {
                $city = str_replace('*', '%', $city);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::CITY, $city, $comparison);
    }

    /**
     * Filter the query on the district column
     *
     * Example usage:
     * <code>
     * $query->filterByDistrict('fooValue');   // WHERE district = 'fooValue'
     * $query->filterByDistrict('%fooValue%'); // WHERE district LIKE '%fooValue%'
     * </code>
     *
     * @param     string $district The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByDistrict($district = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($district)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $district)) {
                $district = str_replace('*', '%', $district);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::DISTRICT, $district, $comparison);
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE country = 'fooValue'
     * $query->filterByCountry('%fooValue%'); // WHERE country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $country)) {
                $country = str_replace('*', '%', $country);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the bank_account column
     *
     * Example usage:
     * <code>
     * $query->filterByBankAccount('fooValue');   // WHERE bank_account = 'fooValue'
     * $query->filterByBankAccount('%fooValue%'); // WHERE bank_account LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankAccount The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByBankAccount($bankAccount = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankAccount)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankAccount)) {
                $bankAccount = str_replace('*', '%', $bankAccount);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::BANK_ACCOUNT, $bankAccount, $comparison);
    }

    /**
     * Filter the query on the bank_name column
     *
     * Example usage:
     * <code>
     * $query->filterByBankName('fooValue');   // WHERE bank_name = 'fooValue'
     * $query->filterByBankName('%fooValue%'); // WHERE bank_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function filterByBankName($bankName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankName)) {
                $bankName = str_replace('*', '%', $bankName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TempContractPeer::BANK_NAME, $bankName, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   TempContract $tempContract Object to remove from the list of results
     *
     * @return TempContractQuery The current query, for fluid interface
     */
    public function prune($tempContract = null)
    {
        if ($tempContract) {
            $this->addUsingAlias(TempContractPeer::ID, $tempContract->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
