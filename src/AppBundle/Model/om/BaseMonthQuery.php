<?php

namespace AppBundle\Model\om;

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
use AppBundle\Model\Contract;
use AppBundle\Model\Doc;
use AppBundle\Model\Month;
use AppBundle\Model\MonthPeer;
use AppBundle\Model\MonthQuery;
use AppBundle\Model\Year;

/**
 * @method MonthQuery orderById($order = Criteria::ASC) Order by the id column
 * @method MonthQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method MonthQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 * @method MonthQuery orderByIsClosed($order = Criteria::ASC) Order by the is_closed column
 * @method MonthQuery orderByFromDate($order = Criteria::ASC) Order by the from_date column
 * @method MonthQuery orderByToDate($order = Criteria::ASC) Order by the to_date column
 * @method MonthQuery orderByYearId($order = Criteria::ASC) Order by the year_id column
 * @method MonthQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method MonthQuery groupById() Group by the id column
 * @method MonthQuery groupByName() Group by the name column
 * @method MonthQuery groupByIsActive() Group by the is_active column
 * @method MonthQuery groupByIsClosed() Group by the is_closed column
 * @method MonthQuery groupByFromDate() Group by the from_date column
 * @method MonthQuery groupByToDate() Group by the to_date column
 * @method MonthQuery groupByYearId() Group by the year_id column
 * @method MonthQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method MonthQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method MonthQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method MonthQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method MonthQuery leftJoinYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the Year relation
 * @method MonthQuery rightJoinYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Year relation
 * @method MonthQuery innerJoinYear($relationAlias = null) Adds a INNER JOIN clause to the query using the Year relation
 *
 * @method MonthQuery leftJoinDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the Doc relation
 * @method MonthQuery rightJoinDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Doc relation
 * @method MonthQuery innerJoinDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the Doc relation
 *
 * @method MonthQuery leftJoinContract($relationAlias = null) Adds a LEFT JOIN clause to the query using the Contract relation
 * @method MonthQuery rightJoinContract($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Contract relation
 * @method MonthQuery innerJoinContract($relationAlias = null) Adds a INNER JOIN clause to the query using the Contract relation
 *
 * @method Month findOne(PropelPDO $con = null) Return the first Month matching the query
 * @method Month findOneOrCreate(PropelPDO $con = null) Return the first Month matching the query, or a new Month object populated from the query conditions when no match is found
 *
 * @method Month findOneByName(string $name) Return the first Month filtered by the name column
 * @method Month findOneByIsActive(boolean $is_active) Return the first Month filtered by the is_active column
 * @method Month findOneByIsClosed(boolean $is_closed) Return the first Month filtered by the is_closed column
 * @method Month findOneByFromDate(string $from_date) Return the first Month filtered by the from_date column
 * @method Month findOneByToDate(string $to_date) Return the first Month filtered by the to_date column
 * @method Month findOneByYearId(int $year_id) Return the first Month filtered by the year_id column
 * @method Month findOneBySortableRank(int $sortable_rank) Return the first Month filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return Month objects filtered by the id column
 * @method array findByName(string $name) Return Month objects filtered by the name column
 * @method array findByIsActive(boolean $is_active) Return Month objects filtered by the is_active column
 * @method array findByIsClosed(boolean $is_closed) Return Month objects filtered by the is_closed column
 * @method array findByFromDate(string $from_date) Return Month objects filtered by the from_date column
 * @method array findByToDate(string $to_date) Return Month objects filtered by the to_date column
 * @method array findByYearId(int $year_id) Return Month objects filtered by the year_id column
 * @method array findBySortableRank(int $sortable_rank) Return Month objects filtered by the sortable_rank column
 */
