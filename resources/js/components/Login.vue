<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="showAlert">
                    <strong>{{ erro }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="card">
                    <div class="card-header">Login teste</div>
                    <div class="card-body">
                        <form method="POST" action="" @submit.prevent="login">
                            <input type="hidden" name="_token" :value="token_csrf">
                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" v-model="email"
                                        required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Senha</label>
                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password"
                                        v-model="password" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Mantenha-me Conectado
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="">
                                        Esqueci a senha
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'LoginComponent',
    props: ['token_csrf'],
    data() {
        return {
            email: '',
            password: '',
            erro: '',
            showAlert: false
        }
    },
    methods: {
        login(event) {
            let url = 'http://localhost:80/api/login'
            let configuracao = {
                method: 'post',
                body: new URLSearchParams({
                    'email': this.email,
                    'password': this.password,
                })
            }


            fetch(url, configuracao)
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        document.cookie = 'token=' + data.token + ';SameSite=:Lax'
                        //dar seguimento ao login
                        event.target.submit()
                    }
                    
                    this.showAlert = false

                    if (data.erro) {
                        this.erro = data.erro
                        this.showAlert = true

                        setTimeout(() => {
                            this.showAlert = false
                        }, 5000)
                    }
                })

        }
    }
}
</script>
