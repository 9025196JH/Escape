<?php
// functie: Herbruikbare timer voor de escape rooms
// auteur: Bashar (Student B)

// Voorbeeld variabelen (als deze nog niet ergens anders zijn gedefinieerd)
if (!isset($TimeLimit)) { $TimeLimit = 120; }
if (!isset($NextPage)) { $NextPage = "../lose.php"; }
?>

<!-- De HTML code voor de timer van Student B -->
<div id="timer" style="position: fixed; top: 20px; right: 20px; background-color: #333; color: #0f0; padding: 15px; border-radius: 10px; font-size: 24px; font-weight: bold; border: 2px solid #0f0; z-index: 9999; font-family: monospace;">
    02:00
</div>

<script>
    // 1. Haal de opgeslagen tijd op of gebruik de PHP limiet
    let savedTime = sessionStorage.getItem('escape_room_time');
    let timeLeft = (savedTime !== null) ? parseInt(savedTime) : <?php echo $TimeLimit; ?>;
    
    let nextPage = "<?php echo $NextPage; ?>";
    let timerElement = document.getElementById('timer');
    let timerInterval;

    // 2. Toon de tijd direct in mm:ss formaat
    function updateTimerDisplay() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        if (minutes < 10) minutes = "0" + minutes;
        if (seconds < 10) seconds = "0" + seconds;

        timerElement.textContent = minutes + ":" + seconds;
    }

    // 3. Start de timer logica
    function startTimer() {
        // Toon direct de juiste starttijd
        updateTimerDisplay();

        // Tel elke seconde 1 af
        timerInterval = setInterval(function() {
            timeLeft--;

            // Sla de live tijd op in sessionStorage
            sessionStorage.setItem('escape_room_time', timeLeft); 

            // Update het scherm
            updateTimerDisplay();

            // Als de tijd op is, stuur door naar de verliespagina
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                sessionStorage.removeItem('escape_room_time'); 
                window.location.href = nextPage;
            }
        }, 1000);
    }

    // Start de timer direct bij het laden van de pagina
    startTimer();
</script>