document.addEventListener('DOMContentLoaded', function() {
    const drawBtn = document.getElementById('drawBtn');
    const countDownDiv = document.getElementById('countDown');
    const winnerDiv = document.getElementById('winner');
    const winnersListDiv = document.getElementById('winnersList');

    document.getElementById('uploadForm').addEventListener('submit', function(event) {
        event.preventDefault(); //Prevents the default behavior of submitting the form for browser.
        const formData = new FormData(document.getElementById('uploadForm'));
        axios.post('../backend/upload.php', formData).then(response => {
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
        axios.get('../backend/sorteio.php').then(response => {
            const responseData = response.data;
            if (responseData.success) {
                winnerDiv.textContent = 'Vencedor: ' + responseData.vencedor.numero + ' - ' + responseData.vencedor.nome;
                let winnersHTML = 'Vencedores: ';
                responseData.vencedores.forEach(vencedor => {
                    winnersHTML += vencedor.numero + ' - ' + vencedor.nome + ', ';
                });
                winnersListDiv.textContent = winnersHTML.slice(0, -2);
            } else {
                alert(responseData.message);
            }
        })
            .catch(error => {
                console.error('Erro ao sortear:', error);
            });
    }

});