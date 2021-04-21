<?php
    namespace Framework;

    class View {
        public static function Render($file, $args = []) {
            if(isset($_ENV['CACHING']) && $_ENV['CACHING'] == 1) {
                if(file_exists('./Views/'.$file)) {
                    ob_start();

                    foreach($args as $key => $value) {
                        $$key = $value;
                    }

                    include './Settings/Cache/'.$file;
                    $returned = ob_get_contents();
                    ob_end_clean();
                    return $returned;
                }
            }

            $replacements = [
                '@endif'    =>  '<?php } ?>',
                '@endforeach'    =>  '<?php } ?>',
                '@endfor'    =>  '<?php } ?>',
                '@endphp'    =>  '<?php ?>',
                '@endwhile'    =>  '<?php } ?>',
                '{%'        =>  '<?php echo ',
                '%}'        =>  ' ; ?>',
                '--}}'    =>  '-->'
            ];

            $regexReplacements = [
                ['\@php', '<?php ', ''],
                ['\@if', '<?php if ', '{ ?>'],
                ['\@elseif', '<?php } else if ', '{ ?>'],
                ['\@else', '<?php } else ', '{ ?>'],
                ['\@foreach', '<?php foreach ', '{ ?>'],
                ['\@for', '<?php for ', '{ ?>'],
                ['\@include', '<?php include ', ' ?>'],
                ['\@while', '<?php while ', '{ ?>'],
                ['{{--', '<!--', ''],
                ['//', '<!--', '-->']
            ];

            $fileData = file_get_contents(INITIAL_DIR . '/Views/'.$file);
            foreach($replacements as $replacementKey => $replacementValue) {
                $fileData = str_replace($replacementKey, $replacementValue, $fileData);
            }

            //$regex = '#\#if(.*)((\s?.)*)(\#endif)#';

            foreach($regexReplacements as $regexReplacement) {
                $fileData = preg_replace('#'.$regexReplacement[0].'(.*)#', $regexReplacement[1]."$1 ".$regexReplacement[2], $fileData);
            }

            file_put_contents(INITIAL_DIR . '/Settings/Cache/'.$file, $fileData);

            ob_start();

            foreach($args as $key => $value) {
                $$key = $value;
            }

            include INITIAL_DIR . '/Settings/Cache/'.$file;
            $returned = ob_get_contents();
            ob_end_clean();
            return $returned;
            

        }
    };
?>