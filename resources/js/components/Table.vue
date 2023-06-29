<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t, key in title" :key="key">{{ t.titulo }}</th>
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
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: ['dados', 'title'],
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
