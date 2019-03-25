<?php
set_time_limit(30);
ini_set('max_execution_time', 30);
date_default_timezone_set('UTC');

include __DIR__."/config.php";


\InstagramAPI\Instagram::$allowDangerousWebUsageAtMyOwnRisk = true;
/////// CONFIG ///////
$page = !empty($_GET['page'])?(int)($_GET['page']):1;
$userlistResult = [];
$countUsers = 0;
$showedUsers = 0;
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: ' . $e->getMessage() . "<br>";
    exit(0);
}

$option = isset($_POST['inlineRadioOptions']) ? $_POST['inlineRadioOptions'] : null ;

if($option===null)
{
    $option = trim(file_get_contents("option.txt"));
    $option = !empty($option) ? $option : null ;
}
else
{
    file_put_contents("option.txt",$option);
}


$message = isset($_GET['message'])?htmlspecialchars($_GET['message']):"";

if($option!==null)
{
    if($option==="option1")
    {
        $username = !empty($_POST["username"])?htmlspecialchars(trim($_POST["username"])):null;

        if($username===null)
        {
            $username = trim(file_get_contents("username.txt"));
            $username = !empty($username) ? $username : null ;
        }
        else
        {
            file_put_contents("username.txt",$username);
        }

        if($username)
        {
            try{
                $rankToken = \InstagramAPI\Signatures::generateUUID();
                $userId = $ig->people->getUserIdForName($username);
                $user = $ig->people->getInfoByName($username)->getUser();

                $userlist = [];

                $type = !empty($_POST["type"])?htmlspecialchars(trim($_POST["type"])):null;

                if($type===null)
                {
                    $type = trim(file_get_contents("type.txt"));
                    $type = !empty($type) ? $type : null ;
                }
                else
                {
                    file_put_contents("type.txt",$type);
                }

                if($type=="followers"){
                    $userlist=$ig->people->getFollowers($userId,$rankToken)->asArray()["users"];
                    $countUsers = $user->getFollowerCount();
                }
                elseif($type=="following") {
                    $userlist = $ig->people->getFollowing($userId, $rankToken)->asArray()["users"];
                    $countUsers = $user->getFollowingCount();
                }



                $total = intval(($countUsers - 1) / $userPerPage) + 1;
                //if($page > $total) $page = $total;

                $showedUsers = $userPerPage*$page;

                $start = $page * $userPerPage - $userPerPage;

                $instPage = 1;
                if($type=="followers"){
                    while($showedUsers>count($userlist)*$instPage)
                    {
                        $max_id = $ig->people->getFollowers($userId,$rankToken)->getNextMaxId();
                        $userlist=$ig->people->getFollowers($userId,$rankToken,null,$max_id)->asArray()["users"];
                        $instPage++;
                    }
                }elseif($type=="following") {
                    while($showedUsers>count($userlist)*$instPage)
                    {
                        $max_id = $ig->people->getFollowing($userId,$rankToken)->getNextMaxId();
                        $userlist = $ig->people->getFollowing($userId, $rankToken,null,$max_id)->asArray()["users"];
                        $instPage++;
                    }
                }
                $end = $userPerPage+$start;
                for ($i=$start;$i<$end;$i++)
                {
                    if(isset($userlist[$i])) {
                        $user = $ig->people->getInfoByName($userlist[$i]["username"])->getUser()->asArray();
                        $userlistResult[$i] = $user;
                    }
                }

                $showedUsers = $userPerPage*($page-1)+count($userlistResult);

                if($showedUsers>$countUsers)
                {
                    $message="It is all. Good luck!";
                    $showedUsers=$countUsers;
                    $userlistResult=[];
                }

            }
            catch (\Exception $e) {
                echo 'Something went wrong: ' . $e->getMessage() . "\n";
            }
        }
        else
        {
            $message = "Parameter username is empty";
        }

    }
    elseif($option==="option2")
    {
        $userlist = !empty($_POST["user-list"])?htmlspecialchars(trim($_POST["user-list"])):null;

        if($userlist===null)
        {
            $userlist = trim(file_get_contents("userlist.txt"));
            $userlist = !empty($userlist) ? $userlist : null ;
        }
        else
        {
            file_put_contents("userlist.txt",$userlist);
        }

        if($userlist)
        {
            try{
                $userlist = explode("\n",$userlist);
                $countUsers = count($userlist);

                $total = intval(($countUsers - 1) / $userPerPage) + 1;

                $showedUsers = $userPerPage*$page;

                $start = $page * $userPerPage - $userPerPage;

                $end = $userPerPage+$start;

                for ($i=$start;$i<$end;$i++)
                {
                    if(isset($userlist[$i])) {
                        $user = $ig->people->getInfoByName(trim($userlist[$i]))->getUser()->asArray();
                        $userlistResult[$i] = $user;
                    }
                }

                $showedUsers = $userPerPage*($page-1)+count($userlistResult);

                if($showedUsers>$countUsers)
                {
                    $message="It is all. Good luck!";
                    $showedUsers=$countUsers;
                    $userlistResult=[];
                }

            }
            catch (\Exception $e) {
                echo 'Something went wrong: ' . $e->getMessage() . "\n";
            }
        }
        else
        {
            $message = "Parameter userlist is empty";
        }
    }
    else $message = "Parameter search type is invalid";
}
else
{
    $message = "Parameter search type is empty";
}
?>




<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="custom.css">

    <title>ii-tool</title>

    <style media="screen">
        #id2 {
            display: none;
        }
    </style>

