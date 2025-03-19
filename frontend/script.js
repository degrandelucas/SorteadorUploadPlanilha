document.addEventListener('DOMContentLoaded', function() {
    const drawBtn = document.getElementById('drawBtn');
    const countDownDiv = document.getElementById('countDown');
    const winnerDiv = document.getElementById('winner');
    const winnersListDiv = document.getElementById('winnersList');

    drawBtn.addEventListener('click', function() {
        let countDown = 3;
        countDownDiv.textContent = 'Contagem Regressiva: ' + countDown;

        const interval = setInterval(() => {
            countDown--;
            countDownDiv.textContent = 'Contagem Regressiva: ' + countDown;

            if (countDown <= 0) {
                clearInterval(interval);
                countDownDiv.textContent = '';
                getWinner();
            }
        }, 1000);
    });

    function getWinner() {
        axios.get('../backend/sorteio.php')
            .then(response => {
                const data = response.data;
                if (data.success) {
                    winnerDiv.textContent = 'Vencedor: ' + data.vencedor.numero + ' - ' + data.vencedor.nome;
                    let winnersHTML = 'Vencedores: ';
                    data.vencedores.forEach(vencedor => {
                        winnersHTML += vencedor.numero + ' - ' + vencedor.nome + ', ';
                    });
                    winnersListDiv.textContent = winnersHTML.slice(0, -2);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao sortear:', error);
            });
    }

    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        axios.post('../backend/upload.php', formData)
            .then(response => {
                const data = response.data;
                if (data.success) {
                    alert('Arquivo enviado com sucesso!');
                } else {
                    alert('Erro ao enviar o arquivo: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar o arquivo:', error);
            });
    });
});