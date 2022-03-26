﻿<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Speech Therapy WebApp</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <!-- <link type="text/css" rel="stylesheet" href="style.css"> -->
    <style>
        body, html {
          margin: 0;
        }
        
        html {
          height: 100%;
        }
        
        body {
          height: inherit;
          overflow: scroll;
          max-width: 800px;
          margin: 0 auto;
        }
        
        h1, p {
          font-family: sans-serif;
          text-align: center;
          padding: 20px;
        }
        
        h3 {
          text-align: center;
        }
        
        sbutton {
          font-family: OCR A Std, monospace;
          text-align: center;
          padding: 10px;
          font-size: 20px;
          margin-right: 20px;
        }
        
        div {
          height: 100px;
          /* overflow: auto;*/
          position: absolute;
          bottom: 5%;
          right: 0;
          left: 0;
          background-color: rgba(255,255,255,0.2);
        }
        
        ul {
          margin: 0;
        }

        textarea {
            border: none;
            outline: none;
            width: 800px;
            height: 300px;
        }
        
        .hints span {
          text-shadow: 0px 0px 6px rgba(255,255,255,0.7);
        }
        
        /* The Modal (background) */
        .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          left: 0;
          top: 0;
          width: 100%; /* Full width */
          height: 100%; /* Full height */
          overflow: auto; /* Enable scroll if needed */
          background-color: rgb(0,0,0); /* Fallback color */
          background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        
        /* Modal Content/Box */
        .modal-content {
          background-color: #fefefe;
          margin: 15% auto; /* 15% from the top and centered */
          padding: 20px;
          border: 1px solid #888;
          width: 80%; /* Could be more or less, depending on screen size */
        }
        
        /* The Close Button */
        .close {
          color: #aaa;
          float: right;
          font-size: 28px;
          font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
          color: black;
          text-decoration: none;
          cursor: pointer;
        }
        .center {
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            padding: 10px;
        }
        
        .button {
          font-family: OCR A Std, monospace;
          text-align: center;
          padding: 20px;
          font-size: 30px;
        }
        
        body {
          font-family: "Lato", sans-serif;
        }
        
        .sidenav {
          height: 100%;
          width: 0;
          position: fixed;
          z-index: 1;
          top: 0;
          left: 0;
          background-color: #111;
          overflow-x: hidden;
          transition: 0.5s;
          padding-top: 60px;
          color: #818181;
        }
        
        .sidenav a {
          padding: 8px 8px 8px 32px;
          text-decoration: none;
          font-size: 25px;
          color: #818181;
          display: block;
          transition: 0.3s;
        }
        
        .sidenav p{
          padding: 8px 8px 8px 32px;
          color:gray; 
          left:0px;
        }
        
        .sidenav a:hover {
          color: #f1f1f1;
        }
        
        .sidenav .closebtn {
          position: absolute;
          top: 0;
          right: 25px;
          font-size: 36px;
          margin-left: 50px;
        }
        .sbutton {
            font-family: OCR A Std, monospace;
            text-align: center;
            padding: 10px;
            font-size: 20px;
            margin-right: 20px;
        }
        .output {
            font-family: OCR A Std, monospace;
            font-size:30px; 
            white-space: pre;
        }
        @media screen and (max-height: 450px) {
          .sidenav {padding-top: 15px;}
          .sidenav a {font-size: 18px;}
        }
     </style>
    <script src="https://www.WebRTC-Experiment.com/RecordRTC.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io-stream/0.9.1/socket.io-stream.js"></script>
</head>


