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
use AppBundle\Model\Contract;
use AppBundle\Model\CostDoc;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocPeer;
use AppBundle\Model\DocQuery;
use AppBundle\Model\File;
use AppBundle\Model\IncomeDoc;
use AppBundle\Model\Month;
use FOS\UserBundle\Propel\User;

/**
 * @method DocQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DocQuery orderByDocumentDate($order = Criteria::ASC) Order by the document_date column
 * @method DocQuery orderByOperationDate($order = Criteria::ASC) Order by the operation_date column
 * @method DocQuery orderByReceiptDate($order = Criteria::ASC) Order by the receipt_date column
 * @method DocQuery orderByBookkingDate($order = Criteria::ASC) Order by the bookking_date column
 * @method DocQuery orderByPaymentDeadlineDate($order = Criteria::ASC) Order by the payment_deadline_date column
 * @method DocQuery orderByPaymentDate($order = Criteria::ASC) Order by the payment_date column
 * @method DocQuery orderByPaymentMethod($order = Criteria::ASC) Order by the payment_method column
 * @method DocQuery orderByMonthId($order = Criteria::ASC) Order by the month_id column
 * @method DocQuery orderByDocCatId($order = Criteria::ASC) Order by the doc_cat_id column
 * @method DocQuery orderByRegIdx($order = Criteria::ASC) Order by the reg_idx column
 * @method DocQuery orderByRegNo($order = Criteria::ASC) Order by the reg_no column
 * @method DocQuery orderByDocIdx($order = Criteria::ASC) Order by the doc_idx column
 * @method DocQuery orderByDocNo($order = Criteria::ASC) Order by the doc_no column
 * @method DocQuery orderByFileId($order = Criteria::ASC) Order by the file_id column
 * @method DocQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method DocQuery orderByDesc($order = Criteria::ASC) Order by the desc column
 * @method DocQuery orderByComment($order = Criteria::ASC) Order by the comment column
 *
 * @method DocQuery groupById() Group by the id column
 * @method DocQuery groupByDocumentDate() Group by the document_date column
 * @method DocQuery groupByOperationDate() Group by the operation_date column
 * @method DocQuery groupByReceiptDate() Group by the receipt_date column
 * @method DocQuery groupByBookkingDate() Group by the bookking_date column
 * @method DocQuery groupByPaymentDeadlineDate() Group by the payment_deadline_date column
 * @method DocQuery groupByPaymentDate() Group by the payment_date column
 * @method DocQuery groupByPaymentMethod() Group by the payment_method column
 * @method DocQuery groupByMonthId() Group by the month_id column
 * @method DocQuery groupByDocCatId() Group by the doc_cat_id column
 * @method DocQuery groupByRegIdx() Group by the reg_idx column
 * @method DocQuery groupByRegNo() Group by the reg_no column
 * @method DocQuery groupByDocIdx() Group by the doc_idx column
 * @method DocQuery groupByDocNo() Group by the doc_no column
 * @method DocQuery groupByFileId() Group by the file_id column
 * @method DocQuery groupByUserId() Group by the user_id column
 * @method DocQuery groupByDesc() Group by the desc column
 * @method DocQuery groupByComment() Group by the comment column
 *
 * @method DocQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DocQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DocQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DocQuery leftJoinMonth($relationAlias = null) Adds a LEFT JOIN clause to the query using the Month relation
 * @method DocQuery rightJoinMonth($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Month relation
 * @method DocQuery innerJoinMonth($relationAlias = null) Adds a INNER JOIN clause to the query using the Month relation
 *
 * @method DocQuery leftJoinDocCat($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocCat relation
 * @method DocQuery rightJoinDocCat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocCat relation
 * @method DocQuery innerJoinDocCat($relationAlias = null) Adds a INNER JOIN clause to the query using the DocCat relation
 *
 * @method DocQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method DocQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method DocQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method DocQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method DocQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method DocQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method DocQuery leftJoinBookk($relationAlias = null) Adds a LEFT JOIN clause to the query using the Bookk relation
 * @method DocQuery rightJoinBookk($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Bookk relation
 * @method DocQuery innerJoinBookk($relationAlias = null) Adds a INNER JOIN clause to the query using the Bookk relation
 *
 * @method DocQuery leftJoinIncomeDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the IncomeDoc relation
 * @method DocQuery rightJoinIncomeDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IncomeDoc relation
 * @method DocQuery innerJoinIncomeDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the IncomeDoc relation
 *
 * @method DocQuery leftJoinCostDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the CostDoc relation
 * @method DocQuery rightJoinCostDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CostDoc relation
 * @method DocQuery innerJoinCostDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the CostDoc relation
 *
 * @method DocQuery leftJoinContract($relationAlias = null) Adds a LEFT JOIN clause to the query using the Contract relation
 * @method DocQuery rightJoinContract($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Contract relation
 * @method DocQuery innerJoinContract($relationAlias = null) Adds a INNER JOIN clause to the query using the Contract relation
 *
 * @method Doc findOne(PropelPDO $con = null) Return the first Doc matching the query
 * @method Doc findOneOrCreate(PropelPDO $con = null) Return the first Doc matching the query, or a new Doc object populated from the query conditions when no match is found
 *
 * @method Doc findOneByDocumentDate(string $document_date) Return the first Doc filtered by the document_date column
 * @method Doc findOneByOperationDate(string $operation_date) Return the first Doc filtered by the operation_date column
 * @method Doc findOneByReceiptDate(string $receipt_date) Return the first Doc filtered by the receipt_date column
 * @method Doc findOneByBookkingDate(string $bookking_date) Return the first Doc filtered by the bookking_date column
 * @method Doc findOneByPaymentDeadlineDate(string $payment_deadline_date) Return the first Doc filtered by the payment_deadline_date column
 * @method Doc findOneByPaymentDate(string $payment_date) Return the first Doc filtered by the payment_date column
 * @method Doc findOneByPaymentMethod(int $payment_method) Return the first Doc filtered by the payment_method column
 * @method Doc findOneByMonthId(int $month_id) Return the first Doc filtered by the month_id column
 * @method Doc findOneByDocCatId(int $doc_cat_id) Return the first Doc filtered by the doc_cat_id column
 * @method Doc findOneByRegIdx(int $reg_idx) Return the first Doc filtered by the reg_idx column
 * @method Doc findOneByRegNo(string $reg_no) Return the first Doc filtered by the reg_no column
 * @method Doc findOneByDocIdx(int $doc_idx) Return the first Doc filtered by the doc_idx column
 * @method Doc findOneByDocNo(string $doc_no) Return the first Doc filtered by the doc_no column
 * @method Doc findOneByFileId(int $file_id) Return the first Doc filtered by the file_id column
 * @method Doc findOneByUserId(int $user_id) Return the first Doc filtered by the user_id column
 * @method Doc findOneByDesc(string $desc) Return the first Doc filtered by the desc column
 * @method Doc findOneByComment(string $comment) Return the first Doc filtered by the comment column
 *
 * @method array findById(int $id) Return Doc objects filtered by the id column
 * @method array findByDocumentDate(string $document_date) Return Doc objects filtered by the document_date column
 * @method array findByOperationDate(string $operation_date) Return Doc objects filtered by the operation_date column
 * @method array findByReceiptDate(string $receipt_date) Return Doc objects filtered by the receipt_date column
 * @method array findByBookkingDate(string $bookking_date) Return Doc objects filtered by the bookking_date column
 * @method array findByPaymentDeadlineDate(string $payment_deadline_date) Return Doc objects filtered by the payment_deadline_date column
 * @method array findByPaymentDate(string $payment_date) Return Doc objects filtered by the payment_date column
 * @method array findByPaymentMethod(int $payment_method) Return Doc objects filtered by the payment_method column
 * @method array findByMonthId(int $month_id) Return Doc objects filtered by the month_id column
 * @method array findByDocCatId(int $doc_cat_id) Return Doc objects filtered by the doc_cat_id column
 * @method array findByRegIdx(int $reg_idx) Return Doc objects filtered by the reg_idx column
 * @method array findByRegNo(string $reg_no) Return Doc objects filtered by the reg_no column
 * @method array findByDocIdx(int $doc_idx) Return Doc objects filtered by the doc_idx column
 * @method array findByDocNo(string $doc_no) Return Doc objects filtered by the doc_no column
 * @method array findByFileId(int $file_id) Return Doc objects filtered by the file_id column
 * @method array findByUserId(int $user_id) Return Doc objects filtered by the user_id column
 * @method array findByDesc(string $desc) Return Doc objects filtered by the desc column
 * @method array findByComment(string $comment) Return Doc objects filtered by the comment column
 */
