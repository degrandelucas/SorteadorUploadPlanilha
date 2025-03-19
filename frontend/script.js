document.getElementById('uploadForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('uploadForm'));

    axios.post('/backend/upload.php', formData)
        .then(function(response) {
            console.log(response.data);
            alert(response.data.message);
        })
        .catch(function(error) {
            console.error(error);
            alert('Erro ao enviar o arquivo.');
        });
});

document.getElementById('drawBtn').addEventListener('click', function() {
    axios.get('/backend/sorteio.php')
        .then(function(response) {
            console.log(response.data);
            if (response.data.success) {
                if (response.data.vencedor) {
                    alert('O vencedor é: ' + response.data.vencedor.nome + ' (Número: ' + response.data.vencedor.numero + ')');
                } else {
                    alert(response.data.message);
                }
            } else {
                alert(response.data.message);
            }
        })
        .catch(function(error) {
            console.error(error);
            alert('Erro ao realizar o sorteio.');
        });
});