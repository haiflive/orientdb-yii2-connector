<?php

namespace OrientDBYii2Connector\restfull;

use yii\web\XmlResponseFormatter;
use DOMDocument;
use DOMElement;
use DOMText;
use yii\base\Arrayable;
use yii\base\Component;
use yii\helpers\StringHelper;

class OrientDBXmlResponseFormatter extends XmlResponseFormatter
{
    public function format($response)
    {
        $charset = $this->encoding === null ? $response->charset : $this->encoding;
        if (stripos($this->contentType, 'charset') === false) {
            $this->contentType .= '; charset=' . $charset;
        }
        $response->getHeaders()->set('Content-Type', $this->contentType);
        if ($response->data !== null) {
            $dom = new DOMDocument($this->version, $charset);
            $root = new DOMElement($this->rootTag);
            $dom->appendChild($root);
            $this->buildXml($root, self::replaceKeys($response->data));
            $response->content = $dom->saveXML();
        }
    }
    
    static protected function replaceKeys($data)
    {
        $result = [];
        
        foreach($data as $key => $val) {
            if($key === '@rid') {
                $result['_rid'] = $val;
                continue;
            }
            
            if($key === '@version') {
                $result['_version'] = $val;
                continue;
            }
            
            if($key === '@class') {
                $result['_class'] = $val;
                continue;
            }
            
            if(is_array($val)) {
                $result[$key] = self::replaceKeys($val);
                continue;
            }
            
            $result[$key] = $val;
        }
        
        return $result;
    }
}
