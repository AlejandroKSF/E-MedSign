document.addEventListener('DOMContentLoaded', function() {
    const daysTag = document.querySelector(".days"),
        currentDate = document.querySelector(".current-date"),
        prevNextIcon = document.querySelectorAll(".icons span");

    let date = new Date(),
        currYear = date.getFullYear(),
        currMonth = date.getMonth();

        



    const months = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho",
        "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];

    const renderCalendar = () => {
        let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
            lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
            lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
            lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();
        let liTag = "";

        for (let i = firstDayofMonth; i > 0; i--) {
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) {
            let isToday = i === date.getDate() && currMonth === new Date().getMonth()
                && currYear === new Date().getFullYear() ? "active" : "";
            liTag += `<li class="${isToday}">${i}</li>`;
        }

        for (let i = lastDayofMonth; i < 6; i++) {
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }
        currentDate.innerText = `${months[currMonth]} ${currYear}`;document.addEventListener('DOMContentLoaded', function() {
            const daysTag = document.querySelector(".days"),
                currentDate = document.querySelector(".current-date"),
                prevNextIcon = document.querySelectorAll(".icons span");
        
            let date = new Date(),
                currYear = date.getFullYear(),
                currMonth = date.getMonth();
        
            const months =["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho",
            "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
        
            const renderCalendar = () => {
                const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay();
                const lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate();
        
                let liTag = "";
        
                for (let i = 1; i <= lastDateOfMonth; i++) {
                    let isToday = i === date.getDate() && currMonth === new Date().getMonth()
                        && currYear === new Date().getFullYear() ? "active" : "";
                    liTag += `<li class="${isToday}">${i}</li>`;
                }
        
                currentDate.innerText = `${months[currMonth]} ${currYear}`;
                daysTag.innerHTML = liTag;
            }
        
            renderCalendar();
        
            prevNextIcon.forEach(icon => {
                icon.addEventListener("click", () => {
                    currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;
        
                    if (currMonth < 0 || currMonth > 11) {
                        date = new Date(currYear, currMonth, new Date().getDate());
                        currYear = date.getFullYear();
                        currMonth = date.getMonth();
                    } else {
                        date = new Date();
                    }
                    renderCalendar();
                });
            });
        
            daysTag.addEventListener('click', function(event) {
                const selectedDay = event.target;
                if (!selectedDay.classList.contains('inactive')) {
                    const selectedDate = parseInt(selectedDay.textContent);
                    // Remover a classe "active" de todos os dias
                    daysTag.querySelectorAll('li').forEach(day => {
                        day.classList.remove('active');
                    });
                    // Adicionar a classe "active" ao dia selecionado
                    selectedDay.classList.add('active');
        
                    // Extrair dia, mês e ano do texto do dia selecionado
                    const selectedMonth = currMonth + 1;
                    const selectedYear = currYear;
                    console.log(`Data selecionada: ${selectedDate}/${selectedMonth}/${selectedYear}`);
                }
            });
        });
        daysTag.innerHTML = liTag;
    }

    renderCalendar();

    prevNextIcon.forEach(icon => {
        icon.addEventListener("click", () => {
            currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

            if (currMonth < 0 || currMonth > 11) {
                date = new Date(currYear, currMonth, new Date().getDate());
                currYear = date.getFullYear();
                currMonth = date.getMonth();
            } else {
                date = new Date();
            }
            renderCalendar();
        });
    });

    daysTag.addEventListener('click', function(event) {
        const selectedDay = event.target;
        if (!selectedDay.classList.contains('inactive')) {
            const selectedDate = new Date(currYear, currMonth, parseInt(selectedDay.textContent));
            const formattedDate = selectedDate.toISOString().split('T')[0]; // Formata a data para o formato 'YYYY-MM-DD'
            console.log(formattedDate);
            console.log(selectedDate);
            
            // Restante do seu código...
        }
    });



    function updateTable(data) {
        const tabelaAgendamentos = document.getElementById("tabela-agendamentos");
        tabelaAgendamentos.innerHTML = "";

        data.forEach(agendamento => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${agendamento[0]}</td>
                <td>${agendamento[1]}</td>
            `;
            tabelaAgendamentos.appendChild(tr);
        });
    }


});



