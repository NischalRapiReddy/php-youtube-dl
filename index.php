<?php require './functions.php'; $error = "";?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Download YouTube video</title>
    <!-- Font-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        .fade-in { animation: fadeIn 1.5s; }
            @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        @media only screen and (max-device-width: 480px) {

            .txt {
                font-size: 16px;
                padding: 1rem;
            }


        }
    </style>

</head>
<body>
    <div class="container">
        <form method="post" action="" class="py-3">
            <div class="row">
                <div class="p-3 text-center" >
                    <h1>📩 Youthoub Converter/Downloader 🥨 </h1>
                </div>
                <div class="">
                    <div class="input-group d-flex justify-content-between">
                        <input type="text" class="form-control" name="video_link" placeholder="Paste link.." <?php if(isset($_POST['video_link'])) echo "value='".$_POST['video_link']."'"; ?>>
                        <!-- <span class="input-group-btn">
                            </span> -->
                        <button type="submit" name="submit" id="submit" class="btn btn-dark">Download</button>
                    </div><!-- /input-group -->
                </div>
            </div><!-- .row -->
        </form>

        <?php if($error) :?>
            <div style="color:red;font-weight: bold;text-align: center"><?php print $error?></div>
        <?php endif;?>

        <?php if(isset($_POST['submit'])): ?>
        
        
            <?php 
            $video_link = $_POST['video_link'];
            parse_str( parse_url( $video_link, PHP_URL_QUERY ), $parse_url );
            $video_id =  $parse_url['v']; 
            $video = json_decode(getVideoInfo($video_id));
            $formats = $video->streamingData->formats;
            $adaptiveFormats = $video->streamingData->adaptiveFormats;
            $thumbnails = $video->videoDetails->thumbnail->thumbnails;
            $title = $video->videoDetails->title;
            $short_description = $video->videoDetails->shortDescription;
            $thumbnail = end($thumbnails)->url;
            ?>
            
            
            <div class="row border rounded py-3 m-1">
                
                <div class="d-flex flex-column text-center ">
                    <h2><?php echo $title; ?> </h2>
                </div>

                <div class="d-flex justify-content-center">
                    <img class="rounded border" src="<?php echo $thumbnail; ?>" style="max-width:50%">
                </div>
            </div>
            
            <?php if(!empty($formats)): ?>
            
            
                <?php if(@$formats[0]->url == ""): ?>
                <div class="card">
                    <div class="card-header">
                        <strong>This video is currently not supported by our downloader!</strong>
                        <small><?php 
                        $signature = "https://example.com?".$formats[0]->signatureCipher;
                                    parse_str( parse_url( $signature, PHP_URL_QUERY ), $parse_signature );
                                    $url = $parse_signature['url']."&sig=".$parse_signature['s'];
                                ?>
                        </small>
                    </div>
                </div>
                <?php 
                die();
                endif;
                ?>
                
                <div class="card m-2">
                    <div class="card-header">
                        <strong>With Video & Sound</strong>
                    </div>
                    
                    <div class="card-body">
                        <table class="table ">
                            <tr>
                                <td>Type</td>
                                <td>Quality</td>
                                <td>Download</td>
                            </tr>
                            <?php foreach($formats as $format): ?>
                                <?php
                                
                                if(@$format->url == ""){
                                    $signature = "https://example.com?".$format->signatureCipher;
                                    parse_str( parse_url( $signature, PHP_URL_QUERY ), $parse_signature );
                                    $url = $parse_signature['url']."&sig=".$parse_signature['s'];
                                    //var_dump($parse_signature);
                                }else{
                                    $url = $format->url;
                                }
                                
                                                               
                                
                                ?>

                                <tr>
                                    
                                    <td>
                                        <?php if($format->mimeType) echo explode(";",explode("/",$format->mimeType)[1])[0]; else echo "Unknown";?>  
                                    </td>
                                    <td>
                                        <?php if($format->qualityLabel) echo $format->qualityLabel; else echo "Unknown"; ?>
                                    </td>
                                    <td>
                                        <a 
                                            href="downloader.php?link=<?php echo urlencode($url)?>&title=<?php echo urlencode($title)?>&type=<?php if($format->mimeType) echo explode(";",explode("/",$format->mimeType)[1])[0]; else echo "mp4";?>"
                                            class="btn btn-dark btn-sm"
                                        >
                                            Download
                                        </a> 
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
                
            
                <div class="card m-2">
                    <div class="card-header">
                        <strong>Videos video only/ Audios audio only</strong>
                    </div>
                    <div class="card-body">
                        <table class="table ">
                            <tr>
                                <td>Type</td>
                                <td>Quality</td>
                                <td>Download</td>
                            </tr>
                            <?php foreach ($adaptiveFormats as $video) :?>
                                <?php
                                try{
                                    $url = $video->url;
                                }catch(Exception $e){
                                    $signature = $video->signatureCipher;
                                    parse_str( parse_url( $signature, PHP_URL_QUERY ), $parse_signature );
                                    $url = $parse_signature['url'];
                                }
                                
                                ?>
                                <tr>
                                    <td><?php if(@$video->mimeType) echo explode(";",explode("/",$video->mimeType)[1])[0]; else echo "Unknown";?></td>
                                    <td><?php if(@$video->qualityLabel) echo $video->qualityLabel; else echo "Unknown"; ?></td>
                                    <td><a class="btn btn-dark btn-sm" href="downloader.php?link=<?php print urlencode($url)?>&title=<?php print urlencode($title)?>&type=<?php if($video->mimeType) echo explode(";",explode("/",$video->mimeType)[1])[0]; else echo "mp4";?>">Download</a> </td>
                                </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        
        
        <?php endif; ?>
       
        <div class="container text-center">
            <h4 class="fade-in txt">Built with 💖 By Nischal</h4>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
</body>
</html>
