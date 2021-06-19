<template>
    <v-layout  align-center justify-center>
        <v-flex xs11 sm8 md6 lg4 xl3 class="mb-12">
            <v-card class="elevation-12"  >
                <v-toolbar dark color="primary" align-center justify-center >
                    <v-toolbar-title>REGISTRO DE PONTO</v-toolbar-title>
                </v-toolbar>

                <v-row class="ml-1  mr-1 mb-3" justify="center" align="center">

                    <v-col cols="md-12" absolute>
                        <v-row class="mt-3" top justify="center">
                            <h1>Entrar</h1>

                        </v-row>
                        <br />
                            <v-text-field  v-model="login.email" outlined placeholder="Informe o e-mail de login" name="login" label="Login" type="text"></v-text-field>
                            <v-text-field  v-model="login.password" outlined placeholder="**************" label="Digite sua senha" type="password" autocomplete="new-password"></v-text-field>

                            <v-btn @click="logar" :loading="loading" large block :disabled="!login.email||!login.password||loading" color="primary">Login</v-btn>

                    </v-col>
                </v-row>
                <v-footer
                    class="font-weight-medium"
                >
                    <v-col
                        class="text-center"
                        cols="12"
                    >

                    </v-col>
                </v-footer>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
    export default {
        name: "LoginSistema",
        data () {
            return {
                login: {},
                loading: false,
            }
        },
        methods:{
            logar(){
                this.loading = true;
            axios.post('login', this.login).then(result =>{
                    this.$swal.fire(
                        {
                            icon: 'success',
                            title: 'Autenticado com sucesso!',
                            timer: 1500
                        });
                    location.reload();
            }).catch(error => {
                if(error.response.status === 422){
                    this.$swal.fire(
                        {
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Erro de autenticação',
                            timer: 3000
                        });
                }else if(error.response.status == 429){
                    this.$swal.fire(
                        {
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Limite de tentativas excedido',
                            text:'Tente novamente 30 segundos.',
                            timer: 30000
                        });
                }else{
                    this.$swal.fire(
                        {
                            icon: 'error',
                            title: 'ERRO #'+error.response.status,
                            text:'Houve um erro inesperado, contate o suporte.',
                            timer: 10000
                        });
                }
            }).finally(()=>this.loading = false)

            }
        }
    }
</script>
