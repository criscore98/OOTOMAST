<!-- This is a static file -->
<!-- served from your routes in server.js -->

<!-- You might want to try something fancier: -->
<!-- html/nunjucks docs: https://mozilla.github.io/nunjucks/ -->
<!-- pug: https://pugjs.org/ -->
<!-- haml: http://haml.info/ -->
<!-- hbs(handlebars): http://handlebarsjs.com/ -->

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>OOTOMAST</title>
    <meta name="description" content="A cool thing made with Glitch">
    <link id="favicon" rel="icon" href="https://glitch.com/edit/favicon-app.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- import the webpage's stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="/mdb.min.css">
    <!-- import the webpage's client-side javascript file -->
    <script src="/client.js" defer></script>

  </head>
  <body>
    <header>
      <div style="margin: 15px 10px ; display: flex;">
        <input type="checkbox" data-toggle="toggle" id="translateTgl">
        <h3 style="font-size: 25px"> Translate </h3>
      </div>
    </header>

    <main>
      <div name="questionBox" id="qBox">
        <p name="questionText" id="qText">
        </p>
      </div>
      
      <div name="choicesBox">
        <form class="form-check" style="display:flex; flex-direction: column; " id="cBox">
          
        </form>
      </div>
      
      <div name="navButtons" id="navBtnCon">
        <form id="btnForm">
          <button class="btn btn-primary btn-lg" type="button" id="prevBtn" value="back" onclick="prev()">Back</button>
          <button class="btn btn-primary btn-lg"  type="button" id="passBtn" value="pass">Pass</button>
          <button class="btn btn-primary btn-lg"  type="button" id="nextBtn" value="next" onclick="next()">Next</button>
        </form>
      </div>
    </main>
    <!-- 
      <section class="dreams">
        <ul id="dreams"></ul>
      </section>
    

    <footer>
      Made with <a href="https://glitch.com">Glitch</a>!
    </footer>-->

    <!-- include the Glitch button to show what the webpage is about and
          to make it easier for folks to view source and remix -->
    <!-- <div class="glitchButton" style="position:fixed;top:20px;right:20px;"></div> -->
    <script src="https://button.glitch.me/button.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>
    
    <script src="js/mdb.min.js"></script>
    <script src="js/papaparse.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script type="text/javascript">

    //Parse and Navigation
      var translated = false
      var qIndex = 0;
      var cIndex = 0;
      var qidIndex = 1;
      var noOfSelectable = 0;
      var cIndexes = [];
      var surveyID;
      var surveyData;
      var resultsArray;
      var selectedAnswer = [];

      <?php $sId = $_POST["survey"]; ?>

      surveyID =  "<?php echo $sId ?>";
      //alert(surveyID);
      setSurvey();
      initializeResultsArray();
      initializeSurvey();

      window.onbeforeunload = function(event) {
        event.returnValue = "Your custom message.";
      };

      function setSurvey(){
        surveyData = JSON.parse(localStorage.getItem(surveyID));
      }

      function initializeResultsArray(){
        var results = []
        results.push(surveyID, [93423]); 
        //results[1].push({"a2a":1}); add new answer
        //results[1].a2a = 2; replace answer
        //results.push([91231]);  add new respondent
        resultsArray = results;

      }

    	function initializeSurvey(){
    		var survey = surveyData;
        var found = false;
        var iterate = qIndex+1;
        var choicesContainer = document.getElementById("cBox");
        
        while(found != true){
          if(survey.data[iterate][0] == "^"){
            //alert("found");
            found = true;
            qIndex = iterate;
          }
        else{
            iterate = iterate + 1;
          }
        }
      

        // Change Question Text
        if(found == true){
          //alert(qIndex);
          noOfSelectable = survey.data[qIndex][1];
          if(translated == true){
            document.getElementById('qText').innerHTML = ""+survey.data[qIndex][(3+1)];
            //document.getElementById('qText').innerHTML = ""+survey.data[qIndex][(2+1)];
          }
          else{
            document.getElementById('qText').innerHTML = ""+survey.data[qIndex][3];
          }
        }

        // Do choice generation
        cIndexes = []
        choicesContainer.innerHTML = "";
        choicesContainer.innerHTML += findChoices();
    	}

      function translate(){
        var survey = surveyData;
        if (translated == false){
          translated = true;
          document.getElementById('qText').innerHTML = ""+survey.data[qIndex][(3+1)];

          // Translate choices
          for (var i = 0; i < cIndexes.length; i++) {
            var choiceTextId = "qChoice"+cIndexes[i];
            document.getElementById(choiceTextId).innerHTML = ""+survey.data[cIndexes[i]][4];
          }
        }
        else{
          translated = false;
          document.getElementById('qText').innerHTML = ""+survey.data[qIndex][3];

          // Translate choices
          for (var i = 0; i < cIndexes.length; i++) {
            var choiceTextId = "qChoice"+cIndexes[i];
            document.getElementById(choiceTextId).innerHTML = ""+survey.data[cIndexes[i]][3];
          }
        }
      }

      function next(){
        // Find next question
        var survey = surveyData;
        var found = false;
        var iterate = qIndex+1;
        var choicesContainer = document.getElementById("cBox");
        var qCode = survey.data[qIndex][2];

         // Record answer
        if(resultsArray[1].qCode == null){
          if(selectedAnswer.length > 1){

          }
          else if(selectedAnswer.length == 1){
            resultsArray[1].push({[qCode]:selectedAnswer[0]});
          }
          else{
            resultsArray[1].push({[qCode]:99});
          }
        }

        while(found != true){
          if(survey.data[iterate][0] == "^"){
            //alert("found");
            found = true;
            qIndex = iterate;
          }
          else{
            iterate = iterate + 1;
          }
        }

        // Change Question Text
        if(found == true){
          //alert(qIndex);
          noOfSelectable = survey.data[qIndex][1];
          if(translated == true){
            document.getElementById('qText').innerHTML = ""+survey.data[qIndex][(3+1)];
            //document.getElementById('qText').innerHTML = ""+survey.data[qIndex][(2+1)];
          }
          else{
            document.getElementById('qText').innerHTML = ""+survey.data[qIndex][3];
          }
        }

        // Do choice generation
        cIndexes = []
        choicesContainer.innerHTML = "";
        choicesContainer.innerHTML += findChoices();
      }

      function prev(){
        // Find previous question
        var survey = surveyData;
        var found = false;
        var iterate = qIndex-1;
        var choicesContainer = document.getElementById("cBox");

        while(found != true){
          if(survey.data[iterate][0] == "^"){
            //alert("found");
            found = true;
            qIndex = iterate;
          }
          else{
            iterate = iterate - 1;
          }
        }

        // Change Question Text
        if(found == true){
          //alert(qIndex);
          noOfSelectable = survey.data[qIndex][1];
          if(translated == true){
            document.getElementById('qText').innerHTML = ""+survey.data[qIndex][(3+1)];
            //document.getElementById('qText').innerHTML = ""+survey.data[qIndex][(2+1)];
          }
          else{
            document.getElementById('qText').innerHTML = ""+survey.data[qIndex][3];
          }
        }

        // Do choice generation
        cIndexes = []
        choicesContainer.innerHTML = "";
        choicesContainer.innerHTML += findChoices();
      }

      function pass(){

      }

      function findChoices(){
        var survey = surveyData;
        cIndex = qIndex+1;
        var cTextIndex = 3;
        var eof = 0;
        var output = "";

        //Find each choices
        while(eof == 0){
          if(survey.data[cIndex][0] == "^"){
            eof = 1;
          }
          else{
            // Generate radio button if only 1 is selectable
            cIndexes.push(cIndex);
            if(noOfSelectable == 1){
              output+= '<input type="radio" onclick="setAnswer('+ survey.data[cIndex][2] +')" class="form-check-input" id="'+ cIndex +'" name="choice1" value="'+ survey.data[cIndex][2] +'">';
              if(translated == true){
                 output+= '<label class="form-check-label" for="'+ cIndex +'" id="qChoice'+ cIndex +'">'+ survey.data[cIndex][4] +'</label>';
              }
              else{
                output+= '<label class="form-check-label" for="'+ cIndex +'" id="qChoice'+ cIndex +'">'+ survey.data[cIndex][3] +'</label>';
              }

            // Set choices that are already checked
            
            }
          }
          cIndex = cIndex+1;
        }
        return output;
      }

      function setAnswer(num){
        if(noOfSelectable == 1){
          selectedAnswer = [];
          selectedAnswer.push(num);
        }
      }

      function checkAnswerButton(){
        // When user pressed previous, check the past answer
      }

      document.getElementById('translateTgl').onchange = function(e){

        if(e.target.checked) {
            translate()
        } else {
            translate()
        }
      };
    </script>
  </body>
</html>