<?php
//clear request
file_put_contents("option.txt","");
file_put_contents("username.txt","");
file_put_contents("type.txt","");
file_put_contents("userlist.txt","");
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
<div class="container" style="padding-top:10%">
    <div class="row">
        <div class="col" style="width: 100%; max-width: 330px; padding: 15px; margin: auto;">
            <h1>📸</h1>
            <hr>
            <form action="scrapper.php" method="post">

                <div class="form-group">

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
                        <label class="form-check-label" for="inlineRadio1">From account</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                        <label class="form-check-label" for="inlineRadio2">From list</label>
                    </div>

                </div>

                <div id="id1">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" id="exampleFormControlInput1" placeholder="Type instagram username...">
                    </div>
                    <div class="form-group">
                        <select name="type" class="form-control" id="exampleFormControlSelect1">
                            <option value="followers">Followers</option>
                            <option value="following">Following</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>

                <div id="id2">
                    <div class="form-group">
                        <textarea name="user-list" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>

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
