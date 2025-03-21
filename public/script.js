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

document.getElementById('resetBtn').addEventListener('click', function() {
    document.getElementById('resetBtn').disabled = true;
    document.getElementById('excelFile').disabled = false;
    document.getElementById('excelFile').value = '';
    document.getElementById('uploadForm').querySelector('[type="submit"]').disabled = false;
});