<template>
    <v-dialog
        v-model="dialog"
        width="800"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                dark
                v-bind="attrs"
                v-on="on"
                @click="getUsers()"
                icon
            >
                <v-icon color="white">
                    mdi-account-multiple-plus
                </v-icon>
            </v-btn>
        </template>

        <v-card>
            <v-card-title class="headline grey lighten-2">
                <v-icon color="primary" class="mr-3">
                    mdi-account-multiple-plus
                </v-icon>
                Usuários
            </v-card-title>
            <v-row class="ma-3">
                <v-dialog
                    v-model="dialog2"
                    width="500"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            v-bind="attrs"
                            v-on="on"
                        >
                            <v-icon class="mr-3">
                                mdi-account-multiple-plus
                            </v-icon>
                            Novo
                        </v-btn>
                    </template>

                    <v-card>
                        <v-card-title class="headline grey lighten-2">
                            Registro de usuário
                        </v-card-title>

                        <v-card-text>
                            <br />
                            <v-form
                                ref="form"
                                v-model="valid"
                                lazy-validation
                            >
                           <v-text-field v-model="novoUser.name" dense outlined :rules="[v => !!v || 'O campo é obrigatório']" label="Nome" />
                                <p />
                                <v-row class="mt-0 mb-0">
                                    <v-col cols="6">
                                        <v-text-field v-model="novoUser.cpf" v-mask="'###.###.###-##'" dense outlined :rules="[v => !!v || 'O campo é obrigatório']" label="CPF" />
                                    </v-col>
                                    <v-col cols="6">
                                        <vuetify-money v-model="novoUser.valor_hora" outlined dense  :rules="[v => !!v || 'O campo é obrigatório']" label="Valor por hora" />
                                    </v-col>
                                </v-row>
                                <v-row class="mt-0 mb-0">
                                <v-col cols="6">
                                    <v-text-field v-model="novoUser.email" dense outlined :rules="emailRules" label="Email" />
                                </v-col>
                                <v-col cols="6">
                                    <v-autocomplete v-model="novoUser.rule_id" dense outlined :rules="[v => !!v || 'O campo é obrigatório']" :items="[{id:1, title:'Administrador'},{id:2, title:'Funcionário'}]" item-value="id" item-text="title" label="Função" />
                                </v-col>
                            </v-row>

                            <v-row class="mt-0 mb-0">
                                <v-col cols="6">
                                    <v-text-field type="password" v-model="novoUser.password" dense outlined :rules="[v => !!v || 'O campo é obrigatório']" label="Senha" />
                                </v-col>
                                <v-col cols="6">
                                    <v-text-field type="password" dense outlined :rules="[v => !!v && v === novoUser.password || 'Senha inválida']" label="Confirme a senha" />
                                </v-col>
                            </v-row>

                           <v-row class="ma-0">
                               <v-spacer />
                               <v-btn
                                   color="primary"
                                   :disabled="!valid"
                                   @click="validarEsalvar()"
                               >
                                   Salvar
                               </v-btn>
                           </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-dialog>
            </v-row>
            <v-divider />
            <v-data-table
                hide-default-footer
                :headers="headers"
                :items="users"
                class="elevation-1"
            >
                <template v-slot:item.action="{ item }">

                    <v-row dense>
                        <div class="text-center row ">
                            <editar-user @atualizado="getUsers()" :useredit="item" />
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                        v-bind="attrs"
                                        v-on="on"
                                        icon
                                        :disabled="item.id === 1"
                                        class="ml-2"
                                        @click="deletarUser(item.id)"
                                    >
                                        <v-icon
                                            class="error--text"
                                        >
                                            delete
                                        </v-icon>
                                    </v-btn>
                                </template>
                                <span>Excluir usuário</span>
                            </v-tooltip>
                        </div>
                    </v-row>
                </template>
            </v-data-table>

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
    import EditarUser from "./EditarUser";
    export default {
        name: "AddUsers",
        components: {EditarUser},
        data () {
            return {
                valid: true,
                dialog: false,
                dialog2: false,
                headers: [
                    {
                        text: 'ID',
                        align: 'start',
                        sortable: false,
                        value: 'id',
                    },
                    { text: 'Nome', value: 'name' },
                    { text: 'CPF', value: 'cpf' },
                    { text: 'Email', value: 'email' },
                    { text: 'Função', value: 'roles[0].title' },
                    { text: 'Valor hora', value: 'valor_hora' },
                    { text: 'Ações', value: 'action' },
                ],
                users: [],
                novoUser:{
                    name: null,
                    cpf: null,
                    email: null,
                    password: null,
                    valor_hora: 0,
                    rule_id: null,
                },
                emailRules: [
                    v => !!v || 'O campo e-mail é obrigatório',
                    v => /.+@.+\..+/.test(v) || 'O e-mail deve ser válido',
                ],
            }
        },
        methods:{
            getUsers(){
                axios.get('/users')
                    .then(res =>{
                        this.users = res?.data;
                    })
            },
            validarEsalvar(){
                if(this.$refs.form.validate()){
                    axios.post('/users', this.novoUser)
                        .then(()=>{
                            this.$swal.fire(
                                {
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Usuário criado com sucesso!',
                                    timer: 3000
                                });
                            this.novoUser.name = null;
                            this.novoUser.email = null;
                            this.novoUser.password = null;
                            this.novoUser.rule_id = null;
                            this.dialog2 = false;
                        }).catch(() => {
                        this.$swal.fire(
                            {
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Falha na solicitação',
                                timer: 3000
                            });
                    }).finally(()=>this.getUsers())
                }
            },
            deletarUser(id){
                axios.delete('/users/'+id).then(()=>{
                    this.$swal.fire(
                        {
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Usuário deletado com sucesso!',
                            timer: 3000
                        });
                })
            }
        },
        mounted() {
            this.getUsers()
        }
    }
</script>

<style scoped>

</style>
