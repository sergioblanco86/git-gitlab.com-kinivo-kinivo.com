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
abstract class FBAOutboundServiceMWS_Model
{
    protected  $_fields = array ();
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
    public function __get($propertyName)
    {
       $getter = "get$propertyName";
       return $this->$getter();
    }
    public function __set($propertyName, $propertyValue)
    {
       $setter = "set$propertyName";
       $this->$setter($propertyValue);
       return $this;
    }
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
    private function _escapeXML($str)
    {
        $from = array( "&", "<", ">", "'", "\"");
        $to = array( "&amp;", "&lt;", "&gt;", "&#039;", "&quot;");
        return str_replace($from, $to, $str);
    }
    private function _fromDOMElement(DOMElement $dom)
    {
        $xpath = new DOMXPath($dom->ownerDocument);
        $xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');

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
    private function _isComplexType ($fieldType)
    {
        return preg_match('/^FBAOutboundServiceMWS_Model_/', $fieldType);
    }
    private function _isAssociativeArray($var) {
        return is_array($var) && array_keys($var) !== range(0, sizeof($var) - 1);
    }
    private function _isDOMElement($var) {
        return $var instanceof DOMElement;
    }
    protected function _isNumericArray($var) {
        return is_array($var) && array_keys($var) === range(0, sizeof($var) - 1);
    }
}
/* Model */
/* Interface */

interface  FBAOutboundServiceMWS_Interface
{
    public function getPackageTrackingDetails($request);
    public function listAllFulfillmentOrders($request);
    public function getFulfillmentPreview($request);
    public function getServiceStatus($request);
    public function listAllFulfillmentOrdersByNextToken($request);
    public function getFulfillmentOrder($request);
    public function cancelFulfillmentOrder($request);
    public function createFulfillmentOrder($request);
}
/* Interface */
/* Exception */
class FBAOutboundServiceMWS_Exception extends Exception
{
    private $_message = null;
    private $_statusCode = -1;
    private $_errorCode = null;
    private $_errorType = null;
    private $_requestId = null;
    private $_xml = null;
    public function __construct(array $errorInfo = array())
    {
        $this->_message = $errorInfo["Message"];
        parent::__construct($this->_message);
        if (array_key_exists("Exception", $errorInfo)) {
            $exception = $errorInfo["Exception"];
            if ($exception instanceof FBAOutboundServiceMWS_Exception) {
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
    public function getErrorCode(){
        return $this->_errorCode;
    }
    public function getErrorType(){
        return $this->_errorType;
    }
    public function getErrorMessage() {
        return $this->_message;
    }
    public function getStatusCode() {
        return $this->_statusCode;
    }
    public function getXML() {
        return $this->_xml;
    }
    public function getRequestId() {
        return $this->_requestId;
    }
}
/* Exception */
/* Client */
class FBAOutboundServiceMWS_Client implements FBAOutboundServiceMWS_Interface
{
    private  $_awsAccessKeyId = null;

    private  $_awsSecretAccessKey = null;

    private  $_config = array ('ServiceURL' => 'http://localhost:8000/',
                               'UserAgent' => 'FBAOutboundServiceMWS PHP5 Library',
                               'SignatureVersion' => 2,
                               'SignatureMethod' => 'HmacSHA256',
                               'ProxyHost' => null,
                               'ProxyPort' => -1,
                               'MaxErrorRetry' => 3
                               );

    private $_serviceVersion = '2010-10-01';

    const REQUEST_TYPE = "POST";

    const MWS_CLIENT_VERSION = "2014-02-20";

    public function __construct(
    $awsAccessKeyId, $awsSecretAccessKey, $config, $applicationName, $applicationVersion, $attributes = null)
    {
        iconv_set_encoding('output_encoding', 'UTF-8');
        iconv_set_encoding('input_encoding', 'UTF-8');
        iconv_set_encoding('internal_encoding', 'UTF-8');

        $this->_awsAccessKeyId = $awsAccessKeyId;
        $this->_awsSecretAccessKey = $awsSecretAccessKey;
        if (!is_null($config)) $this->_config = array_merge($this->_config, $config);
        $this->setUserAgentHeader($applicationName, $applicationVersion, $attributes);
    }
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
  private function collapseWhitespace($s) {
    return preg_replace('/ {2,}|\s/', ' ', $s);
  }
  private function quoteApplicationName($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\//', '\\/', $quotedString);

    return $quotedString;
  }
  private function quoteApplicationVersion($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\(/', '\\(', $quotedString);

    return $quotedString;
  }
  private function quoteAttributeName($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\=/', '\\=', $quotedString);

    return $quotedString;
  }
  private function quoteAttributeValue($s) {
    $quotedString = $this->collapseWhitespace($s);
    $quotedString = preg_replace('/\\\\/', '\\\\\\\\', $quotedString);
    $quotedString = preg_replace('/\\;/', '\\;', $quotedString);
    $quotedString = preg_replace('/\\)/', '\\)', $quotedString);

    return $quotedString;
    }
    public function getPackageTrackingDetails($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsRequest) {
            $request = new FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsRequest($request);
        }
        return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse::fromXML($this->_invoke($this->_convertGetPackageTrackingDetails($request)));
    }
    public function listAllFulfillmentOrders($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest) {
            $request = new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest($request);
        }
        return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse::fromXML($this->_invoke($this->_convertListAllFulfillmentOrders($request)));
    }
    public function getFulfillmentPreview($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest) {
            $request = new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest($request);
        }
        return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse::fromXML($this->_invoke($this->_convertGetFulfillmentPreview($request)));
    }
    public function getServiceStatus($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_GetServiceStatusRequest) {
            $request = new FBAOutboundServiceMWS_Model_GetServiceStatusRequest($request);
        }
        return FBAOutboundServiceMWS_Model_GetServiceStatusResponse::fromXML($this->_invoke($this->_convertGetServiceStatus($request)));
    }
    public function listAllFulfillmentOrdersByNextToken($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenRequest) {
            $request = new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenRequest($request);
        }
        return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse::fromXML($this->_invoke($this->_convertListAllFulfillmentOrdersByNextToken($request)));
    }
    public function getFulfillmentOrder($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest) {
            $request = new FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest($request);
        }
        return FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse::fromXML($this->_invoke($this->_convertGetFulfillmentOrder($request)));
    }
    public function cancelFulfillmentOrder($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest) {
            $request = new FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest($request);
        }
        return FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse::fromXML($this->_invoke($this->_convertCancelFulfillmentOrder($request)));
    }
    public function createFulfillmentOrder($request)
    {
        if (!$request instanceof FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest) {
            $request = new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest($request);
        }
        return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse::fromXML($this->_invoke($this->_convertCreateFulfillmentOrder($request)));
    }
    private function _invoke(array $parameters)
    {
        $actionName = $parameters["Action"];
        $response = array();
        $responseBody = null;
        $statusCode = 200;

        try {

            if (empty($this->_config['ServiceURL'])) {
                throw new MarketplaceWebService_Exception(
                    array('ErrorCode' => 'InvalidServiceUrl',
                          'Message' => "Missing serviceUrl configuration value. You may obtain a list of valid MWS URLs by consulting the MWS Developer's Guide, or reviewing the sample code published along side this library."));
            }

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
                                $errorResponse = FBAOutboundServiceMWS_Model_ErrorResponse::fromXML($response['ResponseBody']);

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
                } catch (Exception $e) {
                    throw new FBAOutboundServiceMWS_Exception(array('Exception' => $e, 'Message' => $e->getMessage()));
                }

            } while ($shouldRetry);

        } catch (FBAOutboundServiceMWS_Exception $se) {
            throw $se;
        } catch (Exception $t) {
            throw new FBAOutboundServiceMWS_Exception(array('Exception' => $t, 'Message' => $t->getMessage()));
        }

        return $response['ResponseBody'];
    }
    private function _reportAnyErrors($responseBody, $status, Exception $e =  null)
    {
        $exProps = array();
        $exProps["StatusCode"] = $status;

        libxml_use_internal_errors(true);
        $xmlBody = simplexml_load_string($responseBody);

        if ($xmlBody !== false) {
            $exProps["XML"] = $responseBody;
            $exProps["ErrorCode"] = $xmlBody->Error->Code;
            $exProps["Message"] = $xmlBody->Error->Message;
            $exProps["ErrorType"] = !empty($xmlBody->Error->Type) ? $xmlBody->Error->Type : "Unknown";
            $exProps["RequestId"] = !empty($xmlBody->RequestID) ? $xmlBody->RequestID : $xmlBody->RequestId;
        } else {
            $exProps["Message"] = "Internal Error";
        }

        return new FBAOutboundServiceMWS_Exception($exProps);
    }
    private function _httpPost(array $parameters)
    {
        $query = $this->_getParametersAsString($parameters);
        $url = parse_url ($this->_config['ServiceURL']);
        $scheme = '';
        if (isset($url['port'])) {
            $port = $url['port'];
        } else {
            $port = null;
        }

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

    throw new FBAOutboundServiceMWS_Exception(
        array(
            'Message' => $errorResponse,
            'ErrorType' => 'HTTP'
        )
    );
}
foreach($response["headers"] as $headers){
$other[] = $headers;
}
$body = $response["body"];
$other = implode(",", $other);
$code = (string)$response["response"]["code"];
$text = (string)$response["response"]["message"];

        return array('Status' => (int)$code, 'ResponseBody' => $body);
        throw new FBAOutboundServiceMWS_Exception(array(
            'Message' => 'Failed to parse valid HTTP response (' . $text . ')',
            'ErrorCode' => (int)$code,
            'ErrorType' => 'HTTP'
        ));
    }
    private function _pauseOnRetry($retries)
    {
        $delay = (int) (pow(4, $retries) * 100000) ;
        usleep($delay);
    }
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
    private function _calculateStringToSignV2(array $parameters) {
        $parsedUrl = parse_url($this->_config['ServiceURL']);
        $endpoint = $parsedUrl['host'];
        if (array_key_exists('port', $parsedUrl)) {
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
    private function _getFormattedTimestamp()
    {
        return gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
    }
    private function _convertGetPackageTrackingDetails($request) {

        $parameters = array();
        $parameters['Action'] = 'GetPackageTrackingDetails';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetPackageNumber()) {
            $parameters['PackageNumber'] =  $request->getPackageNumber();
        }

        return $parameters;
    }
    private function _convertListAllFulfillmentOrders($request) {

        $parameters = array();
        $parameters['Action'] = 'ListAllFulfillmentOrders';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetQueryStartDateTime()) {
            $parameters['QueryStartDateTime'] =  $request->getQueryStartDateTime();
        }
        if ($request->isSetFulfillmentMethod()) {
            $fulfillmentMethodlistAllFulfillmentOrdersRequest = $request->getFulfillmentMethod();
            foreach  ($fulfillmentMethodlistAllFulfillmentOrdersRequest->getmember() as $memberfulfillmentMethodIndex => $memberfulfillmentMethod) {
                $parameters['FulfillmentMethod' . '.' . 'member' . '.'  . ($memberfulfillmentMethodIndex + 1)] =  $memberfulfillmentMethod;
            }
        }

        return $parameters;
    }
    private function _convertGetFulfillmentPreview($request) {

        $parameters = array();
        $parameters['Action'] = 'GetFulfillmentPreview';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetIncludeCODFulfillmentPreview()) {
            $parameters['IncludeCODFulfillmentPreview'] =  $request->getIncludeCODFulfillmentPreview();
        }
        if ($request->isSetAddress()) {
            $addressgetFulfillmentPreviewRequest = $request->getAddress();
            if ($addressgetFulfillmentPreviewRequest->isSetName()) {
                $parameters['Address' . '.' . 'Name'] =  $addressgetFulfillmentPreviewRequest->getName();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetLine1()) {
                $parameters['Address' . '.' . 'Line1'] =  $addressgetFulfillmentPreviewRequest->getLine1();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetLine2()) {
                $parameters['Address' . '.' . 'Line2'] =  $addressgetFulfillmentPreviewRequest->getLine2();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetLine3()) {
                $parameters['Address' . '.' . 'Line3'] =  $addressgetFulfillmentPreviewRequest->getLine3();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetDistrictOrCounty()) {
                $parameters['Address' . '.' . 'DistrictOrCounty'] =  $addressgetFulfillmentPreviewRequest->getDistrictOrCounty();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetCity()) {
                $parameters['Address' . '.' . 'City'] =  $addressgetFulfillmentPreviewRequest->getCity();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetStateOrProvinceCode()) {
                $parameters['Address' . '.' . 'StateOrProvinceCode'] =  $addressgetFulfillmentPreviewRequest->getStateOrProvinceCode();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetCountryCode()) {
                $parameters['Address' . '.' . 'CountryCode'] =  $addressgetFulfillmentPreviewRequest->getCountryCode();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetPostalCode()) {
                $parameters['Address' . '.' . 'PostalCode'] =  $addressgetFulfillmentPreviewRequest->getPostalCode();
            }
            if ($addressgetFulfillmentPreviewRequest->isSetPhoneNumber()) {
                $parameters['Address' . '.' . 'PhoneNumber'] =  $addressgetFulfillmentPreviewRequest->getPhoneNumber();
            }
        }
        if ($request->isSetItems()) {
            $itemsgetFulfillmentPreviewRequest = $request->getItems();
            foreach ($itemsgetFulfillmentPreviewRequest->getmember() as $memberitemsIndex => $memberitems) {
                if ($memberitems->isSetSellerSKU()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'SellerSKU'] =  $memberitems->getSellerSKU();
                }
                if ($memberitems->isSetQuantity()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'Quantity'] =  $memberitems->getQuantity();
                }
                if ($memberitems->isSetSellerFulfillmentOrderItemId()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'SellerFulfillmentOrderItemId'] =  $memberitems->getSellerFulfillmentOrderItemId();
                }

            }
        }
        if ($request->isSetShippingSpeedCategories()) {
            $shippingSpeedCategoriesgetFulfillmentPreviewRequest = $request->getShippingSpeedCategories();
            foreach  ($shippingSpeedCategoriesgetFulfillmentPreviewRequest->getmember() as $membershippingSpeedCategoriesIndex => $membershippingSpeedCategories) {
                $parameters['ShippingSpeedCategories' . '.' . 'member' . '.'  . ($membershippingSpeedCategoriesIndex + 1)] =  $membershippingSpeedCategories;
            }
        }

        return $parameters;
    }
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
    private function _convertListAllFulfillmentOrdersByNextToken($request) {

        $parameters = array();
        $parameters['Action'] = 'ListAllFulfillmentOrdersByNextToken';
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
    private function _convertGetFulfillmentOrder($request) {

        $parameters = array();
        $parameters['Action'] = 'GetFulfillmentOrder';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetSellerFulfillmentOrderId()) {
            $parameters['SellerFulfillmentOrderId'] =  $request->getSellerFulfillmentOrderId();
        }

        return $parameters;
    }
    private function _convertCancelFulfillmentOrder($request) {

        $parameters = array();
        $parameters['Action'] = 'CancelFulfillmentOrder';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetSellerFulfillmentOrderId()) {
            $parameters['SellerFulfillmentOrderId'] =  $request->getSellerFulfillmentOrderId();
        }

        return $parameters;
    }
    private function _convertCreateFulfillmentOrder($request) {

        $parameters = array();
        $parameters['Action'] = 'CreateFulfillmentOrder';
        if ($request->isSetSellerId()) {
            $parameters['SellerId'] =  $request->getSellerId();
        }
        if ($request->isSetMarketplace()) {
            $parameters['Marketplace'] =  $request->getMarketplace();
        }
        if ($request->isSetSellerFulfillmentOrderId()) {
            $parameters['SellerFulfillmentOrderId'] =  $request->getSellerFulfillmentOrderId();
        }
        if ($request->isSetDisplayableOrderId()) {
            $parameters['DisplayableOrderId'] =  $request->getDisplayableOrderId();
        }
        if ($request->isSetDisplayableOrderDateTime()) {
            $parameters['DisplayableOrderDateTime'] =  $request->getDisplayableOrderDateTime();
        }
        if ($request->isSetDisplayableOrderComment()) {
            $parameters['DisplayableOrderComment'] =  $request->getDisplayableOrderComment();
        }
        if ($request->isSetShippingSpeedCategory()) {
            $parameters['ShippingSpeedCategory'] =  $request->getShippingSpeedCategory();
        }
        if ($request->isSetDestinationAddress()) {
            $destinationAddresscreateFulfillmentOrderRequest = $request->getDestinationAddress();
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetName()) {
                $parameters['DestinationAddress' . '.' . 'Name'] =  $destinationAddresscreateFulfillmentOrderRequest->getName();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetLine1()) {
                $parameters['DestinationAddress' . '.' . 'Line1'] =  $destinationAddresscreateFulfillmentOrderRequest->getLine1();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetLine2()) {
                $parameters['DestinationAddress' . '.' . 'Line2'] =  $destinationAddresscreateFulfillmentOrderRequest->getLine2();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetLine3()) {
                $parameters['DestinationAddress' . '.' . 'Line3'] =  $destinationAddresscreateFulfillmentOrderRequest->getLine3();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetDistrictOrCounty()) {
                $parameters['DestinationAddress' . '.' . 'DistrictOrCounty'] =  $destinationAddresscreateFulfillmentOrderRequest->getDistrictOrCounty();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetCity()) {
                $parameters['DestinationAddress' . '.' . 'City'] =  $destinationAddresscreateFulfillmentOrderRequest->getCity();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetStateOrProvinceCode()) {
                $parameters['DestinationAddress' . '.' . 'StateOrProvinceCode'] =  $destinationAddresscreateFulfillmentOrderRequest->getStateOrProvinceCode();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetCountryCode()) {
                $parameters['DestinationAddress' . '.' . 'CountryCode'] =  $destinationAddresscreateFulfillmentOrderRequest->getCountryCode();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetPostalCode()) {
                $parameters['DestinationAddress' . '.' . 'PostalCode'] =  $destinationAddresscreateFulfillmentOrderRequest->getPostalCode();
            }
            if ($destinationAddresscreateFulfillmentOrderRequest->isSetPhoneNumber()) {
                $parameters['DestinationAddress' . '.' . 'PhoneNumber'] =  $destinationAddresscreateFulfillmentOrderRequest->getPhoneNumber();
            }
        }
        if ($request->isSetFulfillmentPolicy()) {
            $parameters['FulfillmentPolicy'] =  $request->getFulfillmentPolicy();
        }
        if ($request->isSetFulfillmentMethod()) {
            $parameters['FulfillmentMethod'] =  $request->getFulfillmentMethod();
        }
        if ($request->isSetShipFromCountryCode()) {
            $parameters['ShipFromCountryCode'] =  $request->getShipFromCountryCode();
        }
        if ($request->isSetNotificationEmailList()) {
            $notificationEmailListcreateFulfillmentOrderRequest = $request->getNotificationEmailList();
            foreach  ($notificationEmailListcreateFulfillmentOrderRequest->getmember() as $membernotificationEmailListIndex => $membernotificationEmailList) {
                $parameters['NotificationEmailList' . '.' . 'member' . '.'  . ($membernotificationEmailListIndex + 1)] =  $membernotificationEmailList;
            }
        }
        if ($request->isSetCODSettings()) {
            $cODSettings = $request->getCODSettings();
            if ($cODSettings->isSetIsCODRequired()) {
                $parameters['CODSettings' . '.' . 'IsCODRequired'] =  $cODSettings->getIsCODRequired();
            }
            if ($cODSettings->isSetCODCharge()) {
                $cODCharge = $cODSettings->getCODCharge();
                if ($cODCharge->isSetCurrencyCode()) {
                    $parameters['CODSettings' . '.' . 'CODCharge' . '.' . 'CurrencyCode'] = $cODCharge->getCurrencyCode();
                }
                if ($cODCharge->isSetValue()) {
                    $parameters['CODSettings' . '.' . 'CODCharge' . '.' . 'Value'] = $cODCharge->getValue();
                }
            }
            if ($cODSettings->isSetCODChargeTax()) {
                $cODChargeTax = $cODSettings->getCODChargeTax();
                if ($cODChargeTax->isSetCurrencyCode()) {
                    $parameters['CODSettings' . '.' . 'CODChargeTax' . '.' . 'CurrencyCode'] = $cODChargeTax->getCurrencyCode();
                }
                if ($cODChargeTax->isSetValue()) {
                    $parameters['CODSettings' . '.' . 'CODChargeTax' . '.' . 'Value'] = $cODChargeTax->getValue();
                }
            }
            if ($cODSettings->isSetShippingCharge()) {
                $shippingCharge = $cODSettings->getShippingCharge();
                if ($shippingCharge->isSetCurrencyCode()) {
                    $parameters['CODSettings' . '.' . 'ShippingCharge' . '.' . 'CurrencyCode'] = $shippingCharge->getCurrencyCode();
                }
                if ($shippingCharge->isSetValue()) {
                    $parameters['CODSettings' . '.' . 'ShippingCharge' . '.' . 'Value'] = $shippingCharge->getValue();
                }
            }
            if ($cODSettings->isSetShippingChargeTax()) {
                $shippingChargeTax = $cODSettings->getShippingChargeTax();
                if ($shippingChargeTax->isSetCurrencyCode()) {
                    $parameters['CODSettings' . '.' . 'ShippingChargeTax' . '.' . 'CurrencyCode'] = $shippingChargeTax->getCurrencyCode();
                }
                if ($shippingChargeTax->isSetValue()) {
                    $parameters['CODSettings' . '.' . 'ShippingChargeTax' . '.' . 'Value'] = $shippingChargeTax->getValue();
                }
            }
        }
        if ($request->isSetItems()) {
            $itemscreateFulfillmentOrderRequest = $request->getItems();
            foreach ($itemscreateFulfillmentOrderRequest->getmember() as $memberitemsIndex => $memberitems) {
                if ($memberitems->isSetSellerSKU()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'SellerSKU'] =  $memberitems->getSellerSKU();
                }
                if ($memberitems->isSetSellerFulfillmentOrderItemId()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'SellerFulfillmentOrderItemId'] =  $memberitems->getSellerFulfillmentOrderItemId();
                }
                if ($memberitems->isSetQuantity()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'Quantity'] =  $memberitems->getQuantity();
                }
                if ($memberitems->isSetGiftMessage()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'GiftMessage'] =  $memberitems->getGiftMessage();
                }
                if ($memberitems->isSetDisplayableComment()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'DisplayableComment'] =  $memberitems->getDisplayableComment();
                }
                if ($memberitems->isSetFulfillmentNetworkSKU()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'FulfillmentNetworkSKU'] =  $memberitems->getFulfillmentNetworkSKU();
                }
                if ($memberitems->isSetOrderItemDisposition()) {
                    $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'OrderItemDisposition'] =  $memberitems->getOrderItemDisposition();
                }
                if ($memberitems->isSetPerUnitDeclaredValue()) {
                    $perUnitDeclaredValuemember = $memberitems->getPerUnitDeclaredValue();
                    if ($perUnitDeclaredValuemember->isSetCurrencyCode()) {
                        $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'PerUnitDeclaredValue' . '.' . 'CurrencyCode'] =  $perUnitDeclaredValuemember->getCurrencyCode();
                    }
                    if ($perUnitDeclaredValuemember->isSetValue()) {
                        $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'PerUnitDeclaredValue' . '.' . 'Value'] =  $perUnitDeclaredValuemember->getValue();
                    }
                }
                if ($memberitems->isSetPerUnitPrice()) {
                    $perUnitPricemember = $memberitems->getPerUnitPrice();
                    if ($perUnitPricemember->isSetCurrencyCode()) {
                        $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'PerUnitPrice' . '.' . 'CurrencyCode'] =  $perUnitPricemember->getCurrencyCode();
                    }
                    if ($perUnitPricemember->isSetValue()) {
                        $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'PerUnitPrice' . '.' . 'Value'] =  $perUnitPricemember->getValue();
                    }
                }
                if ($memberitems->isSetPerUnitTax()) {
                    $perUnitTaxmember = $memberitems->getPerUnitTax();
                    if ($perUnitTaxmember->isSetCurrencyCode()) {
                        $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'PerUnitTax' . '.' . 'CurrencyCode'] =  $perUnitTaxmember->getCurrencyCode();
                    }
                    if ($perUnitTaxmember->isSetValue()) {
                        $parameters['Items' . '.' . 'member' . '.'  . ($memberitemsIndex + 1) . '.' . 'PerUnitTax' . '.' . 'Value'] =  $perUnitTaxmember->getValue();
                    }
                }

            }
        }
        return $parameters;
    }
}
/* Client */
/* Models */
class FBAOutboundServiceMWS_Model_Address extends FBAOutboundServiceMWS_Model
{
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Name' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Line1' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Line2' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Line3' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DistrictOrCounty' => array('FieldValue' => null, 'FieldType' => 'string'),
        'City' => array('FieldValue' => null, 'FieldType' => 'string'),
        'StateOrProvinceCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CountryCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        'PostalCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        'PhoneNumber' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }
    public function getName()
    {
        return $this->_fields['Name']['FieldValue'];
    }
    public function setName($value)
    {
        $this->_fields['Name']['FieldValue'] = $value;
        return $this;
    }
    public function withName($value)
    {
        $this->setName($value);
        return $this;
    }
    public function isSetName()
    {
        return !is_null($this->_fields['Name']['FieldValue']);
    }
    public function getLine1()
    {
        return $this->_fields['Line1']['FieldValue'];
    }
    public function setLine1($value)
    {
        $this->_fields['Line1']['FieldValue'] = $value;
        return $this;
    }
    public function withLine1($value)
    {
        $this->setLine1($value);
        return $this;
    }
    public function isSetLine1()
    {
        return !is_null($this->_fields['Line1']['FieldValue']);
    }
    public function getLine2()
    {
        return $this->_fields['Line2']['FieldValue'];
    }
    public function setLine2($value)
    {
        $this->_fields['Line2']['FieldValue'] = $value;
        return $this;
    }
    public function withLine2($value)
    {
        $this->setLine2($value);
        return $this;
    }
    public function isSetLine2()
    {
        return !is_null($this->_fields['Line2']['FieldValue']);
    }
    public function getLine3()
    {
        return $this->_fields['Line3']['FieldValue'];
    }
    public function setLine3($value)
    {
        $this->_fields['Line3']['FieldValue'] = $value;
        return $this;
    }
    public function withLine3($value)
    {
        $this->setLine3($value);
        return $this;
    }
    public function isSetLine3()
    {
        return !is_null($this->_fields['Line3']['FieldValue']);
    }
    public function getDistrictOrCounty()
    {
        return $this->_fields['DistrictOrCounty']['FieldValue'];
    }
    public function setDistrictOrCounty($value)
    {
        $this->_fields['DistrictOrCounty']['FieldValue'] = $value;
        return $this;
    }
    public function withDistrictOrCounty($value)
    {
        $this->setDistrictOrCounty($value);
        return $this;
    }
    public function isSetDistrictOrCounty()
    {
        return !is_null($this->_fields['DistrictOrCounty']['FieldValue']);
    }
    public function getCity()
    {
        return $this->_fields['City']['FieldValue'];
    }
    public function setCity($value)
    {
        $this->_fields['City']['FieldValue'] = $value;
        return $this;
    }
    public function withCity($value)
    {
        $this->setCity($value);
        return $this;
    }
    public function isSetCity()
    {
        return !is_null($this->_fields['City']['FieldValue']);
    }
    public function getStateOrProvinceCode()
    {
        return $this->_fields['StateOrProvinceCode']['FieldValue'];
    }
    public function setStateOrProvinceCode($value)
    {
        $this->_fields['StateOrProvinceCode']['FieldValue'] = $value;
        return $this;
    }
    public function withStateOrProvinceCode($value)
    {
        $this->setStateOrProvinceCode($value);
        return $this;
    }
    public function isSetStateOrProvinceCode()
    {
        return !is_null($this->_fields['StateOrProvinceCode']['FieldValue']);
    }
    public function getCountryCode()
    {
        return $this->_fields['CountryCode']['FieldValue'];
    }
    public function setCountryCode($value)
    {
        $this->_fields['CountryCode']['FieldValue'] = $value;
        return $this;
    }
    public function withCountryCode($value)
    {
        $this->setCountryCode($value);
        return $this;
    }
    public function isSetCountryCode()
    {
        return !is_null($this->_fields['CountryCode']['FieldValue']);
    }
    public function getPostalCode()
    {
        return $this->_fields['PostalCode']['FieldValue'];
    }
    public function setPostalCode($value)
    {
        $this->_fields['PostalCode']['FieldValue'] = $value;
        return $this;
    }
    public function withPostalCode($value)
    {
        $this->setPostalCode($value);
        return $this;
    }
    public function isSetPostalCode()
    {
        return !is_null($this->_fields['PostalCode']['FieldValue']);
    }
    public function getPhoneNumber()
    {
        return $this->_fields['PhoneNumber']['FieldValue'];
    }
    public function setPhoneNumber($value)
    {
        $this->_fields['PhoneNumber']['FieldValue'] = $value;
        return $this;
    }
    public function withPhoneNumber($value)
    {
        $this->setPhoneNumber($value);
        return $this;
    }
    public function isSetPhoneNumber()
    {
        return !is_null($this->_fields['PhoneNumber']['FieldValue']);
    }

}
class FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     * <li>SellerFulfillmentOrderId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerFulfillmentOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
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
     * @return FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest instance
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
     * @return FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest instance
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
     * Gets the value of the SellerFulfillmentOrderId property.
     *
     * @return string SellerFulfillmentOrderId
     */
    public function getSellerFulfillmentOrderId()
    {
        return $this->_fields['SellerFulfillmentOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId property.
     *
     * @param string SellerFulfillmentOrderId
     * @return this instance
     */
    public function setSellerFulfillmentOrderId($value)
    {
        $this->_fields['SellerFulfillmentOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderId
     * @return FBAOutboundServiceMWS_Model_CancelFulfillmentOrderRequest instance
     */
    public function withSellerFulfillmentOrderId($value)
    {
        $this->setSellerFulfillmentOrderId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderId is set
     *
     * @return bool true if SellerFulfillmentOrderId  is set
     */
    public function isSetSellerFulfillmentOrderId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderId']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:CancelFulfillmentOrderResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse from provided XML.
                                  Make sure that CancelFulfillmentOrderResponse is a root element");
        }

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
     * @return FBAOutboundServiceMWS_Model_CancelFulfillmentOrderResponse instance
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
        $xml .= "<CancelFulfillmentOrderResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</CancelFulfillmentOrderResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_CODSettings extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_CODSettings
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>IsCODRequired: bool</li>
     * <li>CODCharge: FBAOutboundServiceMWS_Model_Currency</li>
     * <li>CODChargeTax: FBAOutboundServiceMWS_Model_Currency</li>
     * <li>ShippingCharge: FBAOutboundServiceMWS_Model_Currency</li>
     * <li>ShippingChargeTax: FBAOutboundServiceMWS_Model_Currency</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'IsCODRequired' => array('FieldValue' => null, 'FieldType' => 'bool'),
        'CODCharge' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        'CODChargeTax' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        'ShippingCharge' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        'ShippingChargeTax' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        );
        parent::__construct($data);
    }

    /**
     * Gets the value of the IsCODRequired property.
     *
     * @return bool Name
     */
    public function getIsCODRequired()
    {
        return $this->_fields['IsCODRequired']['FieldValue'];
    }

    /**
     * Sets the value of the IsCODRequired property.
     *
     * @param bool IsCODRequired
     * @return this instance
     */
    public function setIsCODRequired($value)
    {
        $this->_fields['IsCODRequired']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the IsCODRequired and returns this instance
     *
     * @param bool $value IsCODRequired
     * @return FBAOutboundServiceMWS_Model_IsCODRequired instance
     */
    public function withIsCODRequired($value)
    {
        $this->setIsCODRequired($value);
        return $this;
    }


    /**
     * Checks if IsCODRequired is set
     *
     * @return bool true if IsCODRequired  is set
     */
    public function isSetIsCODRequired()
    {
        return !is_null($this->_fields['IsCODRequired']['FieldValue']);
    }

    /**
     * Gets the value of the CODCharge property.
     *
     * @return Currency CODCharge
     */
    public function getCODCharge()
    {
        return $this->_fields['CODCharge']['FieldValue'];
    }

    /**
     * Sets the value of the CODCharge property.
     *
     * @param Currency CODCharge
     * @return this instance
     */
    public function setCODCharge($value)
    {
        $this->_fields['CODCharge']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CODCharge and returns this instance
     *
     * @param Currency $value CODCharge
     * @return FBAOutboundServiceMWS_Model_CODCharge instance
     */
    public function withCODCharge($value)
    {
        $this->setCODCharge($value);
        return $this;
    }


    /**
     * Checks if CODCharge is set
     *
     * @return bool true if CODCharge  is set
     */
    public function isSetCODCharge()
    {
        return !is_null($this->_fields['CODCharge']['FieldValue']);
    }

    /**
     * Gets the value of the CODChargeTax property.
     *
     * @return Currency CODChargeTax
     */
    public function getCODChargeTax()
    {
        return $this->_fields['CODChargeTax']['FieldValue'];
    }

    /**
     * Sets the value of the CODChargeTax property.
     *
     * @param Currency CODChargeTax
     * @return this instance
     */
    public function setCODChargeTax($value)
    {
        $this->_fields['CODChargeTax']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CODChargeTax and returns this instance
     *
     * @param Currency $value CODChargeTax
     * @return this instance
     */
    public function withCODChargeTax($value)
    {
        $this->setCODChargeTax($value);
        return $this;
    }

    /**
     * Checks if CODChargeTax is set
     *
     * @return bool true if CODChargeTax  is set
     */
    public function isSetCODChargeTax()
    {
        return !is_null($this->_fields['CODChargeTax']['FieldValue']);
    }

    /**
     * Gets the value of the ShippingCharge property.
     *
     * @return Currency ShippingCharge
     */
    public function getShippingCharge()
    {
        return $this->_fields['ShippingCharge']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingCharge property.
     *
     * @param Currency ShippingCharge
     * @return this instance
     */
    public function setShippingCharge($value)
    {
        $this->_fields['ShippingCharge']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippingCharge and returns this instance
     *
     * @param Currency $value ShippingCharge
     * @return FBAOutboundServiceMWS_Model_Address instance
     */
    public function withShippingCharge($value)
    {
        $this->setShippingCharge($value);
        return $this;
    }


    /**
     * Checks if ShippingCharge is set
     *
     * @return bool true if ShippingCharge  is set
     */
    public function isSetShippingCharge()
    {
        return !is_null($this->_fields['ShippingCharge']['FieldValue']);
    }

    /**
     * Gets the value of the ShippingChargeTax property.
     *
     * @return Currency ShippingChargeTax
     */
    public function getShippingChargeTax()
    {
        return $this->_fields['ShippingChargeTax']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingChargeTax property.
     *
     * @param Currency ShippingChargeTax
     * @return this instanceCODCharge
     */
    public function setShippingChargeTax($value)
    {
        $this->_fields['ShippingChargeTax']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippingChargeTax and returns this instance
     *
     * @param Currency $value ShippingChargeTax
     * @return FBAOutboundServiceMWS_Model_Address instance
     */
    public function withShippingChargeTax($value)
    {
        $this->setShippingChargeTax($value);
        return $this;
    }


    /**
     * Checks if ShippingChargeTax is set
     *
     * @return bool true if ShippingChargeTax  is set
     */
    public function isSetShippingChargeTax()
    {
        return !is_null($this->_fields['ShippingChargeTax']['FieldValue']);
    }

}
class FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerSKU: string</li>
     * <li>SellerFulfillmentOrderItemId: string</li>
     * <li>Quantity: int</li>
     * <li>GiftMessage: string</li>
     * <li>DisplayableComment: string</li>
     * <li>FulfillmentNetworkSKU: string</li>
     * <li>OrderItemDisposition: string</li>
     * <li>PerUnitDeclaredValue: FBAOutboundServiceMWS_Model_Currency</li>
     * <li>PerUnitPrice: FBAOutboundServiceMWS_Model_Currency</li>
     * <li>PerUnitTax: FBAOutboundServiceMWS_Model_Currency</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerFulfillmentOrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Quantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'GiftMessage' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableComment' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentNetworkSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'OrderItemDisposition' => array('FieldValue' => null, 'FieldType' => 'string'),
        'PerUnitDeclaredValue' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        'PerUnitPrice' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        'PerUnitTax' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
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
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
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
     * Gets the value of the SellerFulfillmentOrderItemId property.
     *
     * @return string SellerFulfillmentOrderItemId
     */
    public function getSellerFulfillmentOrderItemId()
    {
        return $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId property.
     *
     * @param string SellerFulfillmentOrderItemId
     * @return this instance
     */
    public function setSellerFulfillmentOrderItemId($value)
    {
        $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderItemId
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withSellerFulfillmentOrderItemId($value)
    {
        $this->setSellerFulfillmentOrderItemId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderItemId is set
     *
     * @return bool true if SellerFulfillmentOrderItemId  is set
     */
    public function isSetSellerFulfillmentOrderItemId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderItemId']['FieldValue']);
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
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
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
     * Gets the value of the GiftMessage property.
     *
     * @return string GiftMessage
     */
    public function getGiftMessage()
    {
        return $this->_fields['GiftMessage']['FieldValue'];
    }

    /**
     * Sets the value of the GiftMessage property.
     *
     * @param string GiftMessage
     * @return this instance
     */
    public function setGiftMessage($value)
    {
        $this->_fields['GiftMessage']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the GiftMessage and returns this instance
     *
     * @param string $value GiftMessage
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withGiftMessage($value)
    {
        $this->setGiftMessage($value);
        return $this;
    }


    /**
     * Checks if GiftMessage is set
     *
     * @return bool true if GiftMessage  is set
     */
    public function isSetGiftMessage()
    {
        return !is_null($this->_fields['GiftMessage']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableComment property.
     *
     * @return string DisplayableComment
     */
    public function getDisplayableComment()
    {
        return $this->_fields['DisplayableComment']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableComment property.
     *
     * @param string DisplayableComment
     * @return this instance
     */
    public function setDisplayableComment($value)
    {
        $this->_fields['DisplayableComment']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableComment and returns this instance
     *
     * @param string $value DisplayableComment
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withDisplayableComment($value)
    {
        $this->setDisplayableComment($value);
        return $this;
    }


    /**
     * Checks if DisplayableComment is set
     *
     * @return bool true if DisplayableComment  is set
     */
    public function isSetDisplayableComment()
    {
        return !is_null($this->_fields['DisplayableComment']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentNetworkSKU property.
     *
     * @return string FulfillmentNetworkSKU
     */
    public function getFulfillmentNetworkSKU()
    {
        return $this->_fields['FulfillmentNetworkSKU']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentNetworkSKU property.
     *
     * @param string FulfillmentNetworkSKU
     * @return this instance
     */
    public function setFulfillmentNetworkSKU($value)
    {
        $this->_fields['FulfillmentNetworkSKU']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentNetworkSKU and returns this instance
     *
     * @param string $value FulfillmentNetworkSKU
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withFulfillmentNetworkSKU($value)
    {
        $this->setFulfillmentNetworkSKU($value);
        return $this;
    }


    /**
     * Checks if FulfillmentNetworkSKU is set
     *
     * @return bool true if FulfillmentNetworkSKU  is set
     */
    public function isSetFulfillmentNetworkSKU()
    {
        return !is_null($this->_fields['FulfillmentNetworkSKU']['FieldValue']);
    }

    /**
     * Gets the value of the OrderItemDisposition property.
     *
     * @return string OrderItemDisposition
     */
    public function getOrderItemDisposition()
    {
        return $this->_fields['OrderItemDisposition']['FieldValue'];
    }

    /**
     * Sets the value of the OrderItemDisposition property.
     *
     * @param string OrderItemDisposition
     * @return this instance
     */
    public function setOrderItemDisposition($value)
    {
        $this->_fields['OrderItemDisposition']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the OrderItemDisposition and returns this instance
     *
     * @param string $value OrderItemDisposition
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withOrderItemDisposition($value)
    {
        $this->setOrderItemDisposition($value);
        return $this;
    }


    /**
     * Checks if OrderItemDisposition is set
     *
     * @return bool true if OrderItemDisposition  is set
     */
    public function isSetOrderItemDisposition()
    {
        return !is_null($this->_fields['OrderItemDisposition']['FieldValue']);
    }

    /**
     * Gets the value of the PerUnitDeclaredValue.
     *
     * @return Currency PerUnitDeclaredValue
     */
    public function getPerUnitDeclaredValue()
    {
        return $this->_fields['PerUnitDeclaredValue']['FieldValue'];
    }

    /**
     * Sets the value of the PerUnitDeclaredValue.
     *
     * @param Currency PerUnitDeclaredValue
     * @return void
     */
    public function setPerUnitDeclaredValue($value)
    {
        $this->_fields['PerUnitDeclaredValue']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PerUnitDeclaredValue  and returns this instance
     *
     * @param Currency $value PerUnitDeclaredValue
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withPerUnitDeclaredValue($value)
    {
        $this->setPerUnitDeclaredValue($value);
        return $this;
    }


    /**
     * Checks if PerUnitDeclaredValue  is set
     *
     * @return bool true if PerUnitDeclaredValue property is set
     */
    public function isSetPerUnitDeclaredValue()
    {
        return !is_null($this->_fields['PerUnitDeclaredValue']['FieldValue']);

    }


     /**
     * Gets the value of the PerUnitPrice.
     *
     * @return Currency PerUnitPrice
     */
    public function getPerUnitPrice()
    {
        return $this->_fields['PerUnitPrice']['FieldValue'];
    }

    /**
     * Sets the value of the PerUnitPrice.
     *
     * @param Currency PerUnitPrice
     * @return void
     */
    public function setPerUnitPrice($value)
    {
        $this->_fields['PerUnitPrice']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PerUnitPrice  and returns this instance
     *
     * @param Currency $value PerUnitPrice
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withPerUnitPrice($value)
    {
        $this->setPerUnitPrice($value);
        return $this;
    }


    /**
     * Checks if PerUnitPrice  is set
     *
     * @return bool true if PerUnitPrice property is set
     */
    public function isSetPerUnitPrice()
    {
        return !is_null($this->_fields['PerUnitPrice']['FieldValue']);

    }

    /**
     * Gets the value of the PerUnitTax.
     *
     * @return Currency PerUnitTax
     */
    public function getPerUnitTax()
    {
        return $this->_fields['PerUnitTax']['FieldValue'];
    }

    /**
     * Sets the value of the PerUnitTax.
     *
     * @param Currency PerUnitTax
     * @return void
     */
    public function setPerUnitTax($value)
    {
        $this->_fields['PerUnitTax']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PerUnitTax  and returns this instance
     *
     * @param Currency $value PerUnitTax
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem instance
     */
    public function withPerUnitTax($value)
    {
        $this->setPerUnitTax($value);
        return $this;
    }


    /**
     * Checks if PerUnitTax  is set
     *
     * @return bool true if PerUnitTax property is set
     */
    public function isSetPerUnitTax()
    {
        return !is_null($this->_fields['PerUnitTax']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of CreateFulfillmentOrderItem member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed CreateFulfillmentOrderItem or an array of CreateFulfillmentOrderItem member
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
     * @param CreateFulfillmentOrderItem  $createFulfillmentOrderItemArgs one or more member
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList  instance
     */
    public function withmember($createFulfillmentOrderItemArgs)
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
class FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest extends FBAOutboundServiceMWS_Model
{

    /**
     * Construct new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     * <li>SellerFulfillmentOrderId: string</li>
     * <li>DisplayableOrderId: string</li>
     * <li>DisplayableOrderDateTime: string</li>
     * <li>DisplayableOrderComment: string</li>
     * <li>ShippingSpeedCategory: string</li>
     * <li>DestinationAddress: FBAOutboundServiceMWS_Model_Address</li>
     * <li>FulfillmentPolicy: string</li>
     * <li>FulfillmentMethod: string</li>
     * <li>ShipFromCountryCode: string</li>
     * <li>NotificationEmailList: FBAOutboundServiceMWS_Model_NotificationEmailList</li>
     * <li>CODSettings: FBAOutboundServiceMWS_Model_CODSettings</li>
     * <li>Items: FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerFulfillmentOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableOrderDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableOrderComment' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShippingSpeedCategory' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DestinationAddress' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Address'),
        'FulfillmentPolicy' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentMethod' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShipFromCountryCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        'NotificationEmailList' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_NotificationEmailList'),
        'CODSettings' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_CODSettings'),
        'Items' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_CreateFulfillmentOrderItemList'),
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
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
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
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
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
     * Gets the value of the SellerFulfillmentOrderId property.
     *
     * @return string SellerFulfillmentOrderId
     */
    public function getSellerFulfillmentOrderId()
    {
        return $this->_fields['SellerFulfillmentOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId property.
     *
     * @param string SellerFulfillmentOrderId
     * @return this instance
     */
    public function setSellerFulfillmentOrderId($value)
    {
        $this->_fields['SellerFulfillmentOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderId
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withSellerFulfillmentOrderId($value)
    {
        $this->setSellerFulfillmentOrderId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderId is set
     *
     * @return bool true if SellerFulfillmentOrderId  is set
     */
    public function isSetSellerFulfillmentOrderId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderId']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableOrderId property.
     *
     * @return string DisplayableOrderId
     */
    public function getDisplayableOrderId()
    {
        return $this->_fields['DisplayableOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableOrderId property.
     *
     * @param string DisplayableOrderId
     * @return this instance
     */
    public function setDisplayableOrderId($value)
    {
        $this->_fields['DisplayableOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableOrderId and returns this instance
     *
     * @param string $value DisplayableOrderId
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withDisplayableOrderId($value)
    {
        $this->setDisplayableOrderId($value);
        return $this;
    }


    /**
     * Checks if DisplayableOrderId is set
     *
     * @return bool true if DisplayableOrderId  is set
     */
    public function isSetDisplayableOrderId()
    {
        return !is_null($this->_fields['DisplayableOrderId']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableOrderDateTime property.
     *
     * @return string DisplayableOrderDateTime
     */
    public function getDisplayableOrderDateTime()
    {
        return $this->_fields['DisplayableOrderDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableOrderDateTime property.
     *
     * @param string DisplayableOrderDateTime
     * @return this instance
     */
    public function setDisplayableOrderDateTime($value)
    {
        $this->_fields['DisplayableOrderDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableOrderDateTime and returns this instance
     *
     * @param string $value DisplayableOrderDateTime
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withDisplayableOrderDateTime($value)
    {
        $this->setDisplayableOrderDateTime($value);
        return $this;
    }


    /**
     * Checks if DisplayableOrderDateTime is set
     *
     * @return bool true if DisplayableOrderDateTime  is set
     */
    public function isSetDisplayableOrderDateTime()
    {
        return !is_null($this->_fields['DisplayableOrderDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableOrderComment property.
     *
     * @return string DisplayableOrderComment
     */
    public function getDisplayableOrderComment()
    {
        return $this->_fields['DisplayableOrderComment']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableOrderComment property.
     *
     * @param string DisplayableOrderComment
     * @return this instance
     */
    public function setDisplayableOrderComment($value)
    {
        $this->_fields['DisplayableOrderComment']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableOrderComment and returns this instance
     *
     * @param string $value DisplayableOrderComment
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withDisplayableOrderComment($value)
    {
        $this->setDisplayableOrderComment($value);
        return $this;
    }


    /**
     * Checks if DisplayableOrderComment is set
     *
     * @return bool true if DisplayableOrderComment  is set
     */
    public function isSetDisplayableOrderComment()
    {
        return !is_null($this->_fields['DisplayableOrderComment']['FieldValue']);
    }

    /**
     * Gets the value of the ShippingSpeedCategory property.
     *
     * @return string ShippingSpeedCategory
     */
    public function getShippingSpeedCategory()
    {
        return $this->_fields['ShippingSpeedCategory']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingSpeedCategory property.
     *
     * @param string ShippingSpeedCategory
     * @return this instance
     */
    public function setShippingSpeedCategory($value)
    {
        $this->_fields['ShippingSpeedCategory']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippingSpeedCategory and returns this instance
     *
     * @param string $value ShippingSpeedCategory
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withShippingSpeedCategory($value)
    {
        $this->setShippingSpeedCategory($value);
        return $this;
    }


    /**
     * Checks if ShippingSpeedCategory is set
     *
     * @return bool true if ShippingSpeedCategory  is set
     */
    public function isSetShippingSpeedCategory()
    {
        return !is_null($this->_fields['ShippingSpeedCategory']['FieldValue']);
    }

    /**
     * Gets the value of the DestinationAddress.
     *
     * @return Address DestinationAddress
     */
    public function getDestinationAddress()
    {
        return $this->_fields['DestinationAddress']['FieldValue'];
    }

    /**
     * Sets the value of the DestinationAddress.
     *
     * @param Address DestinationAddress
     * @return void
     */
    public function setDestinationAddress($value)
    {
        $this->_fields['DestinationAddress']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the DestinationAddress  and returns this instance
     *
     * @param Address $value DestinationAddress
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withDestinationAddress($value)
    {
        $this->setDestinationAddress($value);
        return $this;
    }

    /**
     * Gets the value of the COD settings.
     *
     * @return  CODSettings
     */
    public function getCODSettings()
    {
        return $this->_fields['CODSettings']['FieldValue'];
    }

    /**
     * Sets the value of the COD settings.
     *
     * @param CODSettings
     * @return void
     */
    public function setCODSettings($value)
    {
        $this->_fields['CODSettings']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CODSettings  and returns this instance
     *
     * @param CODSettings $value CODSettings
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withCODSettings($value)
    {
        $this->setCODSettings($value);
        return $this;
    }

    /**
     * Checks if DestinationAddress  is set
     *
     * @return bool true if DestinationAddress property is set
     */
    public function isSetDestinationAddress()
    {
        return !is_null($this->_fields['DestinationAddress']['FieldValue']);

    }

    /**
     * Checks if CODSettings  is set
     *
     * @return bool true if CODSettings property is set
     */
    public function isSetCODSettings()
    {
        return !is_null($this->_fields['CODSettings']['FieldValue']);

    }

    /**
     * Gets the value of the FulfillmentPolicy property.
     *
     * @return string FulfillmentPolicy
     */
    public function getFulfillmentPolicy()
    {
        return $this->_fields['FulfillmentPolicy']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentPolicy property.
     *
     * @param string FulfillmentPolicy
     * @return this instance
     */
    public function setFulfillmentPolicy($value)
    {
        $this->_fields['FulfillmentPolicy']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentPolicy and returns this instance
     *
     * @param string $value FulfillmentPolicy
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withFulfillmentPolicy($value)
    {
        $this->setFulfillmentPolicy($value);
        return $this;
    }


    /**
     * Checks if FulfillmentPolicy is set
     *
     * @return bool true if FulfillmentPolicy  is set
     */
    public function isSetFulfillmentPolicy()
    {
        return !is_null($this->_fields['FulfillmentPolicy']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentMethod property.
     *
     * @return string FulfillmentMethod
     */
    public function getFulfillmentMethod()
    {
        return $this->_fields['FulfillmentMethod']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentMethod property.
     *
     * @param string FulfillmentMethod
     * @return this instance
     */
    public function setFulfillmentMethod($value)
    {
        $this->_fields['FulfillmentMethod']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentMethod and returns this instance
     *
     * @param string $value FulfillmentMethod
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withFulfillmentMethod($value)
    {
        $this->setFulfillmentMethod($value);
        return $this;
    }


    /**
     * Checks if FulfillmentMethod is set
     *
     * @return bool true if FulfillmentMethod  is set
     */
    public function isSetFulfillmentMethod()
    {
        return !is_null($this->_fields['FulfillmentMethod']['FieldValue']);
    }

    /**
     * Gets the value of the ShipFromCountryCode property.
     *
     * @return string ShipFromCountryCode
     */
    public function getShipFromCountryCode()
    {
        return $this->_fields['ShipFromCountryCode']['FieldValue'];
    }

    /**
     * Sets the value of the ShipFromCountryCode property.
     *
     * @param string ShipFromCountryCode
     * @return this instance
     */
    public function setShipFromCountryCode($value)
    {
        $this->_fields['ShipFromCountryCode']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShipFromCountryCode and returns this instance
     *
     * @param string $value ShipFromCountryCode
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withShipFromCountryCode($value)
    {
        $this->setShipFromCountryCode($value);
        return $this;
    }


    /**
     * Checks if ShipFromCountryCode is set
     *
     * @return bool true if ShipFromCountryCode  is set
     */
    public function isSetShipFromCountryCode()
    {
        return !is_null($this->_fields['ShipFromCountryCode']['FieldValue']);
    }

    /**
     * Gets the value of the NotificationEmailList.
     *
     * @return NotificationEmailList NotificationEmailList
     */
    public function getNotificationEmailList()
    {
        return $this->_fields['NotificationEmailList']['FieldValue'];
    }

    /**
     * Sets the value of the NotificationEmailList.
     *
     * @param NotificationEmailList NotificationEmailList
     * @return void
     */
    public function setNotificationEmailList($value)
    {
        $this->_fields['NotificationEmailList']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the NotificationEmailList  and returns this instance
     *
     * @param NotificationEmailList $value NotificationEmailList
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withNotificationEmailList($value)
    {
        $this->setNotificationEmailList($value);
        return $this;
    }


    /**
     * Checks if NotificationEmailList  is set
     *
     * @return bool true if NotificationEmailList property is set
     */
    public function isSetNotificationEmailList()
    {
        return !is_null($this->_fields['NotificationEmailList']['FieldValue']);

    }

    /**
     * Gets the value of the Items.
     *
     * @return CreateFulfillmentOrderItemList Items
     */
    public function getItems()
    {
        return $this->_fields['Items']['FieldValue'];
    }

    /**
     * Sets the value of the Items.
     *
     * @param CreateFulfillmentOrderItemList Items
     * @return void
     */
    public function setItems($value)
    {
        $this->_fields['Items']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Items  and returns this instance
     *
     * @param CreateFulfillmentOrderItemList $value Items
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderRequest instance
     */
    public function withItems($value)
    {
        $this->setItems($value);
        return $this;
    }


    /**
     * Checks if Items  is set
     *
     * @return bool true if Items property is set
     */
    public function isSetItems()
    {
        return !is_null($this->_fields['Items']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:CreateFulfillmentOrderResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse from provided XML.
                                  Make sure that CreateFulfillmentOrderResponse is a root element");
        }

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
     * @return FBAOutboundServiceMWS_Model_CreateFulfillmentOrderResponse instance
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
        $xml .= "<CreateFulfillmentOrderResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</CreateFulfillmentOrderResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_Currency extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_Currency
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>CurrencyCode: string</li>
     * <li>Value: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'CurrencyCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Value' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the CurrencyCode property.
     *
     * @return string CurrencyCode
     */
    public function getCurrencyCode()
    {
        return $this->_fields['CurrencyCode']['FieldValue'];
    }

    /**
     * Sets the value of the CurrencyCode property.
     *
     * @param string CurrencyCode
     * @return this instance
     */
    public function setCurrencyCode($value)
    {
        $this->_fields['CurrencyCode']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CurrencyCode and returns this instance
     *
     * @param string $value CurrencyCode
     * @return FBAOutboundServiceMWS_Model_Currency instance
     */
    public function withCurrencyCode($value)
    {
        $this->setCurrencyCode($value);
        return $this;
    }


    /**
     * Checks if CurrencyCode is set
     *
     * @return bool true if CurrencyCode  is set
     */
    public function isSetCurrencyCode()
    {
        return !is_null($this->_fields['CurrencyCode']['FieldValue']);
    }

    /**
     * Gets the value of the Value property.
     *
     * @return string Value
     */
    public function getValue()
    {
        return $this->_fields['Value']['FieldValue'];
    }

    /**
     * Sets the value of the Value property.
     *
     * @param string Value
     * @return this instance
     */
    public function setValue($value)
    {
        $this->_fields['Value']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Value and returns this instance
     *
     * @param string $value Value
     * @return FBAOutboundServiceMWS_Model_Currency instance
     */
    public function withValue($value)
    {
        $this->setValue($value);
        return $this;
    }


    /**
     * Checks if Value is set
     *
     * @return bool true if Value  is set
     */
    public function isSetValue()
    {
        return !is_null($this->_fields['Value']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_Error extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_Error
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Type: string</li>
     * <li>Code: string</li>
     * <li>Message: string</li>
     * <li>Detail: FBAOutboundServiceMWS_Model_Object</li>
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
     * @return FBAOutboundServiceMWS_Model_Error instance
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
     * @return FBAOutboundServiceMWS_Model_Error instance
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
     * @return FBAOutboundServiceMWS_Model_Error instance
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
     * @return FBAOutboundServiceMWS_Model_Error instance
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
class FBAOutboundServiceMWS_Model_ErrorResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ErrorResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Error: FBAOutboundServiceMWS_Model_Error</li>
     * <li>RequestId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Error' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_Error')),
        'RequestId' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_ErrorResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_ErrorResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:ErrorResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_ErrorResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_ErrorResponse from provided XML.
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
     * @return FBAOutboundServiceMWS_Model_ErrorResponse  instance
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
     * @return FBAOutboundServiceMWS_Model_ErrorResponse instance
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
        $xml .= "<ErrorResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ErrorResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_Fee extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_Fee
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Name: string</li>
     * <li>Amount: FBAOutboundServiceMWS_Model_Currency</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Name' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Amount' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Name property.
     *
     * @return string Name
     */
    public function getName()
    {
        return $this->_fields['Name']['FieldValue'];
    }

    /**
     * Sets the value of the Name property.
     *
     * @param string Name
     * @return this instance
     */
    public function setName($value)
    {
        $this->_fields['Name']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Name and returns this instance
     *
     * @param string $value Name
     * @return FBAOutboundServiceMWS_Model_Fee instance
     */
    public function withName($value)
    {
        $this->setName($value);
        return $this;
    }


    /**
     * Checks if Name is set
     *
     * @return bool true if Name  is set
     */
    public function isSetName()
    {
        return !is_null($this->_fields['Name']['FieldValue']);
    }

    /**
     * Gets the value of the Amount.
     *
     * @return Currency Amount
     */
    public function getAmount()
    {
        return $this->_fields['Amount']['FieldValue'];
    }

    /**
     * Sets the value of the Amount.
     *
     * @param Currency Amount
     * @return void
     */
    public function setAmount($value)
    {
        $this->_fields['Amount']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Amount  and returns this instance
     *
     * @param Currency $value Amount
     * @return FBAOutboundServiceMWS_Model_Fee instance
     */
    public function withAmount($value)
    {
        $this->setAmount($value);
        return $this;
    }


    /**
     * Checks if Amount  is set
     *
     * @return bool true if Amount property is set
     */
    public function isSetAmount()
    {
        return !is_null($this->_fields['Amount']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_FeeList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FeeList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_Fee</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_Fee')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of Fee member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed Fee or an array of Fee member
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
     * @param Fee  $feeArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FeeList  instance
     */
    public function withmember($feeArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentMethodList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentMethodList
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
     * @return FBAOutboundServiceMWS_Model_FulfillmentMethodList  instance
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
class FBAOutboundServiceMWS_Model_FulfillmentOrder extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentOrder
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerFulfillmentOrderId: string</li>
     * <li>DisplayableOrderId: string</li>
     * <li>DisplayableOrderDateTime: string</li>
     * <li>DisplayableOrderComment: string</li>
     * <li>ShippingSpeedCategory: string</li>
     * <li>DestinationAddress: FBAOutboundServiceMWS_Model_Address</li>
     * <li>FulfillmentPolicy: string</li>
     * <li>FulfillmentMethod: string</li>
     * <li>ReceivedDateTime: string</li>
     * <li>FulfillmentOrderStatus: string</li>
     * <li>StatusUpdatedDateTime: string</li>
     * <li>NotificationEmailList: FBAOutboundServiceMWS_Model_NotificationEmailList</li>
     * <li>CODSettings: FBAOutboundServiceMWS_Model_CODSettings</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerFulfillmentOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableOrderDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableOrderComment' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShippingSpeedCategory' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DestinationAddress' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Address'),
        'FulfillmentPolicy' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentMethod' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ReceivedDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentOrderStatus' => array('FieldValue' => null, 'FieldType' => 'string'),
        'StatusUpdatedDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'NotificationEmailList' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_NotificationEmailList'),
        'CODSettings' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_CODSettings'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SellerFulfillmentOrderId property.
     *
     * @return string SellerFulfillmentOrderId
     */
    public function getSellerFulfillmentOrderId()
    {
        return $this->_fields['SellerFulfillmentOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId property.
     *
     * @param string SellerFulfillmentOrderId
     * @return this instance
     */
    public function setSellerFulfillmentOrderId($value)
    {
        $this->_fields['SellerFulfillmentOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderId
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withSellerFulfillmentOrderId($value)
    {
        $this->setSellerFulfillmentOrderId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderId is set
     *
     * @return bool true if SellerFulfillmentOrderId  is set
     */
    public function isSetSellerFulfillmentOrderId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderId']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableOrderId property.
     *
     * @return string DisplayableOrderId
     */
    public function getDisplayableOrderId()
    {
        return $this->_fields['DisplayableOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableOrderId property.
     *
     * @param string DisplayableOrderId
     * @return this instance
     */
    public function setDisplayableOrderId($value)
    {
        $this->_fields['DisplayableOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableOrderId and returns this instance
     *
     * @param string $value DisplayableOrderId
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withDisplayableOrderId($value)
    {
        $this->setDisplayableOrderId($value);
        return $this;
    }


    /**
     * Checks if DisplayableOrderId is set
     *
     * @return bool true if DisplayableOrderId  is set
     */
    public function isSetDisplayableOrderId()
    {
        return !is_null($this->_fields['DisplayableOrderId']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableOrderDateTime property.
     *
     * @return string DisplayableOrderDateTime
     */
    public function getDisplayableOrderDateTime()
    {
        return $this->_fields['DisplayableOrderDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableOrderDateTime property.
     *
     * @param string DisplayableOrderDateTime
     * @return this instance
     */
    public function setDisplayableOrderDateTime($value)
    {
        $this->_fields['DisplayableOrderDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableOrderDateTime and returns this instance
     *
     * @param string $value DisplayableOrderDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withDisplayableOrderDateTime($value)
    {
        $this->setDisplayableOrderDateTime($value);
        return $this;
    }


    /**
     * Checks if DisplayableOrderDateTime is set
     *
     * @return bool true if DisplayableOrderDateTime  is set
     */
    public function isSetDisplayableOrderDateTime()
    {
        return !is_null($this->_fields['DisplayableOrderDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableOrderComment property.
     *
     * @return string DisplayableOrderComment
     */
    public function getDisplayableOrderComment()
    {
        return $this->_fields['DisplayableOrderComment']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableOrderComment property.
     *
     * @param string DisplayableOrderComment
     * @return this instance
     */
    public function setDisplayableOrderComment($value)
    {
        $this->_fields['DisplayableOrderComment']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableOrderComment and returns this instance
     *
     * @param string $value DisplayableOrderComment
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withDisplayableOrderComment($value)
    {
        $this->setDisplayableOrderComment($value);
        return $this;
    }


    /**
     * Checks if DisplayableOrderComment is set
     *
     * @return bool true if DisplayableOrderComment  is set
     */
    public function isSetDisplayableOrderComment()
    {
        return !is_null($this->_fields['DisplayableOrderComment']['FieldValue']);
    }

    /**
     * Gets the value of the ShippingSpeedCategory property.
     *
     * @return string ShippingSpeedCategory
     */
    public function getShippingSpeedCategory()
    {
        return $this->_fields['ShippingSpeedCategory']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingSpeedCategory property.
     *
     * @param string ShippingSpeedCategory
     * @return this instance
     */
    public function setShippingSpeedCategory($value)
    {
        $this->_fields['ShippingSpeedCategory']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippingSpeedCategory and returns this instance
     *
     * @param string $value ShippingSpeedCategory
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withShippingSpeedCategory($value)
    {
        $this->setShippingSpeedCategory($value);
        return $this;
    }


    /**
     * Checks if ShippingSpeedCategory is set
     *
     * @return bool true if ShippingSpeedCategory  is set
     */
    public function isSetShippingSpeedCategory()
    {
        return !is_null($this->_fields['ShippingSpeedCategory']['FieldValue']);
    }

    /**
     * Gets the value of the DestinationAddress.
     *
     * @return Address DestinationAddress
     */
    public function getDestinationAddress()
    {
        return $this->_fields['DestinationAddress']['FieldValue'];
    }

    /**
     * Sets the value of the DestinationAddress.
     *
     * @param Address DestinationAddress
     * @return void
     */
    public function setDestinationAddress($value)
    {
        $this->_fields['DestinationAddress']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the DestinationAddress  and returns this instance
     *
     * @param Address $value DestinationAddress
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withDestinationAddress($value)
    {
        $this->setDestinationAddress($value);
        return $this;
    }


    /**
     * Checks if DestinationAddress  is set
     *
     * @return bool true if DestinationAddress property is set
     */
    public function isSetDestinationAddress()
    {
        return !is_null($this->_fields['DestinationAddress']['FieldValue']);

    }

    /**
     * Gets the value of the FulfillmentPolicy property.
     *
     * @return string FulfillmentPolicy
     */
    public function getFulfillmentPolicy()
    {
        return $this->_fields['FulfillmentPolicy']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentPolicy property.
     *
     * @param string FulfillmentPolicy
     * @return this instance
     */
    public function setFulfillmentPolicy($value)
    {
        $this->_fields['FulfillmentPolicy']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentPolicy and returns this instance
     *
     * @param string $value FulfillmentPolicy
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withFulfillmentPolicy($value)
    {
        $this->setFulfillmentPolicy($value);
        return $this;
    }


    /**
     * Checks if FulfillmentPolicy is set
     *
     * @return bool true if FulfillmentPolicy  is set
     */
    public function isSetFulfillmentPolicy()
    {
        return !is_null($this->_fields['FulfillmentPolicy']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentMethod property.
     *
     * @return string FulfillmentMethod
     */
    public function getFulfillmentMethod()
    {
        return $this->_fields['FulfillmentMethod']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentMethod property.
     *
     * @param string FulfillmentMethod
     * @return this instance
     */
    public function setFulfillmentMethod($value)
    {
        $this->_fields['FulfillmentMethod']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentMethod and returns this instance
     *
     * @param string $value FulfillmentMethod
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withFulfillmentMethod($value)
    {
        $this->setFulfillmentMethod($value);
        return $this;
    }


    /**
     * Checks if FulfillmentMethod is set
     *
     * @return bool true if FulfillmentMethod  is set
     */
    public function isSetFulfillmentMethod()
    {
        return !is_null($this->_fields['FulfillmentMethod']['FieldValue']);
    }

    /**
     * Gets the value of the ReceivedDateTime property.
     *
     * @return string ReceivedDateTime
     */
    public function getReceivedDateTime()
    {
        return $this->_fields['ReceivedDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the ReceivedDateTime property.
     *
     * @param string ReceivedDateTime
     * @return this instance
     */
    public function setReceivedDateTime($value)
    {
        $this->_fields['ReceivedDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ReceivedDateTime and returns this instance
     *
     * @param string $value ReceivedDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withReceivedDateTime($value)
    {
        $this->setReceivedDateTime($value);
        return $this;
    }


    /**
     * Checks if ReceivedDateTime is set
     *
     * @return bool true if ReceivedDateTime  is set
     */
    public function isSetReceivedDateTime()
    {
        return !is_null($this->_fields['ReceivedDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentOrderStatus property.
     *
     * @return string FulfillmentOrderStatus
     */
    public function getFulfillmentOrderStatus()
    {
        return $this->_fields['FulfillmentOrderStatus']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentOrderStatus property.
     *
     * @param string FulfillmentOrderStatus
     * @return this instance
     */
    public function setFulfillmentOrderStatus($value)
    {
        $this->_fields['FulfillmentOrderStatus']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentOrderStatus and returns this instance
     *
     * @param string $value FulfillmentOrderStatus
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withFulfillmentOrderStatus($value)
    {
        $this->setFulfillmentOrderStatus($value);
        return $this;
    }


    /**
     * Checks if FulfillmentOrderStatus is set
     *
     * @return bool true if FulfillmentOrderStatus  is set
     */
    public function isSetFulfillmentOrderStatus()
    {
        return !is_null($this->_fields['FulfillmentOrderStatus']['FieldValue']);
    }

    /**
     * Gets the value of the StatusUpdatedDateTime property.
     *
     * @return string StatusUpdatedDateTime
     */
    public function getStatusUpdatedDateTime()
    {
        return $this->_fields['StatusUpdatedDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the StatusUpdatedDateTime property.
     *
     * @param string StatusUpdatedDateTime
     * @return this instance
     */
    public function setStatusUpdatedDateTime($value)
    {
        $this->_fields['StatusUpdatedDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the StatusUpdatedDateTime and returns this instance
     *
     * @param string $value StatusUpdatedDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withStatusUpdatedDateTime($value)
    {
        $this->setStatusUpdatedDateTime($value);
        return $this;
    }


    /**
     * Checks if StatusUpdatedDateTime is set
     *
     * @return bool true if StatusUpdatedDateTime  is set
     */
    public function isSetStatusUpdatedDateTime()
    {
        return !is_null($this->_fields['StatusUpdatedDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the NotificationEmailList.
     *
     * @return NotificationEmailList NotificationEmailList
     */
    public function getNotificationEmailList()
    {
        return $this->_fields['NotificationEmailList']['FieldValue'];
    }

    /**
     * Sets the value of the NotificationEmailList.
     *
     * @param NotificationEmailList NotificationEmailList
     * @return void
     */
    public function setNotificationEmailList($value)
    {
        $this->_fields['NotificationEmailList']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the NotificationEmailList  and returns this instance
     *
     * @param NotificationEmailList $value NotificationEmailList
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withNotificationEmailList($value)
    {
        $this->setNotificationEmailList($value);
        return $this;
    }


    /**
     * Checks if NotificationEmailList  is set
     *
     * @return bool true if NotificationEmailList property is set
     */
    public function isSetNotificationEmailList()
    {
        return !is_null($this->_fields['NotificationEmailList']['FieldValue']);

    }

    /**
     * Gets the value of the COD settings.
     *
     * @return  CODSettings
     */
    public function getCODSettings()
    {
        return $this->_fields['CODSettings']['FieldValue'];
    }

    /**
     * Sets the value of the COD settings.
     *
     * @param CODSettings
     * @return void
     */
    public function setCODSettings($value)
    {
        $this->_fields['CODSettings']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CODSettings  and returns this instance
     *
     * @param CODSettings $value CODSettings
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrder instance
     */
    public function withCODSettings($value)
    {
        $this->setCODSettings($value);
        return $this;
    }

    /**
     * Checks if CODSettings  is set
     *
     * @return bool true if CODSettings property is set
     */
    public function isSetCODSettings()
    {
        return !is_null($this->_fields['CODSettings']['FieldValue']);

    }
}
class FBAOutboundServiceMWS_Model_FulfillmentOrderItem extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentOrderItem
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerSKU: string</li>
     * <li>SellerFulfillmentOrderItemId: string</li>
     * <li>Quantity: int</li>
     * <li>GiftMessage: string</li>
     * <li>DisplayableComment: string</li>
     * <li>FulfillmentNetworkSKU: string</li>
     * <li>OrderItemDisposition: string</li>
     * <li>CancelledQuantity: int</li>
     * <li>UnfulfillableQuantity: int</li>
     * <li>EstimatedShipDateTime: string</li>
     * <li>EstimatedArrivalDateTime: string</li>
     * <li>PerUnitDeclaredValue: FBAOutboundServiceMWS_Model_Currency</li>
     * <li>PerUnitPrice: FBAOutboundServiceMWS_Model_Currency</li>
     * <li>PerUnitTax: FBAOutboundServiceMWS_Model_Currency</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerFulfillmentOrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Quantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'GiftMessage' => array('FieldValue' => null, 'FieldType' => 'string'),
        'DisplayableComment' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentNetworkSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'OrderItemDisposition' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CancelledQuantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'UnfulfillableQuantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'EstimatedShipDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EstimatedArrivalDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'PerUnitDeclaredValue' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        'PerUnitPrice' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
        'PerUnitTax' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Currency'),
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
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
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
     * Gets the value of the SellerFulfillmentOrderItemId property.
     *
     * @return string SellerFulfillmentOrderItemId
     */
    public function getSellerFulfillmentOrderItemId()
    {
        return $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId property.
     *
     * @param string SellerFulfillmentOrderItemId
     * @return this instance
     */
    public function setSellerFulfillmentOrderItemId($value)
    {
        $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderItemId
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withSellerFulfillmentOrderItemId($value)
    {
        $this->setSellerFulfillmentOrderItemId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderItemId is set
     *
     * @return bool true if SellerFulfillmentOrderItemId  is set
     */
    public function isSetSellerFulfillmentOrderItemId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderItemId']['FieldValue']);
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
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
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
     * Gets the value of the GiftMessage property.
     *
     * @return string GiftMessage
     */
    public function getGiftMessage()
    {
        return $this->_fields['GiftMessage']['FieldValue'];
    }

    /**
     * Sets the value of the GiftMessage property.
     *
     * @param string GiftMessage
     * @return this instance
     */
    public function setGiftMessage($value)
    {
        $this->_fields['GiftMessage']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the GiftMessage and returns this instance
     *
     * @param string $value GiftMessage
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withGiftMessage($value)
    {
        $this->setGiftMessage($value);
        return $this;
    }


    /**
     * Checks if GiftMessage is set
     *
     * @return bool true if GiftMessage  is set
     */
    public function isSetGiftMessage()
    {
        return !is_null($this->_fields['GiftMessage']['FieldValue']);
    }

    /**
     * Gets the value of the DisplayableComment property.
     *
     * @return string DisplayableComment
     */
    public function getDisplayableComment()
    {
        return $this->_fields['DisplayableComment']['FieldValue'];
    }

    /**
     * Sets the value of the DisplayableComment property.
     *
     * @param string DisplayableComment
     * @return this instance
     */
    public function setDisplayableComment($value)
    {
        $this->_fields['DisplayableComment']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the DisplayableComment and returns this instance
     *
     * @param string $value DisplayableComment
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withDisplayableComment($value)
    {
        $this->setDisplayableComment($value);
        return $this;
    }


    /**
     * Checks if DisplayableComment is set
     *
     * @return bool true if DisplayableComment  is set
     */
    public function isSetDisplayableComment()
    {
        return !is_null($this->_fields['DisplayableComment']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentNetworkSKU property.
     *
     * @return string FulfillmentNetworkSKU
     */
    public function getFulfillmentNetworkSKU()
    {
        return $this->_fields['FulfillmentNetworkSKU']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentNetworkSKU property.
     *
     * @param string FulfillmentNetworkSKU
     * @return this instance
     */
    public function setFulfillmentNetworkSKU($value)
    {
        $this->_fields['FulfillmentNetworkSKU']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentNetworkSKU and returns this instance
     *
     * @param string $value FulfillmentNetworkSKU
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withFulfillmentNetworkSKU($value)
    {
        $this->setFulfillmentNetworkSKU($value);
        return $this;
    }


    /**
     * Checks if FulfillmentNetworkSKU is set
     *
     * @return bool true if FulfillmentNetworkSKU  is set
     */
    public function isSetFulfillmentNetworkSKU()
    {
        return !is_null($this->_fields['FulfillmentNetworkSKU']['FieldValue']);
    }

    /**
     * Gets the value of the OrderItemDisposition property.
     *
     * @return string OrderItemDisposition
     */
    public function getOrderItemDisposition()
    {
        return $this->_fields['OrderItemDisposition']['FieldValue'];
    }

    /**
     * Sets the value of the OrderItemDisposition property.
     *
     * @param string OrderItemDisposition
     * @return this instance
     */
    public function setOrderItemDisposition($value)
    {
        $this->_fields['OrderItemDisposition']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the OrderItemDisposition and returns this instance
     *
     * @param string $value OrderItemDisposition
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withOrderItemDisposition($value)
    {
        $this->setOrderItemDisposition($value);
        return $this;
    }


    /**
     * Checks if OrderItemDisposition is set
     *
     * @return bool true if OrderItemDisposition  is set
     */
    public function isSetOrderItemDisposition()
    {
        return !is_null($this->_fields['OrderItemDisposition']['FieldValue']);
    }

    /**
     * Gets the value of the CancelledQuantity property.
     *
     * @return int CancelledQuantity
     */
    public function getCancelledQuantity()
    {
        return $this->_fields['CancelledQuantity']['FieldValue'];
    }

    /**
     * Sets the value of the CancelledQuantity property.
     *
     * @param int CancelledQuantity
     * @return this instance
     */
    public function setCancelledQuantity($value)
    {
        $this->_fields['CancelledQuantity']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CancelledQuantity and returns this instance
     *
     * @param int $value CancelledQuantity
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withCancelledQuantity($value)
    {
        $this->setCancelledQuantity($value);
        return $this;
    }


    /**
     * Checks if CancelledQuantity is set
     *
     * @return bool true if CancelledQuantity  is set
     */
    public function isSetCancelledQuantity()
    {
        return !is_null($this->_fields['CancelledQuantity']['FieldValue']);
    }

    /**
     * Gets the value of the UnfulfillableQuantity property.
     *
     * @return int UnfulfillableQuantity
     */
    public function getUnfulfillableQuantity()
    {
        return $this->_fields['UnfulfillableQuantity']['FieldValue'];
    }

    /**
     * Sets the value of the UnfulfillableQuantity property.
     *
     * @param int UnfulfillableQuantity
     * @return this instance
     */
    public function setUnfulfillableQuantity($value)
    {
        $this->_fields['UnfulfillableQuantity']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the UnfulfillableQuantity and returns this instance
     *
     * @param int $value UnfulfillableQuantity
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withUnfulfillableQuantity($value)
    {
        $this->setUnfulfillableQuantity($value);
        return $this;
    }


    /**
     * Checks if UnfulfillableQuantity is set
     *
     * @return bool true if UnfulfillableQuantity  is set
     */
    public function isSetUnfulfillableQuantity()
    {
        return !is_null($this->_fields['UnfulfillableQuantity']['FieldValue']);
    }

    /**
     * Gets the value of the EstimatedShipDateTime property.
     *
     * @return string EstimatedShipDateTime
     */
    public function getEstimatedShipDateTime()
    {
        return $this->_fields['EstimatedShipDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedShipDateTime property.
     *
     * @param string EstimatedShipDateTime
     * @return this instance
     */
    public function setEstimatedShipDateTime($value)
    {
        $this->_fields['EstimatedShipDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EstimatedShipDateTime and returns this instance
     *
     * @param string $value EstimatedShipDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withEstimatedShipDateTime($value)
    {
        $this->setEstimatedShipDateTime($value);
        return $this;
    }


    /**
     * Checks if EstimatedShipDateTime is set
     *
     * @return bool true if EstimatedShipDateTime  is set
     */
    public function isSetEstimatedShipDateTime()
    {
        return !is_null($this->_fields['EstimatedShipDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the EstimatedArrivalDateTime property.
     *
     * @return string EstimatedArrivalDateTime
     */
    public function getEstimatedArrivalDateTime()
    {
        return $this->_fields['EstimatedArrivalDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedArrivalDateTime property.
     *
     * @param string EstimatedArrivalDateTime
     * @return this instance
     */
    public function setEstimatedArrivalDateTime($value)
    {
        $this->_fields['EstimatedArrivalDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EstimatedArrivalDateTime and returns this instance
     *
     * @param string $value EstimatedArrivalDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withEstimatedArrivalDateTime($value)
    {
        $this->setEstimatedArrivalDateTime($value);
        return $this;
    }


    /**
     * Checks if EstimatedArrivalDateTime is set
     *
     * @return bool true if EstimatedArrivalDateTime  is set
     */
    public function isSetEstimatedArrivalDateTime()
    {
        return !is_null($this->_fields['EstimatedArrivalDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the PerUnitDeclaredValue.
     *
     * @return Currency PerUnitDeclaredValue
     */
    public function getPerUnitDeclaredValue()
    {
        return $this->_fields['PerUnitDeclaredValue']['FieldValue'];
    }

    /**
     * Sets the value of the PerUnitDeclaredValue.
     *
     * @param Currency PerUnitDeclaredValue
     * @return void
     */
    public function setPerUnitDeclaredValue($value)
    {
        $this->_fields['PerUnitDeclaredValue']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PerUnitDeclaredValue  and returns this instance
     *
     * @param Currency $value PerUnitDeclaredValue
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withPerUnitDeclaredValue($value)
    {
        $this->setPerUnitDeclaredValue($value);
        return $this;
    }


    /**
     * Checks if PerUnitDeclaredValue  is set
     *
     * @return bool true if PerUnitDeclaredValue property is set
     */
    public function isSetPerUnitDeclaredValue()
    {
        return !is_null($this->_fields['PerUnitDeclaredValue']['FieldValue']);

    }


    /**
     * Gets the value of the PerUnitPrice.
     *
     * @return Currency PerUnitPrice
     */
    public function getPerUnitPrice()
    {
        return $this->_fields['PerUnitPrice']['FieldValue'];
    }

    /**
     * Sets the value of the PerUnitPrice.
     *
     * @param Currency PerUnitPrice
     * @return void
     */
    public function setPerUnitPrice($value)
    {
        $this->_fields['PerUnitPrice']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PerUnitPrice  and returns this instance
     *
     * @param Currency $value PerUnitPrice
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withPerUnitPrice($value)
    {
        $this->setPerUnitPrice($value);
        return $this;
    }


    /**
     * Checks if PerUnitPrice  is set
     *
     * @return bool true if PerUnitPrice property is set
     */
    public function isSetPerUnitPrice()
    {
        return !is_null($this->_fields['PerUnitPrice']['FieldValue']);

    }

    /**
     * Gets the value of the PerUnitTax.
     *
     * @return Currency PerUnitTax
     */
    public function getPerUnitTax()
    {
        return $this->_fields['PerUnitTax']['FieldValue'];
    }

    /**
     * Sets the value of the PerUnitTax.
     *
     * @param Currency PerUnitTax
     * @return void
     */
    public function setPerUnitTax($value)
    {
        $this->_fields['PerUnitTax']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the PerUnitTax  and returns this instance
     *
     * @param Currency $value PerUnitTax
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItem instance
     */
    public function withPerUnitTax($value)
    {
        $this->setPerUnitTax($value);
        return $this;
    }


    /**
     * Checks if PerUnitTax  is set
     *
     * @return bool true if PerUnitTax property is set
     */
    public function isSetPerUnitTax()
    {
        return !is_null($this->_fields['PerUnitTax']['FieldValue']);

    }



}
class FBAOutboundServiceMWS_Model_FulfillmentOrderItemList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentOrderItemList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentOrderItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentOrderItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentOrderItem member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentOrderItem or an array of FulfillmentOrderItem member
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
     * @param FulfillmentOrderItem  $fulfillmentOrderItemArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderItemList  instance
     */
    public function withmember($fulfillmentOrderItemArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentOrderList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentOrderList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentOrder</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentOrder')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentOrder member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentOrder or an array of FulfillmentOrder member
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
     * @param FulfillmentOrder  $fulfillmentOrderArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentOrderList  instance
     */
    public function withmember($fulfillmentOrderArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentPreview extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentPreview
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>ShippingSpeedCategory: string</li>
     * <li>IsFulfillable: bool</li>
     * <li>IsCODCapable: bool</li>
     * <li>EstimatedShippingWeight: FBAOutboundServiceMWS_Model_Weight</li>
     * <li>EstimatedFees: FBAOutboundServiceMWS_Model_FeeList</li>
     * <li>FulfillmentPreviewShipments: FBAOutboundServiceMWS_Model_FulfillmentPreviewShipmentList</li>
     * <li>UnfulfillablePreviewItems: FBAOutboundServiceMWS_Model_UnfulfillablePreviewItemList</li>
     * <li>OrderUnfulfillableReasons: FBAOutboundServiceMWS_Model_StringList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ShippingSpeedCategory' => array('FieldValue' => null, 'FieldType' => 'string'),
        'IsFulfillable' => array('FieldValue' => null, 'FieldType' => 'bool'),
        'IsCODCapable' => array('FieldValue' => null, 'FieldType' => 'bool'),
        'EstimatedShippingWeight' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Weight'),
        'EstimatedFees' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FeeList'),
        'FulfillmentPreviewShipments' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentPreviewShipmentList'),
        'UnfulfillablePreviewItems' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_UnfulfillablePreviewItemList'),
        'OrderUnfulfillableReasons' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_StringList'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the ShippingSpeedCategory property.
     *
     * @return string ShippingSpeedCategory
     */
    public function getShippingSpeedCategory()
    {
        return $this->_fields['ShippingSpeedCategory']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingSpeedCategory property.
     *
     * @param string ShippingSpeedCategory
     * @return this instance
     */
    public function setShippingSpeedCategory($value)
    {
        $this->_fields['ShippingSpeedCategory']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippingSpeedCategory and returns this instance
     *
     * @param string $value ShippingSpeedCategory
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withShippingSpeedCategory($value)
    {
        $this->setShippingSpeedCategory($value);
        return $this;
    }


    /**
     * Checks if ShippingSpeedCategory is set
     *
     * @return bool true if ShippingSpeedCategory  is set
     */
    public function isSetShippingSpeedCategory()
    {
        return !is_null($this->_fields['ShippingSpeedCategory']['FieldValue']);
    }

    /**
     * Gets the value of the IsFulfillable property.
     *
     * @return bool IsFulfillable
     */
    public function getIsFulfillable()
    {
        return $this->_fields['IsFulfillable']['FieldValue'];
    }

    /**
     * Sets the value of the IsFulfillable property.
     *
     * @param bool IsFulfillable
     * @return this instance
     */
    public function setIsFulfillable($value)
    {
        $this->_fields['IsFulfillable']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Gets the value of the IsCODCapable property.
     *
     * @return bool IsCODCapable
     */
    public function getIsCODCapable()
    {
        return $this->_fields['IsCODCapable']['FieldValue'];
    }

    /**
     * Sets the value of the IsCODCapable property.
     *
     * @param bool IsCODCapable
     * @return this instance
     */
    public function setIsCODCapable($value)
    {
        $this->_fields['IsCODCapable']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the IsCODCapable and returns this instance
     *
     * @param bool $value IsCODCapable
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withIsCODCapable($value)
    {
        $this->setIsCODCapable($value);
        return $this;
    }

    /**
     * Sets the value of the IsFulfillable and returns this instance
     *
     * @param bool $value IsFulfillable
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withIsFulfillable($value)
    {
        $this->setIsFulfillable($value);
        return $this;
    }


    /**
     * Checks if IsFulfillable is set
     *
     * @return bool true if IsFulfillable  is set
     */
    public function isSetIsFulfillable()
    {
        return !is_null($this->_fields['IsFulfillable']['FieldValue']);
    }

    /**
     * Checks if IsCODCapable is set
     *
     * @return bool true if IsCODCapable  is set
     */
    public function isSetIsCODCapable()
    {
        return !is_null($this->_fields['IsCODCapable']['FieldValue']);
    }

    /**
     * Gets the value of the EstimatedShippingWeight.
     *
     * @return Weight EstimatedShippingWeight
     */
    public function getEstimatedShippingWeight()
    {
        return $this->_fields['EstimatedShippingWeight']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedShippingWeight.
     *
     * @param Weight EstimatedShippingWeight
     * @return void
     */
    public function setEstimatedShippingWeight($value)
    {
        $this->_fields['EstimatedShippingWeight']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the EstimatedShippingWeight  and returns this instance
     *
     * @param Weight $value EstimatedShippingWeight
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withEstimatedShippingWeight($value)
    {
        $this->setEstimatedShippingWeight($value);
        return $this;
    }


    /**
     * Checks if EstimatedShippingWeight  is set
     *
     * @return bool true if EstimatedShippingWeight property is set
     */
    public function isSetEstimatedShippingWeight()
    {
        return !is_null($this->_fields['EstimatedShippingWeight']['FieldValue']);

    }

    /**
     * Gets the value of the EstimatedFees.
     *
     * @return FeeList EstimatedFees
     */
    public function getEstimatedFees()
    {
        return $this->_fields['EstimatedFees']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedFees.
     *
     * @param FeeList EstimatedFees
     * @return void
     */
    public function setEstimatedFees($value)
    {
        $this->_fields['EstimatedFees']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the EstimatedFees  and returns this instance
     *
     * @param FeeList $value EstimatedFees
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withEstimatedFees($value)
    {
        $this->setEstimatedFees($value);
        return $this;
    }


    /**
     * Checks if EstimatedFees  is set
     *
     * @return bool true if EstimatedFees property is set
     */
    public function isSetEstimatedFees()
    {
        return !is_null($this->_fields['EstimatedFees']['FieldValue']);

    }

    /**
     * Gets the value of the FulfillmentPreviewShipments.
     *
     * @return FulfillmentPreviewShipmentList FulfillmentPreviewShipments
     */
    public function getFulfillmentPreviewShipments()
    {
        return $this->_fields['FulfillmentPreviewShipments']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentPreviewShipments.
     *
     * @param FulfillmentPreviewShipmentList FulfillmentPreviewShipments
     * @return void
     */
    public function setFulfillmentPreviewShipments($value)
    {
        $this->_fields['FulfillmentPreviewShipments']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentPreviewShipments  and returns this instance
     *
     * @param FulfillmentPreviewShipmentList $value FulfillmentPreviewShipments
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withFulfillmentPreviewShipments($value)
    {
        $this->setFulfillmentPreviewShipments($value);
        return $this;
    }


    /**
     * Checks if FulfillmentPreviewShipments  is set
     *
     * @return bool true if FulfillmentPreviewShipments property is set
     */
    public function isSetFulfillmentPreviewShipments()
    {
        return !is_null($this->_fields['FulfillmentPreviewShipments']['FieldValue']);

    }

    /**
     * Gets the value of the UnfulfillablePreviewItems.
     *
     * @return UnfulfillablePreviewItemList UnfulfillablePreviewItems
     */
    public function getUnfulfillablePreviewItems()
    {
        return $this->_fields['UnfulfillablePreviewItems']['FieldValue'];
    }

    /**
     * Sets the value of the UnfulfillablePreviewItems.
     *
     * @param UnfulfillablePreviewItemList UnfulfillablePreviewItems
     * @return void
     */
    public function setUnfulfillablePreviewItems($value)
    {
        $this->_fields['UnfulfillablePreviewItems']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the UnfulfillablePreviewItems  and returns this instance
     *
     * @param UnfulfillablePreviewItemList $value UnfulfillablePreviewItems
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withUnfulfillablePreviewItems($value)
    {
        $this->setUnfulfillablePreviewItems($value);
        return $this;
    }


    /**
     * Checks if UnfulfillablePreviewItems  is set
     *
     * @return bool true if UnfulfillablePreviewItems property is set
     */
    public function isSetUnfulfillablePreviewItems()
    {
        return !is_null($this->_fields['UnfulfillablePreviewItems']['FieldValue']);

    }

    /**
     * Gets the value of the OrderUnfulfillableReasons.
     *
     * @return StringList OrderUnfulfillableReasons
     */
    public function getOrderUnfulfillableReasons()
    {
        return $this->_fields['OrderUnfulfillableReasons']['FieldValue'];
    }

    /**
     * Sets the value of the OrderUnfulfillableReasons.
     *
     * @param StringList OrderUnfulfillableReasons
     * @return void
     */
    public function setOrderUnfulfillableReasons($value)
    {
        $this->_fields['OrderUnfulfillableReasons']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the OrderUnfulfillableReasons  and returns this instance
     *
     * @param StringList $value OrderUnfulfillableReasons
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreview instance
     */
    public function withOrderUnfulfillableReasons($value)
    {
        $this->setOrderUnfulfillableReasons($value);
        return $this;
    }


    /**
     * Checks if OrderUnfulfillableReasons  is set
     *
     * @return bool true if OrderUnfulfillableReasons property is set
     */
    public function isSetOrderUnfulfillableReasons()
    {
        return !is_null($this->_fields['OrderUnfulfillableReasons']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_FulfillmentPreviewItem extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentPreviewItem
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerSKU: string</li>
     * <li>Quantity: int</li>
     * <li>SellerFulfillmentOrderItemId: string</li>
     * <li>EstimatedShippingWeight: FBAOutboundServiceMWS_Model_Weight</li>
     * <li>ShippingWeightCalculationMethod: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Quantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'SellerFulfillmentOrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EstimatedShippingWeight' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Weight'),
        'ShippingWeightCalculationMethod' => array('FieldValue' => null, 'FieldType' => 'string'),
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
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewItem instance
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
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewItem instance
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
     * Gets the value of the SellerFulfillmentOrderItemId property.
     *
     * @return string SellerFulfillmentOrderItemId
     */
    public function getSellerFulfillmentOrderItemId()
    {
        return $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId property.
     *
     * @param string SellerFulfillmentOrderItemId
     * @return this instance
     */
    public function setSellerFulfillmentOrderItemId($value)
    {
        $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderItemId
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewItem instance
     */
    public function withSellerFulfillmentOrderItemId($value)
    {
        $this->setSellerFulfillmentOrderItemId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderItemId is set
     *
     * @return bool true if SellerFulfillmentOrderItemId  is set
     */
    public function isSetSellerFulfillmentOrderItemId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderItemId']['FieldValue']);
    }

    /**
     * Gets the value of the EstimatedShippingWeight.
     *
     * @return Weight EstimatedShippingWeight
     */
    public function getEstimatedShippingWeight()
    {
        return $this->_fields['EstimatedShippingWeight']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedShippingWeight.
     *
     * @param Weight EstimatedShippingWeight
     * @return void
     */
    public function setEstimatedShippingWeight($value)
    {
        $this->_fields['EstimatedShippingWeight']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the EstimatedShippingWeight  and returns this instance
     *
     * @param Weight $value EstimatedShippingWeight
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewItem instance
     */
    public function withEstimatedShippingWeight($value)
    {
        $this->setEstimatedShippingWeight($value);
        return $this;
    }


    /**
     * Checks if EstimatedShippingWeight  is set
     *
     * @return bool true if EstimatedShippingWeight property is set
     */
    public function isSetEstimatedShippingWeight()
    {
        return !is_null($this->_fields['EstimatedShippingWeight']['FieldValue']);

    }

    /**
     * Gets the value of the ShippingWeightCalculationMethod property.
     *
     * @return string ShippingWeightCalculationMethod
     */
    public function getShippingWeightCalculationMethod()
    {
        return $this->_fields['ShippingWeightCalculationMethod']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingWeightCalculationMethod property.
     *
     * @param string ShippingWeightCalculationMethod
     * @return this instance
     */
    public function setShippingWeightCalculationMethod($value)
    {
        $this->_fields['ShippingWeightCalculationMethod']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippingWeightCalculationMethod and returns this instance
     *
     * @param string $value ShippingWeightCalculationMethod
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewItem instance
     */
    public function withShippingWeightCalculationMethod($value)
    {
        $this->setShippingWeightCalculationMethod($value);
        return $this;
    }


    /**
     * Checks if ShippingWeightCalculationMethod is set
     *
     * @return bool true if ShippingWeightCalculationMethod  is set
     */
    public function isSetShippingWeightCalculationMethod()
    {
        return !is_null($this->_fields['ShippingWeightCalculationMethod']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_FulfillmentPreviewItemList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentPreviewItemList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentPreviewItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentPreviewItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentPreviewItem member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentPreviewItem or an array of FulfillmentPreviewItem member
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
     * @param FulfillmentPreviewItem  $fulfillmentPreviewItemArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewItemList  instance
     */
    public function withmember($fulfillmentPreviewItemArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentPreviewList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentPreviewList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentPreview</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentPreview')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentPreview member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentPreview or an array of FulfillmentPreview member
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
     * @param FulfillmentPreview  $fulfillmentPreviewArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewList  instance
     */
    public function withmember($fulfillmentPreviewArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>EarliestShipDate: string</li>
     * <li>LatestShipDate: string</li>
     * <li>EarliestArrivalDate: string</li>
     * <li>LatestArrivalDate: string</li>
     * <li>FulfillmentPreviewItems: FBAOutboundServiceMWS_Model_FulfillmentPreviewItemList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'EarliestShipDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'LatestShipDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EarliestArrivalDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'LatestArrivalDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentPreviewItems' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentPreviewItemList'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the EarliestShipDate property.
     *
     * @return string EarliestShipDate
     */
    public function getEarliestShipDate()
    {
        return $this->_fields['EarliestShipDate']['FieldValue'];
    }

    /**
     * Sets the value of the EarliestShipDate property.
     *
     * @param string EarliestShipDate
     * @return this instance
     */
    public function setEarliestShipDate($value)
    {
        $this->_fields['EarliestShipDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EarliestShipDate and returns this instance
     *
     * @param string $value EarliestShipDate
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment instance
     */
    public function withEarliestShipDate($value)
    {
        $this->setEarliestShipDate($value);
        return $this;
    }


    /**
     * Checks if EarliestShipDate is set
     *
     * @return bool true if EarliestShipDate  is set
     */
    public function isSetEarliestShipDate()
    {
        return !is_null($this->_fields['EarliestShipDate']['FieldValue']);
    }

    /**
     * Gets the value of the LatestShipDate property.
     *
     * @return string LatestShipDate
     */
    public function getLatestShipDate()
    {
        return $this->_fields['LatestShipDate']['FieldValue'];
    }

    /**
     * Sets the value of the LatestShipDate property.
     *
     * @param string LatestShipDate
     * @return this instance
     */
    public function setLatestShipDate($value)
    {
        $this->_fields['LatestShipDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the LatestShipDate and returns this instance
     *
     * @param string $value LatestShipDate
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment instance
     */
    public function withLatestShipDate($value)
    {
        $this->setLatestShipDate($value);
        return $this;
    }


    /**
     * Checks if LatestShipDate is set
     *
     * @return bool true if LatestShipDate  is set
     */
    public function isSetLatestShipDate()
    {
        return !is_null($this->_fields['LatestShipDate']['FieldValue']);
    }

    /**
     * Gets the value of the EarliestArrivalDate property.
     *
     * @return string EarliestArrivalDate
     */
    public function getEarliestArrivalDate()
    {
        return $this->_fields['EarliestArrivalDate']['FieldValue'];
    }

    /**
     * Sets the value of the EarliestArrivalDate property.
     *
     * @param string EarliestArrivalDate
     * @return this instance
     */
    public function setEarliestArrivalDate($value)
    {
        $this->_fields['EarliestArrivalDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EarliestArrivalDate and returns this instance
     *
     * @param string $value EarliestArrivalDate
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment instance
     */
    public function withEarliestArrivalDate($value)
    {
        $this->setEarliestArrivalDate($value);
        return $this;
    }


    /**
     * Checks if EarliestArrivalDate is set
     *
     * @return bool true if EarliestArrivalDate  is set
     */
    public function isSetEarliestArrivalDate()
    {
        return !is_null($this->_fields['EarliestArrivalDate']['FieldValue']);
    }

    /**
     * Gets the value of the LatestArrivalDate property.
     *
     * @return string LatestArrivalDate
     */
    public function getLatestArrivalDate()
    {
        return $this->_fields['LatestArrivalDate']['FieldValue'];
    }

    /**
     * Sets the value of the LatestArrivalDate property.
     *
     * @param string LatestArrivalDate
     * @return this instance
     */
    public function setLatestArrivalDate($value)
    {
        $this->_fields['LatestArrivalDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the LatestArrivalDate and returns this instance
     *
     * @param string $value LatestArrivalDate
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment instance
     */
    public function withLatestArrivalDate($value)
    {
        $this->setLatestArrivalDate($value);
        return $this;
    }


    /**
     * Checks if LatestArrivalDate is set
     *
     * @return bool true if LatestArrivalDate  is set
     */
    public function isSetLatestArrivalDate()
    {
        return !is_null($this->_fields['LatestArrivalDate']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentPreviewItems.
     *
     * @return FulfillmentPreviewItemList FulfillmentPreviewItems
     */
    public function getFulfillmentPreviewItems()
    {
        return $this->_fields['FulfillmentPreviewItems']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentPreviewItems.
     *
     * @param FulfillmentPreviewItemList FulfillmentPreviewItems
     * @return void
     */
    public function setFulfillmentPreviewItems($value)
    {
        $this->_fields['FulfillmentPreviewItems']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentPreviewItems  and returns this instance
     *
     * @param FulfillmentPreviewItemList $value FulfillmentPreviewItems
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment instance
     */
    public function withFulfillmentPreviewItems($value)
    {
        $this->setFulfillmentPreviewItems($value);
        return $this;
    }


    /**
     * Checks if FulfillmentPreviewItems  is set
     *
     * @return bool true if FulfillmentPreviewItems property is set
     */
    public function isSetFulfillmentPreviewItems()
    {
        return !is_null($this->_fields['FulfillmentPreviewItems']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_FulfillmentPreviewShipmentList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentPreviewShipmentList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentPreviewShipment')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentPreviewShipment member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentPreviewShipment or an array of FulfillmentPreviewShipment member
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
     * @param FulfillmentPreviewShipment  $fulfillmentPreviewShipmentArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentPreviewShipmentList  instance
     */
    public function withmember($fulfillmentPreviewShipmentArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentShipment extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentShipment
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>AmazonShipmentId: string</li>
     * <li>FulfillmentCenterId: string</li>
     * <li>FulfillmentShipmentStatus: string</li>
     * <li>ShippingDateTime: string</li>
     * <li>EstimatedArrivalDateTime: string</li>
     * <li>FulfillmentShipmentItem: FBAOutboundServiceMWS_Model_FulfillmentShipmentItemList</li>
     * <li>FulfillmentShipmentPackage: FBAOutboundServiceMWS_Model_FulfillmentShipmentPackageList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'AmazonShipmentId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentCenterId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentShipmentStatus' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShippingDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EstimatedArrivalDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentShipmentItem' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentShipmentItemList'),
        'FulfillmentShipmentPackage' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentShipmentPackageList'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the AmazonShipmentId property.
     *
     * @return string AmazonShipmentId
     */
    public function getAmazonShipmentId()
    {
        return $this->_fields['AmazonShipmentId']['FieldValue'];
    }

    /**
     * Sets the value of the AmazonShipmentId property.
     *
     * @param string AmazonShipmentId
     * @return this instance
     */
    public function setAmazonShipmentId($value)
    {
        $this->_fields['AmazonShipmentId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AmazonShipmentId and returns this instance
     *
     * @param string $value AmazonShipmentId
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipment instance
     */
    public function withAmazonShipmentId($value)
    {
        $this->setAmazonShipmentId($value);
        return $this;
    }


    /**
     * Checks if AmazonShipmentId is set
     *
     * @return bool true if AmazonShipmentId  is set
     */
    public function isSetAmazonShipmentId()
    {
        return !is_null($this->_fields['AmazonShipmentId']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentCenterId property.
     *
     * @return string FulfillmentCenterId
     */
    public function getFulfillmentCenterId()
    {
        return $this->_fields['FulfillmentCenterId']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentCenterId property.
     *
     * @param string FulfillmentCenterId
     * @return this instance
     */
    public function setFulfillmentCenterId($value)
    {
        $this->_fields['FulfillmentCenterId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentCenterId and returns this instance
     *
     * @param string $value FulfillmentCenterId
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipment instance
     */
    public function withFulfillmentCenterId($value)
    {
        $this->setFulfillmentCenterId($value);
        return $this;
    }


    /**
     * Checks if FulfillmentCenterId is set
     *
     * @return bool true if FulfillmentCenterId  is set
     */
    public function isSetFulfillmentCenterId()
    {
        return !is_null($this->_fields['FulfillmentCenterId']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentShipmentStatus property.
     *
     * @return string FulfillmentShipmentStatus
     */
    public function getFulfillmentShipmentStatus()
    {
        return $this->_fields['FulfillmentShipmentStatus']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentShipmentStatus property.
     *
     * @param string FulfillmentShipmentStatus
     * @return this instance
     */
    public function setFulfillmentShipmentStatus($value)
    {
        $this->_fields['FulfillmentShipmentStatus']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the FulfillmentShipmentStatus and returns this instance
     *
     * @param string $value FulfillmentShipmentStatus
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipment instance
     */
    public function withFulfillmentShipmentStatus($value)
    {
        $this->setFulfillmentShipmentStatus($value);
        return $this;
    }


    /**
     * Checks if FulfillmentShipmentStatus is set
     *
     * @return bool true if FulfillmentShipmentStatus  is set
     */
    public function isSetFulfillmentShipmentStatus()
    {
        return !is_null($this->_fields['FulfillmentShipmentStatus']['FieldValue']);
    }

    /**
     * Gets the value of the ShippingDateTime property.
     *
     * @return string ShippingDateTime
     */
    public function getShippingDateTime()
    {
        return $this->_fields['ShippingDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingDateTime property.
     *
     * @param string ShippingDateTime
     * @return this instance
     */
    public function setShippingDateTime($value)
    {
        $this->_fields['ShippingDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShippingDateTime and returns this instance
     *
     * @param string $value ShippingDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipment instance
     */
    public function withShippingDateTime($value)
    {
        $this->setShippingDateTime($value);
        return $this;
    }


    /**
     * Checks if ShippingDateTime is set
     *
     * @return bool true if ShippingDateTime  is set
     */
    public function isSetShippingDateTime()
    {
        return !is_null($this->_fields['ShippingDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the EstimatedArrivalDateTime property.
     *
     * @return string EstimatedArrivalDateTime
     */
    public function getEstimatedArrivalDateTime()
    {
        return $this->_fields['EstimatedArrivalDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedArrivalDateTime property.
     *
     * @param string EstimatedArrivalDateTime
     * @return this instance
     */
    public function setEstimatedArrivalDateTime($value)
    {
        $this->_fields['EstimatedArrivalDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EstimatedArrivalDateTime and returns this instance
     *
     * @param string $value EstimatedArrivalDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipment instance
     */
    public function withEstimatedArrivalDateTime($value)
    {
        $this->setEstimatedArrivalDateTime($value);
        return $this;
    }


    /**
     * Checks if EstimatedArrivalDateTime is set
     *
     * @return bool true if EstimatedArrivalDateTime  is set
     */
    public function isSetEstimatedArrivalDateTime()
    {
        return !is_null($this->_fields['EstimatedArrivalDateTime']['FieldValue']);
    }

    /**
     * Gets the value of the FulfillmentShipmentItem.
     *
     * @return FulfillmentShipmentItemList FulfillmentShipmentItem
     */
    public function getFulfillmentShipmentItem()
    {
        return $this->_fields['FulfillmentShipmentItem']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentShipmentItem.
     *
     * @param FulfillmentShipmentItemList FulfillmentShipmentItem
     * @return void
     */
    public function setFulfillmentShipmentItem($value)
    {
        $this->_fields['FulfillmentShipmentItem']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentShipmentItem  and returns this instance
     *
     * @param FulfillmentShipmentItemList $value FulfillmentShipmentItem
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipment instance
     */
    public function withFulfillmentShipmentItem($value)
    {
        $this->setFulfillmentShipmentItem($value);
        return $this;
    }


    /**
     * Checks if FulfillmentShipmentItem  is set
     *
     * @return bool true if FulfillmentShipmentItem property is set
     */
    public function isSetFulfillmentShipmentItem()
    {
        return !is_null($this->_fields['FulfillmentShipmentItem']['FieldValue']);

    }

    /**
     * Gets the value of the FulfillmentShipmentPackage.
     *
     * @return FulfillmentShipmentPackageList FulfillmentShipmentPackage
     */
    public function getFulfillmentShipmentPackage()
    {
        return $this->_fields['FulfillmentShipmentPackage']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentShipmentPackage.
     *
     * @param FulfillmentShipmentPackageList FulfillmentShipmentPackage
     * @return void
     */
    public function setFulfillmentShipmentPackage($value)
    {
        $this->_fields['FulfillmentShipmentPackage']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentShipmentPackage  and returns this instance
     *
     * @param FulfillmentShipmentPackageList $value FulfillmentShipmentPackage
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipment instance
     */
    public function withFulfillmentShipmentPackage($value)
    {
        $this->setFulfillmentShipmentPackage($value);
        return $this;
    }


    /**
     * Checks if FulfillmentShipmentPackage  is set
     *
     * @return bool true if FulfillmentShipmentPackage property is set
     */
    public function isSetFulfillmentShipmentPackage()
    {
        return !is_null($this->_fields['FulfillmentShipmentPackage']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_FulfillmentShipmentItem extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentShipmentItem
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerSKU: string</li>
     * <li>SellerFulfillmentOrderItemId: string</li>
     * <li>Quantity: int</li>
     * <li>PackageNumber: int</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerFulfillmentOrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Quantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'PackageNumber' => array('FieldValue' => null, 'FieldType' => 'int'),
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
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentItem instance
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
     * Gets the value of the SellerFulfillmentOrderItemId property.
     *
     * @return string SellerFulfillmentOrderItemId
     */
    public function getSellerFulfillmentOrderItemId()
    {
        return $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId property.
     *
     * @param string SellerFulfillmentOrderItemId
     * @return this instance
     */
    public function setSellerFulfillmentOrderItemId($value)
    {
        $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderItemId
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentItem instance
     */
    public function withSellerFulfillmentOrderItemId($value)
    {
        $this->setSellerFulfillmentOrderItemId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderItemId is set
     *
     * @return bool true if SellerFulfillmentOrderItemId  is set
     */
    public function isSetSellerFulfillmentOrderItemId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderItemId']['FieldValue']);
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
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentItem instance
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
     * Gets the value of the PackageNumber property.
     *
     * @return int PackageNumber
     */
    public function getPackageNumber()
    {
        return $this->_fields['PackageNumber']['FieldValue'];
    }

    /**
     * Sets the value of the PackageNumber property.
     *
     * @param int PackageNumber
     * @return this instance
     */
    public function setPackageNumber($value)
    {
        $this->_fields['PackageNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the PackageNumber and returns this instance
     *
     * @param int $value PackageNumber
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentItem instance
     */
    public function withPackageNumber($value)
    {
        $this->setPackageNumber($value);
        return $this;
    }


    /**
     * Checks if PackageNumber is set
     *
     * @return bool true if PackageNumber  is set
     */
    public function isSetPackageNumber()
    {
        return !is_null($this->_fields['PackageNumber']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_FulfillmentShipmentItemList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentShipmentItemList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentShipmentItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentShipmentItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentShipmentItem member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentShipmentItem or an array of FulfillmentShipmentItem member
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
     * @param FulfillmentShipmentItem  $fulfillmentShipmentItemArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentItemList  instance
     */
    public function withmember($fulfillmentShipmentItemArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentShipmentList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentShipmentList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentShipment</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentShipment')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentShipment member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentShipment or an array of FulfillmentShipment member
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
     * @param FulfillmentShipment  $fulfillmentShipmentArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentList  instance
     */
    public function withmember($fulfillmentShipmentArgs)
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
class FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>PackageNumber: int</li>
     * <li>CarrierCode: string</li>
     * <li>TrackingNumber: string</li>
     * <li>EstimatedArrivalDateTime: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'PackageNumber' => array('FieldValue' => null, 'FieldType' => 'int'),
        'CarrierCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        'TrackingNumber' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EstimatedArrivalDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the PackageNumber property.
     *
     * @return int PackageNumber
     */
    public function getPackageNumber()
    {
        return $this->_fields['PackageNumber']['FieldValue'];
    }

    /**
     * Sets the value of the PackageNumber property.
     *
     * @param int PackageNumber
     * @return this instance
     */
    public function setPackageNumber($value)
    {
        $this->_fields['PackageNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the PackageNumber and returns this instance
     *
     * @param int $value PackageNumber
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage instance
     */
    public function withPackageNumber($value)
    {
        $this->setPackageNumber($value);
        return $this;
    }


    /**
     * Checks if PackageNumber is set
     *
     * @return bool true if PackageNumber  is set
     */
    public function isSetPackageNumber()
    {
        return !is_null($this->_fields['PackageNumber']['FieldValue']);
    }

    /**
     * Gets the value of the CarrierCode property.
     *
     * @return string CarrierCode
     */
    public function getCarrierCode()
    {
        return $this->_fields['CarrierCode']['FieldValue'];
    }

    /**
     * Sets the value of the CarrierCode property.
     *
     * @param string CarrierCode
     * @return this instance
     */
    public function setCarrierCode($value)
    {
        $this->_fields['CarrierCode']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CarrierCode and returns this instance
     *
     * @param string $value CarrierCode
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage instance
     */
    public function withCarrierCode($value)
    {
        $this->setCarrierCode($value);
        return $this;
    }


    /**
     * Checks if CarrierCode is set
     *
     * @return bool true if CarrierCode  is set
     */
    public function isSetCarrierCode()
    {
        return !is_null($this->_fields['CarrierCode']['FieldValue']);
    }

    /**
     * Gets the value of the TrackingNumber property.
     *
     * @return string TrackingNumber
     */
    public function getTrackingNumber()
    {
        return $this->_fields['TrackingNumber']['FieldValue'];
    }

    /**
     * Sets the value of the TrackingNumber property.
     *
     * @param string TrackingNumber
     * @return this instance
     */
    public function setTrackingNumber($value)
    {
        $this->_fields['TrackingNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the TrackingNumber and returns this instance
     *
     * @param string $value TrackingNumber
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage instance
     */
    public function withTrackingNumber($value)
    {
        $this->setTrackingNumber($value);
        return $this;
    }


    /**
     * Checks if TrackingNumber is set
     *
     * @return bool true if TrackingNumber  is set
     */
    public function isSetTrackingNumber()
    {
        return !is_null($this->_fields['TrackingNumber']['FieldValue']);
    }

    /**
     * Gets the value of the EstimatedArrivalDateTime property.
     *
     * @return string EstimatedArrivalDateTime
     */
    public function getEstimatedArrivalDateTime()
    {
        return $this->_fields['EstimatedArrivalDateTime']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedArrivalDateTime property.
     *
     * @param string EstimatedArrivalDateTime
     * @return this instance
     */
    public function setEstimatedArrivalDateTime($value)
    {
        $this->_fields['EstimatedArrivalDateTime']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EstimatedArrivalDateTime and returns this instance
     *
     * @param string $value EstimatedArrivalDateTime
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage instance
     */
    public function withEstimatedArrivalDateTime($value)
    {
        $this->setEstimatedArrivalDateTime($value);
        return $this;
    }


    /**
     * Checks if EstimatedArrivalDateTime is set
     *
     * @return bool true if EstimatedArrivalDateTime  is set
     */
    public function isSetEstimatedArrivalDateTime()
    {
        return !is_null($this->_fields['EstimatedArrivalDateTime']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_FulfillmentShipmentPackageList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_FulfillmentShipmentPackageList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_FulfillmentShipmentPackage')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of FulfillmentShipmentPackage member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed FulfillmentShipmentPackage or an array of FulfillmentShipmentPackage member
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
     * @param FulfillmentShipmentPackage  $fulfillmentShipmentPackageArgs one or more member
     * @return FBAOutboundServiceMWS_Model_FulfillmentShipmentPackageList  instance
     */
    public function withmember($fulfillmentShipmentPackageArgs)
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
class FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     * <li>SellerFulfillmentOrderId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SellerFulfillmentOrderId' => array('FieldValue' => null, 'FieldType' => 'string'),
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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest instance
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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest instance
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
     * Gets the value of the SellerFulfillmentOrderId property.
     *
     * @return string SellerFulfillmentOrderId
     */
    public function getSellerFulfillmentOrderId()
    {
        return $this->_fields['SellerFulfillmentOrderId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId property.
     *
     * @param string SellerFulfillmentOrderId
     * @return this instance
     */
    public function setSellerFulfillmentOrderId($value)
    {
        $this->_fields['SellerFulfillmentOrderId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderId
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderRequest instance
     */
    public function withSellerFulfillmentOrderId($value)
    {
        $this->setSellerFulfillmentOrderId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderId is set
     *
     * @return bool true if SellerFulfillmentOrderId  is set
     */
    public function isSetSellerFulfillmentOrderId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderId']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>GetFulfillmentOrderResult: FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult</li>
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'GetFulfillmentOrderResult' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:GetFulfillmentOrderResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse from provided XML.
                                  Make sure that GetFulfillmentOrderResponse is a root element");
        }

    }

    /**
     * Gets the value of the GetFulfillmentOrderResult.
     *
     * @return GetFulfillmentOrderResult GetFulfillmentOrderResult
     */
    public function getGetFulfillmentOrderResult()
    {
        return $this->_fields['GetFulfillmentOrderResult']['FieldValue'];
    }

    /**
     * Sets the value of the GetFulfillmentOrderResult.
     *
     * @param GetFulfillmentOrderResult GetFulfillmentOrderResult
     * @return void
     */
    public function setGetFulfillmentOrderResult($value)
    {
        $this->_fields['GetFulfillmentOrderResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the GetFulfillmentOrderResult  and returns this instance
     *
     * @param GetFulfillmentOrderResult $value GetFulfillmentOrderResult
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse instance
     */
    public function withGetFulfillmentOrderResult($value)
    {
        $this->setGetFulfillmentOrderResult($value);
        return $this;
    }


    /**
     * Checks if GetFulfillmentOrderResult  is set
     *
     * @return bool true if GetFulfillmentOrderResult property is set
     */
    public function isSetGetFulfillmentOrderResult()
    {
        return !is_null($this->_fields['GetFulfillmentOrderResult']['FieldValue']);

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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderResponse instance
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
        $xml .= "<GetFulfillmentOrderResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</GetFulfillmentOrderResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>FulfillmentOrder: FBAOutboundServiceMWS_Model_FulfillmentOrder</li>
     * <li>FulfillmentOrderItem: FBAOutboundServiceMWS_Model_FulfillmentOrderItemList</li>
     * <li>FulfillmentShipment: FBAOutboundServiceMWS_Model_FulfillmentShipmentList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'FulfillmentOrder' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentOrder'),
        'FulfillmentOrderItem' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentOrderItemList'),
        'FulfillmentShipment' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentShipmentList'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the FulfillmentOrder.
     *
     * @return FulfillmentOrder FulfillmentOrder
     */
    public function getFulfillmentOrder()
    {
        return $this->_fields['FulfillmentOrder']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentOrder.
     *
     * @param FulfillmentOrder FulfillmentOrder
     * @return void
     */
    public function setFulfillmentOrder($value)
    {
        $this->_fields['FulfillmentOrder']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentOrder  and returns this instance
     *
     * @param FulfillmentOrder $value FulfillmentOrder
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult instance
     */
    public function withFulfillmentOrder($value)
    {
        $this->setFulfillmentOrder($value);
        return $this;
    }


    /**
     * Checks if FulfillmentOrder  is set
     *
     * @return bool true if FulfillmentOrder property is set
     */
    public function isSetFulfillmentOrder()
    {
        return !is_null($this->_fields['FulfillmentOrder']['FieldValue']);

    }

    /**
     * Gets the value of the FulfillmentOrderItem.
     *
     * @return FulfillmentOrderItemList FulfillmentOrderItem
     */
    public function getFulfillmentOrderItem()
    {
        return $this->_fields['FulfillmentOrderItem']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentOrderItem.
     *
     * @param FulfillmentOrderItemList FulfillmentOrderItem
     * @return void
     */
    public function setFulfillmentOrderItem($value)
    {
        $this->_fields['FulfillmentOrderItem']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentOrderItem  and returns this instance
     *
     * @param FulfillmentOrderItemList $value FulfillmentOrderItem
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult instance
     */
    public function withFulfillmentOrderItem($value)
    {
        $this->setFulfillmentOrderItem($value);
        return $this;
    }


    /**
     * Checks if FulfillmentOrderItem  is set
     *
     * @return bool true if FulfillmentOrderItem property is set
     */
    public function isSetFulfillmentOrderItem()
    {
        return !is_null($this->_fields['FulfillmentOrderItem']['FieldValue']);

    }

    /**
     * Gets the value of the FulfillmentShipment.
     *
     * @return FulfillmentShipmentList FulfillmentShipment
     */
    public function getFulfillmentShipment()
    {
        return $this->_fields['FulfillmentShipment']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentShipment.
     *
     * @param FulfillmentShipmentList FulfillmentShipment
     * @return void
     */
    public function setFulfillmentShipment($value)
    {
        $this->_fields['FulfillmentShipment']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentShipment  and returns this instance
     *
     * @param FulfillmentShipmentList $value FulfillmentShipment
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentOrderResult instance
     */
    public function withFulfillmentShipment($value)
    {
        $this->setFulfillmentShipment($value);
        return $this;
    }


    /**
     * Checks if FulfillmentShipment  is set
     *
     * @return bool true if FulfillmentShipment property is set
     */
    public function isSetFulfillmentShipment()
    {
        return !is_null($this->_fields['FulfillmentShipment']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerSKU: string</li>
     * <li>Quantity: int</li>
     * <li>SellerFulfillmentOrderItemId: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Quantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'SellerFulfillmentOrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem instance
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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem instance
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
     * Gets the value of the SellerFulfillmentOrderItemId property.
     *
     * @return string SellerFulfillmentOrderItemId
     */
    public function getSellerFulfillmentOrderItemId()
    {
        return $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId property.
     *
     * @param string SellerFulfillmentOrderItemId
     * @return this instance
     */
    public function setSellerFulfillmentOrderItemId($value)
    {
        $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderItemId
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem instance
     */
    public function withSellerFulfillmentOrderItemId($value)
    {
        $this->setSellerFulfillmentOrderItemId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderItemId is set
     *
     * @return bool true if SellerFulfillmentOrderItemId  is set
     */
    public function isSetSellerFulfillmentOrderItemId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderItemId']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of GetFulfillmentPreviewItem member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed GetFulfillmentPreviewItem or an array of GetFulfillmentPreviewItem member
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
     * @param GetFulfillmentPreviewItem  $getFulfillmentPreviewItemArgs one or more member
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList  instance
     */
    public function withmember($getFulfillmentPreviewItemArgs)
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
class FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     * <li>IncludeCODFulfillmentPreview: bool</li>
     * <li>Address: FBAOutboundServiceMWS_Model_Address</li>
     * <li>Items: FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList</li>
     * <li>ShippingSpeedCategories: FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        'IncludeCODFulfillmentPreview' => array('FieldValue' => null, 'FieldType' => 'bool'),
        'Address' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_Address'),
        'Items' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_GetFulfillmentPreviewItemList'),
        'ShippingSpeedCategories' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList'),
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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest instance
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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest instance
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
     * Gets the value of the IncludeCODFulfillmentPreview property.
     *
     * @return bool IncludeCODFulfillmentPreview
     */
    public function getIncludeCODFulfillmentPreview()
    {
        return $this->_fields['IncludeCODFulfillmentPreview']['FieldValue'];
    }

    /**
     * Sets the value of the IncludeCODFulfillmentPreview property.
     *
     * @param bool IncludeCODFulfillmentPreview
     * @return this instance
     */
    public function setIncludeCODFulfillmentPreview($value)
    {
        $this->_fields['IncludeCODFulfillmentPreview']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the IncludeCODFulfillmentPreview and returns this instance
     *
     * @param string $value IncludeCODFulfillmentPreview
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest instance
     */
    public function withIncludeCODFulfillmentPreview($value)
    {
        $this->setIncludeCODFulfillmentPreview($value);
        return $this;
    }


    /**
     * Checks if IncludeCODFulfillmentPreview is set
     *
     * @return bool true if IncludeCODFulfillmentPreview  is set
     */
    public function isSetIncludeCODFulfillmentPreview()
    {
        return !is_null($this->_fields['IncludeCODFulfillmentPreview']['FieldValue']);
    }



    /**
     * Gets the value of the Address.
     *
     * @return Address Address
     */
    public function getAddress()
    {
        return $this->_fields['Address']['FieldValue'];
    }

    /**
     * Sets the value of the Address.
     *
     * @param Address Address
     * @return void
     */
    public function setAddress($value)
    {
        $this->_fields['Address']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Address  and returns this instance
     *
     * @param Address $value Address
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest instance
     */
    public function withAddress($value)
    {
        $this->setAddress($value);
        return $this;
    }


    /**
     * Checks if Address  is set
     *
     * @return bool true if Address property is set
     */
    public function isSetAddress()
    {
        return !is_null($this->_fields['Address']['FieldValue']);

    }

    /**
     * Gets the value of the Items.
     *
     * @return GetFulfillmentPreviewItemList Items
     */
    public function getItems()
    {
        return $this->_fields['Items']['FieldValue'];
    }

    /**
     * Sets the value of the Items.
     *
     * @param GetFulfillmentPreviewItemList Items
     * @return void
     */
    public function setItems($value)
    {
        $this->_fields['Items']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the Items  and returns this instance
     *
     * @param GetFulfillmentPreviewItemList $value Items
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest instance
     */
    public function withItems($value)
    {
        $this->setItems($value);
        return $this;
    }


    /**
     * Checks if Items  is set
     *
     * @return bool true if Items property is set
     */
    public function isSetItems()
    {
        return !is_null($this->_fields['Items']['FieldValue']);

    }

    /**
     * Gets the value of the ShippingSpeedCategories.
     *
     * @return ShippingSpeedCategoryList ShippingSpeedCategories
     */
    public function getShippingSpeedCategories()
    {
        return $this->_fields['ShippingSpeedCategories']['FieldValue'];
    }

    /**
     * Sets the value of the ShippingSpeedCategories.
     *
     * @param ShippingSpeedCategoryList ShippingSpeedCategories
     * @return void
     */
    public function setShippingSpeedCategories($value)
    {
        $this->_fields['ShippingSpeedCategories']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ShippingSpeedCategories  and returns this instance
     *
     * @param ShippingSpeedCategoryList $value ShippingSpeedCategories
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewRequest instance
     */
    public function withShippingSpeedCategories($value)
    {
        $this->setShippingSpeedCategories($value);
        return $this;
    }


    /**
     * Checks if ShippingSpeedCategories  is set
     *
     * @return bool true if ShippingSpeedCategories property is set
     */
    public function isSetShippingSpeedCategories()
    {
        return !is_null($this->_fields['ShippingSpeedCategories']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>GetFulfillmentPreviewResult: FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResult</li>
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'GetFulfillmentPreviewResult' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:GetFulfillmentPreviewResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse from provided XML.
                                  Make sure that GetFulfillmentPreviewResponse is a root element");
        }

    }

    /**
     * Gets the value of the GetFulfillmentPreviewResult.
     *
     * @return GetFulfillmentPreviewResult GetFulfillmentPreviewResult
     */
    public function getGetFulfillmentPreviewResult()
    {
        return $this->_fields['GetFulfillmentPreviewResult']['FieldValue'];
    }

    /**
     * Sets the value of the GetFulfillmentPreviewResult.
     *
     * @param GetFulfillmentPreviewResult GetFulfillmentPreviewResult
     * @return void
     */
    public function setGetFulfillmentPreviewResult($value)
    {
        $this->_fields['GetFulfillmentPreviewResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the GetFulfillmentPreviewResult  and returns this instance
     *
     * @param GetFulfillmentPreviewResult $value GetFulfillmentPreviewResult
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse instance
     */
    public function withGetFulfillmentPreviewResult($value)
    {
        $this->setGetFulfillmentPreviewResult($value);
        return $this;
    }


    /**
     * Checks if GetFulfillmentPreviewResult  is set
     *
     * @return bool true if GetFulfillmentPreviewResult property is set
     */
    public function isSetGetFulfillmentPreviewResult()
    {
        return !is_null($this->_fields['GetFulfillmentPreviewResult']['FieldValue']);

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
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResponse instance
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
        $xml .= "<GetFulfillmentPreviewResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</GetFulfillmentPreviewResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResult extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>FulfillmentPreviews: FBAOutboundServiceMWS_Model_FulfillmentPreviewList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'FulfillmentPreviews' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentPreviewList'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the FulfillmentPreviews.
     *
     * @return FulfillmentPreviewList FulfillmentPreviews
     */
    public function getFulfillmentPreviews()
    {
        return $this->_fields['FulfillmentPreviews']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentPreviews.
     *
     * @param FulfillmentPreviewList FulfillmentPreviews
     * @return void
     */
    public function setFulfillmentPreviews($value)
    {
        $this->_fields['FulfillmentPreviews']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentPreviews  and returns this instance
     *
     * @param FulfillmentPreviewList $value FulfillmentPreviews
     * @return FBAOutboundServiceMWS_Model_GetFulfillmentPreviewResult instance
     */
    public function withFulfillmentPreviews($value)
    {
        $this->setFulfillmentPreviews($value);
        return $this;
    }


    /**
     * Checks if FulfillmentPreviews  is set
     *
     * @return bool true if FulfillmentPreviews property is set
     */
    public function isSetFulfillmentPreviews()
    {
        return !is_null($this->_fields['FulfillmentPreviews']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsRequest extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>PackageNumber: int</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'PackageNumber' => array('FieldValue' => null, 'FieldType' => 'int'),
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
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsRequest instance
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
     * Gets the value of the PackageNumber property.
     *
     * @return int PackageNumber
     */
    public function getPackageNumber()
    {
        return $this->_fields['PackageNumber']['FieldValue'];
    }

    /**
     * Sets the value of the PackageNumber property.
     *
     * @param int PackageNumber
     * @return this instance
     */
    public function setPackageNumber($value)
    {
        $this->_fields['PackageNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the PackageNumber and returns this instance
     *
     * @param int $value PackageNumber
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsRequest instance
     */
    public function withPackageNumber($value)
    {
        $this->setPackageNumber($value);
        return $this;
    }


    /**
     * Checks if PackageNumber is set
     *
     * @return bool true if PackageNumber  is set
     */
    public function isSetPackageNumber()
    {
        return !is_null($this->_fields['PackageNumber']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>GetPackageTrackingDetailsResult: FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult</li>
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'GetPackageTrackingDetailsResult' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:GetPackageTrackingDetailsResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse from provided XML.
                                  Make sure that GetPackageTrackingDetailsResponse is a root element");
        }

    }

    /**
     * Gets the value of the GetPackageTrackingDetailsResult.
     *
     * @return GetPackageTrackingDetailsResult GetPackageTrackingDetailsResult
     */
    public function getGetPackageTrackingDetailsResult()
    {
        return $this->_fields['GetPackageTrackingDetailsResult']['FieldValue'];
    }

    /**
     * Sets the value of the GetPackageTrackingDetailsResult.
     *
     * @param GetPackageTrackingDetailsResult GetPackageTrackingDetailsResult
     * @return void
     */
    public function setGetPackageTrackingDetailsResult($value)
    {
        $this->_fields['GetPackageTrackingDetailsResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the GetPackageTrackingDetailsResult  and returns this instance
     *
     * @param GetPackageTrackingDetailsResult $value GetPackageTrackingDetailsResult
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse instance
     */
    public function withGetPackageTrackingDetailsResult($value)
    {
        $this->setGetPackageTrackingDetailsResult($value);
        return $this;
    }


    /**
     * Checks if GetPackageTrackingDetailsResult  is set
     *
     * @return bool true if GetPackageTrackingDetailsResult property is set
     */
    public function isSetGetPackageTrackingDetailsResult()
    {
        return !is_null($this->_fields['GetPackageTrackingDetailsResult']['FieldValue']);

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
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResponse instance
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
        $xml .= "<GetPackageTrackingDetailsResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</GetPackageTrackingDetailsResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>PackageNumber: int</li>
     * <li>TrackingNumber: string</li>
     * <li>CarrierCode: string</li>
     * <li>CarrierPhoneNumber: string</li>
     * <li>CarrierURL: string</li>
     * <li>ShipDate: string</li>
     * <li>EstimatedArrivalDate: string</li>
     * <li>ShipToAddress: FBAOutboundServiceMWS_Model_TrackingAddress</li>
     * <li>CurrentStatus: string</li>
     * <li>SignedForBy: string</li>
     * <li>AdditionalLocationInfo: string</li>
     * <li>TrackingEvents: FBAOutboundServiceMWS_Model_TrackingEventList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'PackageNumber' => array('FieldValue' => null, 'FieldType' => 'int'),
        'TrackingNumber' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CarrierCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CarrierPhoneNumber' => array('FieldValue' => null, 'FieldType' => 'string'),
        'CarrierURL' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShipDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EstimatedArrivalDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ShipToAddress' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_TrackingAddress'),
        'CurrentStatus' => array('FieldValue' => null, 'FieldType' => 'string'),
        'SignedForBy' => array('FieldValue' => null, 'FieldType' => 'string'),
        'AdditionalLocationInfo' => array('FieldValue' => null, 'FieldType' => 'string'),
        'TrackingEvents' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_TrackingEventList'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the PackageNumber property.
     *
     * @return int PackageNumber
     */
    public function getPackageNumber()
    {
        return $this->_fields['PackageNumber']['FieldValue'];
    }

    /**
     * Sets the value of the PackageNumber property.
     *
     * @param int PackageNumber
     * @return this instance
     */
    public function setPackageNumber($value)
    {
        $this->_fields['PackageNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the PackageNumber and returns this instance
     *
     * @param int $value PackageNumber
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withPackageNumber($value)
    {
        $this->setPackageNumber($value);
        return $this;
    }


    /**
     * Checks if PackageNumber is set
     *
     * @return bool true if PackageNumber  is set
     */
    public function isSetPackageNumber()
    {
        return !is_null($this->_fields['PackageNumber']['FieldValue']);
    }

    /**
     * Gets the value of the TrackingNumber property.
     *
     * @return string TrackingNumber
     */
    public function getTrackingNumber()
    {
        return $this->_fields['TrackingNumber']['FieldValue'];
    }

    /**
     * Sets the value of the TrackingNumber property.
     *
     * @param string TrackingNumber
     * @return this instance
     */
    public function setTrackingNumber($value)
    {
        $this->_fields['TrackingNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the TrackingNumber and returns this instance
     *
     * @param string $value TrackingNumber
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withTrackingNumber($value)
    {
        $this->setTrackingNumber($value);
        return $this;
    }


    /**
     * Checks if TrackingNumber is set
     *
     * @return bool true if TrackingNumber  is set
     */
    public function isSetTrackingNumber()
    {
        return !is_null($this->_fields['TrackingNumber']['FieldValue']);
    }

    /**
     * Gets the value of the CarrierCode property.
     *
     * @return string CarrierCode
     */
    public function getCarrierCode()
    {
        return $this->_fields['CarrierCode']['FieldValue'];
    }

    /**
     * Sets the value of the CarrierCode property.
     *
     * @param string CarrierCode
     * @return this instance
     */
    public function setCarrierCode($value)
    {
        $this->_fields['CarrierCode']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CarrierCode and returns this instance
     *
     * @param string $value CarrierCode
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withCarrierCode($value)
    {
        $this->setCarrierCode($value);
        return $this;
    }


    /**
     * Checks if CarrierCode is set
     *
     * @return bool true if CarrierCode  is set
     */
    public function isSetCarrierCode()
    {
        return !is_null($this->_fields['CarrierCode']['FieldValue']);
    }

    /**
     * Gets the value of the CarrierPhoneNumber property.
     *
     * @return string CarrierPhoneNumber
     */
    public function getCarrierPhoneNumber()
    {
        return $this->_fields['CarrierPhoneNumber']['FieldValue'];
    }

    /**
     * Sets the value of the CarrierPhoneNumber property.
     *
     * @param string CarrierPhoneNumber
     * @return this instance
     */
    public function setCarrierPhoneNumber($value)
    {
        $this->_fields['CarrierPhoneNumber']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CarrierPhoneNumber and returns this instance
     *
     * @param string $value CarrierPhoneNumber
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withCarrierPhoneNumber($value)
    {
        $this->setCarrierPhoneNumber($value);
        return $this;
    }


    /**
     * Checks if CarrierPhoneNumber is set
     *
     * @return bool true if CarrierPhoneNumber  is set
     */
    public function isSetCarrierPhoneNumber()
    {
        return !is_null($this->_fields['CarrierPhoneNumber']['FieldValue']);
    }

    /**
     * Gets the value of the CarrierURL property.
     *
     * @return string CarrierURL
     */
    public function getCarrierURL()
    {
        return $this->_fields['CarrierURL']['FieldValue'];
    }

    /**
     * Sets the value of the CarrierURL property.
     *
     * @param string CarrierURL
     * @return this instance
     */
    public function setCarrierURL($value)
    {
        $this->_fields['CarrierURL']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CarrierURL and returns this instance
     *
     * @param string $value CarrierURL
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withCarrierURL($value)
    {
        $this->setCarrierURL($value);
        return $this;
    }


    /**
     * Checks if CarrierURL is set
     *
     * @return bool true if CarrierURL  is set
     */
    public function isSetCarrierURL()
    {
        return !is_null($this->_fields['CarrierURL']['FieldValue']);
    }

    /**
     * Gets the value of the ShipDate property.
     *
     * @return string ShipDate
     */
    public function getShipDate()
    {
        return $this->_fields['ShipDate']['FieldValue'];
    }

    /**
     * Sets the value of the ShipDate property.
     *
     * @param string ShipDate
     * @return this instance
     */
    public function setShipDate($value)
    {
        $this->_fields['ShipDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the ShipDate and returns this instance
     *
     * @param string $value ShipDate
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withShipDate($value)
    {
        $this->setShipDate($value);
        return $this;
    }


    /**
     * Checks if ShipDate is set
     *
     * @return bool true if ShipDate  is set
     */
    public function isSetShipDate()
    {
        return !is_null($this->_fields['ShipDate']['FieldValue']);
    }

    /**
     * Gets the value of the EstimatedArrivalDate property.
     *
     * @return string EstimatedArrivalDate
     */
    public function getEstimatedArrivalDate()
    {
        return $this->_fields['EstimatedArrivalDate']['FieldValue'];
    }

    /**
     * Sets the value of the EstimatedArrivalDate property.
     *
     * @param string EstimatedArrivalDate
     * @return this instance
     */
    public function setEstimatedArrivalDate($value)
    {
        $this->_fields['EstimatedArrivalDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EstimatedArrivalDate and returns this instance
     *
     * @param string $value EstimatedArrivalDate
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withEstimatedArrivalDate($value)
    {
        $this->setEstimatedArrivalDate($value);
        return $this;
    }


    /**
     * Checks if EstimatedArrivalDate is set
     *
     * @return bool true if EstimatedArrivalDate  is set
     */
    public function isSetEstimatedArrivalDate()
    {
        return !is_null($this->_fields['EstimatedArrivalDate']['FieldValue']);
    }

    /**
     * Gets the value of the ShipToAddress.
     *
     * @return TrackingAddress ShipToAddress
     */
    public function getShipToAddress()
    {
        return $this->_fields['ShipToAddress']['FieldValue'];
    }

    /**
     * Sets the value of the ShipToAddress.
     *
     * @param TrackingAddress ShipToAddress
     * @return void
     */
    public function setShipToAddress($value)
    {
        $this->_fields['ShipToAddress']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ShipToAddress  and returns this instance
     *
     * @param TrackingAddress $value ShipToAddress
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withShipToAddress($value)
    {
        $this->setShipToAddress($value);
        return $this;
    }


    /**
     * Checks if ShipToAddress  is set
     *
     * @return bool true if ShipToAddress property is set
     */
    public function isSetShipToAddress()
    {
        return !is_null($this->_fields['ShipToAddress']['FieldValue']);

    }

    /**
     * Gets the value of the CurrentStatus property.
     *
     * @return string CurrentStatus
     */
    public function getCurrentStatus()
    {
        return $this->_fields['CurrentStatus']['FieldValue'];
    }

    /**
     * Sets the value of the CurrentStatus property.
     *
     * @param string CurrentStatus
     * @return this instance
     */
    public function setCurrentStatus($value)
    {
        $this->_fields['CurrentStatus']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CurrentStatus and returns this instance
     *
     * @param string $value CurrentStatus
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withCurrentStatus($value)
    {
        $this->setCurrentStatus($value);
        return $this;
    }


    /**
     * Checks if CurrentStatus is set
     *
     * @return bool true if CurrentStatus  is set
     */
    public function isSetCurrentStatus()
    {
        return !is_null($this->_fields['CurrentStatus']['FieldValue']);
    }

    /**
     * Gets the value of the SignedForBy property.
     *
     * @return string SignedForBy
     */
    public function getSignedForBy()
    {
        return $this->_fields['SignedForBy']['FieldValue'];
    }

    /**
     * Sets the value of the SignedForBy property.
     *
     * @param string SignedForBy
     * @return this instance
     */
    public function setSignedForBy($value)
    {
        $this->_fields['SignedForBy']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SignedForBy and returns this instance
     *
     * @param string $value SignedForBy
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withSignedForBy($value)
    {
        $this->setSignedForBy($value);
        return $this;
    }


    /**
     * Checks if SignedForBy is set
     *
     * @return bool true if SignedForBy  is set
     */
    public function isSetSignedForBy()
    {
        return !is_null($this->_fields['SignedForBy']['FieldValue']);
    }

    /**
     * Gets the value of the AdditionalLocationInfo property.
     *
     * @return string AdditionalLocationInfo
     */
    public function getAdditionalLocationInfo()
    {
        return $this->_fields['AdditionalLocationInfo']['FieldValue'];
    }

    /**
     * Sets the value of the AdditionalLocationInfo property.
     *
     * @param string AdditionalLocationInfo
     * @return this instance
     */
    public function setAdditionalLocationInfo($value)
    {
        $this->_fields['AdditionalLocationInfo']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the AdditionalLocationInfo and returns this instance
     *
     * @param string $value AdditionalLocationInfo
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withAdditionalLocationInfo($value)
    {
        $this->setAdditionalLocationInfo($value);
        return $this;
    }


    /**
     * Checks if AdditionalLocationInfo is set
     *
     * @return bool true if AdditionalLocationInfo  is set
     */
    public function isSetAdditionalLocationInfo()
    {
        return !is_null($this->_fields['AdditionalLocationInfo']['FieldValue']);
    }

    /**
     * Gets the value of the TrackingEvents.
     *
     * @return TrackingEventList TrackingEvents
     */
    public function getTrackingEvents()
    {
        return $this->_fields['TrackingEvents']['FieldValue'];
    }

    /**
     * Sets the value of the TrackingEvents.
     *
     * @param TrackingEventList TrackingEvents
     * @return void
     */
    public function setTrackingEvents($value)
    {
        $this->_fields['TrackingEvents']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the TrackingEvents  and returns this instance
     *
     * @param TrackingEventList $value TrackingEvents
     * @return FBAOutboundServiceMWS_Model_GetPackageTrackingDetailsResult instance
     */
    public function withTrackingEvents($value)
    {
        $this->setTrackingEvents($value);
        return $this;
    }


    /**
     * Checks if TrackingEvents  is set
     *
     * @return bool true if TrackingEvents property is set
     */
    public function isSetTrackingEvents()
    {
        return !is_null($this->_fields['TrackingEvents']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_GetServiceStatusRequest extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetServiceStatusRequest
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
     * @return FBAOutboundServiceMWS_Model_GetServiceStatusRequest instance
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
     * @return FBAOutboundServiceMWS_Model_GetServiceStatusRequest instance
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
class FBAOutboundServiceMWS_Model_GetServiceStatusResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetServiceStatusResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>GetServiceStatusResult: FBAOutboundServiceMWS_Model_GetServiceStatusResult</li>
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'GetServiceStatusResult' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_GetServiceStatusResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_GetServiceStatusResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_GetServiceStatusResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:GetServiceStatusResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_GetServiceStatusResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_GetServiceStatusResponse from provided XML.
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
     * @return FBAOutboundServiceMWS_Model_GetServiceStatusResponse instance
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
     * @return FBAOutboundServiceMWS_Model_GetServiceStatusResponse instance
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
        $xml .= "<GetServiceStatusResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</GetServiceStatusResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_GetServiceStatusResult extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_GetServiceStatusResult
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
     * @return FBAOutboundServiceMWS_Model_GetServiceStatusResult instance
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
     * @return FBAOutboundServiceMWS_Model_GetServiceStatusResult instance
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
class FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenRequest extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenRequest
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenRequest instance
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenRequest instance
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenRequest instance
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
class FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>ListAllFulfillmentOrdersByNextTokenResult: FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResult</li>
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ListAllFulfillmentOrdersByNextTokenResult' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:ListAllFulfillmentOrdersByNextTokenResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse from provided XML.
                                  Make sure that ListAllFulfillmentOrdersByNextTokenResponse is a root element");
        }

    }

    /**
     * Gets the value of the ListAllFulfillmentOrdersByNextTokenResult.
     *
     * @return ListAllFulfillmentOrdersByNextTokenResult ListAllFulfillmentOrdersByNextTokenResult
     */
    public function getListAllFulfillmentOrdersByNextTokenResult()
    {
        return $this->_fields['ListAllFulfillmentOrdersByNextTokenResult']['FieldValue'];
    }

    /**
     * Sets the value of the ListAllFulfillmentOrdersByNextTokenResult.
     *
     * @param ListAllFulfillmentOrdersByNextTokenResult ListAllFulfillmentOrdersByNextTokenResult
     * @return void
     */
    public function setListAllFulfillmentOrdersByNextTokenResult($value)
    {
        $this->_fields['ListAllFulfillmentOrdersByNextTokenResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ListAllFulfillmentOrdersByNextTokenResult  and returns this instance
     *
     * @param ListAllFulfillmentOrdersByNextTokenResult $value ListAllFulfillmentOrdersByNextTokenResult
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse instance
     */
    public function withListAllFulfillmentOrdersByNextTokenResult($value)
    {
        $this->setListAllFulfillmentOrdersByNextTokenResult($value);
        return $this;
    }


    /**
     * Checks if ListAllFulfillmentOrdersByNextTokenResult  is set
     *
     * @return bool true if ListAllFulfillmentOrdersByNextTokenResult property is set
     */
    public function isSetListAllFulfillmentOrdersByNextTokenResult()
    {
        return !is_null($this->_fields['ListAllFulfillmentOrdersByNextTokenResult']['FieldValue']);

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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResponse instance
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
        $xml .= "<ListAllFulfillmentOrdersByNextTokenResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ListAllFulfillmentOrdersByNextTokenResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResult extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>NextToken: string</li>
     * <li>FulfillmentOrders: FBAOutboundServiceMWS_Model_FulfillmentOrderList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentOrders' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentOrderList'),
        );
        parent::__construct($data);
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResult instance
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

    /**
     * Gets the value of the FulfillmentOrders.
     *
     * @return FulfillmentOrderList FulfillmentOrders
     */
    public function getFulfillmentOrders()
    {
        return $this->_fields['FulfillmentOrders']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentOrders.
     *
     * @param FulfillmentOrderList FulfillmentOrders
     * @return void
     */
    public function setFulfillmentOrders($value)
    {
        $this->_fields['FulfillmentOrders']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentOrders  and returns this instance
     *
     * @param FulfillmentOrderList $value FulfillmentOrders
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersByNextTokenResult instance
     */
    public function withFulfillmentOrders($value)
    {
        $this->setFulfillmentOrders($value);
        return $this;
    }


    /**
     * Checks if FulfillmentOrders  is set
     *
     * @return bool true if FulfillmentOrders property is set
     */
    public function isSetFulfillmentOrders()
    {
        return !is_null($this->_fields['FulfillmentOrders']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerId: string</li>
     * <li>Marketplace: string</li>
     * <li>QueryStartDateTime: string</li>
     * <li>FulfillmentMethod: FBAOutboundServiceMWS_Model_FulfillmentMethodList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Marketplace' => array('FieldValue' => null, 'FieldType' => 'string'),
        'QueryStartDateTime' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentMethod' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentMethodList'),
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest instance
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest instance
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest instance
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
     * Gets the value of the FulfillmentMethod.
     *
     * @return FulfillmentMethodList FulfillmentMethod
     */
    public function getFulfillmentMethod()
    {
        return $this->_fields['FulfillmentMethod']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentMethod.
     *
     * @param FulfillmentMethodList FulfillmentMethod
     * @return void
     */
    public function setFulfillmentMethod($value)
    {
        $this->_fields['FulfillmentMethod']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentMethod  and returns this instance
     *
     * @param FulfillmentMethodList $value FulfillmentMethod
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersRequest instance
     */
    public function withFulfillmentMethod($value)
    {
        $this->setFulfillmentMethod($value);
        return $this;
    }


    /**
     * Checks if FulfillmentMethod  is set
     *
     * @return bool true if FulfillmentMethod property is set
     */
    public function isSetFulfillmentMethod()
    {
        return !is_null($this->_fields['FulfillmentMethod']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>ListAllFulfillmentOrdersResult: FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResult</li>
     * <li>ResponseMetadata: FBAOutboundServiceMWS_Model_ResponseMetadata</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'ListAllFulfillmentOrdersResult' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResult'),
        'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_ResponseMetadata'),
        );
        parent::__construct($data);
    }


    /**
     * Construct FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse from XML string
     *
     * @param string $xml XML string to construct from
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse
     */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
    	$xpath->registerNamespace('a', 'http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/');
        $response = $xpath->query('//a:ListAllFulfillmentOrdersResponse');
        if ($response->length == 1) {
            return new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse from provided XML.
                                  Make sure that ListAllFulfillmentOrdersResponse is a root element");
        }

    }

    /**
     * Gets the value of the ListAllFulfillmentOrdersResult.
     *
     * @return ListAllFulfillmentOrdersResult ListAllFulfillmentOrdersResult
     */
    public function getListAllFulfillmentOrdersResult()
    {
        return $this->_fields['ListAllFulfillmentOrdersResult']['FieldValue'];
    }

    /**
     * Sets the value of the ListAllFulfillmentOrdersResult.
     *
     * @param ListAllFulfillmentOrdersResult ListAllFulfillmentOrdersResult
     * @return void
     */
    public function setListAllFulfillmentOrdersResult($value)
    {
        $this->_fields['ListAllFulfillmentOrdersResult']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ListAllFulfillmentOrdersResult  and returns this instance
     *
     * @param ListAllFulfillmentOrdersResult $value ListAllFulfillmentOrdersResult
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse instance
     */
    public function withListAllFulfillmentOrdersResult($value)
    {
        $this->setListAllFulfillmentOrdersResult($value);
        return $this;
    }


    /**
     * Checks if ListAllFulfillmentOrdersResult  is set
     *
     * @return bool true if ListAllFulfillmentOrdersResult property is set
     */
    public function isSetListAllFulfillmentOrdersResult()
    {
        return !is_null($this->_fields['ListAllFulfillmentOrdersResult']['FieldValue']);

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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResponse instance
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
        $xml .= "<ListAllFulfillmentOrdersResponse xmlns=\"http://mws.amazonaws.com/FulfillmentOutboundShipment/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ListAllFulfillmentOrdersResponse>";
        return $xml;
    }

}
class FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResult extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResult
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>NextToken: string</li>
     * <li>FulfillmentOrders: FBAOutboundServiceMWS_Model_FulfillmentOrderList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'NextToken' => array('FieldValue' => null, 'FieldType' => 'string'),
        'FulfillmentOrders' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_FulfillmentOrderList'),
        );
        parent::__construct($data);
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
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResult instance
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

    /**
     * Gets the value of the FulfillmentOrders.
     *
     * @return FulfillmentOrderList FulfillmentOrders
     */
    public function getFulfillmentOrders()
    {
        return $this->_fields['FulfillmentOrders']['FieldValue'];
    }

    /**
     * Sets the value of the FulfillmentOrders.
     *
     * @param FulfillmentOrderList FulfillmentOrders
     * @return void
     */
    public function setFulfillmentOrders($value)
    {
        $this->_fields['FulfillmentOrders']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the FulfillmentOrders  and returns this instance
     *
     * @param FulfillmentOrderList $value FulfillmentOrders
     * @return FBAOutboundServiceMWS_Model_ListAllFulfillmentOrdersResult instance
     */
    public function withFulfillmentOrders($value)
    {
        $this->setFulfillmentOrders($value);
        return $this;
    }


    /**
     * Checks if FulfillmentOrders  is set
     *
     * @return bool true if FulfillmentOrders property is set
     */
    public function isSetFulfillmentOrders()
    {
        return !is_null($this->_fields['FulfillmentOrders']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_NotificationEmailList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_NotificationEmailList
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
     * @return FBAOutboundServiceMWS_Model_NotificationEmailList  instance
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
class FBAOutboundServiceMWS_Model_ResponseMetadata extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ResponseMetadata
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
     * @return FBAOutboundServiceMWS_Model_ResponseMetadata instance
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
class FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList
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
     * @return FBAOutboundServiceMWS_Model_ShippingSpeedCategoryList  instance
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
class FBAOutboundServiceMWS_Model_StringList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_StringList
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
     * @return FBAOutboundServiceMWS_Model_StringList  instance
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
class FBAOutboundServiceMWS_Model_TrackingAddress extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_TrackingAddress
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>City: string</li>
     * <li>State: string</li>
     * <li>Country: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'City' => array('FieldValue' => null, 'FieldType' => 'string'),
        'State' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Country' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the City property.
     *
     * @return string City
     */
    public function getCity()
    {
        return $this->_fields['City']['FieldValue'];
    }

    /**
     * Sets the value of the City property.
     *
     * @param string City
     * @return this instance
     */
    public function setCity($value)
    {
        $this->_fields['City']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the City and returns this instance
     *
     * @param string $value City
     * @return FBAOutboundServiceMWS_Model_TrackingAddress instance
     */
    public function withCity($value)
    {
        $this->setCity($value);
        return $this;
    }


    /**
     * Checks if City is set
     *
     * @return bool true if City  is set
     */
    public function isSetCity()
    {
        return !is_null($this->_fields['City']['FieldValue']);
    }

    /**
     * Gets the value of the State property.
     *
     * @return string State
     */
    public function getState()
    {
        return $this->_fields['State']['FieldValue'];
    }

    /**
     * Sets the value of the State property.
     *
     * @param string State
     * @return this instance
     */
    public function setState($value)
    {
        $this->_fields['State']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the State and returns this instance
     *
     * @param string $value State
     * @return FBAOutboundServiceMWS_Model_TrackingAddress instance
     */
    public function withState($value)
    {
        $this->setState($value);
        return $this;
    }


    /**
     * Checks if State is set
     *
     * @return bool true if State  is set
     */
    public function isSetState()
    {
        return !is_null($this->_fields['State']['FieldValue']);
    }

    /**
     * Gets the value of the Country property.
     *
     * @return string Country
     */
    public function getCountry()
    {
        return $this->_fields['Country']['FieldValue'];
    }

    /**
     * Sets the value of the Country property.
     *
     * @param string Country
     * @return this instance
     */
    public function setCountry($value)
    {
        $this->_fields['Country']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Country and returns this instance
     *
     * @param string $value Country
     * @return FBAOutboundServiceMWS_Model_TrackingAddress instance
     */
    public function withCountry($value)
    {
        $this->setCountry($value);
        return $this;
    }


    /**
     * Checks if Country is set
     *
     * @return bool true if Country  is set
     */
    public function isSetCountry()
    {
        return !is_null($this->_fields['Country']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_TrackingEvent extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_TrackingEvent
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>EventDate: string</li>
     * <li>EventAddress: FBAOutboundServiceMWS_Model_TrackingAddress</li>
     * <li>EventCode: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'EventDate' => array('FieldValue' => null, 'FieldType' => 'string'),
        'EventAddress' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_TrackingAddress'),
        'EventCode' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the EventDate property.
     *
     * @return string EventDate
     */
    public function getEventDate()
    {
        return $this->_fields['EventDate']['FieldValue'];
    }

    /**
     * Sets the value of the EventDate property.
     *
     * @param string EventDate
     * @return this instance
     */
    public function setEventDate($value)
    {
        $this->_fields['EventDate']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EventDate and returns this instance
     *
     * @param string $value EventDate
     * @return FBAOutboundServiceMWS_Model_TrackingEvent instance
     */
    public function withEventDate($value)
    {
        $this->setEventDate($value);
        return $this;
    }


    /**
     * Checks if EventDate is set
     *
     * @return bool true if EventDate  is set
     */
    public function isSetEventDate()
    {
        return !is_null($this->_fields['EventDate']['FieldValue']);
    }

    /**
     * Gets the value of the EventAddress.
     *
     * @return TrackingAddress EventAddress
     */
    public function getEventAddress()
    {
        return $this->_fields['EventAddress']['FieldValue'];
    }

    /**
     * Sets the value of the EventAddress.
     *
     * @param TrackingAddress EventAddress
     * @return void
     */
    public function setEventAddress($value)
    {
        $this->_fields['EventAddress']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the EventAddress  and returns this instance
     *
     * @param TrackingAddress $value EventAddress
     * @return FBAOutboundServiceMWS_Model_TrackingEvent instance
     */
    public function withEventAddress($value)
    {
        $this->setEventAddress($value);
        return $this;
    }


    /**
     * Checks if EventAddress  is set
     *
     * @return bool true if EventAddress property is set
     */
    public function isSetEventAddress()
    {
        return !is_null($this->_fields['EventAddress']['FieldValue']);

    }

    /**
     * Gets the value of the EventCode property.
     *
     * @return string EventCode
     */
    public function getEventCode()
    {
        return $this->_fields['EventCode']['FieldValue'];
    }

    /**
     * Sets the value of the EventCode property.
     *
     * @param string EventCode
     * @return this instance
     */
    public function setEventCode($value)
    {
        $this->_fields['EventCode']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the EventCode and returns this instance
     *
     * @param string $value EventCode
     * @return FBAOutboundServiceMWS_Model_TrackingEvent instance
     */
    public function withEventCode($value)
    {
        $this->setEventCode($value);
        return $this;
    }


    /**
     * Checks if EventCode is set
     *
     * @return bool true if EventCode  is set
     */
    public function isSetEventCode()
    {
        return !is_null($this->_fields['EventCode']['FieldValue']);
    }




}
class FBAOutboundServiceMWS_Model_TrackingEventList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_TrackingEventList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_TrackingEvent</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_TrackingEvent')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of TrackingEvent member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed TrackingEvent or an array of TrackingEvent member
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
     * @param TrackingEvent  $trackingEventArgs one or more member
     * @return FBAOutboundServiceMWS_Model_TrackingEventList  instance
     */
    public function withmember($trackingEventArgs)
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
class FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>SellerSKU: string</li>
     * <li>Quantity: int</li>
     * <li>SellerFulfillmentOrderItemId: string</li>
     * <li>ItemUnfulfillableReasons: FBAOutboundServiceMWS_Model_StringList</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SellerSKU' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Quantity' => array('FieldValue' => null, 'FieldType' => 'int'),
        'SellerFulfillmentOrderItemId' => array('FieldValue' => null, 'FieldType' => 'string'),
        'ItemUnfulfillableReasons' => array('FieldValue' => null, 'FieldType' => 'FBAOutboundServiceMWS_Model_StringList'),
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
     * @return FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem instance
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
     * @return FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem instance
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
     * Gets the value of the SellerFulfillmentOrderItemId property.
     *
     * @return string SellerFulfillmentOrderItemId
     */
    public function getSellerFulfillmentOrderItemId()
    {
        return $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'];
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId property.
     *
     * @param string SellerFulfillmentOrderItemId
     * @return this instance
     */
    public function setSellerFulfillmentOrderItemId($value)
    {
        $this->_fields['SellerFulfillmentOrderItemId']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SellerFulfillmentOrderItemId and returns this instance
     *
     * @param string $value SellerFulfillmentOrderItemId
     * @return FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem instance
     */
    public function withSellerFulfillmentOrderItemId($value)
    {
        $this->setSellerFulfillmentOrderItemId($value);
        return $this;
    }


    /**
     * Checks if SellerFulfillmentOrderItemId is set
     *
     * @return bool true if SellerFulfillmentOrderItemId  is set
     */
    public function isSetSellerFulfillmentOrderItemId()
    {
        return !is_null($this->_fields['SellerFulfillmentOrderItemId']['FieldValue']);
    }

    /**
     * Gets the value of the ItemUnfulfillableReasons.
     *
     * @return StringList ItemUnfulfillableReasons
     */
    public function getItemUnfulfillableReasons()
    {
        return $this->_fields['ItemUnfulfillableReasons']['FieldValue'];
    }

    /**
     * Sets the value of the ItemUnfulfillableReasons.
     *
     * @param StringList ItemUnfulfillableReasons
     * @return void
     */
    public function setItemUnfulfillableReasons($value)
    {
        $this->_fields['ItemUnfulfillableReasons']['FieldValue'] = $value;
        return;
    }

    /**
     * Sets the value of the ItemUnfulfillableReasons  and returns this instance
     *
     * @param StringList $value ItemUnfulfillableReasons
     * @return FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem instance
     */
    public function withItemUnfulfillableReasons($value)
    {
        $this->setItemUnfulfillableReasons($value);
        return $this;
    }


    /**
     * Checks if ItemUnfulfillableReasons  is set
     *
     * @return bool true if ItemUnfulfillableReasons property is set
     */
    public function isSetItemUnfulfillableReasons()
    {
        return !is_null($this->_fields['ItemUnfulfillableReasons']['FieldValue']);

    }




}
class FBAOutboundServiceMWS_Model_UnfulfillablePreviewItemList extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_UnfulfillablePreviewItemList
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>member: FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'member' => array('FieldValue' => array(), 'FieldType' => array('FBAOutboundServiceMWS_Model_UnfulfillablePreviewItem')),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the member.
     *
     * @return array of UnfulfillablePreviewItem member
     */
    public function getmember()
    {
        return $this->_fields['member']['FieldValue'];
    }

    /**
     * Sets the value of the member.
     *
     * @param mixed UnfulfillablePreviewItem or an array of UnfulfillablePreviewItem member
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
     * @param UnfulfillablePreviewItem  $unfulfillablePreviewItemArgs one or more member
     * @return FBAOutboundServiceMWS_Model_UnfulfillablePreviewItemList  instance
     */
    public function withmember($unfulfillablePreviewItemArgs)
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
class FBAOutboundServiceMWS_Model_Weight extends FBAOutboundServiceMWS_Model
{


    /**
     * Construct new FBAOutboundServiceMWS_Model_Weight
     *
     * @param mixed $data DOMElement or Associative Array to construct from.
     *
     * Valid properties:
     * <ul>
     *
     * <li>Unit: string</li>
     * <li>Value: string</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'Unit' => array('FieldValue' => null, 'FieldType' => 'string'),
        'Value' => array('FieldValue' => null, 'FieldType' => 'string'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the Unit property.
     *
     * @return string Unit
     */
    public function getUnit()
    {
        return $this->_fields['Unit']['FieldValue'];
    }

    /**
     * Sets the value of the Unit property.
     *
     * @param string Unit
     * @return this instance
     */
    public function setUnit($value)
    {
        $this->_fields['Unit']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Unit and returns this instance
     *
     * @param string $value Unit
     * @return FBAOutboundServiceMWS_Model_Weight instance
     */
    public function withUnit($value)
    {
        $this->setUnit($value);
        return $this;
    }


    /**
     * Checks if Unit is set
     *
     * @return bool true if Unit  is set
     */
    public function isSetUnit()
    {
        return !is_null($this->_fields['Unit']['FieldValue']);
    }

    /**
     * Gets the value of the Value property.
     *
     * @return string Value
     */
    public function getValue()
    {
        return $this->_fields['Value']['FieldValue'];
    }

    /**
     * Sets the value of the Value property.
     *
     * @param string Value
     * @return this instance
     */
    public function setValue($value)
    {
        $this->_fields['Value']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the Value and returns this instance
     *
     * @param string $value Value
     * @return FBAOutboundServiceMWS_Model_Weight instance
     */
    public function withValue($value)
    {
        $this->setValue($value);
        return $this;
    }


    /**
     * Checks if Value is set
     *
     * @return bool true if Value  is set
     */
    public function isSetValue()
    {
        return !is_null($this->_fields['Value']['FieldValue']);
    }




}
/* Models */
?>
