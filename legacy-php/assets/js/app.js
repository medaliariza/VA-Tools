async function sendMessage() {
    const input = document.getElementById("msg");

    if (!input) {
        return;
    }

    const msg = input.value.trim();

    if (!msg) {
        input.focus();
        return;
    }

    const response = await fetch("../api/send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "message=" + encodeURIComponent(msg)
    });

    if (response.ok) {
        location.reload();
        return;
    }

    alert("Unable to send the message right now.");
}
