function openModal(index) {
  // Zoek het element met de class 'box' en het bijbehorende data-index
  let box = document.querySelector(`.box[data-index='${index}']`);

  // Haal de vraag ut het juiste antwoord uit de dataset van de box
  let riddleText = box.dataset.riddle;
  let correctAnswer = box.dataset.answer;

  // Zet de vraagtekst in het modalvenster
  document.getElementById('riddle').innerText = riddleText;

  // Zet het correcte antwoord in de modal, zodat we het later kunnen vergelijken
  document.getElementById('modal').dataset.answer = correctAnswer;

  // EXTRA TOEGEVOEGD: Zorg dat het verborgen PHP-veld in Kamer 1 weet welke box geklikt is!
  if (document.getElementById('riddle_index')) {
    document.getElementById('riddle_index').value = index;
  }

  // Maak het antwoordveld leeg
  document.getElementById('answer').value = '';

  // Toon de overlay en de modal door de display-stijl te veranderen naar 'block'
  document.getElementById('overlay').style.display = 'block';
  document.getElementById('modal').style.display = 'block';
}

// Deze functie sluit de modal en de overlay
function closeModal() {
  // Zet de overlay en modal weer op 'none' zodat ze niet meer zichtbaar zijn
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';

  // Maak de feedback tekst leeg
  document.getElementById('feedback').innerText = '';
}

// Deze functie controleert of het ingevoerde antwoord correct is
function checkAnswer() {
  // Haal het antwoord van de gebruiker op uit het invoerveld en verwijder onnodige spaties
  let userAnswer = document.getElementById('answer').value.trim();

  // Haal het juiste antwoord op uit de modal
  let correctAnswer = document.getElementById('modal').dataset.answer;

  // Haal het feedback element op om de gebruiker te informeren
  let feedback = document.getElementById('feedback');

  // Vergelijk het antwoord van de gebruiker met het juiste antwoord (hoofdlettergevoeligheid negeren)
  if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
    // Als het antwoord juist is, geef positieve feedback
    feedback.innerText = 'Correct! Goed gedaan!';
    feedback.style.color = 'green';

    // Sluit de modal na 1 seconde
    setTimeout(closeModal, 1000);
  } else {
    // Als het antwoord fout is, geef negatieve feedback
    feedback.innerText = 'Fout, probeer opnieuw!';
    feedback.style.color = 'red';
  }
}

// Room 2 specifieke functies
// Gemaakt door: Student B
let currentQuestionIndex = null;

function room2OpenModal(index) {
  currentQuestionIndex = index;

  const button = document.getElementById(`room2Box${index}`);

  document.getElementById('room2QuestionTitle').innerText =
    `Vraag ${index + 1}`;

  document.getElementById('room2Riddle').innerText =
    button.dataset.riddle;

  document.getElementById('room2HintText').innerText = '';

  document.getElementById('room2Answer').value = '';

  document.getElementById('room2Feedback').innerText = '';

  document.getElementById('room2Overlay').style.display = 'block';
  document.getElementById('room2Modal').style.display = 'block';
}

function room2CloseModal() {
  document.getElementById('room2Overlay').style.display = 'none';
  document.getElementById('room2Modal').style.display = 'none';
}

function room2ShowHint() {
  const button = document.getElementById(`room2Box${currentQuestionIndex}`);

  document.getElementById('room2HintText').innerText =
    button.dataset.hint;
}

function room2CheckAnswer() {
  const button = document.getElementById(`room2Box${currentQuestionIndex}`);

  const correctAnswer =
    button.dataset.answer.toLowerCase().trim();

  const userAnswer =
    document.getElementById('room2Answer')
      .value.toLowerCase().trim();

  const feedback = document.getElementById('room2Feedback');

  if (userAnswer === correctAnswer) {

    feedback.innerText = 'Goed gedaan!';
    feedback.style.color = 'lightgreen';

    document.getElementById(`room2Check${currentQuestionIndex}`).innerText = '✔';

    button.disabled = true;

    let nextIndex = currentQuestionIndex + 1;

    let nextButton = document.getElementById(`room2Box${nextIndex}`);

    if (nextButton) {
      nextButton.disabled = false;
      nextButton.classList.remove('room2-box-locked');
    } else {
      // AANGEPAST: Als er geen volgende vraag is, heeft het team gewonnen.
      // We halen de overgebleven seconden (timeLeft) op uit de timer en sturen dit mee!
      setTimeout(() => {
        window.location.href = '../win.php?time=' + timeLeft;
      }, 1000);
    }

    setTimeout(() => {
      room2CloseModal();
    }, 800);

  } else {
    feedback.innerText = 'Fout antwoord!';
    feedback.style.color = 'red';
  }
}