<?php
if (isset($name)) {
    echo "商品を追加しました。";
    echo <<<_FORM_
    <form>
    <input type="button" value="ホームに戻る" onclick="location.href="../view/home.php>
    </form>
    _FORM_;
}
