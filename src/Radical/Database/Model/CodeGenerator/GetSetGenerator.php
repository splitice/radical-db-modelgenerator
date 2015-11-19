<?php
namespace Radical\Database\Model\CodeGenerator;

use Radical\Database\Model\TableReferenceInstance;

class GetSetGenerator {
    private $table;

    function __construct(TableReferenceInstance $table){
        $this->table = $table;
    }

    function generate_getter_single($name_objective, $name_field, $type){
        //DOCBLOCK
        $ret = '/**'."\r\n";
        $ret.= '* Get the value of '.$name_field."\r\n";
        $ret.= '*'."\r\n";
        if($name_objective != 'id') {
            $ret .= '* @param string|null $arg return type (optional)' . "\r\n";
        }
        $ret.= '* @return '.$type.' the value of '.$name_field."\r\n";
        $ret.= '*/'."\r\n";

        //GETTER
        if($name_objective == 'id'){
            $ret.='function get'.ucfirst($name_objective).'() {'."\r\n";
            $ret .= "\treturn \$this->_getId();\r\n";
        }else {
            $ret.='function get'.ucfirst($name_objective).'($arg = null) {'."\r\n";
            $ret .= "\t" . 'return $this->call_get_member("' . addslashes($name_objective) . '", [$arg]);' . "\r\n";
        }
        $ret.='}'."\r\n";

        return $ret;
    }

    function generate_setter_single($name_objective, $name_field, $type){
        //DOCBLOCK
        $ret = '/**'."\r\n";
        $ret.= '* Set the value of '.$name_field."\r\n";
        $ret.= '*'."\r\n";
        $ret.= '* @param '.$type.' $value set the value of '.$name_field."\r\n";
        $ret.= '*/'."\r\n";

        //GETTER
        $ret.='function set'.ucfirst($name_objective).'($value) {'."\r\n";
        $ret.="\t".'return $this->call_set_value("'.addslashes($name_objective).'", $value);'."\r\n";
        $ret.='}'."\r\n";

        return $ret;
    }

    function generate_getter_related($name_objective, $type, $id_type){
        //DOCBLOCK
        $ret = '/**'."\r\n";
        $ret.= '* Get related instances of '.$name_objective."\r\n";
        $ret.= '*'."\r\n";
        $ret.= '* @return \Radical\Database\Model\Table\TableSet|'.$type.'[]|'.$id_type.' related instances of '.$name_objective."\r\n";
        $ret.= '*/'."\r\n";

        //GETTER
        $ret.='function get'.ucfirst($name_objective).'s() {'."\r\n";
        $ret.="\t".'return $this->call_get_related("'.addslashes($name_objective).'");'."\r\n";
        $ret.='}'."\r\n";

        return $ret;
    }
}
