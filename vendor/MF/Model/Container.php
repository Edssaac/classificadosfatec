<?php

    namespace MF\Model;
    
    use App\Connection;

    class Container {

        public static function getModel($model) {
            $class = "\\App\\Models\\{$model}";

            $db = new Connection();

            return new $class($db);
        }

    }

?>