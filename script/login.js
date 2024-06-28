document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            if(data.user_type === 'medico') {
                window.location.href = "./medico/dashboardMedico.php";
            } else if(data.user_type === 'atendente') {
                window.location.href = "./atendente/dashboardAtendente.php";
            }
            else if(data.user_type === 'admin') {
                window.location.href = "./admin/Clinicas.php";
            }
        } else {
            document.getElementById("message").innerHTML = data.message;
        }
    });
});