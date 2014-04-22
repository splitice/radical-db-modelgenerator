<?php
namespace Radical\Database\Model\CodeGenerator;

use Radical\Database\Model\TableReferenceInstance;

class TraitGeneratorProcessor extends ModelGeneratorProcessor {
    private $namespace;
    function __construct(TableReferenceInstance $table, $namespace){
        parent::__construct($table);
        $this->namespace = $namespace;
    }
    function getTraitName(){
        return 'T'.$this->table->getName().'Generated';
    }
    function getCode(){
        $ret = '<?php'."\r\n";
        $ret.= 'namespace '.ltrim($this->namespace,'\\').';'."\r\n";
        $ret.= "\r\n";
        $ret.= "use ".$this->table->getClass().";\r\n";
        $ret.= "use \\Radical\\Database\\Model\\Table\\TableSet;\r\n";
        $ret.= "/**\r\n";
        $ret.= " * Class ".$this->getTraitName()."\r\n";
        $ret.= " * @package ".$this->namespace."\r\n";
        $ret.= " *\r\n";
        $ret.= " * @method static ".$this->table->getName()." fromId(\$id) Return an instance of ".$this->table->getName()." given \$id\r\n";
        $ret.= " * @method static ".$this->table->getName()." fromFields(\$fields) Return an instance of ".$this->table->getName()." given the \$fields\r\n";
        $ret.= " * @method static ".$this->table->getName()." fromSQL(\$data) Return an instance of ".$this->table->getName()." given the \$data\r\n";
        $ret.= " * @method static ".$this->table->getName()." create(\$data) Create an instance of ".$this->table->getName()." given the \$data\r\n";
        $ret.= " * @method static TableSet|".$this->table->getName()."[] getAll() Return a set of ".$this->table->getName()."\r\n";
        $ret.= " */\r\n";
        $ret.= "trait ".$this->getTraitName()." {\r\n";
        $ret.= "\tabstract function __call(\$k,\$a); \r\n";
        $ret.= "\t".rtrim(str_replace("\r\n","\r\n\t",parent::getCode()))."\r\n";
        $ret.= "}\r\n";
        return $ret;
    }
}
