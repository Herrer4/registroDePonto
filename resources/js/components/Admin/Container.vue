<template>
    <div class="back">
        <v-app id="inspire">
            <v-app-bar :clipped-left="$vuetify.breakpoint.lgAndUp" app color="blue darken-3" dark>
                <v-toolbar-title style="width: 400px" class="ml-0 pl-4">
                    <span>{{titleize(name).split(' ')[0]}} {{name.split(' ').length>1?titleize(name).split(' ')[name.split(' ').length -1]:''}}</span>
                </v-toolbar-title>

                <div class="flex-grow-1"></div>
                <add-users v-if="parseInt(id) === 1" />
                <reset-password />

                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <v-btn
                            icon
                            class="mr-3"
                            v-on="on"
                            @click="sair"
                        >
                            <v-icon>power_settings_new
                            </v-icon>
                        </v-btn>
                    </template>
                    <span>Sair</span>
                </v-tooltip>

            </v-app-bar>

            <v-main>
                <v-container
                    filled
                    fluid fill-height
                >
                    <v-layout
                    >
                        <v-flex text-xs-center >
                            <slot />
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-main>
        </v-app>
    </div>
</template>

<script>

import ResetPassword from "./ResetPassword";
import AddUsers from "./AddUsers";
export default {
    name: "Container",
    props: ['id','name'],
    components: {AddUsers, ResetPassword},
    data: () => ({
        items: [
            { icon: "mdi mdi-home", text: "Início", href: "/"},
            { icon: "reorder", text: "Extra",
                children:
                    [
                        { icon: "reorder", text: "Extra", href: "conf" },
                    ]
            },
        ],
    }),
    methods:{
        redirecionar(){
            window.location.href ='/'
        },

        sair(){
            axios.post('/logout', this.campos)
                .then(() => {
                    location.reload()
                })
        },
        titleize(text) {
            var words = text.toLowerCase().split(" ");
            for (var a = 0; a < words.length; a++) {
                var w = words[a];
                words[a] = w[0].toUpperCase() + w.slice(1);
            }
            return words.join(" ");
        }
    },
    mounted() {
        setInterval( ()=>{
            const today = new Date();
            const date = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
            const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            this.$store.commit('setDataHota',(date + ' ' + time));
        }, 1000);
    }
};
</script>
<style scoped>
.back {
    background-image: url('https://www.gannett-cdn.com/presto/2021/01/25/NAAS/926c2ba9-3470-4705-9a51-c54b7eab4f28-Calendar.JPG?auto=webp&crop=851,479,x0,y0&format=pjpg&width=1200');
    background-size: cover;
}
#inspire {
    background: none;
}
</style>
