<?php  
    include 'php/config.php';
    session_start();
    if(!$_SESSION['username']){
        header('Location: ./index.php');
    }
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $id = $_SESSION['id'];
    

    if(isset($_POST["Image"]))
    {     
        if($_FILES["select_img"]["error"]===4){
            echo "<script>alert('Image does not exist!!')</script>";
        }else {
            $sql = "SELECT img FROM users where Id=$id";    
            $result = mysqli_query($conn,$sql);
            $img = mysqli_fetch_assoc($result)['img'];
            unlink("./img/$img");
            $fileName = $_FILES["select_img"]["name"];
            $fileSize = $_FILES["select_img"]["size"];
            $tmpName = $_FILES["select_img"]["tmp_name"];

            $validExten = ['jpg','jpeg','png'];
            $imageExtension = explode('.',$fileName);
            $imageExtension = strtolower(end($imageExtension));
            if(!in_array($imageExtension,$validExten)){
                echo "<script>alert('Invalid Image Extension!!')</script>";
            } else if($fileSize > 1000000) {
                echo "<script>alert('Image is too big!!')</script>";
            } else {
                $newImageName = uniqid();
                $newImageName.='.'.$imageExtension;

                move_uploaded_file($tmpName,'img/'.$newImageName);
                $sql = "UPDATE users SET img='$newImageName' WHERE Id=$id";
                mysqli_query($conn,$sql);
            }
        }
    }
    $imgSql = "SELECT img FROM users WHERE Id=$id";
    $img = mysqli_query($conn,$imgSql);
    $img = mysqli_fetch_assoc($img)['img'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Grape+Nuts&family=Kanit&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9310e1148a.js" crossorigin="anonymous"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>HEXA</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" 
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" 
            crossorigin="anonymous">
    </script>    
    <script src="js/GetSetPost.js" ></script>
</head>
<body>
    <div class="overlay"></div>
    <div class="navsearch">
         <h2><a href="hexa.php">Projektna <span>nedelja</span></a></h2>
        <input type="text" placeholder="Pretrazite" id="search-input" >
        <button id="aa" aria-pressed="false"><i class="fa-solid fa-magnifying-glass"></i></button>
        <div class="divimg">
            <!--<a href="./user.html"><img src="img/profile2.jpg" width="100%" class="navimg" alt="" srcset=""></a>-->
            <img src="img/<?php echo $img?>" width="100%" class="navimg" alt="" srcset="">
        </div>
    </div>
    <h3 id="userneki"></h3>

    <div class="loadScreen">
        <img src="img/load.svg" alt=“blankimage”>
    </div>
    
    <div class="container">
        <div class="row">
             <div class="col-md-3">
                <!-- OVO  NEBI TREBALO DA POSTOJI -->

            <div class="left-wrapper">
                <div class="inner-container left-side">
                  
                </div>   
            </div>
                 
            <section class="conversation">
                <button class="closeBtn closeConv"><i class="fa-regular fa-x"></i></button>
                <div class="messages"></div>
                <div class="controls">
                    <input type="text" class="msg" placeholder="New message">
                    <button class="sendMsg"> <i class="fa-sharp fa-solid fa-paper-plane"></i></button>
                </div>
            </section>

            <div class="inner-container popup-prof">
                <a href="./user.html"><img src="img/<?php echo $img?>" alt="" class="profile"></a>
                
                <p><b id="username" name = "<?php echo $id?>"><?php echo $username?></b></p>
                <p id="email"><?php echo $email?></p>

                <button id="editAccount">Izmeni nalog</button>
                <button id="logout">Logout</button>   
            </div>


            </div>
            <div class="col-md-9">
                <div class="inner-container right-side">
                    <form id="postForm">
                        <textarea name="text" id="postContent" cols="" rows="" placeholder="Napisi nesto i objavi.."></textarea>
                        <button name="Post" id="postBtn">Objavi</button>
                    </form>
                    <div id="allPostsWrapper"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-modal">
        <button id="closeModal">X</button>
        <form id="editForm">
            <h2>Izmenite korisnika</h2>
                <div>
                    <label for="korisnicko_ime">Korisnicko ime: *</label>
                    <input type="text" id="korisnicko_ime" name="korisnicko_ime" placeholder="example123">
                </div>

                <div>
                    <label for="edit_email">Email adresa: *</label>
                    <input type="text" id="edit_email" name="register_email" placeholder="example@email.com">
                </div>
                <div>
                    <label for="edit_password">Sifra: *</label>
                    <input type="password" id="edit_password" name="sifra" placeholder="********">
                </div>
                

                <div>
                    <button id="#SubmitEdit">Izmeni</button>
                </div>
        </form>
        <form method="POST" action="hexa.php" autocomplete="off" enctype="multipart/form-data">
                    <label for="edit_password">Izmeni Sliku</label>
                    <input type="file" id="select_img" name="select_img">
                    <button type="submit" name="Image">Send files</button>
        </form>

        <hr>

        <button id="deleteProfile">Obrisi profil</button>
    </div>

    <footer><i class="fa-regular fa-star" id="footstar"></i><img src="img/profile2.jpg" width="100%" class="navimg" id="imgsmall"alt="" srcset=""></footer>

    <script src="js/Session.js"></script>
    <script src="js/AllPages.js"></script> 
</body>
</html>