<body> <h1>Speech Therapy WebApp </h1>
    <button class="button" onclick="pronounce();"> Play computer's pronunciation </button>
    <mic class="center" id="start-recording">
        <img src="https://www.pngitem.com/pimgs/m/561-5619978_recording-symbol-png-free-radio-microphone-icon-transparent.png"
            id = "mic_button" height="250" width="240" style="border-radius: 50%; border: 3px solid green;" />
    </mic>
    <button class="button" onclick="playP()" ;> Play my pronunciation  </button>
    <button class="button" onclick="skip()" ;> skip </button>
    <div>
        <p class="output"><em></em></p>
    </div>
    <!-- The Modal -->
    <div id="finished_modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>You have mastered all of the words!</p>
            <button class="sbutton" onclick="location.href='home.html'" type="button">
                Return to Home</button>
            <button class="sbutton" onclick="reset();"> Play again!</button>
        </div>
    </div>
    <div id="not_available" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <p>You have not pronounced the word yet!</p>
        </div>
    </div>
    <span style="font-size:30px;cursor:pointer; left:10px; top:10px; position:absolute" onclick="openNav()">&#9776;
        status</span>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <h1>Status</h1>
        <h3>New</h3>
        <ul id="new" style="color:gray"></ul>
        <h3>Learning</h3>
        <ul id="learning" style="color:gray"></ul>
        <h3>Mastered</h3>
        <ul id="mastered" style="color:gray"></ul>
    </div>

	<?php 
	  $conn = new mysqli("localhost", "yeggi", "yeggi", "mysql");
	  if ($conn->connect_error) {
	    die("ERROR: Unable to connect: " . $conn->connect_error);
	  } 
	  echo 'Connected to the database.<br>';
	  $result = $conn->query("SELECT word FROM groupA");
	  $res = mysqli_fetch_all($result, MYSQLI_ASSOC ); 
	  $result->close();
	  $conn->close();
	?>

    <script>
        var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
        var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList;
        var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent;
        const startRecording = document.getElementById('start-recording');
        const micButton = document.getElementById('mic_button');
        var recordingExists = false;
        //const stopRecording = document.getElementById('stop-recording');
        let recordAudio;
        var blobUrl="";
        const socketio = io();
        const socket = socketio.on('connect', function () {
            startRecording.disabled = false;
        });

        function playP(){
            if(recordingExists){
                var music = new Audio(blobUrl);
                music.play();
            }
            else {
                not_available.style.display = 'block';
                setTimeout(() => {
                    not_available.style.display = 'none';
                    // 👇️ hides element (still takes up space on page)
                    // box.style.visibility = 'hidden';
                }, 3000);
            }
        }

        //3)
        startRecording.onclick = function () {
            // recording started
            startRecording.disabled = true;

            micButton.setAttribute("style", "border-radius: 50%; border: 3px solid red;");
            setTimeout(() => {
                    micButton.setAttribute("style", "border-radius: 50%; border: 3px solid green;");
                    // 👇️ hides element (still takes up space on page)
                    // box.style.visibility = 'hidden';
            }, 1000);


            // make use of HTML 5/WebRTC, JavaScript getUserMedia()
            // to capture the browser microphone stream
            navigator.getUserMedia({
                audio: true
            },  function(stream) {
                    recordAudio = RecordRTC(stream, {
                    type: 'audio',
                    mimeType: 'audio/webm',
                    sampleRate: 44100, // this sampleRate should be the same in your server code

                    // MediaStreamRecorder, StereoAudioRecorder, WebAssemblyRecorder
                    // CanvasRecorder, GifRecorder, WhammyRecorder
                    recorderType: StereoAudioRecorder,

                    // Dialogflow / STT requires mono audio
                    numberOfAudioChannels: 1,

                    // get intervals based blobs
                    // value in milliseconds
                    // as you might not want to make detect calls every seconds
                    timeSlice: 4000,

                    // only for audio track
                    // audioBitsPerSecond: 128000,

                    // used by StereoAudioRecorder
                    // the range 22050 to 96000.
                    // let us force 16khz recording:
                    desiredSampRate: 16000,

                    // as soon as the stream is available
                    ondataavailable: function(blob) {
                        // making use of socket.io-stream for bi-directional
                        // streaming, create a stream
                        var stream = ss.createStream();
                        blobUrl = URL.createObjectURL(blob);
                        // stream directly to server
                        // it will be temp. stored locally
                        ss(socket).emit('stream-transcribe', stream, {
                            name: 'stream.wav', 
                            size: blob.size
                        });
                        recordingExists = true;
                        // pipe the audio blob to the read stream
                        ss.createBlobReadStream(blob).pipe(stream);
                    }
                });

                recordAudio.startRecording();
                //stopRecording.disabled = false;
            }, function(error) {
                console.error(JSON.stringify(error));
            });
        };

        // //7)
        // stopRecording.onclick = function () {
        //     // recording stopped
        //     startRecording.disabled = false;
        //     stopRecording.disabled = true;
        //recordAudio.startRecording();
        // };

	  var items = [ <?php echo(json_encode([$res])); ?> ] ;
	  let words = [];
	  for( [key, item] of Object.entries(items)) {
		words.push( 
	  {
		id: item['word'], 
		status: "new",
		incorrect: 0,
		correct: 0
	  });
	  }
	  conlose.log('hey! - ', words);
        var grammar = '#JSGF V1.0; grammar word; public <word> = ' + words.map(function (item) { return item["id"]; }).join(' | ') + ' ;';
        var recognition = new SpeechRecognition();
        var speechRecognitionList = new SpeechGrammarList();
        speechRecognitionList.addFromString(grammar, 1);
        recognition.grammars = speechRecognitionList;
        recognition.continuous = false;
        recognition.lang = 'en-US';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        var wrong_count = 0;
        var diagnostic = document.querySelector('.output');
        var bg = document.querySelector('html');
        var hints = document.querySelector('.hints');

        var count = 0;
        var correct_word = "";
        // Get the modal
        var finished_modal = document.getElementById("finished_modal");
        var not_available = document.getElementById("not_available");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            makeStatusList();
            document.getElementById('new').appendChild(makeStatusList(0));
            document.getElementById('learning').appendChild(makeStatusList(1));
            document.getElementById('mastered').appendChild(makeStatusList(2));
        }

        function makeStatusList(type) {

            var new_list = document.createElement('ul');
            var learning_list = document.createElement('ul');
            var mastered_list = document.createElement('ul');

            for (var i = 0; i < words.length; i++) {
                // Create the list item:g
                var item = document.createElement('li');

                // Set its contents:
                item.appendChild(document.createTextNode(words[i].id));

                // Add it to the list:
                if (words[i].status == 'new') new_list.appendChild(item);
                else if (words[i].status == 'learning') learning_list.appendChild(item);
                else mastered_list.appendChild(item);
            }

            if (type == 0) return new_list;
            if (type == 1) return learning_list;
            if (type == 2) return mastered_list;

        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById('new').removeChild(document.getElementById('new').firstElementChild);
            document.getElementById('learning').removeChild(document.getElementById('learning').firstElementChild);
            document.getElementById('mastered').removeChild(document.getElementById('mastered').firstElementChild);

        }

        window.onload = function () {
            getNextWord();
        };

        function pronounce() {
            var msg = new SpeechSynthesisUtterance();
            msg.text = correct_word;
            window.speechSynthesis.speak(msg);
        }

        function reset() {
            for (var i = 0; i < words.length; i++) {
                words[i].incorrect = 0;
                words[i].correct = 0;
            }
            count = 0;
            correct_word = words[count].id;
            diagnostic.textContent = correct_word;
            finished_modal.style.display = "none";
        }
        // recognition.onresult = function (event) {
        //     // The SpeechRecognitionEvent results property returns a SpeechRecognitionResultList object
        //     // The SpeechRecognitionResultList object contains SpeechRecognitionResult objects.
        //     // It has a getter so it can be accessed like an array
        //     // The first [0] returns the SpeechRecognitionResult at the last position.
        //     // Each SpeechRecognitionResult object contains SpeechRecognitionAlternative objects that contain individual results.
        //     // These also have getters so they can be accessed like arrays.
        //     // The second [0] returns the SpeechRecognitionAlternative at position 0.
        //     // We then return the transcript property of the SpeechRecognitionAlternative object
        //     var word = event.results[0][0].transcript;
        //     diagnostic.textContent = 'Result received: ' + word;
        //     console.log('Confidence: ' + event.results[0][0].confidence);
        //     if (word === correct_word) {
        //         bg.style.backgroundColor = "green";
        //         words[count].correct++;
        //         if (words[count].correct > words[count].incorrect) words[count].status = "mastered";
        //         diagnostic.textContent = correct_word;
        //         getNextWord();
        //         wrong_count = 0;
        //     }
        //     else {
        //         words[count].status = "learning";
        //         bg.style.backgroundColor = "red";
        //         words[count].incorrect++;
        //         wrong_count++;
        //         if (wrong_count == 3) {
        //             bg.style.backgroundColor = "white";
        //             getNextWord();
        //         }
        //         diagnostic.textContent += "\nWord: " + correct_word;
        //     }
        // }
        //const resultpreview = document.getElementById('results');
        socketio.on('results', function (data) {
            // recording stopped
            recordAudio.stopRecording();
            startRecording.disabled = false;
           // stopRecording.disabled = true;
            // show the results on the screen
            // if(data && data.results[0] && data.results[0].alternatives[0]){
            // resultpreview.innerHTML += " " + data.results[0].alternatives[0].transcript;
            // //data.results[0].alternatives.forEach( (item,index)=> console.log(item,index));
            // }
            console.log(data.results[0].alternatives.length);
            console.log(data.results[0].alternatives[0]);
            var word = data.results[0].alternatives[0].transcript;
            var confidence = data.results[0].alternatives[0].confidence;
            diagnostic.textContent = 'Result received: ' + word;
            console.log('Confidence: ' + confidence);
            if (word === correct_word) {
                bg.style.backgroundColor = "green";
                words[count].correct++;
                if (words[count].correct > words[count].incorrect) words[count].status = "mastered";
                diagnostic.textContent = correct_word;
                getNextWord();
                wrong_count = 0;
            }
            else {
                words[count].status = "learning";
                bg.style.backgroundColor = "red";
                words[count].incorrect++;
                wrong_count++;
                if (wrong_count == 3) {
                    bg.style.backgroundColor = "white";
                    getNextWord();
                }
                diagnostic.textContent += "\r\n";
                diagnostic.textContent += "Word: " + correct_word;
            }
            
            
        });

        function getNextWord() {
            var all_mastered = false;
            count++;
            let prev_count = count;
            if (count == words.length) count = 0;
            while (words[count].correct > words[count].incorrect) {
                count++;
                if (count == words.length) count = 0;
                if (count == prev_count) {
                    all_mastered = true;
                    break;
                }
            }
            if (all_mastered) {
                finished_modal.style.display = "block";
            }
            else {
                correct_word = words[count].id;
            }
            diagnostic.textContent = correct_word;
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            finished_modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == finished_modal) {
                finished_modal.style.display = "none";
            }
        }
        recognition.onspeechend = function () {
            recognition.stop();
        }

        recognition.onnomatch = function (event) {
            diagnostic.textContent = "I didn't recognise that word.";
        }

        recognition.onerror = function (event) {
            diagnostic.textContent = 'Error occurred in recognition: ' + event.error;
        }
    </script>
</body>

</html>
