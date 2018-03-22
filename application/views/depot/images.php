<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <script src="/assets/jquery/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="/assets/plugin/images-input/images-input.css">
<script src="/assets/plugin/images-input/images-input.js"></script>
</head>
<body>
<div id="images-input" name="images-input" path="http://www.xmg.com/sell/Api/upload_base64" value=''>
</div>
<script>
    $("#images-input").imagesInput({
        change:change
    });
    function change(value){
        console.log(value);
    }
</script>
</body>
</html>