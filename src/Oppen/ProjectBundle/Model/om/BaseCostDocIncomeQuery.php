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
use Oppen\ProjectBundle\Model\CostDoc;
use Oppen\ProjectBundle\Model\CostDocIncome;
use Oppen\ProjectBundle\Model\CostDocIncomePeer;
use Oppen\ProjectBundle\Model\CostDocIncomeQuery;
use Oppen\ProjectBundle\Model\Income;

/**
 * @method CostDocIncomeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CostDocIncomeQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method CostDocIncomeQuery orderByCostDocId($order = Criteria::ASC) Order by the cost_doc_id column
 * @method CostDocIncomeQuery orderByIncomeId($order = Criteria::ASC) Order by the income_id column
 *
 * @method CostDocIncomeQuery groupById() Group by the id column
 * @method CostDocIncomeQuery groupByValue() Group by the value column
 * @method CostDocIncomeQuery groupByCostDocId() Group by the cost_doc_id column
 * @method CostDocIncomeQuery groupByIncomeId() Group by the income_id column
 *
 * @method CostDocIncomeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CostDocIncomeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CostDocIncomeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CostDocIncomeQuery leftJoinCostDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the CostDoc relation
 * @method CostDocIncomeQuery rightJoinCostDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CostDoc relation
 * @method CostDocIncomeQuery innerJoinCostDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the CostDoc relation
 *
 * @method CostDocIncomeQuery leftJoinIncome($relationAlias = null) Adds a LEFT JOIN clause to the query using the Income relation
 * @method CostDocIncomeQuery rightJoinIncome($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Income relation
 * @method CostDocIncomeQuery innerJoinIncome($relationAlias = null) Adds a INNER JOIN clause to the query using the Income relation
 *
 * @method CostDocIncome findOne(PropelPDO $con = null) Return the first CostDocIncome matching the query
 * @method CostDocIncome findOneOrCreate(PropelPDO $con = null) Return the first CostDocIncome matching the query, or a new CostDocIncome object populated from the query conditions when no match is found
 *
 * @method CostDocIncome findOneByValue(double $value) Return the first CostDocIncome filtered by the value column
 * @method CostDocIncome findOneByCostDocId(int $cost_doc_id) Return the first CostDocIncome filtered by the cost_doc_id column
 * @method CostDocIncome findOneByIncomeId(int $income_id) Return the first CostDocIncome filtered by the income_id column
 *
 * @method array findById(int $id) Return CostDocIncome objects filtered by the id column
 * @method array findByValue(double $value) Return CostDocIncome objects filtered by the value column
 * @method array findByCostDocId(int $cost_doc_id) Return CostDocIncome objects filtered by the cost_doc_id column
 * @method array findByIncomeId(int $income_id) Return CostDocIncome objects filtered by the income_id column
 */
