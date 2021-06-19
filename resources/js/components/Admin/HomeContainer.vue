<template>
    <v-data-table
        dense
        :headers="headers"
        :items="items"
        :server-items-length="total"
        :items-per-page="this.pagination.rowsPerPage"
        :footer-props="footerProps"
        :no-data-text="'nenhum registro cadastrada.'"
        @update:page="pagination"
        @update:items-per-page="linhasPorpagina"
        :loading="loading"
        locale="pt_BR"
        height="100%"
        fixed-header
        class="elevation-1 fixed-header v-table__overflow"
        style="max-height: calc(97vh - 65px); overflow: auto ;backface-visibility: hidden;"
        no-results-text="Nenhuma registro encontrada"
        loading-text="Buscando pontos..."
    >
        <!--titulo-->
        <template v-slot:top>
            <v-toolbar flat>
                    <v-icon class="mr-2" color="primary">
                        mdi-calendar
                    </v-icon>
                <v-toolbar-title>Controle de pontos</v-toolbar-title>
                <v-spacer />
                <v-btn
                    class="mr-4"
                    color="warning"
                    @click="getFolhaMes()">
                    <v-icon left>mdi-printer</v-icon>Imprimir mês corrente
                </v-btn>
                <div style="display: none" >
                    <div id="modalInvoice">
                        <table class="comBordaSimples "  width="100%" >
                            <tr>
                                <th colspan="12"><h1>Folha de Ponto</h1></th>
                            </tr>
                            <tr>
                                <td height="25" colspan="3">
                                    <div align="left" ><strong>Nome:</strong> {{dados?dados.usuario.name:'****************'}}</div>
                                </td>
                                <td height="25" colspan="3">
                                    <div align="left"><strong>CPF:</strong>{{dados?dados.usuario.cpf:'****************'}}</div>
                                </td>
                                <td height="25"  >
                                </td>
                            </tr>
                            <tr>
                                <td height="25" colspan="3"><strong>Cargo:</strong> {{dados?dados.usuario.isAdmin?'Administrador':'Funcionário':'****************'}}</td>
                                <td height="25" colspan="2"><strong>Valor hora:</strong> {{dados?dados.usuario.valor_hora:'0,00'}} </td>
                                <td height="25" colspan="2"><strong>Mês de referência:</strong> {{dados?dados.mes:'********'}} </td>
                            </tr>
                            <tr>
                                <td width="15%" height="25"> </td>
                                <td width="10%" height="25"> <strong>Entrada</strong> </td>
                                <td width="10%" height="25"> <strong>Saída </strong></td>
                                <td width="10%" height="25"> <strong>Entrada</strong> </td>
                                <td width="10%" height="25"> <strong>Saída </strong></td>
                                <td width="17%" height="25"> <strong>Total em horas</strong></td>
                                <td height="25"> <strong>Justificativa de atraso </strong></td>
                            </tr>
                            <tr v-if="dados && dados.pontos" v-for="(ponto, index) in pontos" :key="index+1">
                                <td width="15%"> {{ponto.day}} </td>
                                <td width="10%"> {{ponto.ponto_1?ponto.ponto_1.split(' ')[1]:'- - - -'}}</td>
                                <td width="10%"> {{ponto.ponto_1?ponto.ponto_2.split(' ')[1]:'- - - -'}} </td>
                                <td width="10%"> {{ponto.ponto_1?ponto.ponto_3.split(' ')[1]:'- - - -'}} </td>
                                <td width="10%"> {{ponto.ponto_1?ponto.ponto_4.split(' ')[1]:'- - - -'}} </td>
                                <td width="15%">{{ponto.totalHoras}} horas</td>
                                <td height="25"> {{ponto.justificativa}} </td>
                            </tr>
                        </table>
                        <table class="comBordaSimples" style="border-top: #ffffff !important"  width="100%">
                            <tr>
                                <td width="25%" height="25"> <strong>Total de horas trabalhadas:</strong></td>
                                <td width="30%" height="25"> {{dados?dados.totalHorasMes:'0'}} horas </td>
                            </tr>
                            <tr>
                                <td width="10%" height="25"> <strong>Assinatura:</strong> </td>
                                <td width="50%" height="25">  </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <new-registro @sucesso="getRegistrosPagination()" :status="status" />
            </v-toolbar>
            <v-divider />
        </template>
        <template v-slot:item.user="{ item }">
            {{item.user?item.user.name.split(' ')[0]:'- - - -'}}
        </template>
        <template v-slot:item.ponto_1="{ item }">
            {{item.ponto_1?item.ponto_1.split(' ')[1]:'- - - -'}}
        </template>
        <template v-slot:item.ponto_2="{ item }">
            {{item.ponto_2?item.ponto_2.split(' ')[1]:'- - - -'}}
        </template>
        <template v-slot:item.ponto_3="{ item }">
            {{item.ponto_3?item.ponto_3.split(' ')[1]:'- - - -'}}
        </template>
        <template v-slot:item.ponto_4="{ item }">
            {{item.ponto_4?item.ponto_4.split(' ')[1]:'- - - -'}}
        </template>
        <template v-slot:item.justificativa="{ item }">
            <span class="red--text">{{item.justificativa}}</span>
        </template>
    </v-data-table>
</template>

