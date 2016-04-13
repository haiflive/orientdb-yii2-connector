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
        foreach($this->records as $record)
            array_push($result, $this->extractRecord($record));
        
        return $result;
    }
    
    public function extractRecord(Record &$record)
    {
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
            
            // -- extract raltion data --
            // EMBEDDED
            else if(is_a($value, 'PhpOrient\Protocols\Binary\Data\Record')) {
                $recordData[$key] = $this->extractRecord($value);
            }
            
            else if(is_array($value)) {
                $embeddedData = [];
                foreach($value as $childRec) {
                    // EMBEDDEDSET, EMBEDDEDLIST, EMBEDDEDMAP
                    if(is_a($childRec, 'PhpOrient\Protocols\Binary\Data\Record')) {
                        array_push($embeddedData, $this->extractRecord($childRec));
                    } else
                    // LINKSET, LINKLIST, LINKMAP
                    if(is_a($childRec, 'PhpOrient\Protocols\Binary\Data\ID')){
                        $vortex = $this->findRelationByRid($childRec);
                        if(!empty($vortex)) {
                            array_push($embeddedData, $this->extractRecord($vortex));
                        }
                    }
                }
                
                $recordData[$key] = $embeddedData;
            }
            
            // -- assign ralation data --
            // LINK
            else if($key !== '@rid' && is_a($value, 'PhpOrient\Protocols\Binary\Data\ID')) {
                $vortex = $this->findRelationByRid($value);
                if(!empty($vortex))
                    $recordData[$key] = $this->extractRecord($vortex); //??? convert to ActiveRecord
            }
            
            // EDGE:
            else if(is_a($value, 'PhpOrient\Protocols\Binary\Data\Bag')) {
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
        
        return $recordData;
    }
    
    static public function getRecordData(Record &$record)
    {
        return (new self())->extractRecord($record);
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
        if(empty($this->relations))
            return null;
        
        foreach($this->relations as $record){
            if($this->compairRid($record->getRid(), $rid))
                return $record;
        }
        
        return null;
    }
    
    /**
     *  check is relation by regex #[0-9]+:[0-9]+
     */
    static protected function isLink($val)
    {
        return preg_match('/#[0-9]+:[0-9]+/', $val) !== 0;
    }
}
