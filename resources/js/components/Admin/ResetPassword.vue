<template>
    <v-dialog
        v-model="dialog"
        width="500"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                dark
                v-bind="attrs"
                v-on="on"
                icon
            >
                <v-icon>
                    mdi-account-lock
                </v-icon>
            </v-btn>
        </template>

        <v-card>
            <v-card-title class="headline grey lighten-2">
                <v-icon color="primary" class="mr-3">mdi-key-chain</v-icon> Alterar senha
            </v-card-title>

            <v-card-text>
                <br />
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation>
                    <v-text-field v-model="update.correntPass" :rules="[v => !!v || 'A senha é obrigatória']" dense outlined label="Digite a senha atual" type="password" />
                    <v-text-field v-model="update.newPass" :rules="[v => !!v || 'Digite a nova senha']" dense outlined label="Digite a nova senha" type="password" />
                    <v-text-field v-model="update.confPass" :rules="[v => !!v && update.confPass === update.newPass || 'As senhas não são iguais']" dense outlined label="Confirme a senha" type="password" />
                    <v-row class="ma-0">
                        <v-btn
                            color="primary"
                            @click="atualizarSenha()"
                            :disabled="!valid"
                        >
                            Salvar
                        </v-btn>
                    </v-row>
                </v-form>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                    color="error"
                    text
                    @click="dialog = false"
                >
                    Fechar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "ResetPassword",
        data () {
            return {
                dialog: false,
                valid: true,
                update:{
                    correntPass:null,
                    newPass:null,
                    confPass:null,
                }
            }
        },
        methods:{
            atualizarSenha() {
                if(this.$refs.form.validate()){
                    axios.post('/reset-password', this.update)
                        .then(() =>{
                            this.$swal.fire(
                                {
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Senha atualizada com sucesso!',
                                    timer: 3000
                                });
                            this.$refs.form.reset();
                            this.dialog = false;
                        }).catch(() =>{
                        this.$swal.fire(
                            {
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Não autorizado!',
                                timer: 3000
                            });
                    })
                }
            }
        }
    }
</script>

<style scoped>

</style>
