<?php

    require "select_purchase.php";
    $file_path = __DIR__ . "/../../collect_data/";
    output_csv_purchase();

    function output_csv_purchase() {
        global $file_path;
        $array = get_purchase_from_db();
        $file_name = "{$file_path}purchase.csv";
        $file_handler = fopen($file_name, "w");
        foreach ( $array as $line ) {
            mb_convert_variables('SJIS', 'UTF-8', $line);
            fputcsv($file_handler, $line);
        }
        fclose($file_handler);

        header('Content-Type: application/octet-stream');
        header("Content-Length: " . filesize($file_name));
        header('Content-Disposition: attachment; filename=purchase_list.csv');
        readfile($file_name);

    }

?>
