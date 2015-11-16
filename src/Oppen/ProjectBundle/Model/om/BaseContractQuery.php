<?php

namespace Oppen\ProjectBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\ContractPeer;
use Oppen\ProjectBundle\Model\ContractQuery;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\Template;

/**
 * @method ContractQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ContractQuery orderByContractNo($order = Criteria::ASC) Order by the contract_no column
 * @method ContractQuery orderByContractDate($order = Criteria::ASC) Order by the contract_date column
 * @method ContractQuery orderByContractPlace($order = Criteria::ASC) Order by the contract_place column
 * @method ContractQuery orderByEventDesc($order = Criteria::ASC) Order by the event_desc column
 * @method ContractQuery orderByEventDate($order = Criteria::ASC) Order by the event_date column
 * @method ContractQuery orderByEventPlace($order = Criteria::ASC) Order by the event_place column
 * @method ContractQuery orderByEventName($order = Criteria::ASC) Order by the event_name column
 * @method ContractQuery orderByEventRole($order = Criteria::ASC) Order by the event_role column
 * @method ContractQuery orderByGross($order = Criteria::ASC) Order by the gross column
 * @method ContractQuery orderByIncomeCost($order = Criteria::ASC) Order by the income_cost column
 * @method ContractQuery orderByTax($order = Criteria::ASC) Order by the tax column
 * @method ContractQuery orderByNetto($order = Criteria::ASC) Order by the netto column
 * @method ContractQuery orderByTaxCoef($order = Criteria::ASC) Order by the tax_coef column
 * @method ContractQuery orderByCostCoef($order = Criteria::ASC) Order by the cost_coef column
 * @method ContractQuery orderByPaymentPeriod($order = Criteria::ASC) Order by the payment_period column
 * @method ContractQuery orderByPaymentMethod($order = Criteria::ASC) Order by the payment_method column
 * @method ContractQuery orderByComment($order = Criteria::ASC) Order by the comment column
 * @method ContractQuery orderByCostId($order = Criteria::ASC) Order by the cost_id column
 * @method ContractQuery orderByTemplateId($order = Criteria::ASC) Order by the template_id column
 * @method ContractQuery orderByFileId($order = Criteria::ASC) Order by the file_id column
 * @method ContractQuery orderByDocId($order = Criteria::ASC) Order by the doc_id column
 * @method ContractQuery orderByMonthId($order = Criteria::ASC) Order by the month_id column
 * @method ContractQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method ContractQuery groupById() Group by the id column
 * @method ContractQuery groupByContractNo() Group by the contract_no column
 * @method ContractQuery groupByContractDate() Group by the contract_date column
 * @method ContractQuery groupByContractPlace() Group by the contract_place column
 * @method ContractQuery groupByEventDesc() Group by the event_desc column
 * @method ContractQuery groupByEventDate() Group by the event_date column
 * @method ContractQuery groupByEventPlace() Group by the event_place column
 * @method ContractQuery groupByEventName() Group by the event_name column
 * @method ContractQuery groupByEventRole() Group by the event_role column
 * @method ContractQuery groupByGross() Group by the gross column
 * @method ContractQuery groupByIncomeCost() Group by the income_cost column
 * @method ContractQuery groupByTax() Group by the tax column
 * @method ContractQuery groupByNetto() Group by the netto column
 * @method ContractQuery groupByTaxCoef() Group by the tax_coef column
 * @method ContractQuery groupByCostCoef() Group by the cost_coef column
 * @method ContractQuery groupByPaymentPeriod() Group by the payment_period column
 * @method ContractQuery groupByPaymentMethod() Group by the payment_method column
 * @method ContractQuery groupByComment() Group by the comment column
 * @method ContractQuery groupByCostId() Group by the cost_id column
 * @method ContractQuery groupByTemplateId() Group by the template_id column
 * @method ContractQuery groupByFileId() Group by the file_id column
 * @method ContractQuery groupByDocId() Group by the doc_id column
 * @method ContractQuery groupByMonthId() Group by the month_id column
 * @method ContractQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method ContractQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ContractQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ContractQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ContractQuery leftJoinCost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cost relation
 * @method ContractQuery rightJoinCost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cost relation
 * @method ContractQuery innerJoinCost($relationAlias = null) Adds a INNER JOIN clause to the query using the Cost relation
 *
 * @method ContractQuery leftJoinTemplate($relationAlias = null) Adds a LEFT JOIN clause to the query using the Template relation
 * @method ContractQuery rightJoinTemplate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Template relation
 * @method ContractQuery innerJoinTemplate($relationAlias = null) Adds a INNER JOIN clause to the query using the Template relation
 *
 * @method ContractQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method ContractQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method ContractQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method ContractQuery leftJoinDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the Doc relation
 * @method ContractQuery rightJoinDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Doc relation
 * @method ContractQuery innerJoinDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the Doc relation
 *
 * @method ContractQuery leftJoinMonth($relationAlias = null) Adds a LEFT JOIN clause to the query using the Month relation
 * @method ContractQuery rightJoinMonth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Month relation
 * @method ContractQuery innerJoinMonth($relationAlias = null) Adds a INNER JOIN clause to the query using the Month relation
 *
 * @method Contract findOne(PropelPDO $con = null) Return the first Contract matching the query
 * @method Contract findOneOrCreate(PropelPDO $con = null) Return the first Contract matching the query, or a new Contract object populated from the query conditions when no match is found
 *
 * @method Contract findOneByContractNo(string $contract_no) Return the first Contract filtered by the contract_no column
 * @method Contract findOneByContractDate(string $contract_date) Return the first Contract filtered by the contract_date column
 * @method Contract findOneByContractPlace(string $contract_place) Return the first Contract filtered by the contract_place column
 * @method Contract findOneByEventDesc(string $event_desc) Return the first Contract filtered by the event_desc column
 * @method Contract findOneByEventDate(string $event_date) Return the first Contract filtered by the event_date column
 * @method Contract findOneByEventPlace(string $event_place) Return the first Contract filtered by the event_place column
 * @method Contract findOneByEventName(string $event_name) Return the first Contract filtered by the event_name column
 * @method Contract findOneByEventRole(string $event_role) Return the first Contract filtered by the event_role column
 * @method Contract findOneByGross(double $gross) Return the first Contract filtered by the gross column
 * @method Contract findOneByIncomeCost(double $income_cost) Return the first Contract filtered by the income_cost column
 * @method Contract findOneByTax(double $tax) Return the first Contract filtered by the tax column
 * @method Contract findOneByNetto(double $netto) Return the first Contract filtered by the netto column
 * @method Contract findOneByTaxCoef(double $tax_coef) Return the first Contract filtered by the tax_coef column
 * @method Contract findOneByCostCoef(double $cost_coef) Return the first Contract filtered by the cost_coef column
 * @method Contract findOneByPaymentPeriod(string $payment_period) Return the first Contract filtered by the payment_period column
 * @method Contract findOneByPaymentMethod(int $payment_method) Return the first Contract filtered by the payment_method column
 * @method Contract findOneByComment(string $comment) Return the first Contract filtered by the comment column
 * @method Contract findOneByCostId(int $cost_id) Return the first Contract filtered by the cost_id column
 * @method Contract findOneByTemplateId(int $template_id) Return the first Contract filtered by the template_id column
 * @method Contract findOneByFileId(int $file_id) Return the first Contract filtered by the file_id column
 * @method Contract findOneByDocId(int $doc_id) Return the first Contract filtered by the doc_id column
 * @method Contract findOneByMonthId(int $month_id) Return the first Contract filtered by the month_id column
 * @method Contract findOneBySortableRank(int $sortable_rank) Return the first Contract filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return Contract objects filtered by the id column
 * @method array findByContractNo(string $contract_no) Return Contract objects filtered by the contract_no column
 * @method array findByContractDate(string $contract_date) Return Contract objects filtered by the contract_date column
 * @method array findByContractPlace(string $contract_place) Return Contract objects filtered by the contract_place column
 * @method array findByEventDesc(string $event_desc) Return Contract objects filtered by the event_desc column
 * @method array findByEventDate(string $event_date) Return Contract objects filtered by the event_date column
 * @method array findByEventPlace(string $event_place) Return Contract objects filtered by the event_place column
 * @method array findByEventName(string $event_name) Return Contract objects filtered by the event_name column
 * @method array findByEventRole(string $event_role) Return Contract objects filtered by the event_role column
 * @method array findByGross(double $gross) Return Contract objects filtered by the gross column
 * @method array findByIncomeCost(double $income_cost) Return Contract objects filtered by the income_cost column
 * @method array findByTax(double $tax) Return Contract objects filtered by the tax column
 * @method array findByNetto(double $netto) Return Contract objects filtered by the netto column
 * @method array findByTaxCoef(double $tax_coef) Return Contract objects filtered by the tax_coef column
 * @method array findByCostCoef(double $cost_coef) Return Contract objects filtered by the cost_coef column
 * @method array findByPaymentPeriod(string $payment_period) Return Contract objects filtered by the payment_period column
 * @method array findByPaymentMethod(int $payment_method) Return Contract objects filtered by the payment_method column
 * @method array findByComment(string $comment) Return Contract objects filtered by the comment column
 * @method array findByCostId(int $cost_id) Return Contract objects filtered by the cost_id column
 * @method array findByTemplateId(int $template_id) Return Contract objects filtered by the template_id column
 * @method array findByFileId(int $file_id) Return Contract objects filtered by the file_id column
 * @method array findByDocId(int $doc_id) Return Contract objects filtered by the doc_id column
 * @method array findByMonthId(int $month_id) Return Contract objects filtered by the month_id column
 * @method array findBySortableRank(int $sortable_rank) Return Contract objects filtered by the sortable_rank column
 */
