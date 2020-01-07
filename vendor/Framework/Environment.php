<?php
    namespace Framework;

    class Environment {
        public static function Generate($file) {
            $handle = fopen($file, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $var = explode('=', $line);
                    $_ENV[$var[0]] = trim(preg_replace('/\s\s+/', ' ', $var[1]));
                }

                fclose($handle);
            }
        }
    };
?>