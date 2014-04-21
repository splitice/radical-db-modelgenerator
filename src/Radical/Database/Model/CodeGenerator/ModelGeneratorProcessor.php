<?php
namespace Radical\Database\Model\CodeGenerator;

use Radical\Database\Model\TableReferenceInstance;

class ModelGeneratorProcessor {
    protected $table;
    private $getset;

    function __construct(TableReferenceInstance $table){
        $this->table = $table;
        $this->getset = new GetSetGenerator($table);
    }

    private function generateGettersAndSetters(){
        $orm = $this->table->getOrm();
        if(!$orm){
            return;
        }
        $ret = '';
        foreach($orm->mappings as $field=>$field_objective){
            $type = 'int|string';
            if(isset($orm->dynamicTyping[$field_objective])){
                $type .= '|'. $orm->dynamicTyping[$field_objective]['var'];
            }
            if(isset($orm->relations[$field])){
                $type .= '|'. $orm->relations[$field]->getTableClass();
            }
            $ret .= $this->getset->generate_getter_single($field_objective,$field,$type)."\r\n";
            $ret .= $this->getset->generate_setter_single($field_objective,$field,$type)."\r\n";
        }
        $ref_done = array();
        foreach($orm->references as $ref){
            $name = $ref['from_table']->getName();
            if(isset($ref_done[$name])){
                continue;
            }
            $ref_done[$name] = true;
            $ret .= $this->getset->generate_getter_related($name, $ref['from_table']->getClass(), 'int[]|string[]')."\r\n";
        }
        return $ret;
    }

    function getCode(){
        $ret = '/* BEGIN AUTO-GENERATED */'."\r\n";

        $ret .= $this->generateGettersAndSetters();

        $ret.= '/* END AUTO-GENERATED */'."\r\n";

        return $ret;
    }
}
