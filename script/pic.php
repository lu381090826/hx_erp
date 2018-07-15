<?php
$Server = "119.129.253.55";
$database = "pic";
$uid = "sa";
$pwd = "hx-inscre-2013";
$conInfo = array('Database' => $database, 'UID' => $uid, 'PWD' => $pwd);
$link = sqlsrv_connect($Server, $conInfo);

if ($link) {
    query($link);

} else {
    print_r(sqlsrv_errors(), true);
    if (($errors = sqlsrv_errors()) != null) {
        foreach ($errors as $error) {
            echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
            echo "code: " . $error['code'] . "<br />";
            echo "message: " . $error['message'] . "<br />";
        }
    }
    die("");
}

function query($link){
    $sql = "SELECT top 1 * from pic.dbo.itempicture;";

    $stmt = sqlsrv_query($link, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){

        $code = $row["code"];
        $pic = $row["picture"];

        print_r($code);
        print_r($pic);
    }
}
