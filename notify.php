<?php
    $dirname = 'C:\\Users\\[change me]\\Desktop\\';
    $filename = (isset($_POST['filename']) ? preg_replace('/[\\|\/|\:|\*|\?|\"|\<|\>|\|]/', '', $_POST['filename']) : 'notify') . '.txt';
    
    if(isset($_POST['save_file']) && $_POST['save_file'] == '1'){
        if(is_dir($dirname)){
            $path = $dirname . $filename;
            
            if(!file_exists($path)){
                $handle = fopen($path, 'w');
                fclose($handle);
                
                if(file_exists($path))
                    echo 1;
            }
        }
    } else if(isset($_POST['save_file']) && $_POST['save_file'] == '0')
        echo 1;