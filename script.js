function updateTimeAndDate() {
  const currentTime = new Date();
  const timeOptions = { hour: 'numeric', minute: 'numeric', second: 'numeric' };
  const formattedTime = currentTime.toLocaleTimeString('en-US', timeOptions);
  
  const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  const formattedDate = currentTime.toLocaleDateString('en-US', dateOptions);

  const timeDateContainer = document.getElementById('current-time-date');
  timeDateContainer.textContent = `${formattedTime} | ${formattedDate}`;
}

// Update time and date every second
setInterval(updateTimeAndDate, 1000);

// Initial update
updateTimeAndDate();

function tempPage(){
	window.location.href = 'tempPage.php';
}

function humPage(){
	window.location.href = 'humPage.php';
}

function waterlevelPage(){
	window.location.href = 'waterlevelPage.php';
}

function phPage(){
	window.location.href = 'phPage.php';
}

function mainpage() {
    window.location.href = 'aquaponicmonitoringsystem.php';
}

function changeRecordLimit() {
            const recordLimit = document.getElementById("record-limit").value;
            document.getElementById("record-limit-form").submit();
        }