<?php

    session_start();

    $con = 0;

    require "select_stocks.php";
    $file_path = __DIR__ . "/../../collect_data/";
    output_csv_stocks();


    function output_csv_stocks() {
        global $file_path;
        $array = get_stocks_from_db();
        $file_name = "{$file_path}tmp.csv";
        $file_handler = fopen($file_name, "w");
        foreach ( $array as $line ) {
            mb_convert_variables('SJIS', 'UTF-8', $line);
            fputcsv($file_handler, $line);
        }
        fclose($file_handler);

        header('Content-Type: application/octet-stream');
        header("Content-Length: " . filesize($file_name));
        header('Content-Disposition: attachment; filename=stock_list.csv');
        readfile($file_name);
        $con=1;

    }

    if ($con = 1) { /* データを追加できた場合は、> 0 になる */
        $_SESSION['flush_message'] = [
            'type' => 'success',
            'content' => "csvを出力しました。",
        ];

    } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
        $_SESSION['flush_message'] = [
            'type' => 'danger',
            'content' => 'csvの出力に失敗しました。',
        ];
}
?>
