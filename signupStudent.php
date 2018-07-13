<?php

require_once("conn.php");
$fname=$lname=$dob=$mail=$coll=$contact=$alt_contact=$year=$pass=$re_pass=null; ?>

<?php
session_start();
if(array_key_exists('submit_1', $_POST)){
    if(isset($_POST['f_name']))
        $fname=$_POST['f_name'];
    if(isset($_POST['l_name']))
        $lname=$_POST['l_name'];
    if(isset($_POST['dob']))
        $dob=$_POST['dob'];
    if(isset($_POST['mail']))
        $mail=$_POST['mail'];
    if(isset($_POST['coll']))
        $coll=$_POST['coll'];
    if(isset($_POST['contact']))
        $contact=$_POST['contact'];
    if(isset($_POST['alt_contact']))
        $alt_contact=$_POST['alt_contact'];
    if(isset($_POST['year']))
        $year=$_POST['year'];
    if(isset($_POST['pass']))
        $pass=$_POST['pass'];
    if(isset($_POST['re_pass']))
        $re_pass=$_POST['re_pass'];


    $filename = $_FILES["Upload"]['name'];
    $filetmp = $_FILES["Upload"]['tmp_name'];
    $filesize = $_FILES["Upload"]['size'];
    $file_basename = basename($_FILES["Upload"]['name']);
    $dir="images/";
    $final_dir=$dir.$file_basename;
    move_uploaded_file($filetmp,$final_dir);

    if($alt_contact!="" && $contact==$alt_contact){
        $error.= " Both contact numbers are same";
    }
    elseif (strlen($contact)<10)
    {
        $error.="Please Enter valid numbers";
    }
    else if($pass!=$re_pass){
        $error.= " Passwords do not match";
    }
    else if($filesize >1000000)
    {
        $error.="File size too large";
    }
    else if (mysqli_num_rows($link->query("SELECT * FROM user WHERE email='$mail'"))>0)
    {
        $error.="Email already exists. Try another";
    }
    else{
        $iquery="INSERT INTO image(image_name,image_path) VALUES ('$filename','$final_dir');";
        $result1=$link->query($iquery);
        $fetchid = null;
        $fetchid="SELECT * FROM image WHERE image_path ='$final_dir' ;";
        $result2=$link->query($fetchid);
        $row = $result2->fetch_assoc();
        $id1 = $row["id"];
        $pass=md5($pass);
        $query="INSERT INTO user (first_name, last_name, dob, email, college, contact_no, alternate_contact_no,image_id, year, password,student) VALUES ('$fname','$lname','$dob','$mail','$coll','$contact','$alt_contact','$id1','$year','$pass','1')";
        $result=mysqli_query($link, $query);
        if(!$result){
            $error.=" Some error in database connection:". mysqli_error($link);
        }
        else{
            $error="Form submitted successfully !";
            session_start("email");
            $_SESSION['email'] = $mail;
            $_SESSION['loggedin'] = true;
            header("location: studentDashboard.php");
        }
    }
}
?>
<html>
<head>
    <title>Login to Knowledge</title>

    <script type="text/javascript" src="jquery.min.js"></script>

    <link rel="stylesheet" href="style/slider-def.css">
    <link href="css/main.css" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/css?family=Mirza" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merienda" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" crossorigin="anonymous">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://use.fontawesome.com/9adc8cef54.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
    <style>
        .hide-text{font:0/0 a;color:transparent;text-shadow:none;background-color:transparent;border:0;}
        .input-block-level{display:block;width:100%;min-height:30px;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
        .btn-file{overflow:hidden;position:relative;vertical-align:middle;}.btn-file>input{position:absolute;top:0;right:0;margin:0;opacity:0;filter:alpha(opacity=0);transform:translate(-300px, 0) scale(4);font-size:23px;direction:ltr;cursor:pointer;}
        .fileupload{margin-bottom:9px;}.fileupload .uneditable-input{display:inline-block;margin-bottom:0px;vertical-align:middle;cursor:text;}
        .fileupload .thumbnail{overflow:hidden;display:inline-block;margin-bottom:5px;vertical-align:middle;text-align:center;}.fileupload .thumbnail>img{display:inline-block;vertical-align:middle;max-height:100%;}
        .fileupload .btn{vertical-align:middle;}
        .fileupload-exists .fileupload-new,.fileupload-new .fileupload-exists{display:none;}
        .fileupload-inline .fileupload-controls{display:inline;}
        .fileupload-new .input-append .btn-file{-webkit-border-radius:0 3px 3px 0;-moz-border-radius:0 3px 3px 0;border-radius:0 3px 3px 0;}
        .thumbnail-borderless .thumbnail{border:none;padding:0;-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;-webkit-box-shadow:none;-moz-box-shadow:none;box-shadow:none;}
        .fileupload-new.thumbnail-borderless .thumbnail{border:1px solid #ddd;}

        body{

            font-family: "Avant Garde", Avantgarde, "Century Gothic", CenturyGothic, "AppleGothic", sans-serif;
        }
        .navbar {

            background: #F44336 !important;
            background: rgba(0, 0, 0, 0.78); /*NON-IE*/

        }
        footer{
            background: #ffffff;
        }
        body{
            background: #cb2d3e;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to top, #ef473a, #cb2d3e);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to top, #ef473a, #cb2d3e); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }
    </style>
</head>
<body>

<nav class="navbar fixed-top navbar-toggleable-md navbar-inverse">

    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

    </button>

    <div class="container">
        <div class="row">
            <a class="navbar-brand" href="home.php"><img class="img-responsive" src="images/logo.png" alt="logo"></a>
            <h2 style="color:white;" class="educot-logo">educot</h2>
        </div></div>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

        <ul class="navbar-nav ml-auto">

            <li class="nav-item active">

                <a class="nav-link" href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Home<span class="sr-only">(current)</span></a>

            </li>

            <li class="nav-item active dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-book" aria-hidden="true"></i>Subjects/Classes
                </a>
                <div class="dropdown-menu row" aria-labelledby="navbarDropdown">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3">
                                <span class="text-uppercase">Classes</span>
                                <div class="dropdown-divider"></div>
                                <ul class="nav flex-column">
                                    <li class="dropdown-item">
                                        <a class="" href="#">Class 6</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Class 7</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Class 8</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Class 9</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Class 10</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Class 11</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Class 12</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="text-uppercase">Subjects</span>
                                <div class="dropdown-divider"></div>
                                <ul class="nav flex-column">
                                    <li class="dropdown-item">
                                        <a class="" href="#">Science</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Maths</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Social Studies</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Computer Science</a>
                                    </li>
                                    <li class="dropdown-item">
                                        <a class="" href="#">Aptitude</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item disabled" href="#">More Subjects coming soon</a>
                </div>
            </li>

            <li class="nav-item active">

                <a class="nav-link" href="aboutus.php"><i class="fa fa-superpowers" aria-hidden="true"></i>About Us</a>

             </li>

            <li class="nav-item active">

                <a class="nav-link divider" href="contactus.php"><i class="fa fa-envelope" aria-hidden="true"></i> Contact Us</a>

            </li>
            <?php if(null==$_SESSION){?>
                <a href="login.php"  class="btn btn-primary" type="button">Login/Sign Up</a>
            <?php } else { ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 40px">
                        <i class="fa fa-user" aria-hidden="true"></i> ACCOUNT
                    </a>

                    <div class="dropdown-menu row" aria-labelledby="navbarDropdown">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3">
                                    <span class="text-uppercase">DETAILS</span>
                                    <div class="dropdown-divider"></div>
                                    <ul class="nav flex-column">
                                        <li class="dropdown-item">
                                            <a class="" href="logout.php">Logout</a>
                                        </li>
                                        <li class="dropdown-item">
                                            <a class="" href="#">Recent Details</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li><?php } ?>







        </ul>
    </div>

</nav>
<section class="login-section">
    <br/>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center signup2">
                <img src="images/educot-brand-heading.png" style="width:50%" class="image-responsive">

                <form method="post" id = "signUpForm" class="form-signin" enctype="multipart/form-data">

                    <h2 class="form-signin-heading">Please fill the form</h2><br>
                    <div>
                        <?php
                        if($error!="")
                        {
                            echo '<div class="alert alert-danger" role="alert">'.$error.'</div><br>';
                        }
                        ?>
                    </div>


                    <div class="student-signup-form">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="fname" name="f_name" class="form-control sign-input" placeholder="First Name" required autofocus>
                                <label for="inputFName" class="sr-only">First Name</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="lname" name="l_name" class="form-control sign-input" placeholder="Last Name" required>
                                <label for="inputLName" class="sr-only">Last Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">

                                <input type="Date" id="dob" name="dob" class="form-control sign-input" placeholder="Date of Birth" pattern="(0?[1-9]|[12][0-9]|3[01])/(0?[1-9]|1[012])/\d{4}" title="correct DD/MM/YYYY" required autofocus>
                                <label for="inputDOB" class="sr-only">DD/MM/YYYY</label>
                            </div>
                            <div class="col-md-6">
                                <input type="email" id="mail" name="mail" class="form-control sign-input" placeholder="Email address" pattern="[a-z0-9._]+@[a-z0-9]+\.[a-z]{2,3}" title="someone@example.com" required autofocus><!---->
                                <label for="inputEmail" class="sr-only">Email Address</label>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" id="coll" name="coll" class="form-control sign-input" placeholder="College/School" required>
                                <label for="inputColl" class="sr-only">College/School Name</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="year" name="year" class="form-control sign-input" placeholder="Class/Year" required>
                                <label for="inputYear" class="sr-only">Class/Year</label>

                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <input type="number" id="contact" name="contact" class="form-control sign-input" placeholder="Contact no." pattern="[0-9]{10,10}" title="0123456789" ><!---->
                                <label for="inputCont" class="sr-only">Contact Number</label>
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="alt_contact" name="alt_contact" class="form-control sign-input" placeholder="Alternate Contact no." pattern="[0-9]{10,10}" title="0123456789" ><!---->
                                <label for="inputCont" class="sr-only">Alt Contact Number</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="inputPassword" class="sr-only">Password</label>
                                <input type="password" id="pass" name="pass" class="form-control sign-input" placeholder="Password" pattern="[a-zA-Z0-9]{2,0}" title="password length 6 to 10 alphanumeric" required ><!---->
                            </div>
                            <div class="col-md-6">
                                <label for="inputPassword2" class="sr-only">Confirm Password</label>
                                <input type="password" id="re_pass" name="re_pass" class="form-control sign-input" placeholder="Confirm Password" pattern="[a-zA-Z0-9]{2,0}" title="password length 6 to 10 alphanumeric" required ><!---->

                            </div>

                            <div class="container">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <span class="btn btn-outline-danger btn-file">
                                    <span class="fileupload-new">Upload Image</span>
                                    <span class="fileupload-exists">Upload Image</span>
                                    <input type="file"  name="Upload" accept=".jpeg,.png,.svg,.jpg"/>
                                    </span>
                                    <span class="fileupload-preview"></span>
                                    <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
                                </div>
                            </div>
                        </div><br/>

                        <button type="submit" class="btn btn-outline-danger btn-lg" name='submit_1' value="1">Sign up</button>

                    </div>

                </form>
                <div><p>Already registered? <a><button class="toggleForms btn btn-danger btn-sm" id="myButton">Sign In</button></p></a></div>

            </div>
        </div>

    </div> <!-- /container -->
</section>
<br/><br/>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <span class="copyright">© 2018 Educot. All Rights Reserved </span>
            </div>
            <div class="col-md-4">
                <div class="text-center center-block">


                    <a href="https://www.facebook.com/Jump2school/"><i id="social-fb" class="fa fa-facebook-square fa-3x social"></i></a>
                    <a href="https://www.youtube.com/channel/UCZW65ojZVSWB5Ct9UYZymcg"><i id="social-tw" class="fa fa-youtube-square fa-3x social"></i></a>

                </div>
            </div>

            <div class="col-md-4">
                <span>| Design by <a href="https://www.facebook.com/aman.r.singh.50">Advanced Tribe</a></span>
            </div>
        </div>
    </div>
</footer>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>
<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>

<script type="text/javascript">
    //signup/login
    $(".toggleForms").click(function() {


    });

    $("#student-signup").click(function(){
        $("#student-signup").hide();
        $("#job-signup").hide();
        $(".student-signup-form").show();
    });
    $("#job-signup").click(function(){
        $("#student-signup").hide();
        $("#job-signup").hide();
        $(".job-signup-form").show();
    });

    function checkScroll(){
        var startY = $('.navbar').height() * 2; //The point where the navbar changes in px

        if($(window).scrollTop() > startY){
            $('.navbar').addClass("scrolled");
        }else{
            $('.navbar').removeClass("scrolled");
        }
    }

    if($('.navbar').length > 0){
        $(window).on("scroll load resize", function(){
            checkScroll();

        });
    }

    $('.navbar-toggler').click(function(){
        var startY = $('.navbar').height() * 2;
        if($(window).scrollTop() < startY){
            $('.navbar').toggleClass("scrolled");
        }
    });

    // typer for hello
    window.onload = function() {
        var elements = document.getElementsByClassName('txt-rotate');
        for (var i = 0; i < elements.length; i++) {
            var toRotate = elements[i].getAttribute('data-rotate');
            var period = elements[i].getAttribute('data-period');
            if (toRotate) {
                new TxtRotate(elements[i], JSON.parse(toRotate), period);
            }
        }
        // INJECT CSS
        var css = document.createElement("style");
        css.type = "text/css";
        css.innerHTML = ".txt-rotate > .wrap { border-right: 10px solid #ffffff }";
        document.body.appendChild(css);
    };

    var TxtRotate = function(el, toRotate, period) {
        this.toRotate = toRotate;
        this.el = el;
        this.loopNum = 0;
        this.period = parseInt(period, 1) || 1000;
        this.txt = '';
        this.tick();
        this.isDeleting = false;
    };

    TxtRotate.prototype.tick = function() {
        var i = this.loopNum % this.toRotate.length;
        var fullTxt = this.toRotate[i];

        if (this.isDeleting) {
            this.txt = fullTxt.substring(0, this.txt.length - 1);
        } else {
            this.txt = fullTxt.substring(0, this.txt.length + 1);
        }

        this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

        var that = this;
        var delta = 200 - Math.random() * 100;

        if (this.isDeleting) {
            delta /= 2;
        }

        if (!this.isDeleting && this.txt === fullTxt) {
            delta = this.period;
            this.isDeleting = true;
        } else if (this.isDeleting && this.txt === '') {
            this.isDeleting = false;
            this.loopNum++;
            delta = 200;
        }

        setTimeout(function() {
            that.tick();
        }, delta);
    };
</script>
<script type="text/javascript">
    document.getElementById("myButton").onclick = function () {
        location.href = "login.php";
    };
</script>

<script>
    var file = undefined;
    ! function(e) {
        var t = function(t, n) {
            this.$element = e(t), this.type = this.$element.data("uploadtype") || (this.$element.find(".thumbnail").length > 0 ? "image" : "file"), this.$input = this.$element.find(":file");
            if (this.$input.length === 0) return;
            this.name = this.$input.attr("name") || n.name, this.$hidden = this.$element.find('input[type=hidden][name="' + this.name + '"]'), this.$hidden.length === 0 && (this.$hidden = e('<input type="hidden" />'), this.$element.prepend(this.$hidden)), this.$preview = this.$element.find(".fileupload-preview");
            var r = this.$preview.css("height");
            this.$preview.css("display") != "inline" && r != "0px" && r != "none" && this.$preview.css("line-height", r), this.original = {
                exists: this.$element.hasClass("fileupload-exists"),
                preview: this.$preview.html(),
                hiddenVal: this.$hidden.val()
            }, this.$remove = this.$element.find('[data-dismiss="fileupload"]'), this.$element.find('[data-trigger="fileupload"]').on("click.fileupload", e.proxy(this.trigger, this)), this.listen()
        };
        t.prototype = {
            listen: function() {
                this.$input.on("change.fileupload", e.proxy(this.change, this)), e(this.$input[0].form).on("reset.fileupload", e.proxy(this.reset, this)), this.$remove && this.$remove.on("click.fileupload", e.proxy(this.clear, this))
            },
            change: function(e, t) {
                if (t === "clear") return;
                var n = e.target.files !== undefined ? e.target.files[0] : e.target.value ? {
                    name: e.target.value.replace(/^.+\\/, ""),
                    size: e.target.value.size,
                } : null;
                if (!n) {
                    this.clear();
                    return
                }
                this.$hidden.val(""),
                    this.$hidden.attr("name", ""),
                    this.$input.attr("name", this.name);
                if (typeof FileReader != "undefined") {
                    var r = new FileReader,
                        i = this.$preview,
                        s = this.$element;
                    r.onload = function(e) {
                        var result = {
                            name: n.name,
                            data: e.target.result,
                            size: n.size,
                        }
                        i.text(result.name), s.addClass("fileupload-exists").removeClass("fileupload-new")
                    }, r.readAsDataURL(n)
                } else this.$preview.text(n.name), this.$element.addClass("fileupload-exists").removeClass("fileupload-new")
            },
            clear: function(e) {
                this.$hidden.val(""), this.$hidden.attr("name", this.name), this.$input.attr("name", "");
                if (navigator.userAgent.match(/msie/i)) {
                    var t = this.$input.clone(!0);
                    this.$input.after(t), this.$input.remove(), this.$input = t
                } else this.$input.val("");
                this.$preview.html(""), this.$element.addClass("fileupload-new").removeClass("fileupload-exists"), e && (this.$input.trigger("change", ["clear"]), e.preventDefault())
                file = undefined;
            },
            reset: function(e) {
                this.clear(), this.$hidden.val(this.original.hiddenVal), this.$preview.html(this.original.preview), this.original.exists ? this.$element.addClass("fileupload-exists").removeClass("fileupload-new") : this.$element.addClass("fileupload-new").removeClass("fileupload-exists")
            },
            trigger: function(e) {
                this.$input.trigger("click"), e.preventDefault()
            }
        }, e.fn.fileupload = function(n) {
            return this.each(function() {
                var r = e(this),
                    i = r.data("fileupload");
                i || r.data("fileupload", i = new t(this, n)), typeof n == "string" && i[n]()
            })
        }, e.fn.fileupload.Constructor = t, e(document).on("click.fileupload.data-api", '[data-provides="fileupload"]', function(t) {
            var n = e(this);
            if (n.data("fileupload")) return;
            n.fileupload(n.data());
            var r = e(t.target).closest('[data-dismiss="fileupload"],[data-trigger="fileupload"]');
            r.length > 0 && (r.trigger("click.fileupload"), t.preventDefault())
        })
    }(window.jQuery)
</script>
</body>
</html>
