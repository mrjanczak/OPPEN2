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
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostDoc;
use Oppen\ProjectBundle\Model\CostDocIncome;
use Oppen\ProjectBundle\Model\CostDocPeer;
use Oppen\ProjectBundle\Model\CostDocQuery;
use Oppen\ProjectBundle\Model\Doc;

/**
 * @method CostDocQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CostDocQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method CostDocQuery orderByDesc($order = Criteria::ASC) Order by the desc column
 * @method CostDocQuery orderByCostId($order = Criteria::ASC) Order by the cost_id column
 * @method CostDocQuery orderByDocId($order = Criteria::ASC) Order by the doc_id column
 *
 * @method CostDocQuery groupById() Group by the id column
 * @method CostDocQuery groupByValue() Group by the value column
 * @method CostDocQuery groupByDesc() Group by the desc column
 * @method CostDocQuery groupByCostId() Group by the cost_id column
 * @method CostDocQuery groupByDocId() Group by the doc_id column
 *
 * @method CostDocQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CostDocQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CostDocQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CostDocQuery leftJoinCost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cost relation
 * @method CostDocQuery rightJoinCost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cost relation
 * @method CostDocQuery innerJoinCost($relationAlias = null) Adds a INNER JOIN clause to the query using the Cost relation
 *
 * @method CostDocQuery leftJoinDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the Doc relation
 * @method CostDocQuery rightJoinDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Doc relation
 * @method CostDocQuery innerJoinDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the Doc relation
 *
 * @method CostDocQuery leftJoinCostDocIncome($relationAlias = null) Adds a LEFT JOIN clause to the query using the CostDocIncome relation
 * @method CostDocQuery rightJoinCostDocIncome($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CostDocIncome relation
 * @method CostDocQuery innerJoinCostDocIncome($relationAlias = null) Adds a INNER JOIN clause to the query using the CostDocIncome relation
 *
 * @method CostDoc findOne(PropelPDO $con = null) Return the first CostDoc matching the query
 * @method CostDoc findOneOrCreate(PropelPDO $con = null) Return the first CostDoc matching the query, or a new CostDoc object populated from the query conditions when no match is found
 *
 * @method CostDoc findOneByValue(double $value) Return the first CostDoc filtered by the value column
 * @method CostDoc findOneByDesc(string $desc) Return the first CostDoc filtered by the desc column
 * @method CostDoc findOneByCostId(int $cost_id) Return the first CostDoc filtered by the cost_id column
 * @method CostDoc findOneByDocId(int $doc_id) Return the first CostDoc filtered by the doc_id column
 *
 * @method array findById(int $id) Return CostDoc objects filtered by the id column
 * @method array findByValue(double $value) Return CostDoc objects filtered by the value column
 * @method array findByDesc(string $desc) Return CostDoc objects filtered by the desc column
 * @method array findByCostId(int $cost_id) Return CostDoc objects filtered by the cost_id column
 * @method array findByDocId(int $doc_id) Return CostDoc objects filtered by the doc_id column
 */
