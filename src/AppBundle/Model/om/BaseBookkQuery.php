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
use AppBundle\Model\Bookk;
use AppBundle\Model\BookkEntry;
use AppBundle\Model\BookkPeer;
use AppBundle\Model\BookkQuery;
use AppBundle\Model\Doc;
use AppBundle\Model\Project;
use AppBundle\Model\Year;

/**
 * @method BookkQuery orderById($order = Criteria::ASC) Order by the id column
 * @method BookkQuery orderByNo($order = Criteria::ASC) Order by the no column
 * @method BookkQuery orderByDesc($order = Criteria::ASC) Order by the desc column
 * @method BookkQuery orderByIsAccepted($order = Criteria::ASC) Order by the is_accepted column
 * @method BookkQuery orderByBookkingDate($order = Criteria::ASC) Order by the bookking_date column
 * @method BookkQuery orderByYearId($order = Criteria::ASC) Order by the year_id column
 * @method BookkQuery orderByDocId($order = Criteria::ASC) Order by the doc_id column
 * @method BookkQuery orderByProjectId($order = Criteria::ASC) Order by the project_id column
 *
 * @method BookkQuery groupById() Group by the id column
 * @method BookkQuery groupByNo() Group by the no column
 * @method BookkQuery groupByDesc() Group by the desc column
 * @method BookkQuery groupByIsAccepted() Group by the is_accepted column
 * @method BookkQuery groupByBookkingDate() Group by the bookking_date column
 * @method BookkQuery groupByYearId() Group by the year_id column
 * @method BookkQuery groupByDocId() Group by the doc_id column
 * @method BookkQuery groupByProjectId() Group by the project_id column
 *
 * @method BookkQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method BookkQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method BookkQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method BookkQuery leftJoinYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the Year relation
 * @method BookkQuery rightJoinYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Year relation
 * @method BookkQuery innerJoinYear($relationAlias = null) Adds a INNER JOIN clause to the query using the Year relation
 *
 * @method BookkQuery leftJoinDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the Doc relation
 * @method BookkQuery rightJoinDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Doc relation
 * @method BookkQuery innerJoinDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the Doc relation
 *
 * @method BookkQuery leftJoinProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Project relation
 * @method BookkQuery rightJoinProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Project relation
 * @method BookkQuery innerJoinProject($relationAlias = null) Adds a INNER JOIN clause to the query using the Project relation
 *
 * @method BookkQuery leftJoinBookkEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the BookkEntry relation
 * @method BookkQuery rightJoinBookkEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BookkEntry relation
 * @method BookkQuery innerJoinBookkEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the BookkEntry relation
 *
 * @method Bookk findOne(PropelPDO $con = null) Return the first Bookk matching the query
 * @method Bookk findOneOrCreate(PropelPDO $con = null) Return the first Bookk matching the query, or a new Bookk object populated from the query conditions when no match is found
 *
 * @method Bookk findOneByNo(int $no) Return the first Bookk filtered by the no column
 * @method Bookk findOneByDesc(string $desc) Return the first Bookk filtered by the desc column
 * @method Bookk findOneByIsAccepted(boolean $is_accepted) Return the first Bookk filtered by the is_accepted column
 * @method Bookk findOneByBookkingDate(string $bookking_date) Return the first Bookk filtered by the bookking_date column
 * @method Bookk findOneByYearId(int $year_id) Return the first Bookk filtered by the year_id column
 * @method Bookk findOneByDocId(int $doc_id) Return the first Bookk filtered by the doc_id column
 * @method Bookk findOneByProjectId(int $project_id) Return the first Bookk filtered by the project_id column
 *
 * @method array findById(int $id) Return Bookk objects filtered by the id column
 * @method array findByNo(int $no) Return Bookk objects filtered by the no column
 * @method array findByDesc(string $desc) Return Bookk objects filtered by the desc column
 * @method array findByIsAccepted(boolean $is_accepted) Return Bookk objects filtered by the is_accepted column
 * @method array findByBookkingDate(string $bookking_date) Return Bookk objects filtered by the bookking_date column
 * @method array findByYearId(int $year_id) Return Bookk objects filtered by the year_id column
 * @method array findByDocId(int $doc_id) Return Bookk objects filtered by the doc_id column
 * @method array findByProjectId(int $project_id) Return Bookk objects filtered by the project_id column
 */
