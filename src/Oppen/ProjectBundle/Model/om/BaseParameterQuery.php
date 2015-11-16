<?php

namespace Oppen\ProjectBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Parameter;
use Oppen\ProjectBundle\Model\ParameterPeer;
use Oppen\ProjectBundle\Model\ParameterQuery;

/**
 * @method ParameterQuery orderById($order = Criteria::ASC) Order by the id column
 * @method ParameterQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method ParameterQuery orderByLabel($order = Criteria::ASC) Order by the label column
 * @method ParameterQuery orderByFieldType($order = Criteria::ASC) Order by the field_type column
 * @method ParameterQuery orderByValueFloat($order = Criteria::ASC) Order by the value_float column
 * @method ParameterQuery orderByValueInt($order = Criteria::ASC) Order by the value_int column
 * @method ParameterQuery orderByValueVarchar($order = Criteria::ASC) Order by the value_varchar column
 * @method ParameterQuery orderByValueDate($order = Criteria::ASC) Order by the value_date column
 * @method ParameterQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method ParameterQuery groupById() Group by the id column
 * @method ParameterQuery groupByName() Group by the name column
 * @method ParameterQuery groupByLabel() Group by the label column
 * @method ParameterQuery groupByFieldType() Group by the field_type column
 * @method ParameterQuery groupByValueFloat() Group by the value_float column
 * @method ParameterQuery groupByValueInt() Group by the value_int column
 * @method ParameterQuery groupByValueVarchar() Group by the value_varchar column
 * @method ParameterQuery groupByValueDate() Group by the value_date column
 * @method ParameterQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method ParameterQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method ParameterQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method ParameterQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method Parameter findOne(PropelPDO $con = null) Return the first Parameter matching the query
 * @method Parameter findOneOrCreate(PropelPDO $con = null) Return the first Parameter matching the query, or a new Parameter object populated from the query conditions when no match is found
 *
 * @method Parameter findOneByName(string $name) Return the first Parameter filtered by the name column
 * @method Parameter findOneByLabel(string $label) Return the first Parameter filtered by the label column
 * @method Parameter findOneByFieldType(string $field_type) Return the first Parameter filtered by the field_type column
 * @method Parameter findOneByValueFloat(double $value_float) Return the first Parameter filtered by the value_float column
 * @method Parameter findOneByValueInt(int $value_int) Return the first Parameter filtered by the value_int column
 * @method Parameter findOneByValueVarchar(string $value_varchar) Return the first Parameter filtered by the value_varchar column
 * @method Parameter findOneByValueDate(string $value_date) Return the first Parameter filtered by the value_date column
 * @method Parameter findOneBySortableRank(int $sortable_rank) Return the first Parameter filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return Parameter objects filtered by the id column
 * @method array findByName(string $name) Return Parameter objects filtered by the name column
 * @method array findByLabel(string $label) Return Parameter objects filtered by the label column
 * @method array findByFieldType(string $field_type) Return Parameter objects filtered by the field_type column
 * @method array findByValueFloat(double $value_float) Return Parameter objects filtered by the value_float column
 * @method array findByValueInt(int $value_int) Return Parameter objects filtered by the value_int column
 * @method array findByValueVarchar(string $value_varchar) Return Parameter objects filtered by the value_varchar column
 * @method array findByValueDate(string $value_date) Return Parameter objects filtered by the value_date column
 * @method array findBySortableRank(int $sortable_rank) Return Parameter objects filtered by the sortable_rank column
 */
