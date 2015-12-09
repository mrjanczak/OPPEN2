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
use AppBundle\Model\Report;
use AppBundle\Model\ReportEntry;
use AppBundle\Model\ReportEntryPeer;
use AppBundle\Model\ReportEntryQuery;

/**
 * @method ReportEntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ReportEntryQuery orderByNo($order = Criteria::ASC) Order by the no column
 * @method ReportEntryQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method ReportEntryQuery orderBySymbol($order = Criteria::ASC) Order by the symbol column
 * @method ReportEntryQuery orderByFormula($order = Criteria::ASC) Order by the formula column
 * @method ReportEntryQuery orderByReportId($order = Criteria::ASC) Order by the report_id column
 * @method ReportEntryQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method ReportEntryQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method ReportEntryQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 *
 * @method ReportEntryQuery groupById() Group by the id column
 * @method ReportEntryQuery groupByNo() Group by the no column
 * @method ReportEntryQuery groupByName() Group by the name column
 * @method ReportEntryQuery groupBySymbol() Group by the symbol column
 * @method ReportEntryQuery groupByFormula() Group by the formula column
 * @method ReportEntryQuery groupByReportId() Group by the report_id column
 * @method ReportEntryQuery groupByTreeLeft() Group by the tree_left column
 * @method ReportEntryQuery groupByTreeRight() Group by the tree_right column
 * @method ReportEntryQuery groupByTreeLevel() Group by the tree_level column
 *
 * @method ReportEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ReportEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ReportEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method ReportEntryQuery leftJoinReport($relationAlias = null) Adds a LEFT JOIN clause to the query using the Report relation
 * @method ReportEntryQuery rightJoinReport($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Report relation
 * @method ReportEntryQuery innerJoinReport($relationAlias = null) Adds a INNER JOIN clause to the query using the Report relation
 *
 * @method ReportEntry findOne(PropelPDO $con = null) Return the first ReportEntry matching the query
 * @method ReportEntry findOneOrCreate(PropelPDO $con = null) Return the first ReportEntry matching the query, or a new ReportEntry object populated from the query conditions when no match is found
 *
 * @method ReportEntry findOneByNo(string $no) Return the first ReportEntry filtered by the no column
 * @method ReportEntry findOneByName(string $name) Return the first ReportEntry filtered by the name column
 * @method ReportEntry findOneBySymbol(string $symbol) Return the first ReportEntry filtered by the symbol column
 * @method ReportEntry findOneByFormula(string $formula) Return the first ReportEntry filtered by the formula column
 * @method ReportEntry findOneByReportId(int $report_id) Return the first ReportEntry filtered by the report_id column
 * @method ReportEntry findOneByTreeLeft(int $tree_left) Return the first ReportEntry filtered by the tree_left column
 * @method ReportEntry findOneByTreeRight(int $tree_right) Return the first ReportEntry filtered by the tree_right column
 * @method ReportEntry findOneByTreeLevel(int $tree_level) Return the first ReportEntry filtered by the tree_level column
 *
 * @method array findById(int $id) Return ReportEntry objects filtered by the id column
 * @method array findByNo(string $no) Return ReportEntry objects filtered by the no column
 * @method array findByName(string $name) Return ReportEntry objects filtered by the name column
 * @method array findBySymbol(string $symbol) Return ReportEntry objects filtered by the symbol column
 * @method array findByFormula(string $formula) Return ReportEntry objects filtered by the formula column
 * @method array findByReportId(int $report_id) Return ReportEntry objects filtered by the report_id column
 * @method array findByTreeLeft(int $tree_left) Return ReportEntry objects filtered by the tree_left column
 * @method array findByTreeRight(int $tree_right) Return ReportEntry objects filtered by the tree_right column
 * @method array findByTreeLevel(int $tree_level) Return ReportEntry objects filtered by the tree_level column
 */
