<?php
//zhengxingok@gmail.com
namespace App\Lib\RPC;

use Service\RPC\Exceptions\ParamsMissingException;

class RPCCmder {
    public static function annotation($procedure) {
        list($procedureName, $className, $methodName) = self::parseName($procedure);

        return new Annotation(self::getClass($className), $methodName);
    }

    public static function call($procedure, array $params) {
        list($procedureName, $className, $methodName) = self::parseName($procedure);

        return [$procedureName => self::callProcedure($className, $methodName, $params)];
    }

    private static function callProcedure($className, $method, $params) {
        $class = self::getClass($className);
        $args = self::buildArgs($class, $method, $params);

        return call_user_func_array([$class, $method], $args);
    }

    private static function parseName($procedure) {
        $matches = self::parse($procedure);
        if (count($matches) !== 3) {
            throw new \Exception('invalid procedure call');
        }

        return $matches;
    }

    private static function buildArgs($class, $method, array $input) {
        $pars = (new \ReflectionMethod($class, $method))->getParameters();
        $callPars = [];
        foreach ($pars as $p) {
            $key = $p->getName();

            if (isset($input[$key])) {
                $callPars[] = $input[$key];
            } elseif ($key == 'otherArgs') {
                $callPars[] = $input;
            } elseif ($p->isDefaultValueAvailable()) {
                $callPars[] = $p->getDefaultValue();
            } else {
                throw new ParamsMissingException("params missing : $key");
            }

            unset($input[$key]);
        }

        return $callPars;
    }

    private static function getClass($name) {
        $name = ucfirst($name);
        $className = "\\App\\Api\\$name";

        return new $className();
    }

    public static function getProcedureName($str) {
        return self::parse($str)[0];
    }

    public static function parse($str) {
        preg_match("/(\w+)\.(\w+)$/", $str, $matches);
        return $matches;
    }
}
