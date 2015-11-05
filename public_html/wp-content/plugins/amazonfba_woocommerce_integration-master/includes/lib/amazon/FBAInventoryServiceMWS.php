<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/* FBAOutboundServiceMWS
	Model
	Interface
	Exception
	Client
		~ Models...
*/
/* Model */
abstract class FBAInventoryServiceMWS_Model
{

    /** @var array */
    protected  $_fields = array ();

    /**
     * Construct new model class
     *
     * @param mixed $data - DOMElement or Associative Array to construct from.
     */
    public function __construct($data = null)
    {
        if (!is_null($data)) {
            if ($this->_isAssociativeArray($data)) {
                $this->_fromAssociativeArray($data);
            } elseif ($this->_isDOMElement($data)) {
                $this->_fromDOMElement($data);
            } else {
                throw new Exception ("Unable to construct from provided data.
                                Please be sure to pass associative array or DOMElement");
            }

        }
    }

    /**
     * Support for virtual properties getters.
     *
     * Virtual property call example:
     *
     *   $action->Property
     *
     * Direct getter(preferred):
     *
     *   $action->getProperty()
     *
     * @param string $propertyName name of the property
     */
    public function __get($propertyName)
    {
       $getter = "get$propertyName";
       return $this->$getter();
    }

    /**
     * Support for virtual properties setters.
     *
     * Virtual property call example:
     *
     *   $action->Property  = 'ABC'
     *
     * Direct setter (preferred):
     *
     *   $action->setProperty('ABC')
     *
     * @param string $propertyName name of the property
     */
    public function __set($propertyName, $propertyValue)
    {
       $setter = "set$propertyName";
       $this->$setter($propertyValue);
       return $this;
    }


    /**
     * XML fragment representation of this object
     * Note, name of the root determined by caller
     * This fragment returns inner fields representation only
     * @return string XML fragment for this object
     */
    protected function _toXMLFragment()
    {
        $xml = "";
        foreach ($this->_fields as $fieldName => $field) {
            $fieldValue = $field['FieldValue'];
            if (!is_null($fieldValue)) {
                $fieldType = $field['FieldType'];
                if (is_array($fieldType)) {
                    if ($this->_isComplexType($fieldType[0])) {
                        foreach ($fieldValue as $item) {
                            $xml .= "<$fieldName>";
                            $xml .= $item->_toXMLFragment();
                            $xml .= "</$fieldName>";
                        }
                    } else {
                        foreach ($fieldValue as $item) {
                            $xml .= "<$fieldName>";
                            $xml .= $this->_escapeXML($item);
                            $xml .= "</$fieldName>";
                        }
                    }
                } else {
                    if ($this->_isComplexType($fieldType)) {
                        $xml .= "<$fieldName>";
                        $xml .= $fieldValue->_toXMLFragment();
                        $xml .= "</$fieldName>";
                    } else {
                        $xml .= "<$fieldName>";
                        $xml .= $this->_escapeXML($fieldValue);
                        $xml .= "</$fieldName>";
                    }
                }
            }
        }
        return $xml;
    }


    /**
     * Escape special XML characters
     * @return string with escaped XML characters
     */
    private function _escapeXML($str)
    {
        $from = array( "&", "<", ">", "'", "\"");
        $to = array( "&amp;", "&lt;", "&gt;", "&#039;", "&quot;");
        return str_replace($from, $to, $str);
    }



    /**
     * Construct from DOMElement
     *
     * This function iterates over object fields and queries XML
     * for corresponding tag value. If query succeeds, value extracted
     * from xml, and field value properly constructed based on field type.
     *
     * Field types defined as arrays always constructed as arrays,
     * even if XML contains a single element - to make sure that
     * data structure is predictable, and no is_array checks are
     * required.
     *
     * @param DOMElement $dom XML element to construct from
     */
    private function _fromDOMElement(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/');

        foreach ($this->_fields as $fieldName => $field) {
            $fieldType = $field['FieldType'];
            if (is_array($fieldType)) {
                if ($this->_isComplexType($fieldType[0])) {
                    $elements = $xpath->query("./a:$fieldName", $dom);
                    if ($elements->length >= 1) {
                        foreach ($elements as $element) {
                            $this->_fields[$fieldName]['FieldValue'][] = new $fieldType[0]($element);
                        }
                    }
                } else {
                    $elements = $xpath->query("./a:$fieldName", $dom);
                    if ($elements->length >= 1) {
                        foreach ($elements as $element) {
                            $text = $xpath->query('./text()', $element);
                            $this->_fields[$fieldName]['FieldValue'][] = $text->item(0)->data;
                        }
                    }
                }
            } else {
                if ($this->_isComplexType($fieldType)) {
                    $elements = $xpath->query("./a:$fieldName", $dom);
                    if ($elements->length == 1) {
                        $this->_fields[$fieldName]['FieldValue'] = new $fieldType($elements->item(0));
                    }
                } else {
                    $element = $xpath->query("./a:$fieldName/text()", $dom);
                    if ($element->length == 1) {
                        $this->_fields[$fieldName]['FieldValue'] = $element->item(0)->data;
                    }
                }
            }
        }
    }


    /**
     * Construct from Associative Array
     *
     *
     * @param array $array associative array to construct from
     */
    private function _fromAssociativeArray(array $array)
    {
        foreach ($this->_fields as $fieldName => $field) {
            $fieldType = $field['FieldType'];
            if (is_array($fieldType)) {
                if ($this->_isComplexType($fieldType[0])) {
                    if (array_key_exists($fieldName, $array)) {
                        $elements = $array[$fieldName];
                        if (!$this->_isNumericArray($elements)) {
                            $elements =  array($elements);
                        }
                        if (count ($elements) >= 1) {
                            foreach ($elements as $element) {
                                $this->_fields[$fieldName]['FieldValue'][] = new $fieldType[0]($element);
                            }
                        }
                    }
                } else {
                    if (array_key_exists($fieldName, $array)) {
                        $elements = $array[$fieldName];
                        if (!$this->_isNumericArray($elements)) {
                            $elements =  array($elements);
                            }
                        if (count ($elements) >= 1) {
                            foreach ($elements as $element) {
                                $this->_fields[$fieldName]['FieldValue'][] = $element;
                            }
                        }
                    }
                }
            } else {
                if ($this->_isComplexType($fieldType)) {
                    if (array_key_exists($fieldName, $array)) {
                        $this->_fields[$fieldName]['FieldValue'] = new $fieldType($array[$fieldName]);
                    }
                } else {
                    if (array_key_exists($fieldName, $array)) {
                        $this->_fields[$fieldName]['FieldValue'] = $array[$fieldName];
                    }
                }
            }
        }
    }



    /**
     * Determines if field is complex type
     *
     * @param string $fieldType field type name
     */
    private function _isComplexType ($fieldType)
    {
        return preg_match('/^FBAInventoryServiceMWS_Model_/', $fieldType);
    }

   /**
    * Checks  whether passed variable is an associative array
    *
    * @param mixed $var
    * @return TRUE if passed variable is an associative array
    */
    private function _isAssociativeArray($var) {
        return is_array($var) && array_keys($var) !== range(0, sizeof($var) - 1);
    }

   /**
    * Checks  whether passed variable is DOMElement
    *
    * @param mixed $var
    * @return TRUE if passed variable is DOMElement
    */
    private function _isDOMElement($var) {
        return $var instanceof DOMElement;
    }

   /**
    * Checks  whether passed variable is numeric array
    *
    * @param mixed $var
    * @return TRUE if passed variable is an numeric array
    */
    protected function _isNumericArray($var) {
        return is_array($var) && array_keys($var) === range(0, sizeof($var) - 1);
    }
}
/* Model */
/* Interface */
interface  FBAInventoryServiceMWS_Interface
{
    public function listInventorySupplyByNextToken($request);
    public function listInventorySupply($request);
    public function getServiceStatus($request);

}
/* Interface */
/* Exception */
class FBAInventoryServiceMWS_Exception extends Exception

{
    /** @var string */
    private $_message = null;
    /** @var int */
    private $_statusCode = -1;
    /** @var string */
    private $_errorCode = null;
    /** @var string */
    private $_errorType = null;
    /** @var string */
    private $_requestId = null;
    /** @var string */
    private $_xml = null;


    /**
     * Constructs FBAInventoryServiceMWS_Exception
     * @param array $errorInfo details of exception.
     * Keys are:
     * <ul>
     * <li>Message - (string) text message for an exception</li>
     * <li>StatusCode - (int) HTTP status code at the time of exception</li>
     * <li>ErrorCode - (string) specific error code returned by the service</li>
     * <li>ErrorType - (string) Possible types:  Sender, Receiver or Unknown</li>
     * <li>RequestId - (string) request id returned by the service</li>
     * <li>XML - (string) compete xml response at the time of exception</li>
     * <li>Exception - (Exception) inner exception if any</li>
     * </ul>
     *
     */
    public function __construct(array $errorInfo = array())
    {
        $this->_message = $errorInfo["Message"];
        parent::__construct($this->_message);
        if (array_key_exists("Exception", $errorInfo)) {
            $exception = $errorInfo["Exception"];
            if ($exception instanceof FBAInventoryServiceMWS_Exception) {
                $this->_statusCode = $exception->getStatusCode();
                $this->_errorCode = $exception->getErrorCode();
                $this->_errorType = $exception->getErrorType();
                $this->_requestId = $exception->getRequestId();
                $this->_xml= $exception->getXML();
            }
        } else {
            $this->_statusCode = $errorInfo["StatusCode"];
            $this->_errorCode = $errorInfo["ErrorCode"];
            $this->_errorType = $errorInfo["ErrorType"];
            $this->_requestId = $errorInfo["RequestId"];
            $this->_xml= $errorInfo["XML"];
        }
    }

    /**
     * Gets error type returned by the service if available.
     *
     * @return string Error Code returned by the service
     */
    public function getErrorCode(){
        return $this->_errorCode;
    }

