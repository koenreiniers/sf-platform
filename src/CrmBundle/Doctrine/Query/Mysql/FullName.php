<?php
namespace CrmBundle\Doctrine\Query\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class FullName extends FunctionNode
{
    private $expr;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $expr = $sqlWalker->walkArithmeticPrimary($this->expr);

        list($tableName) = explode('.', $expr);

        $fieldNames = [
            'name_prefix',
            'first_name',
            'middle_name',
            'last_name',
            'name_suffix',
        ];


        $strs = [];
        foreach($fieldNames as $fieldName) {
            $strs[] = 'COALESCE('.$tableName.'.'.$fieldName.', ""), " "';
        }

        return 'CONCAT('.implode(', ', $strs).')';
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}