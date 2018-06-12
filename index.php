<!doctype html>
<html lang="en">
<head>
   <title>Online Image Uploader - with PHP using IMGUR</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
   <link rel="stylesheet" href="css/style.css?v=1">
</head>
<body>
<div class="container">
<h2 class="text-center">Imgur PHP Image Uploader</h2>
<hr/>
<div class="row">
<form class="col-sm" action="" enctype="multipart/form-data" method="POST">
<h4>Upload Image : </h4>
<div class="uploader" onclick="$('#filePhoto').click()">
<div class="innerUploader">
    Select Image
    <img class="hidden" src="" />
    <input accept="image/*" type="file" name="img"  id="filePhoto" />
</div>
</div>
 <input type="submit" name="submit" value="Upload" />
</form>
<div class="col-sm">
<?php
if(isset($_POST['submit'])){ 
 $img=$_FILES['img']; 
 if($img['name']==''){  
  echo "<h2>Select an Image Please.</h2>";
 }
 else {
  $filename = $img['tmp_name'];
  $client_id='67fd839d20ce847';
  $handle = fopen($filename, 'r');
  $data = fread($handle, filesize($filename));
  $pvars = array('image' => base64_encode($data));
  $timeout = 30;
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
  curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);

  $out = curl_exec($curl);
  curl_close ($curl);
  $pms = json_decode($out,true);
  $url=$pms['data']['link'];
  if($url!=''){
   echo "<h4 bg-success>Uploaded Without Any Problem</h4>";
   echo "<input type='text' id='image-link' value='$url'/><button onclick='copyToClipboard()'>Copy link</button><br/><hr/><h5>Preview : </h5>";
   echo "<img id='imgur-image' alt='imgur-image' src='$url'/>";
  }
  else{
   echo "<h4 class='bg-danger'>There’s a Problem</h4>";
   echo "<div>".$pms['data']['error']."</div>";  
  } 
 }
}
?>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
var imageLoader = document.getElementById('filePhoto');
    imageLoader.addEventListener('change', handleImage, false);

function handleImage(e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        $('.innerUploader img').attr('src',event.target.result).removeClass("hidden" );
    }
    reader.readAsDataURL(e.target.files[0]);
}

function copyToClipboard() {
  var copyText = document.getElementById("image-link");
  copyText.select();
  document.execCommand("copy");
  alert("Copied the link: " + copyText.value);
}

</script>
</body>
</html>
