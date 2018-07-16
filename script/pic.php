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

function query($link)
{
    //$sql = "select top ? * from pic.dbo.itempicture  where code not in ( select top ? code from pic.dbo.itempicture );";
    $sql = "select top 100 * from pic.dbo.itempicture where code<'DAIXE8561' ORDER by code desc;";
    $limit = 3;
    $page = 0;
    $params = array($limit,$page);

    $stmt = sqlsrv_query($link, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

        $code = $row["code"];
        $pic = $row["picture"];
        savePic($code, $pic);
    }
}


function savePic($code = "", $jpg)
{
//生成图片
    $imgDir = 'D:\\hanxun_pic\\';
    $filename = $code . ".jpg";///要生成的图片名字
    if (empty($jpg)) {
        echo 'nostream';
        exit();
    }

    $file = fopen($imgDir . $filename, "w");//打开文件准备写入
    fwrite($file, $jpg);//写入
    fclose($file);//关闭

    $filePath = $imgDir . $filename;

//图片是否存在
    if (!file_exists($filePath)) {
        echo 'createFail';
        exit();
    }
}
