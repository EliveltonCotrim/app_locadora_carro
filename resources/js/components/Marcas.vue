<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component title="Buscar Marcas">
                    <template v-slot:content>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component title="ID" id="inputId" id-help="idHelp"
                                    help-text="Enter the brand ID">

                                    <input type="number" class="form-control" id="inputId" v-model="busca.id"
                                        aria-describedby="idHelp" />
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component title="Name" id="inputName" id-help="nameHelp"
                                    help-text="Enter the brand name">
                                    <input type="text" class="form-control" id="inputName" v-model="busca.nome"
                                        aria-describedby="nameHelp" />
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-sm btn-primary float-end" @click="pesquisar()">
                            Search
                        </button>
                    </template>
                </card-component>
                <!-- end card de busca -->

                <!-- Card de listagem -->
                <card-component title="Relação de Marcas">
                    <template v-slot:content>
                        <table-component :dados="marcas.data" :title="{
                            id: { titulo: 'ID', tipo: 'text' },
                            nome: { titulo: 'Nome', tipo: 'text' },
                            imagem: { titulo: 'Imagem', tipo: 'imagem' },
                            created_at: { titulo: 'Data de criação', tipo: 'data' }
                        }"></table-component>
                    </template>
                    <template v-slot:footer>

                        <div class="row">
                            <div class="col-10">
                                <paginate-component>

                                    <li v-for="link, chave in marcas.links" :key="chave" class="page-item"><a
                                            :class="link.active == true ? 'page-link active' : 'page-link'" href="#"
                                            v-html="link.label" @click="paginacao(link)"></a></li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalAddMarca">
                                    To add
                                </button>

                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- end card list -->
            </div>
        </div>
        <modal-component id="modalAddMarca" title="Add marca" title-button="Save">
            <template v-slot:alerts>
                <alert-component :details="transactionsDetails" title="Cadastro efetuado com sucesso."
                    v-if="transactionStatus == 'success'" type="success" :message="messageAlert"></alert-component>
                <alert-component :details="transactionsDetails" title="Erro ao tentar cadastrar a marca"
                    v-if="transactionStatus == 'error'" type="danger"></alert-component>
            </template>
            <template v-slot:content>
                <div class="form-group mb-2">
                    <input-container-component id="inputName" title="Name" id-help="nameHelp"
                        help-text="Enter the brand name">
                        <input type="text" class="form-control" id="inputName" v-model="inputName"
                            aria-describedby="nameHelp" />
                    </input-container-component>
                </div>
                <div class="form-group mb-2">
                    <input-container-component id="inputImage" title="Image" id-help="imageHelp"
                        help-text="Select an image in PNG format">
                        <input type="file" class="form-control" id="inputImage" @change="imageLoading($event)"
                            aria-describedby="imageHelp" />
                    </input-container-component>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="save()">Save</button>
            </template>
        </modal-component>
    </div>
</template>
<script>
import Alert from './Alert.vue';
import Paginate from './Paginate.vue';
export default {
    components: { Alert, Paginate },
    computed: {
        token() {

            let token = document.cookie.split(';').find(indice => {
                return indice.includes('token=')
            })

            token = token.split('=')[1];
            token = 'Bearer ' + token
            return token;
        }
    },
    data() {
        return {
            urlBase: 'http://localhost:80/api/v1/marca',
            urlPaginacao: '',
            urlFiltro: '',
            inputName: '',
            inputImage: '',
            transactionStatus: '',
            transactionsDetails: {},
            marcas: { data: [] },
            busca: {
                id: '',
                nome: ''
            }

        }
    },
    methods: {
        pesquisar() {
            let filtro = ''

            for (let chave in this.busca) {
                if (this.busca[chave]) {

                    if (filtro != '') {
                        filtro += ';'
                    }

                    filtro += chave + ':like:' + this.busca[chave]
                }
            }

            if (filtro) {
                this.urlPaginacao = 'page=1'
                this.urlFiltro = '&filtro=' + filtro

            } else {
                this.urlFiltro = ''

            }

            this.carregarLista()
        },
        paginacao(link) {
            if (link.url) {
                this.urlPaginacao = link.url.split('?')[1]
                this.carregarLista()
            }
        },
        carregarLista() {
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro
            console.log('url: ' + url)
            axios.get(url)
                .then(response => {
                    this.marcas = response.data
                    console.log(this.marcas)
                })
                .catch(errors => {
                    console.log(errors)
                })

        },
        imageLoading(event) {
            this.inputImage = event.target.files
        },
        save() {

            let formData = new FormData();
            formData.append('nome', this.inputName)
            formData.append('imagem', this.inputImage[0])

            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorization': this.token,

                }
            }

            axios.post(this.urlBase, formData, config)

                .then(response => {
                    this.transactionStatus = 'success'
                    this.transactionsDetails = {
                        message: 'ID do resgistro ' + response.data.id,
                    }
                })

                .catch(errors => {
                    this.transactionStatus = 'error'
                    this.transactionsDetails = {
                        message: errors.response.data.message,
                        dados: errors.response.data.errors
                    }
                })
        }

    },
    mounted() {
        this.carregarLista()
    }
}

</script>