    /**
     * Gets error type returned by the service.
     *
     * @return string Error Type returned by the service.
     * Possible types:  Sender, Receiver or Unknown
     */
    public function getErrorType(){
        return $this->_errorType;
    }


    /**
     * Gets error message
     *
     * @return string Error message
     */
    public function getErrorMessage() {
        return $this->_message;
    }

    /**
     * Gets status code returned by the service if available. If status
     * code is set to -1, it means that status code was unavailable at the
     * time exception was thrown
     *
     * @return int status code returned by the service
     */
    public function getStatusCode() {
        return $this->_statusCode;
    }

    /**
     * Gets XML returned by the service if available.
     *
     * @return string XML returned by the service
     */
    public function getXML() {
        return $this->_xml;
    }

    /**
     * Gets Request ID returned by the service if available.
     *
     * @return string Request ID returned by the service
     */
    public function getRequestId() {
        return $this->_requestId;
    }
}
/* Exception */
/* Client */
class FBAInventoryServiceMWS_Client implements FBAInventoryServiceMWS_Interface
{

    /** @var string */
    private  $_awsAccessKeyId = null;

    /** @var string */
    private  $_awsSecretAccessKey = null;

    /** @var array */
    private  $_config = array ('ServiceURL' => 'http://localhost:8000/',
                               'UserAgent' => 'FBAInventoryServiceMWS PHP5 Library',
                               'SignatureVersion' => 2,
                               'SignatureMethod' => 'HmacSHA256',
                               'ProxyHost' => null,
                               'ProxyPort' => -1,
                               'MaxErrorRetry' => 3
                               );

    private $_serviceVersion = null;

    const REQUEST_TYPE = "POST";

    const MWS_CLIENT_VERSION = "2012-09-28";

    /**
     * Construct new Client
     *
     * @param string $awsAccessKeyId AWS Access Key ID
     * @param string $awsSecretAccessKey AWS Secret Access Key
     * @param array $config configuration options.
     * @param array $attributes user-agent attributes
     * Valid configuration options are:
     * <ul>
     * <li>ServiceURL</li>
     * <li>UserAgent</li>
     * <li>SignatureVersion</li>
     * <li>TimesRetryOnError</li>
     * <li>ProxyHost</li>
     * <li>ProxyPort</li>
     * <li>MaxErrorRetry</li>
     * </ul>
     */

    public function __construct(
    $awsAccessKeyId, $awsSecretAccessKey, $config, $applicationName, $applicationVersion, $attributes = null)
    {
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');

        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;
        $this->_serviceVersion = $applicationVersion;
        if (!is_null($config)) $this->_config = array_merge($this->_config, $config);
        $this->setUserAgentHeader($applicationName, $applicationVersion, $attributes);
    }

  /**
   * Sets a MWS compliant HTTP User-Agent Header value.
   * $attributeNameValuePairs is an associative array.
   *
   * @param $applicationName
   * @param $applicationVersion
   * @param $attributes
   * @return unknown_type
   */
  public function setUserAgentHeader(
      $applicationName,
      $applicationVersion,
      $attributes = null) {

    if (is_null($attributes)) {
      $attributes = array ();
    }

    $this->_config['UserAgent'] =
        $this->constructUserAgentHeader($applicationName, $applicationVersion, $attributes);
  }