abstract class BaseMonthQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseMonthQuery object.
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
            $modelName = 'AppBundle\\Model\\Month';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new MonthQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   MonthQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return MonthQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof MonthQuery) {
            return $criteria;
        }
        $query = new MonthQuery(null, null, $modelAlias);

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
     * @return   Month|Month[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = MonthPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Month A model object, or null if the key is not found
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
     * @return                 Month A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `is_active`, `is_closed`, `from_date`, `to_date`, `year_id`, `sortable_rank` FROM `month` WHERE `id` = :p0';
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
            $obj = new Month();
            $obj->hydrate($row);
            MonthPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Month|Month[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Month[]|mixed the list of results, formatted by the current formatter
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
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(MonthPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(MonthPeer::ID, $keys, Criteria::IN);
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
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(MonthPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(MonthPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MonthPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(MonthPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the is_active column
     *
     * Example usage:
     * <code>
     * $query->filterByIsActive(true); // WHERE is_active = true
     * $query->filterByIsActive('yes'); // WHERE is_active = true
     * </code>
     *
     * @param     boolean|string $isActive The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $isActive = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MonthPeer::IS_ACTIVE, $isActive, $comparison);
    }

    /**
     * Filter the query on the is_closed column
     *
     * Example usage:
     * <code>
     * $query->filterByIsClosed(true); // WHERE is_closed = true
     * $query->filterByIsClosed('yes'); // WHERE is_closed = true
     * </code>
     *
     * @param     boolean|string $isClosed The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByIsClosed($isClosed = null, $comparison = null)
    {
        if (is_string($isClosed)) {
            $isClosed = in_array(strtolower($isClosed), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(MonthPeer::IS_CLOSED, $isClosed, $comparison);
    }

    /**
     * Filter the query on the from_date column
     *
     * Example usage:
     * <code>
     * $query->filterByFromDate('2011-03-14'); // WHERE from_date = '2011-03-14'
     * $query->filterByFromDate('now'); // WHERE from_date = '2011-03-14'
     * $query->filterByFromDate(array('max' => 'yesterday')); // WHERE from_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $fromDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByFromDate($fromDate = null, $comparison = null)
    {
        if (is_array($fromDate)) {
            $useMinMax = false;
            if (isset($fromDate['min'])) {
                $this->addUsingAlias(MonthPeer::FROM_DATE, $fromDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fromDate['max'])) {
                $this->addUsingAlias(MonthPeer::FROM_DATE, $fromDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MonthPeer::FROM_DATE, $fromDate, $comparison);
    }

    /**
     * Filter the query on the to_date column
     *
     * Example usage:
     * <code>
     * $query->filterByToDate('2011-03-14'); // WHERE to_date = '2011-03-14'
     * $query->filterByToDate('now'); // WHERE to_date = '2011-03-14'
     * $query->filterByToDate(array('max' => 'yesterday')); // WHERE to_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $toDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByToDate($toDate = null, $comparison = null)
    {
        if (is_array($toDate)) {
            $useMinMax = false;
            if (isset($toDate['min'])) {
                $this->addUsingAlias(MonthPeer::TO_DATE, $toDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($toDate['max'])) {
                $this->addUsingAlias(MonthPeer::TO_DATE, $toDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MonthPeer::TO_DATE, $toDate, $comparison);
    }

    /**
     * Filter the query on the year_id column
     *
     * Example usage:
     * <code>
     * $query->filterByYearId(1234); // WHERE year_id = 1234
     * $query->filterByYearId(array(12, 34)); // WHERE year_id IN (12, 34)
     * $query->filterByYearId(array('min' => 12)); // WHERE year_id >= 12
     * $query->filterByYearId(array('max' => 12)); // WHERE year_id <= 12
     * </code>
     *
     * @see       filterByYear()
     *
     * @param     mixed $yearId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterByYearId($yearId = null, $comparison = null)
    {
        if (is_array($yearId)) {
            $useMinMax = false;
            if (isset($yearId['min'])) {
                $this->addUsingAlias(MonthPeer::YEAR_ID, $yearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($yearId['max'])) {
                $this->addUsingAlias(MonthPeer::YEAR_ID, $yearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MonthPeer::YEAR_ID, $yearId, $comparison);
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
     * @return MonthQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(MonthPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(MonthPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(MonthPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Year object
     *
     * @param   Year|PropelObjectCollection $year The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MonthQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByYear($year, $comparison = null)
    {
        if ($year instanceof Year) {
            return $this
                ->addUsingAlias(MonthPeer::YEAR_ID, $year->getId(), $comparison);
        } elseif ($year instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(MonthPeer::YEAR_ID, $year->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByYear() only accepts arguments of type Year or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Year relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function joinYear($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Year');

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
            $this->addJoinObject($join, 'Year');
        }

        return $this;
    }

    /**
     * Use the Year relation Year object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\YearQuery A secondary query class using the current class as primary query
     */
    public function useYearQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinYear($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Year', '\AppBundle\Model\YearQuery');
    }

    /**
     * Filter the query by a related Doc object
     *
     * @param   Doc|PropelObjectCollection $doc  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MonthQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDoc($doc, $comparison = null)
    {
        if ($doc instanceof Doc) {
            return $this
                ->addUsingAlias(MonthPeer::ID, $doc->getMonthId(), $comparison);
        } elseif ($doc instanceof PropelObjectCollection) {
            return $this
                ->useDocQuery()
                ->filterByPrimaryKeys($doc->getPrimaryKeys())
                ->endUse();
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
     * @return MonthQuery The current query, for fluid interface
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
     * @return   \AppBundle\Model\DocQuery A secondary query class using the current class as primary query
     */
    public function useDocQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDoc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Doc', '\AppBundle\Model\DocQuery');
    }

    /**
     * Filter the query by a related Contract object
     *
     * @param   Contract|PropelObjectCollection $contract  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 MonthQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByContract($contract, $comparison = null)
    {
        if ($contract instanceof Contract) {
            return $this
                ->addUsingAlias(MonthPeer::ID, $contract->getMonthId(), $comparison);
        } elseif ($contract instanceof PropelObjectCollection) {
            return $this
                ->useContractQuery()
                ->filterByPrimaryKeys($contract->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByContract() only accepts arguments of type Contract or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Contract relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function joinContract($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Contract');

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
            $this->addJoinObject($join, 'Contract');
        }

        return $this;
    }

    /**
     * Use the Contract relation Contract object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\ContractQuery A secondary query class using the current class as primary query
     */
    public function useContractQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinContract($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Contract', '\AppBundle\Model\ContractQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Month $month Object to remove from the list of results
     *
     * @return MonthQuery The current query, for fluid interface
     */
    public function prune($month = null)
    {
        if ($month) {
            $this->addUsingAlias(MonthPeer::ID, $month->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    MonthQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {


        return $this
            ->addUsingAlias(MonthPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    MonthQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(MonthPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(MonthPeer::RANK_COL));
                break;
            default:
                throw new PropelException('MonthQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     PropelPDO $con optional connection
     *
     * @return    Month
     */
    public function findOneByRank($rank, PropelPDO $con = null)
    {

        return $this
            ->filterByRank($rank)
            ->findOne($con);
    }

    /**
     * Returns the list of objects
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($con = null)
    {


        return $this
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . MonthPeer::RANK_COL . ')');
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . MonthPeer::RANK_COL . ')');
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
            $con = Propel::getConnection(MonthPeer::DATABASE_NAME);
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