</head>
<body>
<div class="container" style="padding-top:30px;width: 100%; max-width: 800px;margin: auto;">
    <?php if(!empty($message)):?>
    <div class="alert alert-success" role="alert">
        <?=$message?>
    </div>
    <?endif;?>
    <div class="row">
        <div class="col listin">
            <h4>Showing <?=$showedUsers?><span class="text-black-50">/<?=$countUsers?></span></h4>
            <hr>
            <form method="post" action="save.php?page=<?=$page?>" id="main-form">
                <input type="hidden" value="yes" name="issubmit">
                <input type="hidden" value="<?=$page?>" name="page">
                <?php $number = 0;?>
            <?php foreach ($userlistResult as $key => $value):?>

            <?php
            try {
                $i = 0;
                $arr_img = [];
                if($value["is_private"]!="true") {
                    //get images
                    $count_publications = 10;
                    //$items = $ig->timeline->getUserFeed($value['pk'])->getItems();

                    $response = file_get_contents("http://images" . floor(rand() * 33) . "-focus-opensocial.googleusercontent.com" . "/gadgets/proxy?container=none&url=https://www.instagram.com/" . $value["username"] . "/");

                    preg_match('/_sharedData = ({.*);<\/script>/', $response, $matches);

                    $profile_data = json_decode($matches[1])->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges;

                    foreach ($profile_data as $key=> $item) {
                        if ($i >= $count_publications) break;
                        $node = $item->node;


                        if (isset($node->display_url))
                            $arr_img[$key]['image_versions2']['candidates'][0]['url'] = $node->display_url;
                        if(isset($node->location->name))
                            $arr_img[$key]['location']['city'] = $node->location->name;
                        $i++;
                    }

                    if (empty($value["city_name"])) {
                        $arResult = [];
                        $max = null;
                        foreach ($arr_img as $img) {
                            if (!empty($img['location']['city']))
                            {
                                $arResult[$img['location']['city']] = !empty($arResult[$img['location']['city']]) ? $arResult[$img['location']['city']] + 1 : 1;
                            }
                        }

                        if (!empty($arResult))
                            $max = array_keys($arResult, max($arResult))[0];

                        if (!empty($max))
                            $value["city_name"] = $max;
                    }

                    if (empty($value["public_email"])) {
                        if(preg_match_all("~\b([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})\b~",$value["biography"],$matches)!==false)
                        {
                            $value["public_email"]=$matches[1];
                        }
                    }

                    usleep(200000);
                }
            }
            catch (\Exception $e) {
                echo 'Something went wrong: ' . $e->getMessage() . "<br>";
                continue;
            }
                $number++;
                ?>
            <div class="listing">
                <input class="form-check-input bigge" type="checkbox" name="user<?=$number?>" value="user<?=$number?>" checked>
                <input type="hidden" name="username<?=$number?>" value="<?=$value['username']?>">
                <input type="hidden" name="fullname<?=$number?>" value="<?=$value['full_name']?>">
                <div class="header ">
                    <div class="avatar"> <img src="<?=$value['profile_pic_url']?>" alt=""> </div>
                    <div class="info"> <h4><?=$value['full_name']?></h4> <p><a target="_blank" href="http://www.instagram.com/<?=$value['username']?>/"><?=$value['username']?></a></p> </div>
                </div>
                <div class="description">
                    <p><?=$value['biography']?></p>
                </div>

                <div class="input-group mb-3" name="email-loc">
                    <input type="text" name="email<?=$number?>" class="form-control" placeholder="Email address" aria-describedby="basic-addon1" value="<?=!empty($value["public_email"])?htmlspecialchars($value["public_email"]) :"" ?>">
                    <input type="text" name="location<?=$number?>" class="form-control" placeholder="Location" aria-describedby="basic-addon1" value="<?=!empty($value["city_name"])?htmlspecialchars($value["city_name"]) :"" ?>">
                </div>

                <?php if(!empty($value['category'])):?>
                <div class="form-check form-check-inline" name="category-group">
                    <input checked class="form-check-input" type="checkbox" name="category<?=$number?>-1" value="<?=htmlspecialchars($value['category'])?>">
                    <label class="form-check-label" for="inlineCheckbox1"><?=htmlspecialchars($value['category'])?></label>
                </div>
                <?endif;?>
                <div class="form-check-inline input-group-sm mb-3" name="new-category-form">
                    <input type="text" name="new-category" class="form-control" placeholder="Type new category">
                </div>


                <div class="images">
                    <?foreach ($arr_img as $key=>$img):?>
                        <?php  if(isset($img['image_versions2']['candidates'][0]['url'])):?>
                            <img class="img-fluid img-thumbnail selected" src="<?=$img['image_versions2']['candidates'][0]['url']?>" name="media<?=$number?>-<?=$key?>">
                            <input checked type="checkbox" style="display: none" name="media<?=$number?>-<?=$key?>" value="<?=$img['image_versions2']['candidates'][0]['url']?>">
                        <?php elseif(isset($img['carousel_media'][0]['image_versions2']['candidates'][0]['url'])):?>
                            <img  class="img-fluid img-thumbnail selected" src="<?=$img['carousel_media'][0]['image_versions2']['candidates'][0]['url']?>" alt="">
                            <input checked type="checkbox" style="display: none" name="media<?=$number?>-<?=$key?>" value="<?=$img['carousel_media'][0]['image_versions2']['candidates'][0]['url']?>">
                        <?php endif;?>
                    <?endforeach;?>
                </div>
            </div>

            <?endforeach;?>

            <hr>

            <button type="submit" class="btn btn-primary">Save and continue</button>
            </form>
        </div>
    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="custom.js"></script>
</body>
</html>