abstract class BaseContractQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseContractQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\Contract';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ContractQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ContractQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ContractQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ContractQuery) {
            return $criteria;
        }
        $query = new ContractQuery(null, null, $modelAlias);

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
     * @return   Contract|Contract[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ContractPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Contract A model object, or null if the key is not found
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
     * @return                 Contract A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `contract_no`, `contract_date`, `contract_place`, `event_desc`, `event_date`, `event_place`, `event_name`, `event_role`, `gross`, `income_cost`, `tax`, `netto`, `tax_coef`, `cost_coef`, `payment_period`, `payment_method`, `comment`, `cost_id`, `template_id`, `file_id`, `doc_id`, `month_id`, `sortable_rank` FROM `contract` WHERE `id` = :p0';
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
            $obj = new Contract();
            $obj->hydrate($row);
            ContractPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Contract|Contract[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Contract[]|mixed the list of results, formatted by the current formatter
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ContractPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ContractPeer::ID, $keys, Criteria::IN);
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ContractPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ContractPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::ID, $id, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContractPeer::CONTRACT_NO, $contractNo, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByContractDate($contractDate = null, $comparison = null)
    {
        if (is_array($contractDate)) {
            $useMinMax = false;
            if (isset($contractDate['min'])) {
                $this->addUsingAlias(ContractPeer::CONTRACT_DATE, $contractDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($contractDate['max'])) {
                $this->addUsingAlias(ContractPeer::CONTRACT_DATE, $contractDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::CONTRACT_DATE, $contractDate, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContractPeer::CONTRACT_PLACE, $contractPlace, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContractPeer::EVENT_DESC, $eventDesc, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByEventDate($eventDate = null, $comparison = null)
    {
        if (is_array($eventDate)) {
            $useMinMax = false;
            if (isset($eventDate['min'])) {
                $this->addUsingAlias(ContractPeer::EVENT_DATE, $eventDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventDate['max'])) {
                $this->addUsingAlias(ContractPeer::EVENT_DATE, $eventDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::EVENT_DATE, $eventDate, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContractPeer::EVENT_PLACE, $eventPlace, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContractPeer::EVENT_NAME, $eventName, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ContractPeer::EVENT_ROLE, $eventRole, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByGross($gross = null, $comparison = null)
    {
        if (is_array($gross)) {
            $useMinMax = false;
            if (isset($gross['min'])) {
                $this->addUsingAlias(ContractPeer::GROSS, $gross['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($gross['max'])) {
                $this->addUsingAlias(ContractPeer::GROSS, $gross['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::GROSS, $gross, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByIncomeCost($incomeCost = null, $comparison = null)
    {
        if (is_array($incomeCost)) {
            $useMinMax = false;
            if (isset($incomeCost['min'])) {
                $this->addUsingAlias(ContractPeer::INCOME_COST, $incomeCost['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($incomeCost['max'])) {
                $this->addUsingAlias(ContractPeer::INCOME_COST, $incomeCost['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::INCOME_COST, $incomeCost, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByTax($tax = null, $comparison = null)
    {
        if (is_array($tax)) {
            $useMinMax = false;
            if (isset($tax['min'])) {
                $this->addUsingAlias(ContractPeer::TAX, $tax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tax['max'])) {
                $this->addUsingAlias(ContractPeer::TAX, $tax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::TAX, $tax, $comparison);
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
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByNetto($netto = null, $comparison = null)
    {
        if (is_array($netto)) {
            $useMinMax = false;
            if (isset($netto['min'])) {
                $this->addUsingAlias(ContractPeer::NETTO, $netto['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($netto['max'])) {
                $this->addUsingAlias(ContractPeer::NETTO, $netto['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::NETTO, $netto, $comparison);
    }

    /**
     * Filter the query on the tax_coef column
     *
     * Example usage:
     * <code>
     * $query->filterByTaxCoef(1234); // WHERE tax_coef = 1234
     * $query->filterByTaxCoef(array(12, 34)); // WHERE tax_coef IN (12, 34)
     * $query->filterByTaxCoef(array('min' => 12)); // WHERE tax_coef >= 12
     * $query->filterByTaxCoef(array('max' => 12)); // WHERE tax_coef <= 12
     * </code>
     *
     * @param     mixed $taxCoef The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByTaxCoef($taxCoef = null, $comparison = null)
    {
        if (is_array($taxCoef)) {
            $useMinMax = false;
            if (isset($taxCoef['min'])) {
                $this->addUsingAlias(ContractPeer::TAX_COEF, $taxCoef['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($taxCoef['max'])) {
                $this->addUsingAlias(ContractPeer::TAX_COEF, $taxCoef['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::TAX_COEF, $taxCoef, $comparison);
    }

    /**
     * Filter the query on the cost_coef column
     *
     * Example usage:
     * <code>
     * $query->filterByCostCoef(1234); // WHERE cost_coef = 1234
     * $query->filterByCostCoef(array(12, 34)); // WHERE cost_coef IN (12, 34)
     * $query->filterByCostCoef(array('min' => 12)); // WHERE cost_coef >= 12
     * $query->filterByCostCoef(array('max' => 12)); // WHERE cost_coef <= 12
     * </code>
     *
     * @param     mixed $costCoef The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByCostCoef($costCoef = null, $comparison = null)
    {
        if (is_array($costCoef)) {
            $useMinMax = false;
            if (isset($costCoef['min'])) {
                $this->addUsingAlias(ContractPeer::COST_COEF, $costCoef['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($costCoef['max'])) {
                $this->addUsingAlias(ContractPeer::COST_COEF, $costCoef['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::COST_COEF, $costCoef, $comparison);
    }

    /**
     * Filter the query on the payment_period column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentPeriod('fooValue');   // WHERE payment_period = 'fooValue'
     * $query->filterByPaymentPeriod('%fooValue%'); // WHERE payment_period LIKE '%fooValue%'
     * </code>
     *
     * @param     string $paymentPeriod The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByPaymentPeriod($paymentPeriod = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($paymentPeriod)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $paymentPeriod)) {
                $paymentPeriod = str_replace('*', '%', $paymentPeriod);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ContractPeer::PAYMENT_PERIOD, $paymentPeriod, $comparison);
    }

    /**
     * Filter the query on the payment_method column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentMethod(1234); // WHERE payment_method = 1234
     * $query->filterByPaymentMethod(array(12, 34)); // WHERE payment_method IN (12, 34)
     * $query->filterByPaymentMethod(array('min' => 12)); // WHERE payment_method >= 12
     * $query->filterByPaymentMethod(array('max' => 12)); // WHERE payment_method <= 12
     * </code>
     *
     * @param     mixed $paymentMethod The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByPaymentMethod($paymentMethod = null, $comparison = null)
    {
        if (is_array($paymentMethod)) {
            $useMinMax = false;
            if (isset($paymentMethod['min'])) {
                $this->addUsingAlias(ContractPeer::PAYMENT_METHOD, $paymentMethod['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentMethod['max'])) {
                $this->addUsingAlias(ContractPeer::PAYMENT_METHOD, $paymentMethod['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::PAYMENT_METHOD, $paymentMethod, $comparison);
    }

    /**
     * Filter the query on the comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE comment = 'fooValue'
     * $query->filterByComment('%fooValue%'); // WHERE comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $comment)) {
                $comment = str_replace('*', '%', $comment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ContractPeer::COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query on the cost_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCostId(1234); // WHERE cost_id = 1234
     * $query->filterByCostId(array(12, 34)); // WHERE cost_id IN (12, 34)
     * $query->filterByCostId(array('min' => 12)); // WHERE cost_id >= 12
     * $query->filterByCostId(array('max' => 12)); // WHERE cost_id <= 12
     * </code>
     *
     * @see       filterByCost()
     *
     * @param     mixed $costId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByCostId($costId = null, $comparison = null)
    {
        if (is_array($costId)) {
            $useMinMax = false;
            if (isset($costId['min'])) {
                $this->addUsingAlias(ContractPeer::COST_ID, $costId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($costId['max'])) {
                $this->addUsingAlias(ContractPeer::COST_ID, $costId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::COST_ID, $costId, $comparison);
    }

    /**
     * Filter the query on the template_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTemplateId(1234); // WHERE template_id = 1234
     * $query->filterByTemplateId(array(12, 34)); // WHERE template_id IN (12, 34)
     * $query->filterByTemplateId(array('min' => 12)); // WHERE template_id >= 12
     * $query->filterByTemplateId(array('max' => 12)); // WHERE template_id <= 12
     * </code>
     *
     * @see       filterByTemplate()
     *
     * @param     mixed $templateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByTemplateId($templateId = null, $comparison = null)
    {
        if (is_array($templateId)) {
            $useMinMax = false;
            if (isset($templateId['min'])) {
                $this->addUsingAlias(ContractPeer::TEMPLATE_ID, $templateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($templateId['max'])) {
                $this->addUsingAlias(ContractPeer::TEMPLATE_ID, $templateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::TEMPLATE_ID, $templateId, $comparison);
    }

    /**
     * Filter the query on the file_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileId(1234); // WHERE file_id = 1234
     * $query->filterByFileId(array(12, 34)); // WHERE file_id IN (12, 34)
     * $query->filterByFileId(array('min' => 12)); // WHERE file_id >= 12
     * $query->filterByFileId(array('max' => 12)); // WHERE file_id <= 12
     * </code>
     *
     * @see       filterByFile()
     *
     * @param     mixed $fileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByFileId($fileId = null, $comparison = null)
    {
        if (is_array($fileId)) {
            $useMinMax = false;
            if (isset($fileId['min'])) {
                $this->addUsingAlias(ContractPeer::FILE_ID, $fileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileId['max'])) {
                $this->addUsingAlias(ContractPeer::FILE_ID, $fileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::FILE_ID, $fileId, $comparison);
    }

    /**
     * Filter the query on the doc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDocId(1234); // WHERE doc_id = 1234
     * $query->filterByDocId(array(12, 34)); // WHERE doc_id IN (12, 34)
     * $query->filterByDocId(array('min' => 12)); // WHERE doc_id >= 12
     * $query->filterByDocId(array('max' => 12)); // WHERE doc_id <= 12
     * </code>
     *
     * @see       filterByDoc()
     *
     * @param     mixed $docId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByDocId($docId = null, $comparison = null)
    {
        if (is_array($docId)) {
            $useMinMax = false;
            if (isset($docId['min'])) {
                $this->addUsingAlias(ContractPeer::DOC_ID, $docId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($docId['max'])) {
                $this->addUsingAlias(ContractPeer::DOC_ID, $docId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::DOC_ID, $docId, $comparison);
    }

    /**
     * Filter the query on the month_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMonthId(1234); // WHERE month_id = 1234
     * $query->filterByMonthId(array(12, 34)); // WHERE month_id IN (12, 34)
     * $query->filterByMonthId(array('min' => 12)); // WHERE month_id >= 12
     * $query->filterByMonthId(array('max' => 12)); // WHERE month_id <= 12
     * </code>
     *
     * @see       filterByMonth()
     *
     * @param     mixed $monthId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterByMonthId($monthId = null, $comparison = null)
    {
        if (is_array($monthId)) {
            $useMinMax = false;
            if (isset($monthId['min'])) {
                $this->addUsingAlias(ContractPeer::MONTH_ID, $monthId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($monthId['max'])) {
                $this->addUsingAlias(ContractPeer::MONTH_ID, $monthId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::MONTH_ID, $monthId, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank >= 12
     * $query->filterBySortableRank(array('max' => 12)); // WHERE sortable_rank <= 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(ContractPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(ContractPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ContractPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Cost object
     *
     * @param   Cost|PropelObjectCollection $cost The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ContractQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCost($cost, $comparison = null)
    {
        if ($cost instanceof Cost) {
            return $this
                ->addUsingAlias(ContractPeer::COST_ID, $cost->getId(), $comparison);
        } elseif ($cost instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContractPeer::COST_ID, $cost->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCost() only accepts arguments of type Cost or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Cost relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function joinCost($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Cost');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Cost');
        }

        return $this;
    }

    /**
     * Use the Cost relation Cost object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\CostQuery A secondary query class using the current class as primary query
     */
    public function useCostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Cost', '\Oppen\ProjectBundle\Model\CostQuery');
    }

    /**
     * Filter the query by a related Template object
     *
     * @param   Template|PropelObjectCollection $template The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ContractQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTemplate($template, $comparison = null)
    {
        if ($template instanceof Template) {
            return $this
                ->addUsingAlias(ContractPeer::TEMPLATE_ID, $template->getId(), $comparison);
        } elseif ($template instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContractPeer::TEMPLATE_ID, $template->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTemplate() only accepts arguments of type Template or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Template relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function joinTemplate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Template');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Template');
        }

        return $this;
    }

    /**
     * Use the Template relation Template object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\TemplateQuery A secondary query class using the current class as primary query
     */
    public function useTemplateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTemplate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Template', '\Oppen\ProjectBundle\Model\TemplateQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ContractQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFile($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(ContractPeer::FILE_ID, $file->getId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContractPeer::FILE_ID, $file->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFile() only accepts arguments of type File or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the File relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function joinFile($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('File');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'File');
        }

        return $this;
    }

    /**
     * Use the File relation File object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\Oppen\ProjectBundle\Model\FileQuery');
    }

    /**
     * Filter the query by a related Doc object
     *
     * @param   Doc|PropelObjectCollection $doc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ContractQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDoc($doc, $comparison = null)
    {
        if ($doc instanceof Doc) {
            return $this
                ->addUsingAlias(ContractPeer::DOC_ID, $doc->getId(), $comparison);
        } elseif ($doc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContractPeer::DOC_ID, $doc->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDoc() only accepts arguments of type Doc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Doc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function joinDoc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Doc');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Doc');
        }

        return $this;
    }

    /**
     * Use the Doc relation Doc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\DocQuery A secondary query class using the current class as primary query
     */
    public function useDocQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDoc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Doc', '\Oppen\ProjectBundle\Model\DocQuery');
    }

    /**
     * Filter the query by a related Month object
     *
     * @param   Month|PropelObjectCollection $month The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ContractQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMonth($month, $comparison = null)
    {
        if ($month instanceof Month) {
            return $this
                ->addUsingAlias(ContractPeer::MONTH_ID, $month->getId(), $comparison);
        } elseif ($month instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ContractPeer::MONTH_ID, $month->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByMonth() only accepts arguments of type Month or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Month relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function joinMonth($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Month');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Month');
        }

        return $this;
    }

    /**
     * Use the Month relation Month object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\MonthQuery A secondary query class using the current class as primary query
     */
    public function useMonthQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMonth($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Month', '\Oppen\ProjectBundle\Model\MonthQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Contract $contract Object to remove from the list of results
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function prune($contract = null)
    {
        if ($contract) {
            $this->addUsingAlias(ContractPeer::ID, $contract->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param int $scope Scope to determine which objects node to return
     *
     * @return ContractQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {

        ContractPeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    ContractQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(ContractPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    ContractQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(ContractPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(ContractPeer::RANK_COL));
                break;
            default:
                throw new PropelException('ContractQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    Contract
     */
    public function findOneByRank($rank, $scope = null, PropelPDO $con = null)
    {

        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param int $scope Scope to determine which objects node to return

     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope = null, $con = null)
    {


        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param int $scope Scope to determine which objects node to return

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . ContractPeer::RANK_COL . ')');

        ContractPeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     int $scope		The scope value as scalar type or array($value1, ...).

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . ContractPeer::RANK_COL . ')');
        ContractPeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ContractPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

}
