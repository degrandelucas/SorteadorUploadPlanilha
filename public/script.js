//Upload do arquivo
document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this); //this representa o event marcado id uploadForm

    fetch('../backend/upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // objeto response lê e converte a resposta da requisição
    .then(data => { // variavel data armazana a resposta da requisição
        if (data.success) {
            alert(data.message);
            document.getElementById('resetBtn').disabled = false;
            document.getElementById('excelFile').disabled = true;
            this.querySelector('[type="submit"]').disabled = true;
        } else {
            alert(data.message);
        }
    })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar o arquivo');
        });
});

//Realizar o Sorteio do participante
document.getElementById('drawBtn').addEventListener('click', function() {
    const prize = document.getElementById('giftInput').value;

    //Verificar se o campo de prêmio está preenchido
    if (prize === '') {
        alert('Informe o prêmio');
        return;
    }

    getWinner(prize);

    //contagem regressiva (opcional)
    // let counter = 3;
    // const countdownInterval = setInterval(() => {
    //     document.getElementById('countDown').textContent = `Sorteio em ${counter}...`;
    //     counter--;
    //     if (counter < 0) {
    //         clearInterval(countdownInterval);
    //         document.getElementById('countDown').textContent = '';
    //         getWinner(prize);
    //     }
    // }, 1000);
});

    //Sortear o participante
function getWinner(prize) {
    fetch('../backend/raffle.php', { // Remove ?action=draw da URL
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=draw&prize=' + encodeURIComponent(prize), // Adiciona action=draw no body
    })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                // Exibe o último nome sorteado
                document.getElementById('winner').textContent = `Número: ${data.number}, Nome: ${data.name}, Prêmio: ${prize}`;

                // Adiciona o vencedor à lista
                document.getElementById('winnersList').innerHTML += `Número: ${data.number}, Nome: ${data.name}, Prêmio: ${prize}<br>`;

                // Limpa o campo de prêmio após o sorteio
                document.getElementById('giftInput').value = '';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao realizar o sorteio');
        });
};

//Resetar o formulário
document.getElementById('resetBtn').addEventListener('click', function() {
    document.getElementById('resetBtn').disabled = true;
    document.getElementById('excelFile').disabled = false;
    document.getElementById('excelFile').value = '';
    document.getElementById('uploadForm').querySelector('[type="submit"]').disabled = false;
});


