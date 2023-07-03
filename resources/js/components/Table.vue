<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in title" :key="key">{{ t.titulo }}</th>
                    <th v-if="visualizar.visivel || editar.visivel || remover.visivel">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="title[chaveValor].tipo == 'imagem'"><img :src="'/storage/' + valor" width="30"
                                height="30"></span>
                        <span v-if="title[chaveValor].tipo == 'text'">{{ valor }}</span>
                        <span v-if="title[chaveValor].tipo == 'data'">{{ valor }}</span>
                    </td>
                    <td v-if="visualizar.visivel || editar.visivel || remover.visivel">
                        <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm"
                            :data-bs-toggle="visualizar.dataToggle" :data-bs-target="visualizar.dataTarget"
                            @click="setStore(obj)">Visualiazar</button>

                        <button v-if="editar.visivel" class="btn btn-outline-primary btn-sm"
                            :data-bs-toggle="editar.dataToggle" :data-bs-target="editar.dataTarget" @click="setStore(obj)">
                            Editar </button>

                        <button button v-if="remover.visivel" class="btn btn-outline-danger btn-sm"
                            :data-bs-toggle="remover.dataToggle" :data-bs-target="remover.dataTarget"
                            @click="setStore(obj)">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: ['dados', 'title', 'visualizar', 'remover', 'editar'],
    methods: {
        setStore(obj) {
            this.$store.state.transacao.status = ''
            this.$store.state.transacao.message = ''
            this.$store.state.transacao.dados = ''
            this.$store.state.item = obj
        }
    },
    computed: {
        dadosFiltrados() {
            let campos = Object.keys(this.title);
            let dadosFiltrados = []

            this.dados.map((item, chave) => {

                var itemFiltrado = {}

                campos.forEach(campo => {

                    itemFiltrado[campo] = item[campo]
                })

                let dataFormatada = moment(itemFiltrado.created_at).format('DD/MM/YYYY')
                itemFiltrado.created_at = dataFormatada

                dadosFiltrados.push(itemFiltrado)
            })

            return dadosFiltrados
        }
    }
}
</script>