abstract class BaseDocQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDocQuery object.
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
            $modelName = 'AppBundle\\Model\\Doc';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DocQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DocQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DocQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DocQuery) {
            return $criteria;
        }
        $query = new DocQuery(null, null, $modelAlias);

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
     * @return   Doc|Doc[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DocPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DocPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Doc A model object, or null if the key is not found
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
     * @return                 Doc A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `document_date`, `operation_date`, `receipt_date`, `bookking_date`, `payment_deadline_date`, `payment_date`, `payment_method`, `month_id`, `doc_cat_id`, `reg_idx`, `reg_no`, `doc_idx`, `doc_no`, `file_id`, `user_id`, `desc`, `comment` FROM `doc` WHERE `id` = :p0';
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
            $obj = new Doc();
            $obj->hydrate($row);
            DocPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Doc|Doc[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Doc[]|mixed the list of results, formatted by the current formatter
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
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DocPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DocPeer::ID, $keys, Criteria::IN);
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
     * @return DocQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DocPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DocPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the document_date column
     *
     * Example usage:
     * <code>
     * $query->filterByDocumentDate('2011-03-14'); // WHERE document_date = '2011-03-14'
     * $query->filterByDocumentDate('now'); // WHERE document_date = '2011-03-14'
     * $query->filterByDocumentDate(array('max' => 'yesterday')); // WHERE document_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $documentDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByDocumentDate($documentDate = null, $comparison = null)
    {
        if (is_array($documentDate)) {
            $useMinMax = false;
            if (isset($documentDate['min'])) {
                $this->addUsingAlias(DocPeer::DOCUMENT_DATE, $documentDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($documentDate['max'])) {
                $this->addUsingAlias(DocPeer::DOCUMENT_DATE, $documentDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::DOCUMENT_DATE, $documentDate, $comparison);
    }

    /**
     * Filter the query on the operation_date column
     *
     * Example usage:
     * <code>
     * $query->filterByOperationDate('2011-03-14'); // WHERE operation_date = '2011-03-14'
     * $query->filterByOperationDate('now'); // WHERE operation_date = '2011-03-14'
     * $query->filterByOperationDate(array('max' => 'yesterday')); // WHERE operation_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $operationDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByOperationDate($operationDate = null, $comparison = null)
    {
        if (is_array($operationDate)) {
            $useMinMax = false;
            if (isset($operationDate['min'])) {
                $this->addUsingAlias(DocPeer::OPERATION_DATE, $operationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($operationDate['max'])) {
                $this->addUsingAlias(DocPeer::OPERATION_DATE, $operationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::OPERATION_DATE, $operationDate, $comparison);
    }

    /**
     * Filter the query on the receipt_date column
     *
     * Example usage:
     * <code>
     * $query->filterByReceiptDate('2011-03-14'); // WHERE receipt_date = '2011-03-14'
     * $query->filterByReceiptDate('now'); // WHERE receipt_date = '2011-03-14'
     * $query->filterByReceiptDate(array('max' => 'yesterday')); // WHERE receipt_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $receiptDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByReceiptDate($receiptDate = null, $comparison = null)
    {
        if (is_array($receiptDate)) {
            $useMinMax = false;
            if (isset($receiptDate['min'])) {
                $this->addUsingAlias(DocPeer::RECEIPT_DATE, $receiptDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($receiptDate['max'])) {
                $this->addUsingAlias(DocPeer::RECEIPT_DATE, $receiptDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::RECEIPT_DATE, $receiptDate, $comparison);
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
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByBookkingDate($bookkingDate = null, $comparison = null)
    {
        if (is_array($bookkingDate)) {
            $useMinMax = false;
            if (isset($bookkingDate['min'])) {
                $this->addUsingAlias(DocPeer::BOOKKING_DATE, $bookkingDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookkingDate['max'])) {
                $this->addUsingAlias(DocPeer::BOOKKING_DATE, $bookkingDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::BOOKKING_DATE, $bookkingDate, $comparison);
    }

    /**
     * Filter the query on the payment_deadline_date column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentDeadlineDate('2011-03-14'); // WHERE payment_deadline_date = '2011-03-14'
     * $query->filterByPaymentDeadlineDate('now'); // WHERE payment_deadline_date = '2011-03-14'
     * $query->filterByPaymentDeadlineDate(array('max' => 'yesterday')); // WHERE payment_deadline_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $paymentDeadlineDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByPaymentDeadlineDate($paymentDeadlineDate = null, $comparison = null)
    {
        if (is_array($paymentDeadlineDate)) {
            $useMinMax = false;
            if (isset($paymentDeadlineDate['min'])) {
                $this->addUsingAlias(DocPeer::PAYMENT_DEADLINE_DATE, $paymentDeadlineDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentDeadlineDate['max'])) {
                $this->addUsingAlias(DocPeer::PAYMENT_DEADLINE_DATE, $paymentDeadlineDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::PAYMENT_DEADLINE_DATE, $paymentDeadlineDate, $comparison);
    }

    /**
     * Filter the query on the payment_date column
     *
     * Example usage:
     * <code>
     * $query->filterByPaymentDate('2011-03-14'); // WHERE payment_date = '2011-03-14'
     * $query->filterByPaymentDate('now'); // WHERE payment_date = '2011-03-14'
     * $query->filterByPaymentDate(array('max' => 'yesterday')); // WHERE payment_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $paymentDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByPaymentDate($paymentDate = null, $comparison = null)
    {
        if (is_array($paymentDate)) {
            $useMinMax = false;
            if (isset($paymentDate['min'])) {
                $this->addUsingAlias(DocPeer::PAYMENT_DATE, $paymentDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentDate['max'])) {
                $this->addUsingAlias(DocPeer::PAYMENT_DATE, $paymentDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::PAYMENT_DATE, $paymentDate, $comparison);
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
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByPaymentMethod($paymentMethod = null, $comparison = null)
    {
        if (is_array($paymentMethod)) {
            $useMinMax = false;
            if (isset($paymentMethod['min'])) {
                $this->addUsingAlias(DocPeer::PAYMENT_METHOD, $paymentMethod['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paymentMethod['max'])) {
                $this->addUsingAlias(DocPeer::PAYMENT_METHOD, $paymentMethod['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::PAYMENT_METHOD, $paymentMethod, $comparison);
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
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByMonthId($monthId = null, $comparison = null)
    {
        if (is_array($monthId)) {
            $useMinMax = false;
            if (isset($monthId['min'])) {
                $this->addUsingAlias(DocPeer::MONTH_ID, $monthId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($monthId['max'])) {
                $this->addUsingAlias(DocPeer::MONTH_ID, $monthId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::MONTH_ID, $monthId, $comparison);
    }

    /**
     * Filter the query on the doc_cat_id column
     *
     * Example usage:
     * <code>
     * $query->filterByDocCatId(1234); // WHERE doc_cat_id = 1234
     * $query->filterByDocCatId(array(12, 34)); // WHERE doc_cat_id IN (12, 34)
     * $query->filterByDocCatId(array('min' => 12)); // WHERE doc_cat_id >= 12
     * $query->filterByDocCatId(array('max' => 12)); // WHERE doc_cat_id <= 12
     * </code>
     *
     * @see       filterByDocCat()
     *
     * @param     mixed $docCatId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByDocCatId($docCatId = null, $comparison = null)
    {
        if (is_array($docCatId)) {
            $useMinMax = false;
            if (isset($docCatId['min'])) {
                $this->addUsingAlias(DocPeer::DOC_CAT_ID, $docCatId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($docCatId['max'])) {
                $this->addUsingAlias(DocPeer::DOC_CAT_ID, $docCatId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::DOC_CAT_ID, $docCatId, $comparison);
    }

    /**
     * Filter the query on the reg_idx column
     *
     * Example usage:
     * <code>
     * $query->filterByRegIdx(1234); // WHERE reg_idx = 1234
     * $query->filterByRegIdx(array(12, 34)); // WHERE reg_idx IN (12, 34)
     * $query->filterByRegIdx(array('min' => 12)); // WHERE reg_idx >= 12
     * $query->filterByRegIdx(array('max' => 12)); // WHERE reg_idx <= 12
     * </code>
     *
     * @param     mixed $regIdx The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByRegIdx($regIdx = null, $comparison = null)
    {
        if (is_array($regIdx)) {
            $useMinMax = false;
            if (isset($regIdx['min'])) {
                $this->addUsingAlias(DocPeer::REG_IDX, $regIdx['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($regIdx['max'])) {
                $this->addUsingAlias(DocPeer::REG_IDX, $regIdx['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::REG_IDX, $regIdx, $comparison);
    }

    /**
     * Filter the query on the reg_no column
     *
     * Example usage:
     * <code>
     * $query->filterByRegNo('fooValue');   // WHERE reg_no = 'fooValue'
     * $query->filterByRegNo('%fooValue%'); // WHERE reg_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $regNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByRegNo($regNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($regNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $regNo)) {
                $regNo = str_replace('*', '%', $regNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocPeer::REG_NO, $regNo, $comparison);
    }

    /**
     * Filter the query on the doc_idx column
     *
     * Example usage:
     * <code>
     * $query->filterByDocIdx(1234); // WHERE doc_idx = 1234
     * $query->filterByDocIdx(array(12, 34)); // WHERE doc_idx IN (12, 34)
     * $query->filterByDocIdx(array('min' => 12)); // WHERE doc_idx >= 12
     * $query->filterByDocIdx(array('max' => 12)); // WHERE doc_idx <= 12
     * </code>
     *
     * @param     mixed $docIdx The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByDocIdx($docIdx = null, $comparison = null)
    {
        if (is_array($docIdx)) {
            $useMinMax = false;
            if (isset($docIdx['min'])) {
                $this->addUsingAlias(DocPeer::DOC_IDX, $docIdx['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($docIdx['max'])) {
                $this->addUsingAlias(DocPeer::DOC_IDX, $docIdx['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::DOC_IDX, $docIdx, $comparison);
    }

    /**
     * Filter the query on the doc_no column
     *
     * Example usage:
     * <code>
     * $query->filterByDocNo('fooValue');   // WHERE doc_no = 'fooValue'
     * $query->filterByDocNo('%fooValue%'); // WHERE doc_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $docNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByDocNo($docNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($docNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $docNo)) {
                $docNo = str_replace('*', '%', $docNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocPeer::DOC_NO, $docNo, $comparison);
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
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByFileId($fileId = null, $comparison = null)
    {
        if (is_array($fileId)) {
            $useMinMax = false;
            if (isset($fileId['min'])) {
                $this->addUsingAlias(DocPeer::FILE_ID, $fileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileId['max'])) {
                $this->addUsingAlias(DocPeer::FILE_ID, $fileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::FILE_ID, $fileId, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id >= 12
     * $query->filterByUserId(array('max' => 12)); // WHERE user_id <= 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(DocPeer::USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(DocPeer::USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocPeer::USER_ID, $userId, $comparison);
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
     * @return DocQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DocPeer::DESC, $desc, $comparison);
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
     * @return DocQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DocPeer::COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query by a related Month object
     *
     * @param   Month|PropelObjectCollection $month The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByMonth($month, $comparison = null)
    {
        if ($month instanceof Month) {
            return $this
                ->addUsingAlias(DocPeer::MONTH_ID, $month->getId(), $comparison);
        } elseif ($month instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocPeer::MONTH_ID, $month->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DocQuery The current query, for fluid interface
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
     * @return   \AppBundle\Model\MonthQuery A secondary query class using the current class as primary query
     */
    public function useMonthQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinMonth($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Month', '\AppBundle\Model\MonthQuery');
    }

    /**
     * Filter the query by a related DocCat object
     *
     * @param   DocCat|PropelObjectCollection $docCat The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDocCat($docCat, $comparison = null)
    {
        if ($docCat instanceof DocCat) {
            return $this
                ->addUsingAlias(DocPeer::DOC_CAT_ID, $docCat->getId(), $comparison);
        } elseif ($docCat instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocPeer::DOC_CAT_ID, $docCat->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByDocCat() only accepts arguments of type DocCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocCat relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function joinDocCat($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocCat');

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
            $this->addJoinObject($join, 'DocCat');
        }

        return $this;
    }

    /**
     * Use the DocCat relation DocCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\DocCatQuery A secondary query class using the current class as primary query
     */
    public function useDocCatQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDocCat($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocCat', '\AppBundle\Model\DocCatQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFile($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(DocPeer::FILE_ID, $file->getId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocPeer::FILE_ID, $file->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DocQuery The current query, for fluid interface
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
     * @return   \AppBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\AppBundle\Model\FileQuery');
    }

    /**
     * Filter the query by a related User object
     *
     * @param   User|PropelObjectCollection $user The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof User) {
            return $this
                ->addUsingAlias(DocPeer::USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocPeer::USER_ID, $user->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type User or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \FOS\UserBundle\Propel\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\FOS\UserBundle\Propel\UserQuery');
    }

    /**
     * Filter the query by a related Bookk object
     *
     * @param   Bookk|PropelObjectCollection $bookk  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBookk($bookk, $comparison = null)
    {
        if ($bookk instanceof Bookk) {
            return $this
                ->addUsingAlias(DocPeer::ID, $bookk->getDocId(), $comparison);
        } elseif ($bookk instanceof PropelObjectCollection) {
            return $this
                ->useBookkQuery()
                ->filterByPrimaryKeys($bookk->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBookk() only accepts arguments of type Bookk or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Bookk relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function joinBookk($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Bookk');

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
            $this->addJoinObject($join, 'Bookk');
        }

        return $this;
    }

    /**
     * Use the Bookk relation Bookk object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\BookkQuery A secondary query class using the current class as primary query
     */
    public function useBookkQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookk($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Bookk', '\AppBundle\Model\BookkQuery');
    }

    /**
     * Filter the query by a related IncomeDoc object
     *
     * @param   IncomeDoc|PropelObjectCollection $incomeDoc  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncomeDoc($incomeDoc, $comparison = null)
    {
        if ($incomeDoc instanceof IncomeDoc) {
            return $this
                ->addUsingAlias(DocPeer::ID, $incomeDoc->getDocId(), $comparison);
        } elseif ($incomeDoc instanceof PropelObjectCollection) {
            return $this
                ->useIncomeDocQuery()
                ->filterByPrimaryKeys($incomeDoc->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIncomeDoc() only accepts arguments of type IncomeDoc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IncomeDoc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function joinIncomeDoc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IncomeDoc');

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
            $this->addJoinObject($join, 'IncomeDoc');
        }

        return $this;
    }

    /**
     * Use the IncomeDoc relation IncomeDoc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\IncomeDocQuery A secondary query class using the current class as primary query
     */
    public function useIncomeDocQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncomeDoc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IncomeDoc', '\AppBundle\Model\IncomeDocQuery');
    }

    /**
     * Filter the query by a related CostDoc object
     *
     * @param   CostDoc|PropelObjectCollection $costDoc  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCostDoc($costDoc, $comparison = null)
    {
        if ($costDoc instanceof CostDoc) {
            return $this
                ->addUsingAlias(DocPeer::ID, $costDoc->getDocId(), $comparison);
        } elseif ($costDoc instanceof PropelObjectCollection) {
            return $this
                ->useCostDocQuery()
                ->filterByPrimaryKeys($costDoc->getPrimaryKeys())
                ->endUse();
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
     * @return DocQuery The current query, for fluid interface
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
     * @return   \AppBundle\Model\CostDocQuery A secondary query class using the current class as primary query
     */
    public function useCostDocQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCostDoc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CostDoc', '\AppBundle\Model\CostDocQuery');
    }

    /**
     * Filter the query by a related Contract object
     *
     * @param   Contract|PropelObjectCollection $contract  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByContract($contract, $comparison = null)
    {
        if ($contract instanceof Contract) {
            return $this
                ->addUsingAlias(DocPeer::ID, $contract->getDocId(), $comparison);
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
     * @return DocQuery The current query, for fluid interface
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
     * @param   Doc $doc Object to remove from the list of results
     *
     * @return DocQuery The current query, for fluid interface
     */
    public function prune($doc = null)
    {
        if ($doc) {
            $this->addUsingAlias(DocPeer::ID, $doc->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
