<template>
    <v-card color="basil">
        <!-- <v-card-title class="text-center justify-center py-6">
            <h1 class="font-weight-bold text-h2 basil--text">
                Carte Planning Section
            </h1>
        </v-card-title> -->
        <v-tabs
            v-model="tab"
            background-color="transparent"
            color="basil"
            grow
        >
            <v-tab
                style="font-size:20px;"
                v-for="item in items"
                :key="item.tab"
            >
                {{ item.tab}}
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
            <v-tab-item
            >
                <v-card
                    style="border:1px solid red; margin:2px;"
                    color="basil"
                    flat
                    >
                    <v-card-text><PCGM/></v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <v-card
                    style="border:1px solid red; margin:2px;"
                    color="basil"
                    flat
                    >
                    <v-card-text><PCGR/></v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs-items>
    </v-card>
</template>

<script>
import PCGM from './PCGMonitoring.vue'
import PCGR from './PCGReport.vue'
export default {
    components : {
        PCGM, PCGR
    },
    data() {
        return {
            
            tab: null,
            items: [
                { tab: 'PCG Monitoring', content: 'Tab 1 Content' },
                { tab: 'PCG Report', content: 'Tab 2 Content' },
                { tab: 'PCG Chart', content: 'Tab 3 Content' },
            ],
        }
    },

}
</script>

<style >
    .basil {
        background-color: #FFFBE6 !important;
    }
    .basil--text {
        color: #356859 !important;
    }
</style>