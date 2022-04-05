<?php

error_reporting(0);
include("header.php");
if (!isset($_SESSION['user_email'])) {
  header("location: login.php");
} else {
  $user_email = $_SESSION['user_email'];
  discussion_list();
  if (isset($_GET['discussion'])) {
    getMessage();
  }

  //if (isset($_POST['send'])) {

  //  send_message();
  //}
  if (isset($_GET['user1']) and isset($_GET['user2'])) {
    create_discussion($_GET['user1'], $_GET['user2']);
  }
}


function send_message()
{

  if (isset($_POST['send'])) {
    if ($_REQUEST['content'] != '') {
      $user_email = $_SESSION['user_email'];
      $row_user = get_current_user1($user_email);
      $user_id =  $row_user['user_id'];


      //$content = htmlentities($_POST['content']);
      $content = htmlentities($_POST['content']);
      //echo "<script>alert( '$content')</script>";
      $disc = $_GET['discussion'];
      $row_discussion = select_discussion($disc);
      $disc = $_GET['discussion'];
      //echo "<script>alert( '$disc')</script>";
      if ($row_discussion['Member1'] <> $user_id) {
        $friend = $row_discussion['Member1'];
      } else {
        $friend = $row_discussion['Member2'];
      }

      $insert = "insert into messages (messageContent, transmitter, receiver,timestamp,Discussion_id) values ('$content','$user_id','$friend', NOW(),'$disc')";
      $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
      $run = mysqli_query($con, $insert);
      //echo "<script>alert('$insert')</script>";
      if ($run) {
        //echo "<script>alert('Message Sent!')</script>";
        //echo "<script>prompt()</script>";
        $update = "update discussion set Discussion_timestamp=NOW() where Discussion_id='$disc'";
        $run_update = mysqli_query($con, $update);
        //header("Location: chat.php?discussion=$disc");
        // unset($_REQUEST['content']);
        $_POST['send'] = '';
        //getMessage();
        //$url1="<script>window.open('chat.php?discussion=";
        //$url2="\", '_self')</script>'";
        //echo "$url1$disc$url2";

      } else {
        //echo "<script>window.open('chat.php?discussion=$disc, '_self')</script>";
        //header("Location: chat.php?discussion=$disc");
        $url1 = "<script>window.open('chat.php?discussion=";
        $url2 = "\", '_self')</script>'";
        //echo "<script>prompt('echec')</script>";
        //echo "$url1$disc$url2";

      }

      //$content=$_REQUEST['content'];
      //echo "<script>alert( '$content')</script>";
    }
    /*  else {
            echo "<script>alert('POST')</script>";
        }*/
  }
  //else echo '<script>alert("Empty message")</script>';
  // $_POST = array();

  //Header('Location: '.$_SERVER['PHP_SELF']);
  // $page = $_SERVER['PHP_SELF'];
  //

  //echo '<meta http-equiv="Refresh" content="0;' . $page . '">';

}



function getFriend()
{
  $user_email = $_SESSION['user_email'];
  $row_user = get_current_user1($user_email);
  $user_id =  $row_user['user_id'];

  $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
  $disc = $_GET['discussion'];
  $row_discussion = select_discussion($disc);

  //echo "<script>alert( '$disc')</script>";
  if ($row_discussion['Member1'] <> $user_id) {
    $friend = $row_discussion['Member1'];
  } else {
    $friend = $row_discussion['Member2'];
  }
  return $friend;
}

