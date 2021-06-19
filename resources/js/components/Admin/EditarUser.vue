<template>
    <v-dialog
        v-model="dialog"
        width="500"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="primary"
                dark
                v-bind="attrs"
                v-on="on"
                icon
            >
                <v-icon
                    class="warning--text"
                >
                    mdi-account-edit
                </v-icon>
            </v-btn>
        </template>

        <v-card>
            <v-card-title class="headline grey lighten-2">
                Editar usuário
            </v-card-title>

            <v-card-text>
                <br />
                <v-form
                    ref="form"
                    v-model="valid"
                    lazy-validation
                >
                    <v-text-field v-model="useredit.name" dense outlined :rules="[v => !!v || 'O campo é obrigatório']" label="Nome" />
                    <v-text-field v-model="useredit.email" dense outlined :rules="emailRules" label="Email" />
                    <v-autocomplete :disabled="useredit.id === 1" v-model="useredit.roles[0].id" dense outlined :rules="[v => !!v || 'O campo é obrigatório']" :items="[{id:1, title:'Administrador'},{id:2, title:'Funcionário'}]" item-value="id" item-text="title" label="Função" />
                    <v-row class="ma-0">
                        <v-spacer />
                        <v-btn
                            color="primary"
                            :disabled="!valid"
                            @click="editarUser(useredit.id)"
                        >
                            Salvar
                        </v-btn>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props:['useredit'],
        name: "EditarUser",
        data () {
            return {
                valid: true,
                dialog: false,
                emailRules: [
                    v => !!v || 'O campo e-mail é obrigatório',
                    v => /.+@.+\..+/.test(v) || 'O e-mail deve ser válido',
                ],
            }
        },
        methods:{
            editarUser(id){
                if(this.$refs.form.validate()) {
                    axios.patch('/users/' + id, this.useredit).then(() => {
                        this.$swal.fire(
                            {
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Usuário atualizado com sucesso!',
                                timer: 3000
                            });
                    })
                    this.dialog = false;
                    this.$emit('atualizado')
                }
            }
        }
    }
</script>

<style scoped>

</style>