<script>
    import NewRegistro from "./NewRegistro";
    export default {
        name: "HomeContainer",
        components: {NewRegistro},
        data(){
            return{
                expanded: [],
                loading: false,
                total: 0,
                items: [],
                pagina: null,
                searchEntrega: null,
                headers: [
                    {
                        text: 'ID',
                        align: 'start',
                        sortable: false,
                        value: 'id',
                        width: 10
                    },
                    { text: 'Data', sortable: false, value: 'day' },
                    { text: 'Nome', sortable: false, value: 'user.name' },
                    { text: 'Entrada', value: 'ponto_1', sortable: false },
                    { text: 'Saída', value: 'ponto_2', sortable: false},
                    { text: 'Entrada', value: 'ponto_3', sortable: false},
                    { text: 'Saída', value: 'ponto_4', sortable: false},
                    { text: 'Justificativa', value: 'justificativa', sortable: false }
                ],
                footerProps: {
                    'items-per-page-options': [15, 17, 30, 50, 100],
                    'items-per-page-text':'Registros por página'
                },
                status: [],
                style: {
                    borderCard: {
                        borderTop: 'solid',
                        borderTopColor: '#77AD1C'
                    }
                },
                dados: null,
                pontos: null
            }
        },
        methods:{
            deleteEntrega(id){
                this.$swal.fire({
                    title: 'Você tem certeza?',
                    text: "Você não poderá reverter isso!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim, excluir agora!'
                }).then(result => {
                    if (result.value) {
                        axios.delete(`/pontos/${id}`)
                            .then(() => {
                                this.$swal.fire(
                                    'Registro excluído!',
                                    'O registro da registro foi excluído com sucesso.',
                                    'success'
                                );
                                this.getRegistrosPagination()
                            })
                    }
                })
            },
            pagination(val) {
                this.pagina = val;
                this.getRegistrosPagination();
            },
            linhasPorpagina(val) {
                this.pagination.rowsPerPage = val;
                this.getRegistrosPagination();
            },
            getRegistrosPagination() {
                this.loading = true;
                if (this.searchEntrega) {
                    axios.get(`/pontos?query=${this.searchEntrega}&page=${this.pagina ? this.pagina : 1}&per_page=${this.pagination.rowsPerPage ? this.pagination.rowsPerPage : 15}`)
                        .then(res => {
                            this.items = res?.data?.data;
                            this.total = res?.data?.meta.total;
                        })
                        .finally(() => this.loading = false);
                }
                if (this.pagination.sortBy && !this.searchEntrega) {
                    const direction = this.pagination.descending ? 'desc' : 'asc';
                    axios.get(`/pontos?direction=${direction}&sortBy=${this.pagination.sortBy}&page=${this.pagina ? this.pagina : 1}&per_page=${this.pagination.rowsPerPage ? this.pagination.rowsPerPage : 15}`)
                        .then(res => {
                            this.items = res?.data?.data;
                            this.total = res?.data?.meta.total;
                        })
                        .catch(err => console.log(err.response.data))
                        .finally(() => this.loading = false);
                }
                if (!this.searchEntrega && !this.pagination.sortBy) {
                    axios.get(`/pontos?page=${this.pagina ? this.pagina : 1}&per_page=${this.pagination.rowsPerPage ? this.pagination.rowsPerPage : 15}`)
                        .then(res => {
                            this.items = res?.data?.data;
                            this.total = res?.data?.meta?.total;
                        })
                        .catch(err => console.log(err))
                        .finally(() => this.loading = false);
                }
            },
            print () {
                const modal = document.getElementById("modalInvoice")
                const cloned = modal.cloneNode(true)
                let section = document.getElementById("print")

                if (!section) {
                    section  = document.createElement("div")
                    section.id = "print"
                    document.body.appendChild(section)
                }

                section.innerHTML = "";
                section.appendChild(cloned);
                window.print();
            },

            async getFolhaMes(){
                await axios.get('/pontos/1').then(resultado =>{
                    this.dados = resultado.data
                    this.pontos = resultado.data.pontos
                }).finally(()=> this.dialog = false)
                this.print()
            }
        },
        mounted() {
            this.getRegistrosPagination();
        },
    }
</script>

<style>
@media screen {
    #print {
        display: none;
    }
}
table.comBordaSimples {
    border-collapse: collapse; /* CSS2 */
    background: #FFFFF0;
}

table.comBordaSimples td {
    border: 1px solid black;
    padding-left: 5px;
}

table.comBordaSimples th {
    border: 1px solid black;
    background: #F0FFF0;
}
@media print {
    body * {
        visibility:hidden;
    }
    #print, #print * {
        visibility:visible;
    }
    #print {
        position:absolute;
        left:0;
        top:0;
        right:0;
    }
}

    >>>.v-data-table-header-mobile{
        display: none !important;
    }
    .fixed-header {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .fixed-header table {
        table-layout: fixed;
    }
    .fixed-header th {
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .fixed-header th:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
    }
    .fixed-header tr.v-datatable__progress th {
        height: 1px;
    }
    .fixed-header .v-table__overflow {
        flex-grow: 1;
        flex-shrink: 1;
        overflow-x: auto;
        overflow-y: auto;
    }
    .fixed-header .v-datatable.v-table {
        flex-grow: 0;
        flex-shrink: 1;
    }
    .fixed-header .v-datatable.v-table .v-datatable__actions {
        flex-wrap: nowrap;
    }
    .fixed-header .v-datatable.v-table .v-datatable__actions .v-datatable__actions__pagination {
        white-space: nowrap;
    }
    #table .v-data-footer {
        position: fixed;
        bottom: 67px;
        width: 98%;
        border-radius: 0;
        margin-right: 0px;

    }
    /*tr {*/
    /*    background-color: red !important;*/
    /*}*/
    .v-application .elevation-2 {
        position: absolute;
        margin-right: 13px;
        margin-left: 1px;
        border-radius: 0;
    }

    #table .v-data-footer {
        position: fixed;
        bottom: 65px;
        border-radius: 0;
        width: 98%;

    }
    .theme--light.v-data-table {
        background-color: #ffffffc7 !important;
        color: rgba(0,0,0,.87);
    }
    #table .v-data-table__wrapper {
        margin-bottom: 75px;
    }
</style>