abstract class BaseReportEntryQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseReportEntryQuery object.
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
            $modelName = 'AppBundle\\Model\\ReportEntry';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ReportEntryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ReportEntryQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ReportEntryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ReportEntryQuery) {
            return $criteria;
        }
        $query = new ReportEntryQuery(null, null, $modelAlias);

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
     * @return   ReportEntry|ReportEntry[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ReportEntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ReportEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 ReportEntry A model object, or null if the key is not found
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
     * @return                 ReportEntry A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `no`, `name`, `symbol`, `formula`, `report_id`, `tree_left`, `tree_right`, `tree_level` FROM `report_entry` WHERE `id` = :p0';
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
            $obj = new ReportEntry();
            $obj->hydrate($row);
            ReportEntryPeer::addInstanceToPool($obj, (string) $key);
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
     * @return ReportEntry|ReportEntry[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|ReportEntry[]|mixed the list of results, formatted by the current formatter
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
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ReportEntryPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ReportEntryPeer::ID, $keys, Criteria::IN);
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
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ReportEntryPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ReportEntryPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the no column
     *
     * Example usage:
     * <code>
     * $query->filterByNo('fooValue');   // WHERE no = 'fooValue'
     * $query->filterByNo('%fooValue%'); // WHERE no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $no The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByNo($no = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($no)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $no)) {
                $no = str_replace('*', '%', $no);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::NO, $no, $comparison);
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
     * @return ReportEntryQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ReportEntryPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the symbol column
     *
     * Example usage:
     * <code>
     * $query->filterBySymbol('fooValue');   // WHERE symbol = 'fooValue'
     * $query->filterBySymbol('%fooValue%'); // WHERE symbol LIKE '%fooValue%'
     * </code>
     *
     * @param     string $symbol The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterBySymbol($symbol = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($symbol)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $symbol)) {
                $symbol = str_replace('*', '%', $symbol);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::SYMBOL, $symbol, $comparison);
    }

    /**
     * Filter the query on the formula column
     *
     * Example usage:
     * <code>
     * $query->filterByFormula('fooValue');   // WHERE formula = 'fooValue'
     * $query->filterByFormula('%fooValue%'); // WHERE formula LIKE '%fooValue%'
     * </code>
     *
     * @param     string $formula The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByFormula($formula = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($formula)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $formula)) {
                $formula = str_replace('*', '%', $formula);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::FORMULA, $formula, $comparison);
    }

    /**
     * Filter the query on the report_id column
     *
     * Example usage:
     * <code>
     * $query->filterByReportId(1234); // WHERE report_id = 1234
     * $query->filterByReportId(array(12, 34)); // WHERE report_id IN (12, 34)
     * $query->filterByReportId(array('min' => 12)); // WHERE report_id >= 12
     * $query->filterByReportId(array('max' => 12)); // WHERE report_id <= 12
     * </code>
     *
     * @see       filterByReport()
     *
     * @param     mixed $reportId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByReportId($reportId = null, $comparison = null)
    {
        if (is_array($reportId)) {
            $useMinMax = false;
            if (isset($reportId['min'])) {
                $this->addUsingAlias(ReportEntryPeer::REPORT_ID, $reportId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($reportId['max'])) {
                $this->addUsingAlias(ReportEntryPeer::REPORT_ID, $reportId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::REPORT_ID, $reportId, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left >= 12
     * $query->filterByTreeLeft(array('max' => 12)); // WHERE tree_left <= 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(ReportEntryPeer::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(ReportEntryPeer::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right >= 12
     * $query->filterByTreeRight(array('max' => 12)); // WHERE tree_right <= 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(ReportEntryPeer::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(ReportEntryPeer::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level >= 12
     * $query->filterByTreeLevel(array('max' => 12)); // WHERE tree_level <= 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(ReportEntryPeer::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(ReportEntryPeer::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ReportEntryPeer::TREE_LEVEL, $treeLevel, $comparison);
    }

    /**
     * Filter the query by a related Report object
     *
     * @param   Report|PropelObjectCollection $report The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 ReportEntryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByReport($report, $comparison = null)
    {
        if ($report instanceof Report) {
            return $this
                ->addUsingAlias(ReportEntryPeer::REPORT_ID, $report->getId(), $comparison);
        } elseif ($report instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ReportEntryPeer::REPORT_ID, $report->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByReport() only accepts arguments of type Report or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Report relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function joinReport($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Report');

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
            $this->addJoinObject($join, 'Report');
        }

        return $this;
    }

    /**
     * Use the Report relation Report object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\ReportQuery A secondary query class using the current class as primary query
     */
    public function useReportQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinReport($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Report', '\AppBundle\Model\ReportQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ReportEntry $reportEntry Object to remove from the list of results
     *
     * @return ReportEntryQuery The current query, for fluid interface
     */
    public function prune($reportEntry = null)
    {
        if ($reportEntry) {
            $this->addUsingAlias(ReportEntryPeer::ID, $reportEntry->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // nested_set behavior

    /**
     * Filter the query to restrict the result to root objects
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function treeRoots()
    {
        return $this->addUsingAlias(ReportEntryPeer::LEFT_COL, 1, Criteria::EQUAL);
    }

    /**
     * Returns the objects in a certain tree, from the tree scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function inTree($scope = null)
    {
        return $this->addUsingAlias(ReportEntryPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     ReportEntry $reportEntry The object to use for descendant search
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function descendantsOf($reportEntry)
    {
        return $this
            ->inTree($reportEntry->getScopeValue())
            ->addUsingAlias(ReportEntryPeer::LEFT_COL, $reportEntry->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(ReportEntryPeer::LEFT_COL, $reportEntry->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ReportEntry $reportEntry The object to use for branch search
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function branchOf($reportEntry)
    {
        return $this
            ->inTree($reportEntry->getScopeValue())
            ->addUsingAlias(ReportEntryPeer::LEFT_COL, $reportEntry->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(ReportEntryPeer::LEFT_COL, $reportEntry->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     ReportEntry $reportEntry The object to use for child search
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function childrenOf($reportEntry)
    {
        return $this
            ->descendantsOf($reportEntry)
            ->addUsingAlias(ReportEntryPeer::LEVEL_COL, $reportEntry->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     ReportEntry $reportEntry The object to use for sibling search
     * @param      PropelPDO $con Connection to use.
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function siblingsOf($reportEntry, PropelPDO $con = null)
    {
        if ($reportEntry->isRoot()) {
            return $this->
                add(ReportEntryPeer::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($reportEntry->getParent($con))
                ->prune($reportEntry);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     ReportEntry $reportEntry The object to use for ancestors search
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function ancestorsOf($reportEntry)
    {
        return $this
            ->inTree($reportEntry->getScopeValue())
            ->addUsingAlias(ReportEntryPeer::LEFT_COL, $reportEntry->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(ReportEntryPeer::RIGHT_COL, $reportEntry->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     ReportEntry $reportEntry The object to use for roots search
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function rootsOf($reportEntry)
    {
        return $this
            ->inTree($reportEntry->getScopeValue())
            ->addUsingAlias(ReportEntryPeer::LEFT_COL, $reportEntry->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(ReportEntryPeer::RIGHT_COL, $reportEntry->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(ReportEntryPeer::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(ReportEntryPeer::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    ReportEntryQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addAscendingOrderByColumn(ReportEntryPeer::RIGHT_COL);
        } else {
            return $this
                ->addDescendingOrderByColumn(ReportEntryPeer::RIGHT_COL);
        }
    }

    /**
     * Returns a root node for the tree
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     ReportEntry The tree root object
     */
    public function findRoot($scope = null, $con = null)
    {
        return $this
            ->addUsingAlias(ReportEntryPeer::LEFT_COL, 1, Criteria::EQUAL)
            ->inTree($scope)
            ->findOne($con);
    }

    /**
     * Returns the root objects for all trees.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return    mixed the list of results, formatted by the current formatter
     */
    public function findRoots($con = null)
    {
        return $this
            ->treeRoots()
            ->find($con);
    }

    /**
     * Returns a tree of objects
     *
     * @param      int $scope		Scope to determine which tree node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findTree($scope = null, $con = null)
    {
        return $this
            ->inTree($scope)
            ->orderByBranch()
            ->find($con);
    }

}