abstract class BaseBookkQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseBookkQuery object.
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
            $modelName = 'AppBundle\\Model\\Bookk';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new BookkQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   BookkQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return BookkQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof BookkQuery) {
            return $criteria;
        }
        $query = new BookkQuery(null, null, $modelAlias);

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
     * @return   Bookk|Bookk[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BookkPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(BookkPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Bookk A model object, or null if the key is not found
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
     * @return                 Bookk A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `no`, `desc`, `is_accepted`, `bookking_date`, `year_id`, `doc_id`, `project_id` FROM `bookk` WHERE `id` = :p0';
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
            $obj = new Bookk();
            $obj->hydrate($row);
            BookkPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Bookk|Bookk[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Bookk[]|mixed the list of results, formatted by the current formatter
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
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BookkPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BookkPeer::ID, $keys, Criteria::IN);
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
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BookkPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BookkPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the no column
     *
     * Example usage:
     * <code>
     * $query->filterByNo(1234); // WHERE no = 1234
     * $query->filterByNo(array(12, 34)); // WHERE no IN (12, 34)
     * $query->filterByNo(array('min' => 12)); // WHERE no >= 12
     * $query->filterByNo(array('max' => 12)); // WHERE no <= 12
     * </code>
     *
     * @param     mixed $no The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByNo($no = null, $comparison = null)
    {
        if (is_array($no)) {
            $useMinMax = false;
            if (isset($no['min'])) {
                $this->addUsingAlias(BookkPeer::NO, $no['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($no['max'])) {
                $this->addUsingAlias(BookkPeer::NO, $no['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkPeer::NO, $no, $comparison);
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
     * @return BookkQuery The current query, for fluid interface
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

        return $this->addUsingAlias(BookkPeer::DESC, $desc, $comparison);
    }

    /**
     * Filter the query on the is_accepted column
     *
     * Example usage:
     * <code>
     * $query->filterByIsAccepted(true); // WHERE is_accepted = true
     * $query->filterByIsAccepted('yes'); // WHERE is_accepted = true
     * </code>
     *
     * @param     boolean|string $isAccepted The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByIsAccepted($isAccepted = null, $comparison = null)
    {
        if (is_string($isAccepted)) {
            $isAccepted = in_array(strtolower($isAccepted), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(BookkPeer::IS_ACCEPTED, $isAccepted, $comparison);
    }

    /**
     * Filter the query on the bookking_date column
     *
     * Example usage:
     * <code>
     * $query->filterByBookkingDate('2011-03-14'); // WHERE bookking_date = '2011-03-14'
     * $query->filterByBookkingDate('now'); // WHERE bookking_date = '2011-03-14'
     * $query->filterByBookkingDate(array('max' => 'yesterday')); // WHERE bookking_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $bookkingDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByBookkingDate($bookkingDate = null, $comparison = null)
    {
        if (is_array($bookkingDate)) {
            $useMinMax = false;
            if (isset($bookkingDate['min'])) {
                $this->addUsingAlias(BookkPeer::BOOKKING_DATE, $bookkingDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookkingDate['max'])) {
                $this->addUsingAlias(BookkPeer::BOOKKING_DATE, $bookkingDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkPeer::BOOKKING_DATE, $bookkingDate, $comparison);
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
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByYearId($yearId = null, $comparison = null)
    {
        if (is_array($yearId)) {
            $useMinMax = false;
            if (isset($yearId['min'])) {
                $this->addUsingAlias(BookkPeer::YEAR_ID, $yearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($yearId['max'])) {
                $this->addUsingAlias(BookkPeer::YEAR_ID, $yearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkPeer::YEAR_ID, $yearId, $comparison);
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
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByDocId($docId = null, $comparison = null)
    {
        if (is_array($docId)) {
            $useMinMax = false;
            if (isset($docId['min'])) {
                $this->addUsingAlias(BookkPeer::DOC_ID, $docId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($docId['max'])) {
                $this->addUsingAlias(BookkPeer::DOC_ID, $docId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkPeer::DOC_ID, $docId, $comparison);
    }

    /**
     * Filter the query on the project_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProjectId(1234); // WHERE project_id = 1234
     * $query->filterByProjectId(array(12, 34)); // WHERE project_id IN (12, 34)
     * $query->filterByProjectId(array('min' => 12)); // WHERE project_id >= 12
     * $query->filterByProjectId(array('max' => 12)); // WHERE project_id <= 12
     * </code>
     *
     * @see       filterByProject()
     *
     * @param     mixed $projectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function filterByProjectId($projectId = null, $comparison = null)
    {
        if (is_array($projectId)) {
            $useMinMax = false;
            if (isset($projectId['min'])) {
                $this->addUsingAlias(BookkPeer::PROJECT_ID, $projectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($projectId['max'])) {
                $this->addUsingAlias(BookkPeer::PROJECT_ID, $projectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkPeer::PROJECT_ID, $projectId, $comparison);
    }

    /**
     * Filter the query by a related Year object
     *
     * @param   Year|PropelObjectCollection $year The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByYear($year, $comparison = null)
    {
        if ($year instanceof Year) {
            return $this
                ->addUsingAlias(BookkPeer::YEAR_ID, $year->getId(), $comparison);
        } elseif ($year instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkPeer::YEAR_ID, $year->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return BookkQuery The current query, for fluid interface
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
     * @param   Doc|PropelObjectCollection $doc The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDoc($doc, $comparison = null)
    {
        if ($doc instanceof Doc) {
            return $this
                ->addUsingAlias(BookkPeer::DOC_ID, $doc->getId(), $comparison);
        } elseif ($doc instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkPeer::DOC_ID, $doc->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return BookkQuery The current query, for fluid interface
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
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProject($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(BookkPeer::PROJECT_ID, $project->getId(), $comparison);
        } elseif ($project instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkPeer::PROJECT_ID, $project->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByProject() only accepts arguments of type Project or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Project relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function joinProject($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Project');

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
            $this->addJoinObject($join, 'Project');
        }

        return $this;
    }

    /**
     * Use the Project relation Project object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\ProjectQuery A secondary query class using the current class as primary query
     */
    public function useProjectQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinProject($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Project', '\AppBundle\Model\ProjectQuery');
    }

    /**
     * Filter the query by a related BookkEntry object
     *
     * @param   BookkEntry|PropelObjectCollection $bookkEntry  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBookkEntry($bookkEntry, $comparison = null)
    {
        if ($bookkEntry instanceof BookkEntry) {
            return $this
                ->addUsingAlias(BookkPeer::ID, $bookkEntry->getBookkId(), $comparison);
        } elseif ($bookkEntry instanceof PropelObjectCollection) {
            return $this
                ->useBookkEntryQuery()
                ->filterByPrimaryKeys($bookkEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBookkEntry() only accepts arguments of type BookkEntry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BookkEntry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function joinBookkEntry($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BookkEntry');

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
            $this->addJoinObject($join, 'BookkEntry');
        }

        return $this;
    }

    /**
     * Use the BookkEntry relation BookkEntry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\BookkEntryQuery A secondary query class using the current class as primary query
     */
    public function useBookkEntryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookkEntry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BookkEntry', '\AppBundle\Model\BookkEntryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Bookk $bookk Object to remove from the list of results
     *
     * @return BookkQuery The current query, for fluid interface
     */
    public function prune($bookk = null)
    {
        if ($bookk) {
            $this->addUsingAlias(BookkPeer::ID, $bookk->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
