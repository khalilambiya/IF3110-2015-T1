<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="SSEstylesheet.css">
</head>

<?php
$questionID=$_GET["questionID"];
//commencing database access
$servername = "localhost";
$username = "root";
$password = "";
$basisdata= "question";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $basisdata);
//fetching data
$sql =  "select asked_by, questiontopic , vote_point, answers, content, datetime
             from questions where question_id=$questionID";
$result = mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($result);
$asked_by = $row["asked_by"];
$questiontopic = $row["questiontopic"];
$vote_point = $row["vote_point"];
$nAnswerquestion = $row["answers"];
$contentquestion = $row["content"];
$datetimequestion = $row["datetime"];

?>



<body>
<Center>
    <br>
    <a href="Homepage.php" id="dashboard">Simple StackExchange</a>
    <br><br>

    <h4 class="relative5">
        <?php
            echo $questiontopic;
        ?>
    </h4>

    <hr width="770">

   <table width="700" border="0px">
       <tr>
           <td width="10">
               <img src="arrowup.png" alt="upvote" style="width:40px;height:40px;" align="center" onclick="Upvote()">
           </td>
           <td rowspan="3">
               <?php
               echo $contentquestion;
               ?>
           </td>
       </tr>

       <tr>
           <td>
               <center>
                   <div id="votepoint">
                       <?php
                       echo $vote_point;
                       ?>
                   </div>

               </center>
           </td>
       </tr>

       <tr>
           <td>
               <img src="arrowdown.png" alt="upvote" style="width:40px;height:40px;" align="center" onclick="Downvote()">
           </td>
       </tr>

       <tr>
           <td colspan="4" align="right">

               asked by
               <a href="http://www.google.com" id="bluelink">
                   <?php
                   echo $asked_by;
                   ?></a>
               at <?php
               echo $datetimequestion;
               ?>
               [
               <a href="editquestion.php?questionID=<?php echo $questionID ?>">edit</a>
               ]
               <a href="deleteonequestion.php?questionID='<?php echo $questionID?>'" id="redlink">delete</a>

           </td>
       </tr>

       </table>

    <script>
        function Upvote() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("votepoint").innerHTML = xhttp.responseText;
                }
            }
            xhttp.open("GET", "upvote.php?questionID="+<?php echo $questionID?>, true);
            xhttp.send();
        }
    </script>

    <script>
        function Downvote() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("votepoint").innerHTML = xhttp.responseText;
                }
            }
            xhttp.open("GET", "downvote.php?questionID="+<?php echo $questionID?>, true);
            xhttp.send();
        }
    </script>



    <br><br><br><br>

    <?php

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $basisdata);
    //Count questions
    $sql = "select count(question_id)
            as num_of_answers
            from answer
            where question_id=$questionID;
            ";
    $result = mysqli_query($conn,$sql);
    $fetched = mysqli_fetch_assoc($result);
    if ($fetched["num_of_answers"]==NULL){
        $num_of_answers=0;
    }
    else{
        $num_of_answers = $fetched["num_of_answers"];
    }

    echo '<h4 class="relative4">

            '.$num_of_answers.' Answers
        </h4>';

    $displayanswer=$num_of_answers;
    if ($displayanswer>2){
        $displayanswer=2;
    }
    //fetching data
    $sql =  "select answer_id, answered_by, vote, content, datetime, email
         from answer
         where question_id=$questionID;";
    $result = mysqli_query($conn,$sql);
    $answer_id = array();
    $answered_by = array();
    $vote = array();
    $content = array();
    $datetime = array();
    $email = array();

    for ($nAnswer = 1;$nAnswer<=$num_of_answers;$nAnswer++){
        $row=mysqli_fetch_assoc($result);
        $answer_id[$nAnswer] = $row["answer_id"];
        $answered_by_by[$nAnswer] = $row["answered_by"];
        $vote[$nAnswer] = $row["vote"];
        $content[$nAnswer] = $row["content"];
        $datetime[$nAnswer] = $row["datetime"];
        $email[$nAnswer] = $row["email"];
    }
    //Close Connection
    mysqli_close($conn);

    echo '
    <center>
        <h4 class="relativeshowingof">
            showing '.$displayanswer.' of '.$num_of_answers.'
        </h4>
    </center>
    ';
    for ($count=1;$count<=$displayanswer;$count++) {
        echo '
     <hr width="770">

   <table width="700" border="0px">
       <tr>
           <td width="10">
               <img src="arrowup.png" alt="upvote" style="width:25px;height:25px;" align="center" onclick="Upvoteanswer('.$answer_id[$count].')">
           </td>
           <td rowspan="3">
           '.$content[$count].'
           </td>
       </tr>

       <tr>
           <td>
               <center><div id="voteanswer'.$answer_id[$count].'">
                   '.$vote[$count].'
               </center></div>
           </td>
       </tr>

       <tr>
           <td>
               <img src="arrowdown.png" alt="upvote" style="width:25px;height:25px;" onclick="Downvoteanswer('.$answer_id[$count].')">
           </td>
       </tr>

       <tr>
           <td colspan="4" align="right">

               answered by
               <a href="http://www.google.com" id="bluelink">'.$email[$count].'</a>
               at '.$datetime[$count].'
               <a href="deleteoneanswer.php?answerID='.$answer_id[$count].'" id="redlink">delete</a>

           </td>
       </tr>

       </table>'
        ;
    }

    ?>

    <script>
        function Upvoteanswer(int) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("voteanswer"+int).innerHTML = xhttp.responseText;
                }
            }
            xhttp.open("GET", "upvoteanswer.php?answerID="+int, true);
            xhttp.send();
        }
    </script>

    <script>
        function Downvoteanswer(int) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState == 4 && xhttp.status == 200) {
                    document.getElementById("voteanswer"+int).innerHTML = xhttp.responseText;
                }
            }
            xhttp.open("GET", "downvoteanswer.php?answerID="+int, true);
            xhttp.send();
        }
    </script>




    <hr width="770">
    <h4 class="relative3">
        Your Answer
    </h4>


    <script>

        function checkscript() {
            var namesubmitted = document.forms["inputnewanswer"]["Name"].value;
            var emailsubmitted = document.forms["inputnewanswer"]["Email"].value;
            var contentsubmitted = document.forms["inputnewanswer"]["Content"].value;
            if (namesubmitted=="") {
                // something i s wrong
                alert("field nama tidak boleh kosong");
                return false;
            }else if (emailsubmitted==""){
                alert("field email tidak boleh kosong");
                return false;
            }else if (contentsubmitted==""){
                alert("field content tidak boleh kosong");
                return false;
            }else if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(emailsubmitted)){
                return true;
            }else{
                alert("format penulisan email salah");
                return false;
            }
        }
    </script>



    <h4 class="relative2">
        <form name="inputnewanswer" action="InputNewAnswer.php?questionID=<?php echo $questionID ?>" method="post">
            <input type="text" name="Name" value="Name" size="100"><br>
            <input type="text" name="Email" value="Email@example.com"size="100"><br>
            <textarea class="newanswer" cols="91" rows="4" type="text" name="Content">Content
            </textarea>
            <br>
            <input class="textboxposquestion" type="submit" value="post" onclick="return checkscript()">
        </form>
    </h4>








</Center>



</body>
</html>
