<?php
namespace OrientDBYii2Connector;

use yii\base\Component;
use PhpOrient\Protocols\Binary\Data\Record;
use PhpOrient\Protocols\Binary\Data\ID;

/**
 *  working with relations
 *  
 */

class DataRreaderOrientDB extends Component
{
    public $records;
    public $relations;
    
    public function getTree()
    {
        $result = [];
        foreach($this->records as $record) {
            // prepair data:
            $rid = $record->getRid();
            $recordData             = $record->getOData();
            $recordData['@rid']     = $this->IDtoRid($rid);
            $recordData['@version'] = $record->getVersion();
            $recordData['@class']   = $record->getOClass();
            foreach($recordData as $key => $value) {
                if (is_a($value, 'DateTime')) {
                    $value = $value->format('Y-m-d H:i:s');
                    $recordData[$key] = $value;
                }
                
                // assign ralation data
                if(is_a($value, 'PhpOrient\Protocols\Binary\Data\Bag')) {
                    $rids = $value->getRids();
                    foreach($rids as $rid) {
                        $relation = $this->findRelationByRid($rid);
                        if(empty($relation))
                            continue; // relation not loaded
                        if($this->isEdge($relation)) { //? it always edge
                            $edgeData = $relation->getOData();
                            $vortex = $this->findRelationByRid($edgeData['in']);
                            $vortexData = $vortex->getOData();
                        
                            // convert DateTime to ISO
                            foreach($vortexData as $vkey => $vval) {
                                if (is_a($vval, 'DateTime')) {
                                    $vortexData[$vkey] = $vval->format('Y-m-d H:i:s');
                                }
                            }
                            
                            $vortexData['@rid']     = $this->IDtoRid($vortex->getRid());
                            $vortexData['@version'] = $vortex->getVersion();
                            $vortexData['@class']   = $vortex->getOClass();
                            $vortexData['_edge']   =  $this->IDtoRid($relation->getRid());
                            
                            $recordData[$key] = $vortexData;
                        }
                    }
                    
                }
                
                // continue;
                
                // if(    !is_a($value, 'PhpOrient\Protocols\Binary\Data\Bag')
                    // // && !is_a($value, 'PhpOrient\Protocols\Binary\Data\ID')
                // ) {
                    // continue;
                // }
            }
            
            array_push($result, $recordData);
        }
        
        return $result;
    }
    
    static public function getRecordData(Record &$record)
    {
        $rid = $record->getRid();
        $recordData             = $record->getOData();
        $recordData['@rid']     = static::IDtoRid($rid);
        $recordData['@version'] = $record->getVersion();
        $recordData['@class']   = $record->getOClass();
        foreach($recordData as $key => $value) {
            if (is_a($value, 'DateTime')) {
                $value = $value->format('Y-m-d H:i:s');
                $recordData[$key] = $value;
            }
        }
        
        return $recordData;
    }
    
    static protected function isEdge( Record &$record )
    {
        $data = $record->getOData();
        if(isset($data['in']) && isset($data['out']))
            return true;
        
        return false;
    }
    
    static protected function compairRid(ID $rid, ID $rid2)
    {
        return $rid->cluster === $rid2->cluster && $rid->position === $rid2->position;
    }
    
    static protected function IDtoRid(ID $rid)
    {
        return '#' . $rid->cluster . ':' . $rid->position; 
    }
    
    //!? relations ordered, you can find from last index
    protected function findRelationByRid(ID $rid)
    {
        foreach($this->relations as $record){
            if($this->compairRid($record->getRid(), $rid))
                return $record;
        }
        
        return null;
    }
    
    /**
     *  check is relation by regex #[0-9]+:[0-9]+
     */
    static protected function isRelation($val)
    {
        if(is_array($val)) {
            // return true;
        } else if(is_string($val)) {
            
        }
        return false;
    }
}
