<?php

    session_start();
    require_once __DIR__ . "/../connect/db_connect.php";
    $data = get_csv_file();
    add_product($data);

    function get_csv_file() {
        $file_name = $_FILES["product_file"]["tmp_name"];
        $file_handler = fopen($file_name, "r");
        $add_data = [];
        while ( $line = fgetcsv($file_handler) ) {
            mb_convert_variables('UTF-8', 'SJIS', $line);
            $tmp = [];
            $tmp["name"] = $line[0];
            $tmp["price"] = $line[1];
            $tmp["manufacturer"] = $line[2];
            $tmp["date"] = $line[3];
            array_push($add_data, $tmp);
        }
        fclose($file_handler);
        return $add_data;
    }

    function add_product($data){
        global $dbh;
        $sql = "INSERT INTO products (name, price, manufacturer, date) VALUES ";
        $tmp = "(?, ?, ?, ?)";
        $param_tmp = $tmp;
        for ( $i = 1; $i < count($data); $i++ ) {
            $param_tmp .= ", {$tmp}";
        }
        $sql .= "{$param_tmp};";
        $stmt = $dbh->prepare($sql);

        $i = 1;
        foreach ( $data as $line ) {
            foreach ($line as $val ) {
                $stmt->bindValue($i, $val);
                $i++;
            }
        }

        echo $sql;
        var_dump($data);

        $stmt->execute();

        if ($stmt->rowCount() > 0) { /* データを追加できた場合は、> 0 になる */
            $_SESSION['flush_message'] = [
                'type' => 'success',
                'content' => "商品データを追加しました",
            ];
        } else { /* > 0 ではない場合(データを追加できなかった場合)、エラー扱いとする */
            $_SESSION['flush_message'] = [
                'type' => 'danger',
                'content' => '商品データの追加に失敗しました',
            ];
        }
        /* $_POST データが送信されていた場合の処理、ここまで */
        header("Location: ../../view/home.php");
        exit();
    }

?>
