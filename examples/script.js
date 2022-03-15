var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition
var SpeechGrammarList = SpeechGrammarList || webkitSpeechGrammarList
var SpeechRecognitionEvent = SpeechRecognitionEvent || webkitSpeechRecognitionEvent

let words = [
  {
    id: "hello",
    status: "new",
    incorrect: 0,
    correct: 0
  },
  {
    id: "smart",
    status: "new",
    incorrect: 0,
    correct: 0
  }
  ,{
    id: "happy",
    status: "new",
    incorrect: 0,
    correct: 0
  },
  {
    id: "tangerine",
    status: "new",
    incorrect: 0,
    correct: 0
  },
  {
    id: "playful",
    status: "new",
    incorrect: 0,
    correct: 0
  }
];
var grammar = '#JSGF V1.0; grammar word; public <word> = ' + words.map(function (item) { return item["id"]; }).join(' | ') + ' ;'
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
    // Create the list item:
    var item = document.createElement('li');

    // Set its contents:
    item.appendChild(document.createTextNode(words[i].id));

    // Add it to the list:
    if(words[i].status == 'new') new_list.appendChild(item);
    else if(words[i].status == 'learning')learning_list.appendChild(item);
    else mastered_list.appendChild(item);
  }

  if(type == 0) return new_list;
  if(type == 1) return learning_list;
  if(type == 2) return mastered_list;

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

function myFunction() {
  recognition.start();
  console.log('Ready to receive word.');
}

function reset(){
  for(var i=0; i<words.length; i++){
    words[i].incorrect = 0;
    words[i].correct = 0;
  }
  count = 0;
  correct_word = words[count].id;
  diagnostic.textContent = correct_word;
  finished_modal.style.display = "none";
}
recognition.onresult = function (event) {
  // The SpeechRecognitionEvent results property returns a SpeechRecognitionResultList object
  // The SpeechRecognitionResultList object contains SpeechRecognitionResult objects.
  // It has a getter so it can be accessed like an array
  // The first [0] returns the SpeechRecognitionResult at the last position.
  // Each SpeechRecognitionResult object contains SpeechRecognitionAlternative objects that contain individual results.
  // These also have getters so they can be accessed like arrays.
  // The second [0] returns the SpeechRecognitionAlternative at position 0.
  // We then return the transcript property of the SpeechRecognitionAlternative object
  var word = event.results[0][0].transcript;
  diagnostic.textContent = 'Result received: ' + word;
  console.log('Confidence: ' + event.results[0][0].confidence);
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
    if(wrong_count == 3) {
      bg.style.backgroundColor = "white";
      getNextWord();
    }
    diagnostic.textContent += "\nWord: " + correct_word;
  }
}

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
  if(all_mastered){
    finished_modal.style.display = "block";
  }
  else{
    correct_word = words[count].id;
  }
  diagnostic.textContent = correct_word;
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  finished_modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
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
const recorder = document.getElementById('recorder');
const player = document.getElementById('player');

recorder.addEventListener('change', function(e) {
  const file = e.target.files[0];
  const url = URL.createObjectURL(file);
  // Do something with the audio file.
  player.src = url;
});