function getMessage()
{
  if (isset($_GET['discussion'])) {
    $user_email = $_SESSION['user_email'];
    $discussion_id = $_GET['discussion'];
    $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
    $user = "select * from users where user_email ='$user_email'";
    $run_user = mysqli_query($con, $user);
    $row_user = mysqli_fetch_array($run_user);
    $user_id = $row_user['user_id'];
    $user_idG = $user_id;
    $user_name = $row_user['user_name'];
    $friend_id = getFriend();
    //echo '<script>alert("Pass 1 '$friend_id'")</script>';
    $user2 = "select * from users where user_id ='$friend_id'";
    $run_user2 = mysqli_query($con, $user2);
    $row_user2 = mysqli_fetch_array($run_user2);

    $friend_name = $row_user2['user_name'];
    //discussion_list();
    $friend_img = "SELECT user_image FROM users WHERE user_id =$friend_id";
    $run_fimg = mysqli_query($con, $friend_img);
    $row_fimg = mysqli_fetch_array($run_fimg);
    $friend_img = $row_fimg['user_image'];


    echo " 
            <div id='msg' class='content'>        
                 <div class='contact-profile'>         
                        <img src='$friend_img' alt='' />                        
                        <h4> Discussion avec : <span class= 'fr'>$friend_name</span></h4>                              
		        </div>
        	   	<div id='messages' class='messages'>
                                                      
   
        		<ul>";
    $get_messages = "select * from messages WHERE discussion_ID='$discussion_id'ORDER BY `timestamp` ASC";
    $run_messages = mysqli_query($con, $get_messages);
    $i = 1;
    while ($row_messages = mysqli_fetch_array($run_messages)) {

      $transmitter = $row_messages['transmitter'];
      $receiver = $row_messages['receiver'];
      $message_date = $row_messages['timestamp'];
      $content = $row_messages['messageContent'];

      //echo "<script>alert( '$content');</script>";
      $i++;
      // echo "</br></br></br>";

      //now displaying messages from database

      if ($transmitter == $user_id) {
        $trans_img = "SELECT user_image FROM users WHERE user_id=$transmitter";
        $run_timg = mysqli_query($con, $trans_img);
        $row_timg = mysqli_fetch_array($run_timg);
        $trans_img = $row_timg['user_image'];

        echo
        "
                    <li class='sent'>
    				      	<img src='$trans_img' alt='' /> 
    					<p> $content </p>
                        <span class='time-left'>$message_date</span>
    				</li>
                    ";
      } else if ($receiver == $user_id) {
        $rec_img = "SELECT user_image FROM users WHERE user_id=$transmitter";
        $run_rimg = mysqli_query($con, $rec_img);
        $row_rimg = mysqli_fetch_array($run_rimg);
        $rec_img = $row_rimg['user_image'];

        echo
        "
                    <li class='replies'>
                    <img src='$rec_img' alt='' /> 
    					<p> $content </p>
                      <li>  <span class='time-right'>$message_date</span></li>
    				</li>
                    ";
      } else {
        echo '<script>alert("No message")</script>';
      }
    }
    echo
    "</ul>
		</div>
		<div class='message-input'>
			<div class='wrap'>
                <form method='post'>
                    <input type='text' id='content' style='width:615px' name='content' placeholder='Write your message...' />
                    <button name='send' onClick='location.reload();' class='sending'>Send</button>
                </form>     
                    
			</div>
		</div>";
    if (isset($_POST['content']) && isset($_POST['send'])) {
      send_message();
      $_POST = array();
    }
    //echo "<script>ocation.reload()</script>";
    //echo "<script>window.open('chat.php?discussion='$discussion_id', '_self')</script>";

  }
}

function check_discussion(INT $user2)
{
  $user_email = $_SESSION['user_email'];

  $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
  $user1 = mysqli_query($con, "select * from users where user_email ='$user_email'");
  $result = mysqli_query($con, "select Discussion_id from Discussion WHERE Member1='$user1' OR Member1='$user2' AND Member2='$user1' OR Member1='$user2'");
  if (!$result) {
    //getMessage($user2);
  } else {
    $run = mysqli_query($con, "insert into discussion values ('',$user1,$user2,insert_time=now()");
    //getMessage($user2);
  }
}

function get_current_user1($user_email)
{
  //$user_email=$_SESSION['user_email'];
  $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
  $user = "select * from users where user_email ='$user_email'";
  $run_user = mysqli_query($con, $user);
  $row_user = mysqli_fetch_array($run_user);
  return $row_user;
}

function select_discussion($disc)
{
  $user_email = $_SESSION['user_email'];
  $row_user = get_current_user1($user_email);
  $user_id =  $row_user['user_id'];
  $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
  //$discussion= "select * from discussion where Member1 ='$user_id' OR Member2 ='$user_id' ORDER BY `Discussion_timestamp` DESC";
  $discussion = "select * from discussion where Discussion_id='$disc' ORDER BY `Discussion_timestamp` DESC";
  $run_discussion = mysqli_query($con, $discussion);
  $row_discussion = mysqli_fetch_array($run_discussion);
  return $row_discussion;
}

