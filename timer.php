<?php
// Timer Systeem
// Gemaakt door: Student B

// Standaard tijd instellen (2 minuten) als er niks is meegegeven
$TimeLimit = isset($TimeLimit) ? $TimeLimit : 120;

// Standaard pagina instellen als er niks is meegegeven
$NextPage = isset($NextPage) ? $NextPage : "../win.php";
?>

<!-- De HTML code voor de timer die rechtsboven staat -->
<div id="timer" style="position: fixed; top: 20px; right: 20px; background-color: #333; color: #0f0; padding: 15px; border-radius: 10px; font-size: 24px; font-weight: bold; border: 2px solid #0f0; z-index: 9999; font-family: monospace;">
    02:00
</div>

<!-- De JavaScript code om de timer te laten lopen -->
<script>
    // De tijd ophalen uit de PHP variabele
    let timeLeft = <?php echo $TimeLimit; ?>;
    let nextPage = "<?php echo $NextPage; ?>";
    let timerElement = document.getElementById('timer');
    let timerInterval;

    // Functie om de tijd tekst op het scherm te zetten
    function updateTimerDisplay() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        // Een nul toevoegen als seconden kleiner zijn dan 10 (bijv: 05)
        if (seconds < 10) {
            seconds = "0" + seconds;
        }

        timerElement.innerText = minutes + ":" + seconds;
    }

    // Start de teller
    timerInterval = setInterval(function() {
        timeLeft = timeLeft - 1;
        updateTimerDisplay();

        // Check of de tijd op is
        if (timeLeft <= 0) {
            clearInterval(timerInterval);

            window.location.href = "../lose.php";
        }
    }, 1000);
</script>