abstract class BaseCostDocQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCostDocQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\CostDoc';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CostDocQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CostDocQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CostDocQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CostDocQuery) {
            return $criteria;
        }
        $query = new CostDocQuery(null, null, $modelAlias);

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
     * @return   CostDoc|CostDoc[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CostDocPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CostDocPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 CostDoc A model object, or null if the key is not found
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
     * @return                 CostDoc A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `value`, `desc`, `cost_id`, `doc_id` FROM `cost_doc` WHERE `id` = :p0';
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
            $obj = new CostDoc();
            $obj->hydrate($row);
            CostDocPeer::addInstanceToPool($obj, (string) $key);
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
     * @return CostDoc|CostDoc[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|CostDoc[]|mixed the list of results, formatted by the current formatter
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
     * @return CostDocQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CostDocPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CostDocQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CostDocPeer::ID, $keys, Criteria::IN);
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
     * @return CostDocQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CostDocPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CostDocPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocPeer::ID, $id, $comparison);
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
     * @return CostDocQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (is_array($value)) {
            $useMinMax = false;
            if (isset($value['min'])) {
                $this->addUsingAlias(CostDocPeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($value['max'])) {
                $this->addUsingAlias(CostDocPeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocPeer::VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the desc column
     *
     * Example usage:
     * <code>
     * $query->filterByDesc('fooValue');   // WHERE desc = 'fooValue'
     * $query->filterByDesc('%fooValue%'); // WHERE desc LIKE '%fooValue%'
     * </code>
     *
     * @param     string $desc The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CostDocQuery The current query, for fluid interface
     */
    public function filterByDesc($desc = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($desc)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $desc)) {
                $desc = str_replace('*', '%', $desc);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CostDocPeer::DESC, $desc, $comparison);
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
     * @return CostDocQuery The current query, for fluid interface
     */
    public function filterByCostId($costId = null, $comparison = null)
    {
        if (is_array($costId)) {
            $useMinMax = false;
            if (isset($costId['min'])) {
                $this->addUsingAlias(CostDocPeer::COST_ID, $costId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($costId['max'])) {
                $this->addUsingAlias(CostDocPeer::COST_ID, $costId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocPeer::COST_ID, $costId, $comparison);
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
     * @return CostDocQuery The current query, for fluid interface
     */
    public function filterByDocId($docId = null, $comparison = null)
    {
        if (is_array($docId)) {
            $useMinMax = false;
            if (isset($docId['min'])) {
                $this->addUsingAlias(CostDocPeer::DOC_ID, $docId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($docId['max'])) {
                $this->addUsingAlias(CostDocPeer::DOC_ID, $docId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CostDocPeer::DOC_ID, $docId, $comparison);
    }

    /**
     * Filter the query by a related Cost object
     *
     * @param   Cost|PropelObjectCollection $cost The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CostDocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCost($cost, $comparison = null)
    {
        if ($cost instanceof Cost) {
            return $this
                ->addUsingAlias(CostDocPeer::COST_ID, $cost->getId(), $comparison);
        } elseif ($cost instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CostDocPeer::COST_ID, $cost->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CostDocQuery The current query, for fluid interface
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
     * Filter the query by a related Doc object
     *
     * @param   Doc|PropelObjectCollection $doc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CostDocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDoc($doc, $comparison = null)
    {
        if ($doc instanceof Doc) {
            return $this
                ->addUsingAlias(CostDocPeer::DOC_ID, $doc->getId(), $comparison);
        } elseif ($doc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CostDocPeer::DOC_ID, $doc->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return CostDocQuery The current query, for fluid interface
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
     * Filter the query by a related CostDocIncome object
     *
     * @param   CostDocIncome|PropelObjectCollection $costDocIncome  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CostDocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCostDocIncome($costDocIncome, $comparison = null)
    {
        if ($costDocIncome instanceof CostDocIncome) {
            return $this
                ->addUsingAlias(CostDocPeer::ID, $costDocIncome->getCostDocId(), $comparison);
        } elseif ($costDocIncome instanceof PropelObjectCollection) {
            return $this
                ->useCostDocIncomeQuery()
                ->filterByPrimaryKeys($costDocIncome->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCostDocIncome() only accepts arguments of type CostDocIncome or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CostDocIncome relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CostDocQuery The current query, for fluid interface
     */
    public function joinCostDocIncome($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CostDocIncome');

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
            $this->addJoinObject($join, 'CostDocIncome');
        }

        return $this;
    }

    /**
     * Use the CostDocIncome relation CostDocIncome object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\CostDocIncomeQuery A secondary query class using the current class as primary query
     */
    public function useCostDocIncomeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCostDocIncome($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CostDocIncome', '\Oppen\ProjectBundle\Model\CostDocIncomeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CostDoc $costDoc Object to remove from the list of results
     *
     * @return CostDocQuery The current query, for fluid interface
     */
    public function prune($costDoc = null)
    {
        if ($costDoc) {
            $this->addUsingAlias(CostDocPeer::ID, $costDoc->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