function create_discussion($user1, $user2)
{
  $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
  $result = mysqli_query($con, "select * from discussion where (Member1 ='$user1' OR Member2 ='$user1') AND (Member1 ='$user2' OR Member2 ='$user2')");
  $new_discussion = mysqli_fetch_array($result);
  $new_discussion_id = $new_discussion['Discussion_id'];
  //echo "<script>alert('$user1 & $user2')</script>";
  if (!isset($new_discussion_id)) {
    $insert = "insert into discussion (Member1, Member2 ,Discussion_timestamp) values ('$user1','$user2', NOW())";
    $content = htmlentities($_POST['content']);
    $run = mysqli_query($con, $insert);

    $result = mysqli_query($con, "select * from discussion where (Member1 ='$user1' OR Member2 ='$user1') AND (Member1 ='$user2' OR Member2 ='$user2')");
    $new_discussion = mysqli_fetch_array($result);
    $new_discussion_id = $new_discussion['Discussion_id'];
    //echo "<script>alert('$new_discussion_id')</script>";
        $url1='<script>window.open("chat.php?discussion=';
        $url2="\", '_self')</script>'";
        echo "$url1$new_discussion_id$url2";

  }
}

function discussion_list()
{

  $disc = 1;
  if (isset($_GET['discussion']))
    $disc = $_GET['discussion'];
  echo "<body>
  
       <div id='frame'>
       <div id='sidepanel'>
       <div id='searching'>
    
         
         <form  method='Get'>
    
           <label for=''><i class='fa fa-search' aria-hidden='true'></i></label>
           <input type='text' id='search' name='search' placeholder='Search contacts...' />
       

            <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js'  type='text/javascript'></script>
            <script type='text/javascript'>
            $(document).ready(function() {
              $('#search').keydown(function(e) {
            
                if (e.which === 13) {
                    window.location = window.location.href+&document.getElementById('search').value;
                  }
                  return false;
                }
              });
            });
            </script>

           </br>
           <div id='display'></div>
           </form>
          
         </div>
         <div id='contacts'>
           <ul>
             ";

  if (isset($_GET['search'])) {

    $search = $_GET['search'];

    $user_email = $_SESSION['user_email'];
    $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
    $row_user = get_current_user1($user_email);
    $user_id =  $row_user['user_id'];
    $current_user_name = $row_user['user_name'];
    $user = "select * from users WHERE user_name LIKE '%$search%' AND user_id<>'$user_id'";
    $run_user = mysqli_query($con, $user);
    

    $i = 1;
    while ($row_user2 = mysqli_fetch_array($run_user)) {

      $user_id2 = $row_user2['user_id'];
      $user_name = $row_user2['user_name'];
     
      $discussion = "select * from discussion where (Member1 ='$user_id' OR Member2 ='$user_id') AND (Member1 ='$user_id2' OR Member2 ='$user_id2') ORDER BY `Discussion_timestamp` DESC";
      echo "<script>alert('D $discussion')</script>";
      $run_discussion = mysqli_query($con, $discussion);
      $row_discussion = mysqli_fetch_array($run_discussion);


      if (isset($discussion_id)) {
       
        $discussion_id = $row_discussion['Discussion_id'];
        echo "<script>alert('$discussion')</script>";
       

        $get_messages = "select * from messages WHERE Discussion_id='$discussion_id' ORDER BY `timestamp` DESC";
        $run_messages = mysqli_query($con, $get_messages);
        $row_messages = mysqli_fetch_array($run_messages);


        $img_liste_dis = "SELECT user_image FROM users WHERE user_id=$user_id2";
        $run_img = mysqli_query($con,  $img_liste_dis);
        $row_img = mysqli_fetch_array($run_img);
        $img_liste_dis = $row_img['user_image'];

        echo
        "
                                        <li class='contact'>
                        					<div class='wrap'>
                                                <img src='$img_liste_dis' alt='' />
                        						<div class='meta'>
                                                    <a class='name' href='chat.php?discussion=$discussion_id'>$user_name</a>
                        							<p class='preview'></p>
                        						</div>
                        					</div>
                        				</li>
                                      ";

        //  }
      } else {

        $user = "select * from users where user_name LIKE '%$search%'";
        $run_img = mysqli_query($con,  $user);
        $row_img = mysqli_fetch_array($run_img);
        $img_liste_dis = $row_img['user_image'];

        echo
        "
                                        <li class='contact'>
                        					<div class='wrap'>
                                                <img src='$img_liste_dis' alt='' />
                        						<div class='meta'>
                                                    <a class='name' href='chat.php?user1=$user_id&user2=$user_id2'>$user_name</a>
                        							<p class='preview'>opening</p>
                        						</div>
                        					</div>
                        				</li>
                                      ";
      
      }
      
    }
  } else {
    $user_email = $_SESSION['user_email'];
    $con = mysqli_connect("localhost", "root", "", "projet_web") or die("Connection was not established");
    $row_user = get_current_user1($user_email);
    $user_id =  $row_user['user_id'];
    $user_name = $row_user['user_name'];

    $row_discussion = select_discussion($disc);
    $discussion = "select * from discussion where Member1 ='$user_id' OR Member2 ='$user_id' ORDER BY `Discussion_timestamp` DESC";
    $run_discussion = mysqli_query($con, $discussion);


    while ($row_discussion = mysqli_fetch_array($run_discussion)) {
      //echo "<script>alert(test2)</script>";
      if ($row_discussion['Member1'] <> $user_id) {
        $user2 = $row_discussion['Member1'];
      } else {
        $user2 = $row_discussion['Member2'];
      }
      $discussion_id = $row_discussion['Discussion_id'];
      $user = "select * from users where user_id ='$user2'";
      $run_user = mysqli_query($con, $user);
      $row_user = mysqli_fetch_array($run_user);
      //$user_id = $row_user['user_id'];
      $user_name = $row_user['user_name'];
      $get_messages = "select * from messages WHERE Discussion_id='$discussion_id' ORDER BY `timestamp` DESC";
      $run_messages = mysqli_query($con, $get_messages);
      $row_messages = mysqli_fetch_array($run_messages);

      $img_liste_dis = "SELECT user_image FROM users WHERE user_id=$user2";
      $run_img = mysqli_query($con,  $img_liste_dis);
      $row_img = mysqli_fetch_array($run_img);
      $img_liste_dis = $row_img['user_image'];
      $content = $row_messages['messageContent'];

      if (empty($content)) {
        $content1 = '..';
      } else {

        $content1 = $content;
      }

      echo
      " 
                                <li class='contact'>
                					<div class='wrap'>
                                        <img src='$img_liste_dis' alt='' />
                						<div class='meta'>
                                            <a class='name' href='chat.php?discussion=$discussion_id'>$user_name</a>
                							<p class='preview'> $content1 </p>
                						</div>
                					</div>
                				</li>    
                              ";


    }
  }

  echo "  </ul>
    </div>		
	   </div>";
}

