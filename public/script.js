const uploadForm = document.getElementById('uploadForm');
const drawBtn = document.getElementById('drawBtn');

uploadForm.addEventListener('submit', async (event) => {
    event.preventDefault();
    try {
        const response = await axios.post('../backend/upload.php', new FormData(uploadForm));
        alert(response.data.message);
    } catch (error) {
        console.error(error);
        alert('Erro upload');
    }
});

drawBtn.addEventListener('click', async () => {
    try {
        const response = await axios.get('../backend/sorteio.php');
        if (response.data.success) {
            alert(response.data.vencedor ? `Vencedor: ${response.data.vencedor.numero} (${response.data.vencedor.nome})` : response.data.message);
        }
        else {
            alert(response.data.message);
        }
    } catch (error) {

        console.error(error);
        alert('Erro sorteio');
    }
});