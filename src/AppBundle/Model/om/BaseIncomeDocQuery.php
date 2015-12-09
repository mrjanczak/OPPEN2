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
use AppBundle\Model\Doc;
use AppBundle\Model\Income;
use AppBundle\Model\IncomeDoc;
use AppBundle\Model\IncomeDocPeer;
use AppBundle\Model\IncomeDocQuery;

/**
 * @method IncomeDocQuery orderById($order = Criteria::ASC) Order by the id column
 * @method IncomeDocQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method IncomeDocQuery orderByDesc($order = Criteria::ASC) Order by the desc column
 * @method IncomeDocQuery orderByIncomeId($order = Criteria::ASC) Order by the income_id column
 * @method IncomeDocQuery orderByDocId($order = Criteria::ASC) Order by the doc_id column
 *
 * @method IncomeDocQuery groupById() Group by the id column
 * @method IncomeDocQuery groupByValue() Group by the value column
 * @method IncomeDocQuery groupByDesc() Group by the desc column
 * @method IncomeDocQuery groupByIncomeId() Group by the income_id column
 * @method IncomeDocQuery groupByDocId() Group by the doc_id column
 *
 * @method IncomeDocQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method IncomeDocQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method IncomeDocQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method IncomeDocQuery leftJoinIncome($relationAlias = null) Adds a LEFT JOIN clause to the query using the Income relation
 * @method IncomeDocQuery rightJoinIncome($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Income relation
 * @method IncomeDocQuery innerJoinIncome($relationAlias = null) Adds a INNER JOIN clause to the query using the Income relation
 *
 * @method IncomeDocQuery leftJoinDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the Doc relation
 * @method IncomeDocQuery rightJoinDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Doc relation
 * @method IncomeDocQuery innerJoinDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the Doc relation
 *
 * @method IncomeDoc findOne(PropelPDO $con = null) Return the first IncomeDoc matching the query
 * @method IncomeDoc findOneOrCreate(PropelPDO $con = null) Return the first IncomeDoc matching the query, or a new IncomeDoc object populated from the query conditions when no match is found
 *
 * @method IncomeDoc findOneByValue(double $value) Return the first IncomeDoc filtered by the value column
 * @method IncomeDoc findOneByDesc(string $desc) Return the first IncomeDoc filtered by the desc column
 * @method IncomeDoc findOneByIncomeId(int $income_id) Return the first IncomeDoc filtered by the income_id column
 * @method IncomeDoc findOneByDocId(int $doc_id) Return the first IncomeDoc filtered by the doc_id column
 *
 * @method array findById(int $id) Return IncomeDoc objects filtered by the id column
 * @method array findByValue(double $value) Return IncomeDoc objects filtered by the value column
 * @method array findByDesc(string $desc) Return IncomeDoc objects filtered by the desc column
 * @method array findByIncomeId(int $income_id) Return IncomeDoc objects filtered by the income_id column
 * @method array findByDocId(int $doc_id) Return IncomeDoc objects filtered by the doc_id column
 */
abstract class BaseIncomeDocQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseIncomeDocQuery object.
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
            $modelName = 'AppBundle\\Model\\IncomeDoc';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new IncomeDocQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   IncomeDocQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return IncomeDocQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof IncomeDocQuery) {
            return $criteria;
        }
        $query = new IncomeDocQuery(null, null, $modelAlias);

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
     * @return   IncomeDoc|IncomeDoc[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IncomeDocPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(IncomeDocPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 IncomeDoc A model object, or null if the key is not found
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
     * @return                 IncomeDoc A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `value`, `desc`, `income_id`, `doc_id` FROM `income_doc` WHERE `id` = :p0';
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
            $obj = new IncomeDoc();
            $obj->hydrate($row);
            IncomeDocPeer::addInstanceToPool($obj, (string) $key);
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
     * @return IncomeDoc|IncomeDoc[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|IncomeDoc[]|mixed the list of results, formatted by the current formatter
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
     * @return IncomeDocQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(IncomeDocPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return IncomeDocQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(IncomeDocPeer::ID, $keys, Criteria::IN);
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
     * @return IncomeDocQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IncomeDocPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IncomeDocPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomeDocPeer::ID, $id, $comparison);
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
     * @return IncomeDocQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (is_array($value)) {
            $useMinMax = false;
            if (isset($value['min'])) {
                $this->addUsingAlias(IncomeDocPeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($value['max'])) {
                $this->addUsingAlias(IncomeDocPeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomeDocPeer::VALUE, $value, $comparison);
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
     * @return IncomeDocQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IncomeDocPeer::DESC, $desc, $comparison);
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
     * @return IncomeDocQuery The current query, for fluid interface
     */
    public function filterByIncomeId($incomeId = null, $comparison = null)
    {
        if (is_array($incomeId)) {
            $useMinMax = false;
            if (isset($incomeId['min'])) {
                $this->addUsingAlias(IncomeDocPeer::INCOME_ID, $incomeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($incomeId['max'])) {
                $this->addUsingAlias(IncomeDocPeer::INCOME_ID, $incomeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomeDocPeer::INCOME_ID, $incomeId, $comparison);
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
     * @return IncomeDocQuery The current query, for fluid interface
     */
    public function filterByDocId($docId = null, $comparison = null)
    {
        if (is_array($docId)) {
            $useMinMax = false;
            if (isset($docId['min'])) {
                $this->addUsingAlias(IncomeDocPeer::DOC_ID, $docId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($docId['max'])) {
                $this->addUsingAlias(IncomeDocPeer::DOC_ID, $docId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomeDocPeer::DOC_ID, $docId, $comparison);
    }

    /**
     * Filter the query by a related Income object
     *
     * @param   Income|PropelObjectCollection $income The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeDocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncome($income, $comparison = null)
    {
        if ($income instanceof Income) {
            return $this
                ->addUsingAlias(IncomeDocPeer::INCOME_ID, $income->getId(), $comparison);
        } elseif ($income instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IncomeDocPeer::INCOME_ID, $income->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return IncomeDocQuery The current query, for fluid interface
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
     * @return   \AppBundle\Model\IncomeQuery A secondary query class using the current class as primary query
     */
    public function useIncomeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncome($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Income', '\AppBundle\Model\IncomeQuery');
    }

    /**
     * Filter the query by a related Doc object
     *
     * @param   Doc|PropelObjectCollection $doc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeDocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDoc($doc, $comparison = null)
    {
        if ($doc instanceof Doc) {
            return $this
                ->addUsingAlias(IncomeDocPeer::DOC_ID, $doc->getId(), $comparison);
        } elseif ($doc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IncomeDocPeer::DOC_ID, $doc->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return IncomeDocQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   IncomeDoc $incomeDoc Object to remove from the list of results
     *
     * @return IncomeDocQuery The current query, for fluid interface
     */
    public function prune($incomeDoc = null)
    {
        if ($incomeDoc) {
            $this->addUsingAlias(IncomeDocPeer::ID, $incomeDoc->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