?>
<style class='cp-pen-styles'>
  body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 80px;
    background: #A8D8EA;

    letter-spacing: 0.1px;
    color: #32465a;
    text-rendering: optimizeLegibility;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.004);
    -webkit-font-smoothing: antialiased;

  }

  .fr {
    font-size: 20px;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    color: #393E46;

  }

  h4 {
    font-size: 20px;
    padding: 20px;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    color: #00ADB5;
  }

  .time-right {
    display: block;
    color: #747474;
    font-size: 10px;
    float: right;
    padding-top: 2px;
    padding-right: 35px;

  }


  .time-left {
    display: block;
    color: #747474;
    font-size: 10px;
    padding: 8px 35 0;
  }


  #frame {
    width: 100%;
    min-width: 260px;
    max-width: 1350px;
    height: 100%;
    min-height: 300px;
    max-height: 677px;
    background: #E6EAEA;
    margin: 60 210;
  }

  @media screen and (max-width: 360px) {
    #frame {
      width: 100%;
      height: 100vh;
      font-size: 25 px;

    }
  }

  #frame #sidepanel {
    float: left;
    min-width: 280px;
    max-width: 340px;
    width: 40%;
    height: 100%;
    background: #2c3e50;
    color: #f5f5f5;
    overflow: hidden;
    position: relative;
    font-size: 25 px;

  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel {
      width: 58px;
      min-width: 58px;
      font-size: 25 px;

    }
  }

  #frame #sidepanel #profile {
    width: 80%;
    margin: 25px auto;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #profile {
      width: 100%;
      margin: 0 auto;
      padding: 5px 0 0 0;
      background: #32465a;
    }
  }

  #frame #sidepanel #profile.expanded .wrap {
    height: 210px;
    line-height: initial;
  }

  #frame #sidepanel #profile.expanded .wrap p {
    margin-top: 20px;
  }

  #frame #sidepanel #profile.expanded .wrap i.expand-button {
    -moz-transform: scaleY(-1);
    -o-transform: scaleY(-1);
    -webkit-transform: scaleY(-1);
    transform: scaleY(-1);
    filter: FlipH;
    -ms-filter: 'FlipH';
  }

  #frame #sidepanel #profile .wrap {
    height: 60px;
    line-height: 60px;
    overflow: hidden;
    -moz-transition: 0.3s height ease;
    -o-transition: 0.3s height ease;
    -webkit-transition: 0.3s height ease;
    transition: 0.3s height ease;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #profile .wrap {
      height: 55px;
    }
  }

  #frame #sidepanel #profile .wrap img {
    width: 50px;
    border-radius: 50%;
    padding: 3px;
    border: 2px solid #e74c3c;
    height: auto;
    float: left;
    cursor: pointer;
    -moz-transition: 0.3s border ease;
    -o-transition: 0.3s border ease;
    -webkit-transition: 0.3s border ease;
    transition: 0.3s border ease;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #profile .wrap img {
      width: 40px;
      margin-left: 4px;
    }
  }


  @media screen and (max-width: 735px) {
    #frame #sidepanel #profile .wrap p {
      display: none;
    }
  }






  #frame #sidepanel #profile .wrap #status-options ul {
    overflow: hidden;
    border-radius: 6px;
  }

  #frame #sidepanel #profile .wrap #status-options ul li {
    padding: 15px 0 30px 18px;
    display: block;
    cursor: pointer;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #profile .wrap #status-options ul li {
      padding: 15px 0 35px 22px;
    }
  }






  #frame #sidepanel #profile .wrap #expanded {
    padding: 100px 0 0 0;
    display: block;
    line-height: initial !important;
  }

  #frame #sidepanel #profile .wrap #expanded label {
    float: left;
    clear: both;
    margin: 0 8px 5px 0;
    padding: 5px 0;
  }



  @media screen and (max-width: 500px) {
    #frame #sidepanel #searching {
      display: none;
    }
  }

  #frame #sidepanel #searching label {
    position: absolute;
    margin: 10px 0 0 20px;
  }

  #frame #sidepanel #searching input {
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    padding: 10px 0 10px 46px;
    width: calc(100% - 25px);
    border: none;
    background: #32465a;
    color: #f5f5f5;
  }

  #frame #sidepanel #searching input:focus {
    outline: none;
    background: #00ADB5;
  }

  #frame #sidepanel #searching input::-webkit-input-placeholder {
    color: #f5f5f5;
  }



  #frame #sidepanel #contacts {
    height: calc(100% - 177px);
    overflow-y: scroll;
    overflow-x: hidden;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #contacts {
      height: calc(100% - 149px);
      overflow-y: scroll;
      overflow-x: hidden;
    }

    #frame #sidepanel #contacts::-webkit-scrollbar {
      display: none;

    }
  }

  #frame #sidepanel #contacts.expanded {
    height: calc(100% - 334px);
  }

  #frame #sidepanel #contacts::-webkit-scrollbar {
    width: 8px;
    background: #2c3e50;

  }

  #frame #sidepanel #contacts::-webkit-scrollbar-thumb {
    background-color: #7a8086;
    ;
  }

  #frame #sidepanel #contacts ul li.contact {
    position: relative;
    padding: 10px 0 15px 0;
    font-size: 0.9em;
    cursor: pointer;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #contacts ul li.contact {
      padding: 6px 0 46px 8px;
    }
  }



  #frame #sidepanel #contacts ul li.contact:hover {
    background: #32465a;
  }

  #frame #sidepanel #contacts ul li.contact.active {
    background: #32465a;
    border-right: 5px solid #00ADB5;
  }

  #frame #sidepanel #contacts ul li.contact.active span.contact-status {
    border: 2px solid #5a324b !important;
  }

  #frame #sidepanel #contacts ul li.contact .wrap {
    width: 88%;
    margin: 0 auto;
    position: relative;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #contacts ul li.contact .wrap {
      width: 100%;
    }
  }

  #frame #sidepanel #contacts ul li.contact .wrap span {
    position: absolute;
    left: 0;
    margin: -2px 0 0 -2px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    border: 2px solid #2c3e50;
    background: #95a5a6;
  }

  #frame #sidepanel #contacts ul li.contact .wrap span.online {
    background: #2ecc71;
  }

  #frame #sidepanel #contacts ul li.contact .wrap span.away {
    background: #f1c40f;
  }

  #frame #sidepanel #contacts ul li.contact .wrap span.busy {
    background: #e74c3c;
  }

  #frame #sidepanel #contacts ul li.contact .wrap img {
    width: 40px;
    border-radius: 50%;
    float: left;
    margin-right: 10px;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #contacts ul li.contact .wrap img {
      margin-right: 0px;
    }
  }

  #frame #sidepanel #contacts ul li.contact .wrap .meta {
    padding: 5px 0 0 0;
  }

  @media screen and (max-width: 735px) {
    #frame #sidepanel #contacts ul li.contact .wrap .meta {
      display: none;
    }
  }

  #frame #sidepanel #contacts ul li.contact .wrap .meta .name {
    font-weight: 600;
  }

  #frame #sidepanel #contacts ul li.contact .wrap .meta .preview {
    padding: 0 0 1px;

    font-weight: 400;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    -moz-transition: 1s all ease;
    -o-transition: 1s all ease;
    -webkit-transition: 1s all ease;
    transition: 1s all ease;

  }

  #frame #sidepanel #contacts ul li.contact .wrap .meta .preview span {
    position: initial;
    border-radius: initial;
    background: none;
    border: none;
    padding: 0 2px 0 0;
    margin: 0 0 0 1px;
    opacity: .5;

  }







  #frame .content {
    float: right;
    width: 60%;
    height: 100%;
    overflow: hidden;
    position: relative;

  }

  @media screen and (min-width: 900px) {
    #frame .content {
      width: calc(100% - 340px);
    }
  }

  #frame .content .contact-profile {
    width: 100%;
    height: 60px;
    line-height: 60px;
    background: #f5f5f5;

  }

  #frame .content .contact-profile img {
    width: 40px;
    border-radius: 50%;
    float: left;
    margin: 9px 12px 0 9px;
  }

  #frame .content .contact-profile .social-media i:nth-last-child(1) {
    margin-right: 20px;
  }

  #frame .content .contact-profile .social-media i:hover {
    color: #435f7a;
  }

  #frame .content .messages {
    height: auto;
    min-height: calc(100% - 125px);
    max-height: calc(100% - 125px);
    overflow-y: scroll;
    overflow-x: hidden;

  }

  @media screen and (max-width: 735px) {
    #frame .content .messages {
      max-height: calc(100% - 105px);


    }
  }

  #frame .content .messages::-webkit-scrollbar {
    width: 8px;
    background: transparent;

  }

  #frame .content .messages::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.3);

  }

  #frame .content .messages ul li {
    display: inline-block;
    clear: both;
    float: left;
    margin: 10px 10px 0px 0px;
    width: calc(100% - 25px);
    font-size: 15px;


  }

  #frame .content .messages ul li:nth-last-child(1) {
    margin-bottom: 20px;
  }

  #frame .content .messages ul li.sent img {
    margin: 8px 8px 0 0;

  }

  #frame .content .messages ul li.sent p {
    background: #2c3e50;
    color: #f5f5f5;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    line-height: 1.6;



  }

  #frame .content .messages ul li.replies img {
    float: right;
    margin: 6px 0 0 8px;


  }

  #frame .content .messages ul li.replies p {
    background: #f5f5f5;
    float: right;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    line-height: 1.6;



  }

  #frame .content .messages ul li img {
    width: 22px;
    border-radius: 50%;
    float: left;
  }

  #frame .content .messages ul li p {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 20px;
    max-width: 200px;
    line-height: 70%;
    margin: auto;

  }

  @media screen and (min-width: 735px) {
    #frame .content .messages ul li p {
      max-width: 300px;
    }
  }

  #frame .content .message-input {
    position: absolute;
    bottom: 0;
    width: 100%;
    z-index: 99;
  }

  #frame .content .message-input .wrap {
    position: relative;
  }

  #frame .content .message-input .wrap input {
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    float: left;
    border: none;
    width: calc(100% - 90px);
    padding: 11px 32px 10px 8px;

    color: #32465a;
  }



  #frame .content .message-input .wrap button {
    float: right;
    border: none;
    width: 90px;
    cursor: pointer;
    background: #00ADB5;
    padding: 10px 5;

    color: #f5f5f5;


  }
</style>


</html>