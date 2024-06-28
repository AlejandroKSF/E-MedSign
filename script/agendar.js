
document.addEventListener("DOMContentLoaded", function () {
    var form = document.getElementById("AgendarForm");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission behavior

        // Serialize form data
        var formData = new FormData(form);

        // Send form data to the server using AJAX
        fetch("novoAgendamento.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    updateCalendar();
                    alert("Agendamento cadastrado com sucesso!!!");
                    
                    // You can also perform additional actions like clearing the form or redirecting the user
                } else {
                    // Show error message
                    alert("Falha em agendar. Erro: " + data.error);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                // Show error message if there's a network error or other issues
                alert("Ocorreu um erro ao processar sua solicitação. Tente novamente mais tarde.");
            });
    });
});