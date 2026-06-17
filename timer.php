<div id="timer" style="position: fixed; top: 20px; right: 20px; background-color: #333; color: #0f0; padding: 15px; border-radius: 10px; font-size: 24px; font-weight: bold; border: 2px solid #0f0; z-index: 9999; font-family: monospace;">
    02:00
</div>

<!-- De JavaScript code om de timer te laten lopen -->
<script>
    // CONTROLEER EERST OF ER AL EEN TIJD STAAT IN SESSIONSTORAGE
    let savedTime = sessionStorage.getItem('escape_room_time');
    let timeLeft = (savedTime !== null) ? parseInt(savedTime) : <?php echo $TimeLimit; ?>;
    
    let nextPage = "<?php echo $NextPage; ?>";
    let timerElement = document.getElementById('timer');
    let timerInterval;

    function updateTimerDisplay() {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        if (minutes < 10) minutes = "0" + minutes;
        if (seconds < 10) seconds = "0" + seconds;

        timerElement.textContent = minutes + ":" + seconds;
    }

    function startTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(function() {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                sessionStorage.removeItem('escape_room_time'); // Verwijder tijd bij game over
                window.location.href = nextPage;
            } else {
                timeLeft--;
                updateTimerDisplay();
                sessionStorage.setItem('escape_room_time', timeLeft); // Sla direct live op
            }
        }, 1000);
    }

    // Start de timer direct
    startTimer();
</script>