  /**
   * Construct a valid MWS compliant HTTP User-Agent Header. From the MWS Developer's Guide, this
   * entails:
   * "To meet the requirements, begin with the name of your application, followed by a forward
   * slash, followed by the version of the application, followed by a space, an opening
   * parenthesis, the Language name value pair, and a closing paranthesis. The Language parameter
   * is a required attribute, but you can add additional attributes separated by semi-colons."
   *
   * @param $applicationName
   * @param $applicationVersion
   * @param $additionalNameValuePairs
   * @return unknown_type
   */
  private function constructUserAgentHeader($applicationName, $applicationVersion, $attributes = null) {

    if (is_null($applicationName) || $applicationName === "") {
      throw new InvalidArguementException('$applicationName cannot be null.');
    }

    if (is_null($applicationVersion) || $applicationVersion === "") {
      throw new InvalidArguementException('$applicationVersion cannot be null.');
    }

    $userAgent =
    $this->quoteApplicationName($applicationName)
        . '/'
        . $this->quoteApplicationVersion($applicationVersion);

    $userAgent .= ' (';

    $userAgent .= 'Language=PHP/' . phpversion();
    $userAgent .= '; ';
    $userAgent .= 'Platform=' . php_uname('s') . '/' . php_uname('m') . '/' . php_uname('r');
    $userAgent .= '; ';
    $userAgent .= 'MWSClientVersion=' . self::MWS_CLIENT_VERSION;

    foreach ($attributes as $key => $value) {
      if (is_null($value) || $value === '') {
        throw new InvalidArgumentException("Value for $key cannot be null or empty.");
      }

      $userAgent .= '; '
        . $this->quoteAttributeName($key)
        . '='
        . $this->quoteAttributeValue($value);
    }
    $userAgent .= ')';

    return $userAgent;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' character.
   * @param $s
   * @return string
   */
  private function collapseWhitespace($s) {
    return preg_replace('/ {2,}|\s/', ' ', $s);
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
   * and '/' characters from a string.
   * @param $s
   * @return string
   */
  private function quoteApplicationName($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\//', '\\/', $quotedString);

    return $quotedString;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
   * and '(' characters from a string.
   *
   * @param $s
   * @return string
   */
  private function quoteApplicationVersion($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\(/', '\\(', $quotedString);

    return $quotedString;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape '\',
   * and '=' characters from a string.
   *
   * @param $s
   * @return unknown_type
   */
  private function quoteAttributeName($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\=/', '\\=', $quotedString);

    return $quotedString;
  }

  /**
   * Collapse multiple whitespace characters into a single ' ' and backslash escape ';', '\',
   * and ')' characters from a string.
   *
   * @param $s
   * @return unknown_type
   */
  private function quoteAttributeValue($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
    $quotedString = preg_replace('/\\)/', '\\)', $quotedString);

    return $quotedString;
    }

    // Public API ------------------------------------------------------------//



    /**
     * List Inventory Supply By Next Token
     * Continues pagination over a resultset of inventory data for inventory
     * items.
     *
     * This operation is used in conjunction with ListUpdatedInventorySupply.
     * Please refer to documentation for that operation for further details.
     *
     * @param mixed $request array of parameters for FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest request
     * or FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest object itself
     * @see FBAInventoryServiceMWS_Model_ListInventorySupplyByNextToken
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse
     *
     * @throws FBAInventoryServiceMWS_Exception
     */
    public function listInventorySupplyByNextToken($request)
    {
        if (!$request instanceof FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest) {
            $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest($request);
        }
        return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse::fromXML($this->_invoke($this->_convertListInventorySupplyByNextToken($request)));
    }



    /**
     * List Inventory Supply
     * Get information about the supply of seller-owned inventory in
     * Amazon's fulfillment network. "Supply" is inventory that is available
     * for fulfilling (a.k.a. Multi-Channel Fulfillment) orders. In general
     * this includes all sellable inventory that has been received by Amazon,
     * that is not reserved for existing orders or for internal FC processes,
     * and also inventory expected to be received from inbound shipments.
     * This operation provides 2 typical usages by setting different
     * ListInventorySupplyRequest value:
     *
     * 1. Set value to SellerSkus and not set value to QueryStartDateTime,
     * this operation will return all sellable inventory that has been received
     * by Amazon's fulfillment network for these SellerSkus.
     * 2. Not set value to SellerSkus and set value to QueryStartDateTime,
     * This operation will return information about the supply of all seller-owned
     * inventory in Amazon's fulfillment network, for inventory items that may have had
     * recent changes in inventory levels. It provides the most efficient mechanism
     * for clients to maintain local copies of inventory supply data.
     * Only 1 of these 2 parameters (SellerSkus and QueryStartDateTime) can be set value for 1 request.
     * If both with values or neither with values, an exception will be thrown.
     * This operation is used with ListInventorySupplyByNextToken
     * to paginate over the resultset. Begin pagination by invoking the
     * ListInventorySupply operation, and retrieve the first set of
     * results. If more results are available,continuing iteratively requesting further
     * pages results by invoking the ListInventorySupplyByNextToken operation (each time
     * passing in the NextToken value from the previous result), until the returned NextToken
     * is null, indicating no further results are available.
     *
     * @param mixed $request array of parameters for FBAInventoryServiceMWS_Model_ListInventorySupplyRequest request
     * or FBAInventoryServiceMWS_Model_ListInventorySupplyRequest object itself
     * @see FBAInventoryServiceMWS_Model_ListInventorySupply
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyResponse FBAInventoryServiceMWS_Model_ListInventorySupplyResponse
     *
     * @throws FBAInventoryServiceMWS_Exception
     */
    public function listInventorySupply($request)
    {
        if (!$request instanceof FBAInventoryServiceMWS_Model_ListInventorySupplyRequest) {
            $request = new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest($request);
        }
        return FBAInventoryServiceMWS_Model_ListInventorySupplyResponse::fromXML($this->_invoke($this->_convertListInventorySupply($request)));
    }



    /**
     * Get Service Status
     * Gets the status of the service.
     * Status is one of GREEN, RED representing:
     * GREEN: This API section of the service is operating normally.
     * RED: The service is disrupted.
     *
     * @param mixed $request array of parameters for FBAInventoryServiceMWS_Model_GetServiceStatusRequest request
     * or FBAInventoryServiceMWS_Model_GetServiceStatusRequest object itself
     * @see FBAInventoryServiceMWS_Model_GetServiceStatus
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusResponse FBAInventoryServiceMWS_Model_GetServiceStatusResponse
     *
     * @throws FBAInventoryServiceMWS_Exception
     */
    public function getServiceStatus($request)
    {
        if (!$request instanceof FBAInventoryServiceMWS_Model_GetServiceStatusRequest) {
            $request = new FBAInventoryServiceMWS_Model_GetServiceStatusRequest($request);
        }
        return FBAInventoryServiceMWS_Model_GetServiceStatusResponse::fromXML($this->_invoke($this->_convertGetServiceStatus($request)));
    }

        // Private API ------------------------------------------------------------//

    /**
     * Invoke request and return response
     */
    private function _invoke(array $parameters)
    {
        $actionName = $parameters["Action"];
        $response = array();
        $responseBody = null;
        $statusCode = 200;

        /* Submit the request and read response body */
        try {

            // Ensure the endpoint URL is set.
            if (empty($this->_config['ServiceURL'])) {
                throw new MarketplaceWebService_Exception(
                    array('ErrorCode' => 'InvalidServiceUrl',
                          'Message' => "Missing serviceUrl configuration value. You may obtain a list of valid MWS URLs by consulting the MWS Developer's Guide, or reviewing the sample code published along side this library."));
            }

            /* Add required request parameters */
            $parameters = $this->_addRequiredParameters($parameters);

            $shouldRetry = false;
            $retries = 0;
            do {
                try {
                        $response = $this->_httpPost($parameters);
                        $httpStatus = $response['Status'];
                        switch ($httpStatus)
                        {
                            case 200:
                                $shouldRetry = false;
                                break;

                            case 500:
                            case 503:
                                $errorResponse = FBAInventoryServiceMWS_Model_ErrorResponse::fromXML($response['ResponseBody']);

                                // We will not retry throttling errors since this would just add to the throttling problem.
                                $errors = $errorResponse->getError();
                                $shouldRetry = ($errors[0]->getCode() === 'RequestThrottled') ? false : true;

                                if ($shouldRetry && $retries <= $this->config['MaxErrorRetry'])
                                {
                                    $this->_pauseOnRetry(++$retries);
                                }
                                else
                                {
                                    throw $this->_reportAnyErrors($response['ResponseBody'], $response['Status']);
                                }
                                break;

                            default:
                                    $shouldRetry = false;
                                    throw $this->_reportAnyErrors($response['ResponseBody'], $response['Status']);
                                break;
                        }
                /* Rethrow on deserializer error */
                } catch (Exception $e) {
                    throw new FBAInventoryServiceMWS_Exception(array('Exception' => $e, 'Message' => $e->getMessage()));
                }

            } while ($shouldRetry);

        } catch (FBAInventoryServiceMWS_Exception $se) {
            throw $se;
        } catch (Exception $t) {
            throw new FBAInventoryServiceMWS_Exception(array('Exception' => $t, 'Message' => $t->getMessage()));
        }

        return $response['ResponseBody'];
    }

    /**
     * Look for additional error strings in the response and return formatted exception
     */
    private function _reportAnyErrors($responseBody, $status, Exception $e =  null)
    {
        $ex = null;
        if (!is_null($responseBody) && strpos($responseBody, '<') === 0) {
            if (preg_match('@<RequestId>(.*)</RequestId>.*<Error><Code>(.*)</Code><Message>(.*)</Message></Error>.*(<Error>)?@mi',
                $responseBody, $errorMatcherOne)) {

                $requestId = $errorMatcherOne[1];
                $code = $errorMatcherOne[2];
                $message = $errorMatcherOne[3];

                $ex = new FBAInventoryServiceMWS_Exception(array ('Message' => $message, 'StatusCode' => $status, 'ErrorCode' => $code,
                                                           'ErrorType' => 'Unknown', 'RequestId' => $requestId, 'XML' => $responseBody));

            } elseif (preg_match('@<Error><Code>(.*)</Code><Message>(.*)</Message></Error>.*(<Error>)?.*<RequestID>(.*)</RequestID>@mi',
                $responseBody, $errorMatcherTwo)) {

                $code = $errorMatcherTwo[1];
                $message = $errorMatcherTwo[2];
                $requestId = $errorMatcherTwo[4];
                $ex = new FBAInventoryServiceMWS_Exception(array ('Message' => $message, 'StatusCode' => $status, 'ErrorCode' => $code,
                                                              'ErrorType' => 'Unknown', 'RequestId' => $requestId, 'XML' => $responseBody));
            } elseif (preg_match('@<Error><Type>(.*)</Type><Code>(.*)</Code><Message>(.*)</Message>.*</Error>.*(<Error>)?.*<RequestId>(.*)</RequestId>@mi',
                $responseBody, $errorMatcherThree)) {

                $type = $errorMatcherThree[1];
                $code = $errorMatcherThree[2];
                $message = $errorMatcherThree[3];
                $requestId = $errorMatcherThree[5];
                $ex = new FBAInventoryServiceMWS_Exception(array ('Message' => $message, 'StatusCode' => $status, 'ErrorCode' => $code,
                                                              'ErrorType' => $type, 'RequestId' => $requestId, 'XML' => $responseBody));

            } elseif (preg_match('@<Error>\n.*<Type>(.*)</Type>\n.*<Code>(.*)</Code>\n.*<Message>(.*)</Message>\n.*</Error>\n?.*<RequestId>(.*)</RequestId>\n.*@mi',
                $responseBody, $errorMatcherFour)) {

                $type = $errorMatcherFour[1];
                $code = $errorMatcherFour[2];
                $message = $errorMatcherFour[3];
                $requestId = $errorMatcherFour[4];
                $ex = new FBAInventoryServiceMWS_Exception(array ('Message' => $message, 'StatusCode' => $status, 'ErrorCode' => $code,
                                                              'ErrorType' => $type, 'RequestId' => $requestId, 'XML' => $responseBody));

            } else {
                $ex = new FBAInventoryServiceMWS_Exception(array('Message' => 'Internal Error', 'StatusCode' => $status));
            }
        } else {
            $ex = new FBAInventoryServiceMWS_Exception(array('Message' => 'Internal Error', 'StatusCode' => $status));
        }
        return $ex;
    }



    /**
     * Perform HTTP post with exponential retries on error 500 and 503
     *
     */
    private function _httpPost(array $parameters)
    {
        $query = $this->_getParametersAsString($parameters);
        $url = parse_url ($this->_config['ServiceURL']);
        $scheme = '';

        switch ($url['scheme']) {
            case 'https':
                $scheme = 'https://';
                $port = $port === null ? 443 : $port;
                break;
            default:
                $scheme = '';
                $port = $port === null ? 80 : $port;
        }

$THEurl = $scheme . $url['host'] . $url['path'];
$response = wp_remote_post( $THEurl, array(
    'method' => 'POST',
    'timeout' => 45,
    'redirection' => 5,
    'httpversion' => '1.0',
    'blocking' => true,
    'headers' => array(),
    'body' => $query,
    'cookies' => array()
    )
);

if ( is_wp_error( $response ) ) {
    $errorResponse = $response->get_error_message();

    throw new FBAInventoryServiceMWS_Exception(
        array(
            'Message' => $errorResponse,
            'ErrorType' => 'HTTP'
        )
    );
}
foreach($response["headers"] as $headers){
$other[] = $headers;
}
		$responseBody = $response["body"];
    $other = implode(",", $other);
		$code = (string)$response["response"]["code"];
		$text = (string)$response["response"]["message"];
        return array ('Status' => (int)$code, 'ResponseBody' => $responseBody);
    }

    /**
     * Exponential sleep on failed request
     * @param retries current retry
     * @throws FBAInventoryServiceMWS_Exception if maximum number of retries has been reached
     */
    private function _pauseOnRetry($retries)
    {
        $delay = (int) (pow(4, $retries) * 100000) ;
        usleep($delay);
    }

    /**
     * Add authentication related and version parameters
     */
    private function _addRequiredParameters(array $parameters)
    {
        $parameters['AWSAccessKeyId'] = $this->_awsAccessKeyId;
        $parameters['Timestamp'] = $this->_getFormattedTimestamp();
        $parameters['Version'] = $this->_serviceVersion;
        $parameters['SignatureVersion'] = $this->_config['SignatureVersion'];
        if ($parameters['SignatureVersion'] > 1) {
            $parameters['SignatureMethod'] = $this->_config['SignatureMethod'];
        }
        $parameters['Signature'] = $this->_signParameters($parameters, $this->_awsSecretAccessKey);

        return $parameters;
    }

    /**
     * Convert paremeters to Url encoded query string
     */
    private function _getParametersAsString(array $parameters)
    {
        $queryParameters = array();
        foreach ($parameters as $key => $value) {
            if (!is_null($key) && $key !=='' && !is_null($value) && $value!=='')
            {
                $queryParameters[] = $key . '=' . $this->_urlencode($value);
            }
        }
        return implode('&', $queryParameters);
    }


    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * Signature Version 0: This is not supported in the MWS.
     *
     * Signature Version 1: This is not supported in the MWS.
     *
     * Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private function _signParameters(array $parameters, $key) {
        $signatureVersion = $parameters['SignatureVersion'];
        $algorithm = "HmacSHA1";
        $stringToSign = null;
        if (0 === $signatureVersion) {
            throw new InvalidArguementException(
                'Signature Version 0 is no longer supported. Only Signature Version 2 is supported.');
        } else if (1 === $signatureVersion) {
            throw new InvalidArguementException(
                'Signature Version 1 is no longer supported. Only Signature Version 2 is supported.');
        } else if (2 === $signatureVersion) {
            $algorithm = $this->_config['SignatureMethod'];
            $parameters['SignatureMethod'] = $algorithm;
            $stringToSign = $this->_calculateStringToSignV2($parameters);
        } else {
            throw new Exception("Invalid Signature Version specified");
        }
        return $this->_sign($stringToSign, $key, $algorithm);
    }

    /**
     * Calculate String to Sign for SignatureVersion 2
     * @param array $parameters request parameters
     * @return String to Sign
     */
    private function _calculateStringToSignV2(array $parameters) {
        $parsedUrl = parse_url($this->_config['ServiceURL']);
        $endpoint = $parsedUrl['host'];
        if (!is_null($parsedUrl['port'])) {
          $endpoint .= ':' . $parsedUrl['port'];
        }

        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url ($this->_config['ServiceURL']);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset ($uri)) {
            $uri = "/";
        }
        $uriencoded = implode("/", array_map(array($this, "_urlencode"), explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($parameters, 'strcmp');
        $data .= $this->_getParametersAsString($parameters);
        return $data;
    }

    private function _urlencode($value) {
        return str_replace('%7E', '~', rawurlencode($value));
    }


    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private function _sign($data, $key, $algorithm)
    {
        if ($algorithm === 'HmacSHA1') {
            $hash = 'sha1';
        } else if ($algorithm === 'HmacSHA256') {
            $hash = 'sha256';
        } else {
            throw new Exception ("Non-supported signing method specified");
        }
        return base64_encode(
            hash_hmac($hash, $data, $key, true)
        );
    }


    /**
     * Formats date as ISO 8601 timestamp
     */
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }



    /**
     * Convert ListInventorySupplyByNextTokenRequest to name value pairs
     */
    private function _convertListInventorySupplyByNextToken($request) {

        $parameters = array();
        $parameters['Action'] = 'ListInventorySupplyByNextToken';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetNextToken()) {
            $parameters['NextToken'] =  $request->getNextToken();
        }

        return $parameters;
    }


    /**
     * Convert ListInventorySupplyRequest to name value pairs
     */
    private function _convertListInventorySupply($request) {

        $parameters = array();
        $parameters['Action'] = 'ListInventorySupply';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetSellerSkus()) {
            $sellerSkuslistInventorySupplyRequest = $request->getSellerSkus();
            foreach  ($sellerSkuslistInventorySupplyRequest->getmember() as $membersellerSkusIndex => $membersellerSkus) {
                $parameters['SellerSkus' . '.' . 'member' . '.'  . ($membersellerSkusIndex + 1)] =  $membersellerSkus;
            }
        }
        if ($request->isSetQueryStartDateTime()) {
            $parameters['QueryStartDateTime'] =  $request->getQueryStartDateTime();
        }
        if ($request->isSetResponseGroup()) {
            $parameters['ResponseGroup'] =  $request->getResponseGroup();
        }

        return $parameters;
    }


    /**
     * Convert GetServiceStatusRequest to name value pairs
     */
    private function _convertGetServiceStatus($request) {

        $parameters = array();
        $parameters['Action'] = 'GetServiceStatus';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }

        return $parameters;
    }


}
/* Client */
/* Models */
class FBAInventoryServiceMWS_Model_Error extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_Error
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Type: string</li>
     * <li>Code: string</li>
     * <li>Message: string</li>
     * <li>Detail: FBAInventoryServiceMWS_Model_Object</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Type' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Code' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Message' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Detail' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Type property.
     *
     * @return string Type
     */
    public function getType()
    {
        return $this->_fields['Type']['FieldValue'];
    }

    /**
     * Sets the value of the Type property.
     *
     * @param string Type
     * @return this instance
     */
    public function setType($value)
    {
        $this->_fields['Type']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Type and returns this instance
     *
     * @param string $value Type
     * @return FBAInventoryServiceMWS_Model_Error instance
     */
    public function withType($value)
    {
        $this->setType($value);
        return $this;
    }


    /**
     * Checks if Type is set
     *
     * @return bool true if Type  is set
     */
    public function isSetType()
    {
        return !is_null($this->_fields['Type']['FieldValue']);
    }

    /**
     * Gets the value of the Code property.
     *
     * @return string Code
     */
    public function getCode()
    {
        return $this->_fields['Code']['FieldValue'];
    }

    /**
     * Sets the value of the Code property.
     *
     * @param string Code
     * @return this instance
     */
    public function setCode($value)
    {
        $this->_fields['Code']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Code and returns this instance
     *
     * @param string $value Code
     * @return FBAInventoryServiceMWS_Model_Error instance
     */
    public function withCode($value)
    {
        $this->setCode($value);
        return $this;
    }


    /**
     * Checks if Code is set
     *
     * @return bool true if Code  is set
     */
    public function isSetCode()
    {
        return !is_null($this->_fields['Code']['FieldValue']);
    }

    /**
     * Gets the value of the Message property.
     *
     * @return string Message
     */
    public function getMessage()
    {
        return $this->_fields['Message']['FieldValue'];
    }

    /**
     * Sets the value of the Message property.
     *
     * @param string Message
     * @return this instance
     */
    public function setMessage($value)
    {
        $this->_fields['Message']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Message and returns this instance
     *
     * @param string $value Message
     * @return FBAInventoryServiceMWS_Model_Error instance
     */
    public function withMessage($value)
    {
        $this->setMessage($value);
        return $this;
    }


    /**
     * Checks if Message is set
     *
     * @return bool true if Message  is set
     */
    public function isSetMessage()
    {
        return !is_null($this->_fields['Message']['FieldValue']);
    }

    /**
     * Gets the value of the Detail.
     *
     * @return Error.Detail Detail
     */
    public function getDetail()
    {
        return $this->_fields['Detail']['FieldValue'];
    }

    /**
     * Sets the value of the Detail.
     *
     * @param Error.Detail Detail
     * @return void
     */
    public function setDetail($value)
    {
        $this->_fields['Detail']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Detail  and returns this instance
     *
     * @param Object $value Detail
     * @return FBAInventoryServiceMWS_Model_Error instance
     */
    public function withDetail($value)
    {
        $this->setDetail($value);
        return $this;
    }


    /**
     * Checks if Detail  is set
     *
     * @return bool true if Detail property is set
     */
    public function isSetDetail()
    {
        return !is_null($this->_fields['Detail']['FieldValue']);

    }




}
class FBAInventoryServiceMWS_Model_ErrorResponse extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ErrorResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Error: FBAInventoryServiceMWS_Model_Error</li>
     * <li>RequestId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Error' => array('FieldValue' => array(), 'FieldType' => array('FBAInventoryServiceMWS_Model_Error')),
        'RequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAInventoryServiceMWS_Model_ErrorResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAInventoryServiceMWS_Model_ErrorResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/');
        $response = $xpath->query('//a:ErrorResponse');
        if ($response->length == 1) {
            return new FBAInventoryServiceMWS_Model_ErrorResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAInventoryServiceMWS_Model_ErrorResponse from provided XML.
                                  Make sure that ErrorResponse is a root element");
        }

    }

    /**
     * Gets the value of the Error.
     *
     * @return array of Error Error
     */
    public function getError()
    {
        return $this->_fields['Error']['FieldValue'];
    }

    /**
     * Sets the value of the Error.
     *
     * @param mixed Error or an array of Error Error
     * @return this instance
     */
    public function setError($error)
    {
        if (!$this->_isNumericArray($error)) {
            $error =  array ($error);
        }
        $this->_fields['Error']['FieldValue'] = $error;
        return $this;
    }


    /**
     * Sets single or multiple values of Error list via variable number of arguments.
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withError($error1, $error2)</code>
     *
     * @param Error  $errorArgs one or more Error
     * @return FBAInventoryServiceMWS_Model_ErrorResponse  instance
     */
    public function withError($errorArgs)
    {
        foreach (func_get_args() as $error) {
            $this->_fields['Error']['FieldValue'][] = $error;
        }
        return $this;
    }



    /**
     * Checks if Error list is non-empty
     *
     * @return bool true if Error list is non-empty
     */
    public function isSetError()
    {
        return count ($this->_fields['Error']['FieldValue']) > 0;
    }

    /**
     * Gets the value of the RequestId property.
     *
     * @return string RequestId
     */
    public function getRequestId()
    {
        return $this->_fields['RequestId']['FieldValue'];
    }

    /**
     * Sets the value of the RequestId property.
     *
     * @param string RequestId
     * @return this instance
     */
    public function setRequestId($value)
    {
        $this->_fields['RequestId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the RequestId and returns this instance
     *
     * @param string $value RequestId
     * @return FBAInventoryServiceMWS_Model_ErrorResponse instance
     */
    public function withRequestId($value)
    {
        $this->setRequestId($value);
        return $this;
    }


    /**
     * Checks if RequestId is set
     *
     * @return bool true if RequestId  is set
     */
    public function isSetRequestId()
    {
        return !is_null($this->_fields['RequestId']['FieldValue']);
    }



    /**
     * XML Representation for this object
     *
     * @return string XML for this object
     */
    public function toXML()
    {
        $xml = "";
        $xml .= "<ErrorResponse xmlns=\"http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ErrorResponse>";
        return $xml;
    }

}
class FBAInventoryServiceMWS_Model_GetServiceStatusRequest extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_GetServiceStatusRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SellerId property.
     *
     * @return string SellerId
     */
    public function getSellerId()
    {
        return $this->_fields['SellerId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerId property.
     *
     * @param string SellerId
     * @return this instance
     */
    public function setSellerId($value)
    {
        $this->_fields['SellerId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerId and returns this instance
     *
     * @param string $value SellerId
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusRequest instance
     */
    public function withSellerId($value)
    {
        $this->setSellerId($value);
        return $this;
    }


    /**
     * Checks if SellerId is set
     *
     * @return bool true if SellerId  is set
     */
    public function isSetSellerId()
    {
        return !is_null($this->_fields['SellerId']['FieldValue']);
    }

    /**
     * Gets the value of the Marketplace property.
     *
     * @return string Marketplace
     */
    public function getMarketplace()
    {
        return $this->_fields['Marketplace']['FieldValue'];
    }

    /**
     * Sets the value of the Marketplace property.
     *
     * @param string Marketplace
     * @return this instance
     */
    public function setMarketplace($value)
    {
        $this->_fields['Marketplace']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Marketplace and returns this instance
     *
     * @param string $value Marketplace
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusRequest instance
     */
    public function withMarketplace($value)
    {
        $this->setMarketplace($value);
        return $this;
    }


    /**
     * Checks if Marketplace is set
     *
     * @return bool true if Marketplace  is set
     */
    public function isSetMarketplace()
    {
        return !is_null($this->_fields['Marketplace']['FieldValue']);
    }




}
class FBAInventoryServiceMWS_Model_GetServiceStatusResponse extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_GetServiceStatusResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>GetServiceStatusResult: FBAInventoryServiceMWS_Model_GetServiceStatusResult</li>
     * <li>ResponseMetadata: FBAInventoryServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'GetServiceStatusResult' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_GetServiceStatusResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAInventoryServiceMWS_Model_GetServiceStatusResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/');
        $response = $xpath->query('//a:GetServiceStatusResponse');
        if ($response->length == 1) {
            return new FBAInventoryServiceMWS_Model_GetServiceStatusResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAInventoryServiceMWS_Model_GetServiceStatusResponse from provided XML.
                                  Make sure that GetServiceStatusResponse is a root element");
        }

    }

    /**
     * Gets the value of the GetServiceStatusResult.
     *
     * @return GetServiceStatusResult GetServiceStatusResult
     */
    public function getGetServiceStatusResult()
    {
        return $this->_fields['GetServiceStatusResult']['FieldValue'];
    }

    /**
     * Sets the value of the GetServiceStatusResult.
     *
     * @param GetServiceStatusResult GetServiceStatusResult
     * @return void
     */
    public function setGetServiceStatusResult($value)
    {
        $this->_fields['GetServiceStatusResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the GetServiceStatusResult  and returns this instance
     *
     * @param GetServiceStatusResult $value GetServiceStatusResult
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusResponse instance
     */
    public function withGetServiceStatusResult($value)
    {
        $this->setGetServiceStatusResult($value);
        return $this;
    }


    /**
     * Checks if GetServiceStatusResult  is set
     *
     * @return bool true if GetServiceStatusResult property is set
     */
    public function isSetGetServiceStatusResult()
    {
        return !is_null($this->_fields['GetServiceStatusResult']['FieldValue']);

    }

    /**
     * Gets the value of the ResponseMetadata.
     *
     * @return ResponseMetadata ResponseMetadata
     */
    public function getResponseMetadata()
    {
        return $this->_fields['ResponseMetadata']['FieldValue'];
    }

    /**
     * Sets the value of the ResponseMetadata.
     *
     * @param ResponseMetadata ResponseMetadata
     * @return void
     */
    public function setResponseMetadata($value)
    {
        $this->_fields['ResponseMetadata']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ResponseMetadata  and returns this instance
     *
     * @param ResponseMetadata $value ResponseMetadata
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusResponse instance
     */
    public function withResponseMetadata($value)
    {
        $this->setResponseMetadata($value);
        return $this;
    }


    /**
     * Checks if ResponseMetadata  is set
     *
     * @return bool true if ResponseMetadata property is set
     */
    public function isSetResponseMetadata()
    {
        return !is_null($this->_fields['ResponseMetadata']['FieldValue']);

    }



    /**
     * XML Representation for this object
     *
     * @return string XML for this object
     */
    public function toXML()
    {
        $xml = "";
        $xml .= "<GetServiceStatusResponse xmlns=\"http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</GetServiceStatusResponse>";
        return $xml;
    }

}
class FBAInventoryServiceMWS_Model_GetServiceStatusResult extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_GetServiceStatusResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Status: string</li>
     * <li>Timestamp: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Status' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Timestamp' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Status property.
     *
     * @return string Status
     */
    public function getStatus()
    {
        return $this->_fields['Status']['FieldValue'];
    }

    /**
     * Sets the value of the Status property.
     *
     * @param string Status
     * @return this instance
     */
    public function setStatus($value)
    {
        $this->_fields['Status']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Status and returns this instance
     *
     * @param string $value Status
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusResult instance
     */
    public function withStatus($value)
    {
        $this->setStatus($value);
        return $this;
    }


    /**
     * Checks if Status is set
     *
     * @return bool true if Status  is set
     */
    public function isSetStatus()
    {
        return !is_null($this->_fields['Status']['FieldValue']);
    }

    /**
     * Gets the value of the Timestamp property.
     *
     * @return string Timestamp
     */
    public function getTimestamp()
    {
        return $this->_fields['Timestamp']['FieldValue'];
    }

    /**
     * Sets the value of the Timestamp property.
     *
     * @param string Timestamp
     * @return this instance
     */
    public function setTimestamp($value)
    {
        $this->_fields['Timestamp']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Timestamp and returns this instance
     *
     * @param string $value Timestamp
     * @return FBAInventoryServiceMWS_Model_GetServiceStatusResult instance
     */
    public function withTimestamp($value)
    {
        $this->setTimestamp($value);
        return $this;
    }


    /**
     * Checks if Timestamp is set
     *
     * @return bool true if Timestamp  is set
     */
    public function isSetTimestamp()
    {
        return !is_null($this->_fields['Timestamp']['FieldValue']);
    }




}
class FBAInventoryServiceMWS_Model_InventorySupply extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_InventorySupply
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerSKU: string</li>
     * <li>FNSKU: string</li>
     * <li>ASIN: string</li>
     * <li>Condition: string</li>
     * <li>TotalSupplyQuantity: int</li>
     * <li>InStockSupplyQuantity: int</li>
     * <li>EarliestAvailability: FBAInventoryServiceMWS_Model_Timepoint</li>
     * <li>SupplyDetail: FBAInventoryServiceMWS_Model_InventorySupplyDetailList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FNSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ASIN' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Condition' => array('FieldValue' => null, 'FieldType' => 'string'),
        'TotalSupplyQuantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'InStockSupplyQuantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'EarliestAvailability' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_Timepoint'),
        'SupplyDetail' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_InventorySupplyDetailList'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SellerSKU property.
     *
     * @return string SellerSKU
     */
    public function getSellerSKU()
    {
        return $this->_fields['SellerSKU']['FieldValue'];
    }

    /**
     * Sets the value of the SellerSKU property.
     *
     * @param string SellerSKU
     * @return this instance
     */
    public function setSellerSKU($value)
    {
        $this->_fields['SellerSKU']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerSKU and returns this instance
     *
     * @param string $value SellerSKU
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withSellerSKU($value)
    {
        $this->setSellerSKU($value);
        return $this;
    }


    /**
     * Checks if SellerSKU is set
     *
     * @return bool true if SellerSKU  is set
     */
    public function isSetSellerSKU()
    {
        return !is_null($this->_fields['SellerSKU']['FieldValue']);
    }

    /**
     * Gets the value of the FNSKU property.
     *
     * @return string FNSKU
     */
    public function getFNSKU()
    {
        return $this->_fields['FNSKU']['FieldValue'];
    }

    /**
     * Sets the value of the FNSKU property.
     *
     * @param string FNSKU
     * @return this instance
     */
    public function setFNSKU($value)
    {
        $this->_fields['FNSKU']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FNSKU and returns this instance
     *
     * @param string $value FNSKU
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withFNSKU($value)
    {
        $this->setFNSKU($value);
        return $this;
    }


    /**
     * Checks if FNSKU is set
     *
     * @return bool true if FNSKU  is set
     */
    public function isSetFNSKU()
    {
        return !is_null($this->_fields['FNSKU']['FieldValue']);
    }

    /**
     * Gets the value of the ASIN property.
     *
     * @return string ASIN
     */
    public function getASIN()
    {
        return $this->_fields['ASIN']['FieldValue'];
    }

    /**
     * Sets the value of the ASIN property.
     *
     * @param string ASIN
     * @return this instance
     */
    public function setASIN($value)
    {
        $this->_fields['ASIN']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ASIN and returns this instance
     *
     * @param string $value ASIN
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withASIN($value)
    {
        $this->setASIN($value);
        return $this;
    }


    /**
     * Checks if ASIN is set
     *
     * @return bool true if ASIN  is set
     */
    public function isSetASIN()
    {
        return !is_null($this->_fields['ASIN']['FieldValue']);
    }

    /**
     * Gets the value of the Condition property.
     *
     * @return string Condition
     */
    public function getCondition()
    {
        return $this->_fields['Condition']['FieldValue'];
    }

    /**
     * Sets the value of the Condition property.
     *
     * @param string Condition
     * @return this instance
     */
    public function setCondition($value)
    {
        $this->_fields['Condition']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Condition and returns this instance
     *
     * @param string $value Condition
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withCondition($value)
    {
        $this->setCondition($value);
        return $this;
    }


    /**
     * Checks if Condition is set
     *
     * @return bool true if Condition  is set
     */
    public function isSetCondition()
    {
        return !is_null($this->_fields['Condition']['FieldValue']);
    }

    /**
     * Gets the value of the TotalSupplyQuantity property.
     *
     * @return int TotalSupplyQuantity
     */
    public function getTotalSupplyQuantity()
    {
        return $this->_fields['TotalSupplyQuantity']['FieldValue'];
    }

    /**
     * Sets the value of the TotalSupplyQuantity property.
     *
     * @param int TotalSupplyQuantity
     * @return this instance
     */
    public function setTotalSupplyQuantity($value)
    {
        $this->_fields['TotalSupplyQuantity']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the TotalSupplyQuantity and returns this instance
     *
     * @param int $value TotalSupplyQuantity
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withTotalSupplyQuantity($value)
    {
        $this->setTotalSupplyQuantity($value);
        return $this;
    }


    /**
     * Checks if TotalSupplyQuantity is set
     *
     * @return bool true if TotalSupplyQuantity  is set
     */
    public function isSetTotalSupplyQuantity()
    {
        return !is_null($this->_fields['TotalSupplyQuantity']['FieldValue']);
    }

    /**
     * Gets the value of the InStockSupplyQuantity property.
     *
     * @return int InStockSupplyQuantity
     */
    public function getInStockSupplyQuantity()
    {
        return $this->_fields['InStockSupplyQuantity']['FieldValue'];
    }

    /**
     * Sets the value of the InStockSupplyQuantity property.
     *
     * @param int InStockSupplyQuantity
     * @return this instance
     */
    public function setInStockSupplyQuantity($value)
    {
        $this->_fields['InStockSupplyQuantity']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the InStockSupplyQuantity and returns this instance
     *
     * @param int $value InStockSupplyQuantity
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withInStockSupplyQuantity($value)
    {
        $this->setInStockSupplyQuantity($value);
        return $this;
    }


    /**
     * Checks if InStockSupplyQuantity is set
     *
     * @return bool true if InStockSupplyQuantity  is set
     */
    public function isSetInStockSupplyQuantity()
    {
        return !is_null($this->_fields['InStockSupplyQuantity']['FieldValue']);
    }

    /**
     * Gets the value of the EarliestAvailability.
     *
     * @return Timepoint EarliestAvailability
     */
    public function getEarliestAvailability()
    {
        return $this->_fields['EarliestAvailability']['FieldValue'];
    }

    /**
     * Sets the value of the EarliestAvailability.
     *
     * @param Timepoint EarliestAvailability
     * @return void
     */
    public function setEarliestAvailability($value)
    {
        $this->_fields['EarliestAvailability']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the EarliestAvailability  and returns this instance
     *
     * @param Timepoint $value EarliestAvailability
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withEarliestAvailability($value)
    {
        $this->setEarliestAvailability($value);
        return $this;
    }


    /**
     * Checks if EarliestAvailability  is set
     *
     * @return bool true if EarliestAvailability property is set
     */
    public function isSetEarliestAvailability()
    {
        return !is_null($this->_fields['EarliestAvailability']['FieldValue']);

    }

    /**
     * Gets the value of the SupplyDetail.
     *
     * @return InventorySupplyDetailList SupplyDetail
     */
    public function getSupplyDetail()
    {
        return $this->_fields['SupplyDetail']['FieldValue'];
    }

    /**
     * Sets the value of the SupplyDetail.
     *
     * @param InventorySupplyDetailList SupplyDetail
     * @return void
     */
    public function setSupplyDetail($value)
    {
        $this->_fields['SupplyDetail']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the SupplyDetail  and returns this instance
     *
     * @param InventorySupplyDetailList $value SupplyDetail
     * @return FBAInventoryServiceMWS_Model_InventorySupply instance
     */
    public function withSupplyDetail($value)
    {
        $this->setSupplyDetail($value);
        return $this;
    }


    /**
     * Checks if SupplyDetail  is set
     *
     * @return bool true if SupplyDetail property is set
     */
    public function isSetSupplyDetail()
    {
        return !is_null($this->_fields['SupplyDetail']['FieldValue']);

    }




}
class FBAInventoryServiceMWS_Model_InventorySupplyDetail extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_InventorySupplyDetail
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Quantity: int</li>
     * <li>SupplyType: string</li>
     * <li>EarliestAvailableToPick: FBAInventoryServiceMWS_Model_Timepoint</li>
     * <li>LatestAvailableToPick: FBAInventoryServiceMWS_Model_Timepoint</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Quantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'SupplyType' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EarliestAvailableToPick' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_Timepoint'),
        'LatestAvailableToPick' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_Timepoint'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Quantity property.
     *
     * @return int Quantity
     */
    public function getQuantity()
    {
        return $this->_fields['Quantity']['FieldValue'];
    }

    /**
     * Sets the value of the Quantity property.
     *
     * @param int Quantity
     * @return this instance
     */
    public function setQuantity($value)
    {
        $this->_fields['Quantity']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Quantity and returns this instance
     *
     * @param int $value Quantity
     * @return FBAInventoryServiceMWS_Model_InventorySupplyDetail instance
     */
    public function withQuantity($value)
    {
        $this->setQuantity($value);
        return $this;
    }


    /**
     * Checks if Quantity is set
     *
     * @return bool true if Quantity  is set
     */
    public function isSetQuantity()
    {
        return !is_null($this->_fields['Quantity']['FieldValue']);
    }

    /**
     * Gets the value of the SupplyType property.
     *
     * @return string SupplyType
     */
    public function getSupplyType()
    {
        return $this->_fields['SupplyType']['FieldValue'];
    }

    /**
     * Sets the value of the SupplyType property.
     *
     * @param string SupplyType
     * @return this instance
     */
    public function setSupplyType($value)
    {
        $this->_fields['SupplyType']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SupplyType and returns this instance
     *
     * @param string $value SupplyType
     * @return FBAInventoryServiceMWS_Model_InventorySupplyDetail instance
     */
    public function withSupplyType($value)
    {
        $this->setSupplyType($value);
        return $this;
    }


    /**
     * Checks if SupplyType is set
     *
     * @return bool true if SupplyType  is set
     */
    public function isSetSupplyType()
    {
        return !is_null($this->_fields['SupplyType']['FieldValue']);
    }

    /**
     * Gets the value of the EarliestAvailableToPick.
     *
     * @return Timepoint EarliestAvailableToPick
     */
    public function getEarliestAvailableToPick()
    {
        return $this->_fields['EarliestAvailableToPick']['FieldValue'];
    }

    /**
     * Sets the value of the EarliestAvailableToPick.
     *
     * @param Timepoint EarliestAvailableToPick
     * @return void
     */
    public function setEarliestAvailableToPick($value)
    {
        $this->_fields['EarliestAvailableToPick']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the EarliestAvailableToPick  and returns this instance
     *
     * @param Timepoint $value EarliestAvailableToPick
     * @return FBAInventoryServiceMWS_Model_InventorySupplyDetail instance
     */
    public function withEarliestAvailableToPick($value)
    {
        $this->setEarliestAvailableToPick($value);
        return $this;
    }


    /**
     * Checks if EarliestAvailableToPick  is set
     *
     * @return bool true if EarliestAvailableToPick property is set
     */
    public function isSetEarliestAvailableToPick()
    {
        return !is_null($this->_fields['EarliestAvailableToPick']['FieldValue']);

    }

    /**
     * Gets the value of the LatestAvailableToPick.
     *
     * @return Timepoint LatestAvailableToPick
     */
    public function getLatestAvailableToPick()
    {
        return $this->_fields['LatestAvailableToPick']['FieldValue'];
    }

    /**
     * Sets the value of the LatestAvailableToPick.
     *
     * @param Timepoint LatestAvailableToPick
     * @return void
     */
    public function setLatestAvailableToPick($value)
    {
        $this->_fields['LatestAvailableToPick']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the LatestAvailableToPick  and returns this instance
     *
     * @param Timepoint $value LatestAvailableToPick
     * @return FBAInventoryServiceMWS_Model_InventorySupplyDetail instance
     */
    public function withLatestAvailableToPick($value)
    {
        $this->setLatestAvailableToPick($value);
        return $this;
    }


    /**
     * Checks if LatestAvailableToPick  is set
     *
     * @return bool true if LatestAvailableToPick property is set
     */
    public function isSetLatestAvailableToPick()
    {
        return !is_null($this->_fields['LatestAvailableToPick']['FieldValue']);

    }




}
class FBAInventoryServiceMWS_Model_InventorySupplyDetailList extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_InventorySupplyDetailList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAInventoryServiceMWS_Model_InventorySupplyDetail</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAInventoryServiceMWS_Model_InventorySupplyDetail')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of InventorySupplyDetail member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed InventorySupplyDetail or an array of InventorySupplyDetail member
     * @return this instance
     */
    public function setmember($member)
    {
        if (!$this->_isNumericArray($member)) {
            $member =  array ($member);
        }
        $this->_fields['member']['FieldValue'] = $member;
        return $this;
    }


    /**
     * Sets single or multiple values of member list via variable number of arguments.
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withmember($member1, $member2)</code>
     *
     * @param InventorySupplyDetail  $inventorySupplyDetailArgs one or more member
     * @return FBAInventoryServiceMWS_Model_InventorySupplyDetailList  instance
     */
    public function withmember($inventorySupplyDetailArgs)
    {
        foreach (func_get_args() as $member) {
            $this->_fields['member']['FieldValue'][] = $member;
        }
        return $this;
    }



    /**
     * Checks if member list is non-empty
     *
     * @return bool true if member list is non-empty
     */
    public function isSetmember()
    {
        return count ($this->_fields['member']['FieldValue']) > 0;
    }




}
class FBAInventoryServiceMWS_Model_InventorySupplyList extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_InventorySupplyList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAInventoryServiceMWS_Model_InventorySupply</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAInventoryServiceMWS_Model_InventorySupply')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of InventorySupply member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed InventorySupply or an array of InventorySupply member
     * @return this instance
     */
    public function setmember($member)
    {
        if (!$this->_isNumericArray($member)) {
            $member =  array ($member);
        }
        $this->_fields['member']['FieldValue'] = $member;
        return $this;
    }


    /**
     * Sets single or multiple values of member list via variable number of arguments.
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withmember($member1, $member2)</code>
     *
     * @param InventorySupply  $inventorySupplyArgs one or more member
     * @return FBAInventoryServiceMWS_Model_InventorySupplyList  instance
     */
    public function withmember($inventorySupplyArgs)
    {
        foreach (func_get_args() as $member) {
            $this->_fields['member']['FieldValue'][] = $member;
        }
        return $this;
    }



    /**
     * Checks if member list is non-empty
     *
     * @return bool true if member list is non-empty
     */
    public function isSetmember()
    {
        return count ($this->_fields['member']['FieldValue']) > 0;
    }




}
class FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     * <li>NextToken: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SellerId property.
     *
     * @return string SellerId
     */
    public function getSellerId()
    {
        return $this->_fields['SellerId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerId property.
     *
     * @param string SellerId
     * @return this instance
     */
    public function setSellerId($value)
    {
        $this->_fields['SellerId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerId and returns this instance
     *
     * @param string $value SellerId
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest instance
     */
    public function withSellerId($value)
    {
        $this->setSellerId($value);
        return $this;
    }


    /**
     * Checks if SellerId is set
     *
     * @return bool true if SellerId  is set
     */
    public function isSetSellerId()
    {
        return !is_null($this->_fields['SellerId']['FieldValue']);
    }

    /**
     * Gets the value of the Marketplace property.
     *
     * @return string Marketplace
     */
    public function getMarketplace()
    {
        return $this->_fields['Marketplace']['FieldValue'];
    }

    /**
     * Sets the value of the Marketplace property.
     *
     * @param string Marketplace
     * @return this instance
     */
    public function setMarketplace($value)
    {
        $this->_fields['Marketplace']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Marketplace and returns this instance
     *
     * @param string $value Marketplace
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest instance
     */
    public function withMarketplace($value)
    {
        $this->setMarketplace($value);
        return $this;
    }


    /**
     * Checks if Marketplace is set
     *
     * @return bool true if Marketplace  is set
     */
    public function isSetMarketplace()
    {
        return !is_null($this->_fields['Marketplace']['FieldValue']);
    }

    /**
     * Gets the value of the NextToken property.
     *
     * @return string NextToken
     */
    public function getNextToken()
    {
        return $this->_fields['NextToken']['FieldValue'];
    }

    /**
     * Sets the value of the NextToken property.
     *
     * @param string NextToken
     * @return this instance
     */
    public function setNextToken($value)
    {
        $this->_fields['NextToken']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the NextToken and returns this instance
     *
     * @param string $value NextToken
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenRequest instance
     */
    public function withNextToken($value)
    {
        $this->setNextToken($value);
        return $this;
    }


    /**
     * Checks if NextToken is set
     *
     * @return bool true if NextToken  is set
     */
    public function isSetNextToken()
    {
        return !is_null($this->_fields['NextToken']['FieldValue']);
    }




}
class FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>ListInventorySupplyByNextTokenResult: FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResult</li>
     * <li>ResponseMetadata: FBAInventoryServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ListInventorySupplyByNextTokenResult' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/');
        $response = $xpath->query('//a:ListInventorySupplyByNextTokenResponse');
        if ($response->length == 1) {
            return new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse from provided XML.
                                  Make sure that ListInventorySupplyByNextTokenResponse is a root element");
        }

    }

    /**
     * Gets the value of the ListInventorySupplyByNextTokenResult.
     *
     * @return ListInventorySupplyByNextTokenResult ListInventorySupplyByNextTokenResult
     */
    public function getListInventorySupplyByNextTokenResult()
    {
        return $this->_fields['ListInventorySupplyByNextTokenResult']['FieldValue'];
    }

    /**
     * Sets the value of the ListInventorySupplyByNextTokenResult.
     *
     * @param ListInventorySupplyByNextTokenResult ListInventorySupplyByNextTokenResult
     * @return void
     */
    public function setListInventorySupplyByNextTokenResult($value)
    {
        $this->_fields['ListInventorySupplyByNextTokenResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ListInventorySupplyByNextTokenResult  and returns this instance
     *
     * @param ListInventorySupplyByNextTokenResult $value ListInventorySupplyByNextTokenResult
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse instance
     */
    public function withListInventorySupplyByNextTokenResult($value)
    {
        $this->setListInventorySupplyByNextTokenResult($value);
        return $this;
    }


    /**
     * Checks if ListInventorySupplyByNextTokenResult  is set
     *
     * @return bool true if ListInventorySupplyByNextTokenResult property is set
     */
    public function isSetListInventorySupplyByNextTokenResult()
    {
        return !is_null($this->_fields['ListInventorySupplyByNextTokenResult']['FieldValue']);

    }

    /**
     * Gets the value of the ResponseMetadata.
     *
     * @return ResponseMetadata ResponseMetadata
     */
    public function getResponseMetadata()
    {
        return $this->_fields['ResponseMetadata']['FieldValue'];
    }

    /**
     * Sets the value of the ResponseMetadata.
     *
     * @param ResponseMetadata ResponseMetadata
     * @return void
     */
    public function setResponseMetadata($value)
    {
        $this->_fields['ResponseMetadata']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ResponseMetadata  and returns this instance
     *
     * @param ResponseMetadata $value ResponseMetadata
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResponse instance
     */
    public function withResponseMetadata($value)
    {
        $this->setResponseMetadata($value);
        return $this;
    }


    /**
     * Checks if ResponseMetadata  is set
     *
     * @return bool true if ResponseMetadata property is set
     */
    public function isSetResponseMetadata()
    {
        return !is_null($this->_fields['ResponseMetadata']['FieldValue']);

    }



    /**
     * XML Representation for this object
     *
     * @return string XML for this object
     */
    public function toXML()
    {
        $xml = "";
        $xml .= "<ListInventorySupplyByNextTokenResponse xmlns=\"http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ListInventorySupplyByNextTokenResponse>";
        return $xml;
    }

}
class FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResult extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>InventorySupplyList: FBAInventoryServiceMWS_Model_InventorySupplyList</li>
     * <li>NextToken: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'InventorySupplyList' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_InventorySupplyList'),
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the InventorySupplyList.
     *
     * @return InventorySupplyList InventorySupplyList
     */
    public function getInventorySupplyList()
    {
        return $this->_fields['InventorySupplyList']['FieldValue'];
    }

    /**
     * Sets the value of the InventorySupplyList.
     *
     * @param InventorySupplyList InventorySupplyList
     * @return void
     */
    public function setInventorySupplyList($value)
    {
        $this->_fields['InventorySupplyList']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the InventorySupplyList  and returns this instance
     *
     * @param InventorySupplyList $value InventorySupplyList
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResult instance
     */
    public function withInventorySupplyList($value)
    {
        $this->setInventorySupplyList($value);
        return $this;
    }


    /**
     * Checks if InventorySupplyList  is set
     *
     * @return bool true if InventorySupplyList property is set
     */
    public function isSetInventorySupplyList()
    {
        return !is_null($this->_fields['InventorySupplyList']['FieldValue']);

    }

    /**
     * Gets the value of the NextToken property.
     *
     * @return string NextToken
     */
    public function getNextToken()
    {
        return $this->_fields['NextToken']['FieldValue'];
    }

    /**
     * Sets the value of the NextToken property.
     *
     * @param string NextToken
     * @return this instance
     */
    public function setNextToken($value)
    {
        $this->_fields['NextToken']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the NextToken and returns this instance
     *
     * @param string $value NextToken
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyByNextTokenResult instance
     */
    public function withNextToken($value)
    {
        $this->setNextToken($value);
        return $this;
    }


    /**
     * Checks if NextToken is set
     *
     * @return bool true if NextToken  is set
     */
    public function isSetNextToken()
    {
        return !is_null($this->_fields['NextToken']['FieldValue']);
    }




}
class FBAInventoryServiceMWS_Model_ListInventorySupplyRequest extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ListInventorySupplyRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     * <li>SellerSkus: FBAInventoryServiceMWS_Model_SellerSkuList</li>
     * <li>QueryStartDateTime: string</li>
     * <li>ResponseGroup: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerSkus' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_SellerSkuList'),
        'QueryStartDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ResponseGroup' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SellerId property.
     *
     * @return string SellerId
     */
    public function getSellerId()
    {
        return $this->_fields['SellerId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerId property.
     *
     * @param string SellerId
     * @return this instance
     */
    public function setSellerId($value)
    {
        $this->_fields['SellerId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerId and returns this instance
     *
     * @param string $value SellerId
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyRequest instance
     */
    public function withSellerId($value)
    {
        $this->setSellerId($value);
        return $this;
    }


    /**
     * Checks if SellerId is set
     *
     * @return bool true if SellerId  is set
     */
    public function isSetSellerId()
    {
        return !is_null($this->_fields['SellerId']['FieldValue']);
    }

    /**
     * Gets the value of the Marketplace property.
     *
     * @return string Marketplace
     */
    public function getMarketplace()
    {
        return $this->_fields['Marketplace']['FieldValue'];
    }

    /**
     * Sets the value of the Marketplace property.
     *
     * @param string Marketplace
     * @return this instance
     */
    public function setMarketplace($value)
    {
        $this->_fields['Marketplace']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Marketplace and returns this instance
     *
     * @param string $value Marketplace
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyRequest instance
     */
    public function withMarketplace($value)
    {
        $this->setMarketplace($value);
        return $this;
    }


    /**
     * Checks if Marketplace is set
     *
     * @return bool true if Marketplace  is set
     */
    public function isSetMarketplace()
    {
        return !is_null($this->_fields['Marketplace']['FieldValue']);
    }

    /**
     * Gets the value of the SellerSkus.
     *
     * @return SellerSkuList SellerSkus
     */
    public function getSellerSkus()
    {
        return $this->_fields['SellerSkus']['FieldValue'];
    }

    /**
     * Sets the value of the SellerSkus.
     *
     * @param SellerSkuList SellerSkus
     * @return void
     */
    public function setSellerSkus($value)
    {
        $this->_fields['SellerSkus']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the SellerSkus  and returns this instance
     *
     * @param SellerSkuList $value SellerSkus
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyRequest instance
     */
    public function withSellerSkus($value)
    {
        $this->setSellerSkus($value);
        return $this;
    }


    /**
     * Checks if SellerSkus  is set
     *
     * @return bool true if SellerSkus property is set
     */
    public function isSetSellerSkus()
    {
        return !is_null($this->_fields['SellerSkus']['FieldValue']);

    }

    /**
     * Gets the value of the QueryStartDateTime property.
     *
     * @return string QueryStartDateTime
     */
    public function getQueryStartDateTime()
    {
        return $this->_fields['QueryStartDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the QueryStartDateTime property.
     *
     * @param string QueryStartDateTime
     * @return this instance
     */
    public function setQueryStartDateTime($value)
    {
        $this->_fields['QueryStartDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the QueryStartDateTime and returns this instance
     *
     * @param string $value QueryStartDateTime
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyRequest instance
     */
    public function withQueryStartDateTime($value)
    {
        $this->setQueryStartDateTime($value);
        return $this;
    }


    /**
     * Checks if QueryStartDateTime is set
     *
     * @return bool true if QueryStartDateTime  is set
     */
    public function isSetQueryStartDateTime()
    {
        return !is_null($this->_fields['QueryStartDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the ResponseGroup property.
     *
     * @return string ResponseGroup
     */
    public function getResponseGroup()
    {
        return $this->_fields['ResponseGroup']['FieldValue'];
    }

    /**
     * Sets the value of the ResponseGroup property.
     *
     * @param string ResponseGroup
     * @return this instance
     */
    public function setResponseGroup($value)
    {
        $this->_fields['ResponseGroup']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ResponseGroup and returns this instance
     *
     * @param string $value ResponseGroup
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyRequest instance
     */
    public function withResponseGroup($value)
    {
        $this->setResponseGroup($value);
        return $this;
    }


    /**
     * Checks if ResponseGroup is set
     *
     * @return bool true if ResponseGroup  is set
     */
    public function isSetResponseGroup()
    {
        return !is_null($this->_fields['ResponseGroup']['FieldValue']);
    }




}
class FBAInventoryServiceMWS_Model_ListInventorySupplyResponse extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ListInventorySupplyResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>ListInventorySupplyResult: FBAInventoryServiceMWS_Model_ListInventorySupplyResult</li>
     * <li>ResponseMetadata: FBAInventoryServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ListInventorySupplyResult' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_ListInventorySupplyResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAInventoryServiceMWS_Model_ListInventorySupplyResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/');
        $response = $xpath->query('//a:ListInventorySupplyResponse');
        if ($response->length == 1) {
            return new FBAInventoryServiceMWS_Model_ListInventorySupplyResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAInventoryServiceMWS_Model_ListInventorySupplyResponse from provided XML.
                                  Make sure that ListInventorySupplyResponse is a root element");
        }

    }

    /**
     * Gets the value of the ListInventorySupplyResult.
     *
     * @return ListInventorySupplyResult ListInventorySupplyResult
     */
    public function getListInventorySupplyResult()
    {
        return $this->_fields['ListInventorySupplyResult']['FieldValue'];
    }

    /**
     * Sets the value of the ListInventorySupplyResult.
     *
     * @param ListInventorySupplyResult ListInventorySupplyResult
     * @return void
     */
    public function setListInventorySupplyResult($value)
    {
        $this->_fields['ListInventorySupplyResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ListInventorySupplyResult  and returns this instance
     *
     * @param ListInventorySupplyResult $value ListInventorySupplyResult
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyResponse instance
     */
    public function withListInventorySupplyResult($value)
    {
        $this->setListInventorySupplyResult($value);
        return $this;
    }


    /**
     * Checks if ListInventorySupplyResult  is set
     *
     * @return bool true if ListInventorySupplyResult property is set
     */
    public function isSetListInventorySupplyResult()
    {
        return !is_null($this->_fields['ListInventorySupplyResult']['FieldValue']);

    }

    /**
     * Gets the value of the ResponseMetadata.
     *
     * @return ResponseMetadata ResponseMetadata
     */
    public function getResponseMetadata()
    {
        return $this->_fields['ResponseMetadata']['FieldValue'];
    }

    /**
     * Sets the value of the ResponseMetadata.
     *
     * @param ResponseMetadata ResponseMetadata
     * @return void
     */
    public function setResponseMetadata($value)
    {
        $this->_fields['ResponseMetadata']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ResponseMetadata  and returns this instance
     *
     * @param ResponseMetadata $value ResponseMetadata
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyResponse instance
     */
    public function withResponseMetadata($value)
    {
        $this->setResponseMetadata($value);
        return $this;
    }


    /**
     * Checks if ResponseMetadata  is set
     *
     * @return bool true if ResponseMetadata property is set
     */
    public function isSetResponseMetadata()
    {
        return !is_null($this->_fields['ResponseMetadata']['FieldValue']);

    }



    /**
     * XML Representation for this object
     *
     * @return string XML for this object
     */
    public function toXML()
    {
        $xml = "";
        $xml .= "<ListInventorySupplyResponse xmlns=\"http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ListInventorySupplyResponse>";
        return $xml;
    }

}
class FBAInventoryServiceMWS_Model_ListInventorySupplyResult extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ListInventorySupplyResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>InventorySupplyList: FBAInventoryServiceMWS_Model_InventorySupplyList</li>
     * <li>NextToken: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'InventorySupplyList' => array('FieldValue' => null, 'FieldType' => 'FBAInventoryServiceMWS_Model_InventorySupplyList'),
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the InventorySupplyList.
     *
     * @return InventorySupplyList InventorySupplyList
     */
    public function getInventorySupplyList()
    {
        return $this->_fields['InventorySupplyList']['FieldValue'];
    }

    /**
     * Sets the value of the InventorySupplyList.
     *
     * @param InventorySupplyList InventorySupplyList
     * @return void
     */
    public function setInventorySupplyList($value)
    {
        $this->_fields['InventorySupplyList']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the InventorySupplyList  and returns this instance
     *
     * @param InventorySupplyList $value InventorySupplyList
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyResult instance
     */
    public function withInventorySupplyList($value)
    {
        $this->setInventorySupplyList($value);
        return $this;
    }


    /**
     * Checks if InventorySupplyList  is set
     *
     * @return bool true if InventorySupplyList property is set
     */
    public function isSetInventorySupplyList()
    {
        return !is_null($this->_fields['InventorySupplyList']['FieldValue']);

    }

    /**
     * Gets the value of the NextToken property.
     *
     * @return string NextToken
     */
    public function getNextToken()
    {
        return $this->_fields['NextToken']['FieldValue'];
    }

    /**
     * Sets the value of the NextToken property.
     *
     * @param string NextToken
     * @return this instance
     */
    public function setNextToken($value)
    {
        $this->_fields['NextToken']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the NextToken and returns this instance
     *
     * @param string $value NextToken
     * @return FBAInventoryServiceMWS_Model_ListInventorySupplyResult instance
     */
    public function withNextToken($value)
    {
        $this->setNextToken($value);
        return $this;
    }


    /**
     * Checks if NextToken is set
     *
     * @return bool true if NextToken  is set
     */
    public function isSetNextToken()
    {
        return !is_null($this->_fields['NextToken']['FieldValue']);
    }




}
class FBAInventoryServiceMWS_Model_ResponseMetadata extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_ResponseMetadata
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>RequestId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'RequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the RequestId property.
     *
     * @return string RequestId
     */
    public function getRequestId()
    {
        return $this->_fields['RequestId']['FieldValue'];
    }

    /**
     * Sets the value of the RequestId property.
     *
     * @param string RequestId
     * @return this instance
     */
    public function setRequestId($value)
    {
        $this->_fields['RequestId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the RequestId and returns this instance
     *
     * @param string $value RequestId
     * @return FBAInventoryServiceMWS_Model_ResponseMetadata instance
     */
    public function withRequestId($value)
    {
        $this->setRequestId($value);
        return $this;
    }


    /**
     * Checks if RequestId is set
     *
     * @return bool true if RequestId  is set
     */
    public function isSetRequestId()
    {
        return !is_null($this->_fields['RequestId']['FieldValue']);
    }




}
class FBAInventoryServiceMWS_Model_SellerSkuList extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_SellerSkuList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('string')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member .
     *
     * @return array of string member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param string or an array of string member
     * @return this instance
     */
    public function setmember($member)
    {
        if (!$this->_isNumericArray($member)) {
            $member =  array ($member);
        }
        $this->_fields['member']['FieldValue'] = $member;
        return $this;
    }


    /**
     * Sets single or multiple values of member list via variable number of arguments.
     * For example, to set the list with two elements, simply pass two values as arguments to this function
     * <code>withmember($member1, $member2)</code>
     *
     * @param string  $stringArgs one or more member
     * @return FBAInventoryServiceMWS_Model_SellerSkuList  instance
     */
    public function withmember($stringArgs)
    {
        foreach (func_get_args() as $member) {
            $this->_fields['member']['FieldValue'][] = $member;
        }
        return $this;
    }


    /**
     * Checks if member list is non-empty
     *
     * @return bool true if member list is non-empty
     */
    public function isSetmember()
    {
        return count ($this->_fields['member']['FieldValue']) > 0;
    }




}
class FBAInventoryServiceMWS_Model_Timepoint extends FBAInventoryServiceMWS_Model
{


    /**
     * Construct new FBAInventoryServiceMWS_Model_Timepoint
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>TimepointType: string</li>
     * <li>DateTime: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'TimepointType' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the TimepointType property.
     *
     * @return string TimepointType
     */
    public function getTimepointType()
    {
        return $this->_fields['TimepointType']['FieldValue'];
    }

    /**
     * Sets the value of the TimepointType property.
     *
     * @param string TimepointType
     * @return this instance
     */
    public function setTimepointType($value)
    {
        $this->_fields['TimepointType']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the TimepointType and returns this instance
     *
     * @param string $value TimepointType
     * @return FBAInventoryServiceMWS_Model_Timepoint instance
     */
    public function withTimepointType($value)
    {
        $this->setTimepointType($value);
        return $this;
    }


    /**
     * Checks if TimepointType is set
     *
     * @return bool true if TimepointType  is set
     */
    public function isSetTimepointType()
    {
        return !is_null($this->_fields['TimepointType']['FieldValue']);
    }

    /**
     * Gets the value of the DateTime property.
     *
     * @return string DateTime
     */
    public function getDateTime()
    {
        return $this->_fields['DateTime']['FieldValue'];
    }

    /**
     * Sets the value of the DateTime property.
     *
     * @param string DateTime
     * @return this instance
     */
    public function setDateTime($value)
    {
        $this->_fields['DateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DateTime and returns this instance
     *
     * @param string $value DateTime
     * @return FBAInventoryServiceMWS_Model_Timepoint instance
     */
    public function withDateTime($value)
    {
        $this->setDateTime($value);
        return $this;
    }


    /**
     * Checks if DateTime is set
     *
     * @return bool true if DateTime  is set
     */
    public function isSetDateTime()
    {
        return !is_null($this->_fields['DateTime']['FieldValue']);
    }




}
/* Models */
