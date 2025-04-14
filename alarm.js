document.addEventListener("DOMContentLoaded", () => {
  const alarmSound = document.getElementById("alarmSound");

  function checkAlarm() {
    const now = new Date();
    const nowString = now.toISOString().slice(0, 16); // yyyy-MM-ddTHH:mm

    document.querySelectorAll(".alarm-time").forEach((cell) => {
      const alarmTime = new Date(cell.textContent);
      const alarmString = alarmTime.toISOString().slice(0, 16);

      if (alarmString === nowString && !cell.classList.contains("triggered")) {
        cell.classList.add("triggered");
        const note = cell.previousElementSibling.textContent;

        alarmSound.play();

        if (Notification.permission === "granted") {
          new Notification("⏰ Alarm Berbunyi!", {
            body: `Catatan: ${note}`,
            icon: "assets/alarm.png",
          });
        } else if (Notification.permission !== "denied") {
          Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
              new Notification("⏰ Alarm Berbunyi!", {
                body: `Catatan: ${note}`,
                icon: "assets/alarm.png",
              });
            }
          });
        }

        alert("ALARM: " + note);
      }
    });
  }

  if (Notification.permission !== "granted") {
    Notification.requestPermission();
  }

  setInterval(checkAlarm, 1000);
});
