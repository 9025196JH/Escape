<p>Gefeliciteerd! Jullie hebben gewonnen met nog <span id="eindtijd">00:00</span> over op de klok!</p>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let savedTime = sessionStorage.getItem('escape_room_time');
    
    if (savedTime) {
        let totalSeconds = parseInt(savedTime);
        let minutes = Math.floor(totalSeconds / 60);
        let seconds = totalSeconds % 60;
        
        if (seconds < 10) {
            seconds = "0" + seconds;
        }
        
        let timeDisplay = document.getElementById('eindtijd');
        if (timeDisplay) {
            timeDisplay.innerText = minutes + ":" + seconds;
        }
    }
});
</script>