abstract class BaseParameterQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseParameterQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\Parameter';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ParameterQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   ParameterQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return ParameterQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof ParameterQuery) {
            return $criteria;
        }
        $query = new ParameterQuery(null, null, $modelAlias);

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
     * @return   Parameter|Parameter[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = ParameterPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(ParameterPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Parameter A model object, or null if the key is not found
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
     * @return                 Parameter A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `label`, `field_type`, `value_float`, `value_int`, `value_varchar`, `value_date`, `sortable_rank` FROM `parameter` WHERE `id` = :p0';
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
            $obj = new Parameter();
            $obj->hydrate($row);
            ParameterPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Parameter|Parameter[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Parameter[]|mixed the list of results, formatted by the current formatter
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
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ParameterPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ParameterPeer::ID, $keys, Criteria::IN);
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
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ParameterPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ParameterPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ParameterPeer::ID, $id, $comparison);
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
     * @return ParameterQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ParameterPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the label column
     *
     * Example usage:
     * <code>
     * $query->filterByLabel('fooValue');   // WHERE label = 'fooValue'
     * $query->filterByLabel('%fooValue%'); // WHERE label LIKE '%fooValue%'
     * </code>
     *
     * @param     string $label The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByLabel($label = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($label)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $label)) {
                $label = str_replace('*', '%', $label);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ParameterPeer::LABEL, $label, $comparison);
    }

    /**
     * Filter the query on the field_type column
     *
     * Example usage:
     * <code>
     * $query->filterByFieldType('fooValue');   // WHERE field_type = 'fooValue'
     * $query->filterByFieldType('%fooValue%'); // WHERE field_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fieldType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByFieldType($fieldType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fieldType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fieldType)) {
                $fieldType = str_replace('*', '%', $fieldType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ParameterPeer::FIELD_TYPE, $fieldType, $comparison);
    }

    /**
     * Filter the query on the value_float column
     *
     * Example usage:
     * <code>
     * $query->filterByValueFloat(1234); // WHERE value_float = 1234
     * $query->filterByValueFloat(array(12, 34)); // WHERE value_float IN (12, 34)
     * $query->filterByValueFloat(array('min' => 12)); // WHERE value_float >= 12
     * $query->filterByValueFloat(array('max' => 12)); // WHERE value_float <= 12
     * </code>
     *
     * @param     mixed $valueFloat The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByValueFloat($valueFloat = null, $comparison = null)
    {
        if (is_array($valueFloat)) {
            $useMinMax = false;
            if (isset($valueFloat['min'])) {
                $this->addUsingAlias(ParameterPeer::VALUE_FLOAT, $valueFloat['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($valueFloat['max'])) {
                $this->addUsingAlias(ParameterPeer::VALUE_FLOAT, $valueFloat['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ParameterPeer::VALUE_FLOAT, $valueFloat, $comparison);
    }

    /**
     * Filter the query on the value_int column
     *
     * Example usage:
     * <code>
     * $query->filterByValueInt(1234); // WHERE value_int = 1234
     * $query->filterByValueInt(array(12, 34)); // WHERE value_int IN (12, 34)
     * $query->filterByValueInt(array('min' => 12)); // WHERE value_int >= 12
     * $query->filterByValueInt(array('max' => 12)); // WHERE value_int <= 12
     * </code>
     *
     * @param     mixed $valueInt The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByValueInt($valueInt = null, $comparison = null)
    {
        if (is_array($valueInt)) {
            $useMinMax = false;
            if (isset($valueInt['min'])) {
                $this->addUsingAlias(ParameterPeer::VALUE_INT, $valueInt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($valueInt['max'])) {
                $this->addUsingAlias(ParameterPeer::VALUE_INT, $valueInt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ParameterPeer::VALUE_INT, $valueInt, $comparison);
    }

    /**
     * Filter the query on the value_varchar column
     *
     * Example usage:
     * <code>
     * $query->filterByValueVarchar('fooValue');   // WHERE value_varchar = 'fooValue'
     * $query->filterByValueVarchar('%fooValue%'); // WHERE value_varchar LIKE '%fooValue%'
     * </code>
     *
     * @param     string $valueVarchar The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByValueVarchar($valueVarchar = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($valueVarchar)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $valueVarchar)) {
                $valueVarchar = str_replace('*', '%', $valueVarchar);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(ParameterPeer::VALUE_VARCHAR, $valueVarchar, $comparison);
    }

    /**
     * Filter the query on the value_date column
     *
     * Example usage:
     * <code>
     * $query->filterByValueDate('2011-03-14'); // WHERE value_date = '2011-03-14'
     * $query->filterByValueDate('now'); // WHERE value_date = '2011-03-14'
     * $query->filterByValueDate(array('max' => 'yesterday')); // WHERE value_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $valueDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterByValueDate($valueDate = null, $comparison = null)
    {
        if (is_array($valueDate)) {
            $useMinMax = false;
            if (isset($valueDate['min'])) {
                $this->addUsingAlias(ParameterPeer::VALUE_DATE, $valueDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($valueDate['max'])) {
                $this->addUsingAlias(ParameterPeer::VALUE_DATE, $valueDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ParameterPeer::VALUE_DATE, $valueDate, $comparison);
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
     * @return ParameterQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(ParameterPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(ParameterPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ParameterPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   Parameter $parameter Object to remove from the list of results
     *
     * @return ParameterQuery The current query, for fluid interface
     */
    public function prune($parameter = null)
    {
        if ($parameter) {
            $this->addUsingAlias(ParameterPeer::ID, $parameter->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    ParameterQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {


        return $this
            ->addUsingAlias(ParameterPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    ParameterQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(ParameterPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(ParameterPeer::RANK_COL));
                break;
            default:
                throw new PropelException('ParameterQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     PropelPDO $con optional connection
     *
     * @return    Parameter
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
            $con = Propel::getConnection(ParameterPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . ParameterPeer::RANK_COL . ')');
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
            $con = Propel::getConnection(ParameterPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . ParameterPeer::RANK_COL . ')');
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
            $con = Propel::getConnection(ParameterPeer::DATABASE_NAME);
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
