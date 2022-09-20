<?php

    namespace MF\Model;
    
    use App\Connection;

    class Container {

        public static function getModel($model) {
            $class = "\\App\\Models\\{$model}";
            $table = "";

            switch($model) {
                case "Usuario":
                    $table = "tb_usuarios";
                break;
            }

            $db = new Connection($table);

            return new $class($db);
        }

    }

?>