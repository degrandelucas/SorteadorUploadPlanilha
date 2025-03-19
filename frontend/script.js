new Vue({
    el: '#app',
    data: {
        vencedor: null,
        vencedores: [],
        contagemRegressiva: 0,
    },
    methods: {
        sortear() {
            this.contagemRegressiva = 3; // Contagem regressiva de 3 segundos
            const intervalo = setInterval(() => {
                this.contagemRegressiva--;
                if (this.contagemRegressiva <= 0) {
                    clearInterval(intervalo);
                    this.obterVencedor(); // Chama a função para obter o vencedor após a contagem regressiva
                }
            }, 1000);
        },
        obterVencedor() {
            axios.get('../backend/sorteio.php')
                .then(response => {
                    const data = response.data;
                    if (data.success) {
                        this.vencedor = data.vencedor;
                        this.vencedores = data.vencedores;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro ao sortear:', error);
                });
        },
    },
});