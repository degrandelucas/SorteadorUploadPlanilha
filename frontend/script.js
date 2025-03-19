document.addEventListener('DOMContentLoaded', function() {
    const sortearBtn = document.getElementById('sortearBtn');
    const contagemRegressivaDiv = document.getElementById('contagemRegressiva');
    const vencedorDiv = document.getElementById('vencedor');
    const vencedoresDiv = document.getElementById('vencedores');

    sortearBtn.addEventListener('click', function() {
        let contagemRegressiva = 3;
        contagemRegressivaDiv.textContent = 'Contagem Regressiva: ' + contagemRegressiva;

        const intervalo = setInterval(() => {
            contagemRegressiva--;
            contagemRegressivaDiv.textContent = 'Contagem Regressiva: ' + contagemRegressiva;

            if (contagemRegressiva <= 0) {
                clearInterval(intervalo);
                contagemRegressivaDiv.textContent = '';
                obterVencedor();
            }
        }, 1000);
    });

    function obterVencedor() {
        axios.get('../backend/sorteio.php')
            .then(response => {
                const data = response.data;
                if (data.success) {
                    vencedorDiv.textContent = 'Vencedor: ' + data.vencedor.numero + ' - ' + data.vencedor.nome;
                    let vencedoresHTML = 'Vencedores: ';
                    data.vencedores.forEach(vencedor => {
                        vencedoresHTML += vencedor.numero + ' - ' + vencedor.nome + ', ';
                    });
                    vencedoresDiv.textContent = vencedoresHTML.slice(0, -2);
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