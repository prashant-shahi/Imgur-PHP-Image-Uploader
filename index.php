<!doctype html>
<html lang="en">
<head>
   <title>Online Image Uploader - PHP using IMGUR</title>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
   <style>
	.uploader {
	  display:table;
	  position:relative;
	  overflow:hidden;
	  width:300px;
	  height:300px;
	  background:#f3f3f3;
	  border:2px dashed #e8e8e8;
	}
	.innerUploader {
	  display:table-cell;
	  position:relative;
	  outline:2px dashed red;
	  outline-offset: -3px;
	  justify-content:center;
	  align-items:center;
	  overflow:hidden;
	  vertical-align:middle;
	  text-align:center;
	}
	#filePhoto{
	    position:absolute;
	    width:290px;
	    height:290px;
	    top:-5px;
	    left:0;
	    z-index:2;
	    opacity:0;
	    cursor:pointer;
	}
	.uploader img{
	    position:absolute;
	    height:300px;
	    width:300;
	    top:-1px;
	    left:-1px;
	    z-index:1;
	    border:none;
	    min-width:100%;
	    min-height:100%;
	}
	#imgur-image{
	    max-width:100%;
	}
	.hidden {
	  display:none;
	}   
  </style>
</head>
<body>
<div class="container">
<h4>Upload Image : </h4>
<div class="row">
<form class="col-sm" action="" enctype="multipart/form-data" method="POST">
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
   echo "<h2>Uploaded Without Any Problem</h2>";
   echo "<input type='text' id='image-link' value='$url'/><button onclick='copyToClipboard()'>Copy link</button><br/><hr/>";
   echo "<img id='imgur-image' alt='imgur-image' src='$url'/>";
  }
  else{
   echo "<h2>There’s a Problem</h2>";
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