abstract class BaseCostDocIncomeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCostDocIncomeQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\CostDocIncome';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CostDocIncomeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CostDocIncomeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CostDocIncomeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CostDocIncomeQuery) {
            return $criteria;
        }
        $query = new CostDocIncomeQuery(null, null, $modelAlias);

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
     * @return   CostDocIncome|CostDocIncome[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CostDocIncomePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CostDocIncomePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CostDocIncome A model object, or null if the key is not found
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
     * @return                 CostDocIncome A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `value`, `cost_doc_id`, `income_id` FROM `cost_doc_income` WHERE `id` = :p0';
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
            $obj = new CostDocIncome();
            $obj->hydrate($row);
            CostDocIncomePeer::addInstanceToPool($obj, (string) $key);
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
     * @return CostDocIncome|CostDocIncome[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CostDocIncome[]|mixed the list of results, formatted by the current formatter
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
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CostDocIncomePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CostDocIncomePeer::ID, $keys, Criteria::IN);
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
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CostDocIncomePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CostDocIncomePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocIncomePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue(1234); // WHERE value = 1234
     * $query->filterByValue(array(12, 34)); // WHERE value IN (12, 34)
     * $query->filterByValue(array('min' => 12)); // WHERE value >= 12
     * $query->filterByValue(array('max' => 12)); // WHERE value <= 12
     * </code>
     *
     * @param     mixed $value The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (is_array($value)) {
            $useMinMax = false;
            if (isset($value['min'])) {
                $this->addUsingAlias(CostDocIncomePeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($value['max'])) {
                $this->addUsingAlias(CostDocIncomePeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocIncomePeer::VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the cost_doc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCostDocId(1234); // WHERE cost_doc_id = 1234
     * $query->filterByCostDocId(array(12, 34)); // WHERE cost_doc_id IN (12, 34)
     * $query->filterByCostDocId(array('min' => 12)); // WHERE cost_doc_id >= 12
     * $query->filterByCostDocId(array('max' => 12)); // WHERE cost_doc_id <= 12
     * </code>
     *
     * @see       filterByCostDoc()
     *
     * @param     mixed $costDocId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function filterByCostDocId($costDocId = null, $comparison = null)
    {
        if (is_array($costDocId)) {
            $useMinMax = false;
            if (isset($costDocId['min'])) {
                $this->addUsingAlias(CostDocIncomePeer::COST_DOC_ID, $costDocId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($costDocId['max'])) {
                $this->addUsingAlias(CostDocIncomePeer::COST_DOC_ID, $costDocId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocIncomePeer::COST_DOC_ID, $costDocId, $comparison);
    }

    /**
     * Filter the query on the income_id column
     *
     * Example usage:
     * <code>
     * $query->filterByIncomeId(1234); // WHERE income_id = 1234
     * $query->filterByIncomeId(array(12, 34)); // WHERE income_id IN (12, 34)
     * $query->filterByIncomeId(array('min' => 12)); // WHERE income_id >= 12
     * $query->filterByIncomeId(array('max' => 12)); // WHERE income_id <= 12
     * </code>
     *
     * @see       filterByIncome()
     *
     * @param     mixed $incomeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function filterByIncomeId($incomeId = null, $comparison = null)
    {
        if (is_array($incomeId)) {
            $useMinMax = false;
            if (isset($incomeId['min'])) {
                $this->addUsingAlias(CostDocIncomePeer::INCOME_ID, $incomeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($incomeId['max'])) {
                $this->addUsingAlias(CostDocIncomePeer::INCOME_ID, $incomeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocIncomePeer::INCOME_ID, $incomeId, $comparison);
    }

    /**
     * Filter the query by a related CostDoc object
     *
     * @param   CostDoc|PropelObjectCollection $costDoc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CostDocIncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCostDoc($costDoc, $comparison = null)
    {
        if ($costDoc instanceof CostDoc) {
            return $this
                ->addUsingAlias(CostDocIncomePeer::COST_DOC_ID, $costDoc->getId(), $comparison);
        } elseif ($costDoc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CostDocIncomePeer::COST_DOC_ID, $costDoc->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCostDoc() only accepts arguments of type CostDoc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CostDoc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function joinCostDoc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CostDoc');

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
            $this->addJoinObject($join, 'CostDoc');
        }

        return $this;
    }

    /**
     * Use the CostDoc relation CostDoc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\CostDocQuery A secondary query class using the current class as primary query
     */
    public function useCostDocQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCostDoc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CostDoc', '\Oppen\ProjectBundle\Model\CostDocQuery');
    }

    /**
     * Filter the query by a related Income object
     *
     * @param   Income|PropelObjectCollection $income The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CostDocIncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncome($income, $comparison = null)
    {
        if ($income instanceof Income) {
            return $this
                ->addUsingAlias(CostDocIncomePeer::INCOME_ID, $income->getId(), $comparison);
        } elseif ($income instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CostDocIncomePeer::INCOME_ID, $income->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByIncome() only accepts arguments of type Income or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Income relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function joinIncome($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Income');

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
            $this->addJoinObject($join, 'Income');
        }

        return $this;
    }

    /**
     * Use the Income relation Income object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\IncomeQuery A secondary query class using the current class as primary query
     */
    public function useIncomeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncome($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Income', '\Oppen\ProjectBundle\Model\IncomeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CostDocIncome $costDocIncome Object to remove from the list of results
     *
     * @return CostDocIncomeQuery The current query, for fluid interface
     */
    public function prune($costDocIncome = null)
    {
        if ($costDocIncome) {
            $this->addUsingAlias(CostDocIncomePeer::ID, $costDocIncome->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
