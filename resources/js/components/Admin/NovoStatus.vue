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
            >
                <v-icon small class="mr-2">
                    mdi-ambulance
                </v-icon>
                Novo
            </v-btn>
        </template>

        <v-card>
            <v-card-title>
                INFORMAR LOCALIZAÇÃO
            </v-card-title>

            <v-card-text>
                <v-form ref="form" v-model="valid" lazy-validation>
                    <v-text-field
                        required :rules="requerido" v-model="historico.origem"
                        outlined dense label="Origem" />
                    <v-text-field
                        required :rules="requerido" v-model="historico.destino"
                        outlined dense label="Destino" />
                    <v-autocomplete
                        required :rules="requerido" v-model="historico.status_entrega_id"
                        :items="status" item-text="descricao" item-value="id"
                        outlined dense label="Status" />
                    <v-spacer></v-spacer>
                    <v-row class="ma-0">
                        <v-btn
                            color="primary"
                            @click="save()"
                            :disabled="!valid"
                        >
                            Salvar
                        </v-btn>
                        <v-spacer />
                        <v-btn
                            color="error"
                            @click="dialog = !dialog"
                            text
                        >
                            fechar
                        </v-btn>
                    </v-row>

                </v-form>

            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props: ['entrega'],
        name: "NovoStatus",
        data () {
            return {
                dialog: false,
                valid: true,
                status: [],
                historico:{
                    origem: null,
                    destindo: null,
                    entrega_id: this.entrega,
                    status_entrega_id: null
                },
                requerido: [
                    v => !!v || 'O campo é obrigatório',
                ],
            }
        },
        methods:{
            save(){
                if (this.$refs.form.validate()){
                    axios.post(`/historico`,this.historico)
                        .then(() => {
                            this.$swal.fire(
                                'Historico atualizado!',
                                'Um novo registro da entrega foi adicionado com sucesso.',
                                'success'
                            );
                            this.$emit('criadoComSucesso')
                            this.dialog = false;
                        })
                }
            }
        },
        watch:{
            dialog(val){
                if (val){
                    axios.get(`/status`)
                        .then(res => {
                            this.status = res.data?.data;
                        })
                }
            }
        }
    }
</script>

<style scoped>

</style>
