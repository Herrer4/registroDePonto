<template>
    <div class="text-center">
        <v-dialog
            v-model="dialog"
            width="400"
            persistent
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                >
                    <v-icon>
                        mdi-plus
                    </v-icon>
                    Registrar ponto
                </v-btn>
            </template>

            <v-card>
                <v-card-title class="primary white--text">
                    <v-icon class="mr-4" color="white">
                        mdi-calendar
                    </v-icon>
                    REGISTRAR PONTO
                </v-card-title>

                <v-card-text>
                    <v-form ref="form"
                            v-model="valid"
                            lazy-validation >
                        <v-row class="ma-0">
                            <v-col cols="12">
                                <br>
                                <v-row class="ma-0" justify="center" align="center">
                                    <h1>
                                        {{$store.state.datahora}}
                                    </h1>
                                </v-row>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="ponto.justificativa"
                                    label="Justificar atraso" dense outlined />
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-row class="ma-0">
                        <v-col cols="6">
                            <v-btn
                                color="error"
                                @click="dialog = false"
                                block
                            >
                                Cancelar
                            </v-btn>
                        </v-col>
                        <v-col cols="6">
                            <v-btn
                                block
                                color="primary"
                                :disabled="!valid"
                                @click="validate()"
                            >
                                Confirmar
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    export default {
        props: ['status'],
        name: "NewEntrega",
        data () {
            return {
                dialog: true,
                valid: true,
                ponto: {
                    justificativa: null,
                    hora: null
                },
                requerido: [
                    v => !!v || 'O campo é obrigatório',
                ],
            }
        },
        watch:{
            dialog(val){
                if (!val){
                    this.$refs.form.reset()
                }
            }
        },
        methods:{
            validate() {
                if(this.$refs.form.validate()){
                    const today = new Date();
                    let mes = (today.getMonth()+1)
                    if(mes < 10){
                        mes = '0'+ mes.toString()
                    }
                    const date = today.getFullYear()+'-'+mes+'-'+today.getDate();
                    const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                    this.ponto.hora = (date + ' ' + time)
                    axios.post('/pontos', this.ponto)
                        .then(() =>{
                            this.$swal.fire({ icon: 'success', title: 'Ponto cadastrada com sucesso!', timer: 1000, showConfirmButton: false });
                            this.$refs.form.reset();
                            this.$emit('sucesso');
                            this.dialog = false;
                        }).catch(error =>{
                        this.$swal.fire({ icon: 'warning', title: 'A folha de ponto de já foi preenchida. Volte amanhã!', timer: 3000, showConfirmButton: true });
                        this.$refs.form.reset();
                        this.dialog = false;
                    })
                }
            }
        },

    }
</script>

<style scoped>

</style>
