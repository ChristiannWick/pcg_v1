<template>
    <div class="example">
        <v-row class="justify-center mt-2 ml-5 mr-5">
            <v-tabs centered 
                v-model="tempGraph"
                color="primary"
                slider-color="primary">
                <v-tab style="font-size:20px">
                    PCG Report 
                </v-tab>
                <v-tab style="font-size:20px">
                    Planner Report
                </v-tab>
                <v-tab style="font-size:20px">
                    Salesman Report
                </v-tab>
            </v-tabs>
        </v-row>
            <div class="container">
                <v-row class="titles">
                    <v-col cols="5" class="container">
                        <v-row class="container pt-0"> 
                            <a class="titles">Breakdown from:</a>  
                                <v-col>
                                    <v-menu
                                        v-model="menu1"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field 
                                         
                                            v-model="dateFrom"
                                            style="font-size:21px"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        @change="getCharttype(), getChartPlanner(), getChartSalesman()"
                                        v-model="dateFrom"
                                        @input="menu1 = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>
                            <a class="titles">to:</a> 
                                <v-col>
                                    <v-menu
                                        v-model="menu2"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="auto">
                                        <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                           
                                            style="font-size:21px"
                                            v-model="dateTo"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                        </template>
                                        <v-date-picker
                                        @change="getCharttype(), getChartPlanner(), getChartSalesman()"
                                        v-model="dateTo"
                                        @input="(menu2 = false)"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </div>
            <v-row class="justify-end mr-15 pb-0 pt-0 pr-15">
                <v-col cols="2">
                <v-btn small v-show="drillon == true" color="primary" @click="BacktoMain">
                    <v-icon small>mdi-chevron-double-left</v-icon>Back
                </v-btn>
                </v-col>
            </v-row>  
                <v-row class="justify-center mt-10 mb-10">
                    <v-tabs-items v-model="tempGraph">
                        <v-tab-item>
                            <apexchart width="830" type="bar" ref="chart1" :options="options1" :series="series1" ></apexchart>
                        </v-tab-item>
                        <v-tab-item>
                            <apexchart width="930" type="bar" ref="chart2" :options="options2" :series="series2"></apexchart>
                        </v-tab-item>
                        <v-tab-item>
                            <apexchart width="930" type="bar" ref="chart3" :options="options3" :series="series3"></apexchart>
                        </v-tab-item>
                    </v-tabs-items>
                </v-row>    
  

        <v-overlay v-model="onLoad">
            <span>Loading...Please wait..</span>
                <v-progress-circular
                    indeterminate
                    color="primary"
                    size="50"
                    width="10">
                </v-progress-circular>
        </v-overlay>

    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
import moment from 'moment';
import { mapState, mapActions } from 'vuex';
    export default {
        name: "chart",
        components: {
            apexchart : VueApexCharts
        },
        data() {
            return {
///////PCGCAType//////
                selectionCount: 0,
                series1: [{
                    name: 'PCG Type',
                    data: [],
                },
                ],
                options1: {
                    chart: {
                        id : this.getChartId(),
                        type: 'bar',
                        height: 520,
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 1000,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                    },  
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                        toolbar: {
                            show: true, 
                            tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                            customIcons: []
                            }, 
                        },
                        zoom: {
                            enabled: true
                        },
                        events:{
                            dataPointSelection:(event, chartContext, config)=> {
                                if (this.selectionCount < 1) {
                                    this.selectionCount++;
                                    this.$emit("dataPointSelection", config.dataPointIndex);
                                    this.DrillDown({
                                        params:{
                                            BarTitle:config.w.config.xaxis.categories[config.dataPointIndex]
                                        }
                                    })
                                }
                                // this.DrillDown({
                                //     params:{
                                //         BarTitle:config.w.config.xaxis.categories[config.dataPointIndex]
                                //     }
                                // })
                                
                            }
                        }
                        
                    },
                    legend: {
                        show: false
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            distributed: true,
                            endingShape: 'rounded',
                            dataLabels: {
                                position: 'top',
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        position: 'top',
                        formatter: function (val,opts) {
                            return val;
                        },
                      
                        stroke: {
                            show: true,
                            width: 1,
                            colors: ['transparent']
                        },
                        offsetY: -30,
                        offsetX: 0,
                        style: {
                            fontSize: '15px',
                            colors: ["#827717"],
                            position: 'bottom',
                        }
                    },
                    stroke: {
                        show: true,
                        width: 0,
                        colors: ['transparent']
                    },
                  
                    xaxis: {
                        labels: {
                        show: true,
                        rotate: -45,
                        rotateAlways: true,
                        minHeight: 100,
                        maxHeight: 180,
                        },
                        categories: [],
                        position: 'bottom',
                        fontSize: '100%',
                        width: 70,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Total number of PCG Request',

                        },
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return " " + val + " "
                            }
                        }
                    }
                },
///////PLanner Report//////
                series2: [{
                    name: 'PLanner Report',
                    data: [],
                }],
                options2: {
                    chart: {
                        id : this.getPlannerId(),
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: true,  
                        },
                        zoom: {
                            enabled: true
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            dataLabels: {
                                position: 'top',
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        position: 'top',
                        formatter: function (val) {
                            return val;
                        },
                        stroke: {
                            show: true,
                            width: 3,
                            colors: ['transparent']
                        },
                        offsetY: -30,
                        offsetX: 0,
                        style: {
                            fontSize: '15px',
                            colors: ["#827717"],
                            position: 'bottom',
                        }
                    },
                    stroke: {
                        show: true,
                        width: 0,
                        colors: ['transparent']
                    },
                    xaxis: {
                        labels: {
                        show: true,
                        rotate: -60,
                        rotateAlways: true,
                        minHeight: 100,
                        maxHeight: 180,
                        },
                        categories: [],
                        position: 'bottom',
                        fontSize: '100%',
                        width: 100,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Total number of PCG from Planner'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return " " + val + " "
                            }
                        }
                    }
                },
///////Salesman Report//////
                series3: [{
                    name: 'Salesman Report',
                    data: [],
                }],
                options3: {
                    chart: {
                        id : this.getSalesmanId(),
                        type: 'bar',
                        height: 750,
                        toolbar: {
                            show: true,  
                        },
                        zoom: {
                            enabled: true
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            dataLabels: {
                                position: 'top',
                            },
                        },
                    },
                    dataLabels: {
                        enabled: true,
                        position: 'top',
                        formatter: function (val) {
                            return val;
                        },
                        stroke: {
                            show: true,
                            width: 3,
                            colors: ['transparent']
                        },
                        offsetY: -30,
                        offsetX: 0,
                        style: {
                            fontSize: '15px',
                            colors: ["#827717"],
                            position: 'bottom',
                        }
                    },
                    stroke: {
                        show: true,
                        width: 0,
                        colors: ['transparent']
                    },
                    xaxis: {
                        labels: {
                        show: true,
                        rotate: -60,
                        rotateAlways: true,
                        minHeight: 100,
                        maxHeight: 180,
                            style: {
                            colors: [],
                            fontSize: '12px',
                            fontWeight: 500,
                            cssClass: 'apexcharts-xaxis-label',
                        },
                    },
                        categories: [],
                        position: 'bottom',
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true
                        },
                        axisBorder: {
                            show: true
                        },
                    },
                    yaxis: {
                        title: {
                            text: 'Total number of PCG from Salesman'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return " " + val + " "
                            }
                        }
                    }
                },
   
                items : ['All','Wakugumi','Jikugumi'],
                // date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                dateTo :moment().endOf('month').format('YYYY-MM-DD'),
                dateFrom : moment().startOf('month').format('YYYY-MM-DD'),
                menu: false,
                modal: false,
                menu1 : false,
                menu2: false,
                pcgMonitoring_data : [],
                categoryTotal : [],
                onLoad: false,
                tempGraph: null,
                drillon: false, 
            }
        },

        computed: {
            ...mapState(["userInfo"]),
        },

        created(){
            this.options1.chart.id = this.getChartId();
            this.options2.chart.id = this.getPlannerId();
            this.options3.chart.id = this.getSalesmanId();
        },

        methods: {
            ...mapActions(["storeUserInfo"]),

            getChartId() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is zero-based, so we add 1 and pad with leading 0 if needed
                const day = String(date.getDate()).padStart(2, '0'); // Pad day with leading 0 if needed
                return "PCG_Report_" + year + "-" + month + "-" + day;
            },
            getPlannerId() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is zero-based, so we add 1 and pad with leading 0 if needed
                const day = String(date.getDate()).padStart(2, '0'); // Pad day with leading 0 if needed
                return "Planner_Report_" + year + "-" + month + "-" + day;
            },
            getSalesmanId() {
                const date = new Date();
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is zero-based, so we add 1 and pad with leading 0 if needed
                const day = String(date.getDate()).padStart(2, '0'); // Pad day with leading 0 if needed
                return "Salesman_Report_" + year + "-" + month + "-" + day;
            },
            
            BacktoMain(){
                // console.log(this.pcgMonitoring_data)
                this.selectionCount = 0;
                this.drillon = false
                this.series1[0].data=[]
                this.options1.xaxis.categories = []
                const countByPcgType = this.pcgMonitoring_data.reduce((count, obj) => {
                        const pcgType = obj.pcg_type;
                        count[pcgType] = (count[pcgType] || 0) + 1;
                        return count;
                    }, []);
                    // console.log(countByPcgType,'countByPcgType')
                    let newArr2 = [];
                    let newArr = Object.entries(countByPcgType);
                    let test = newArr.sort((a, b) => b[1] - a[1]);
                    let obj = Object.entries(test);
                    newArr2 = [obj];
                    for (let i = 0; i < 20; i++) {
                        // console.log(newArr2[0][i])
                        if (newArr2[0][i]) {
                            this.categoryTotal.push({
                                itemName: newArr2[0][i][1][0],
                                itemCount: newArr2[0][i][1][1],
                            });
                            this.options1.xaxis.categories.push(newArr2[0][i][1][0])
                            this.series1[0].data.push(newArr2[0][i][1][1]);
                        } 
                    }
                    this.$refs.chart1.updateSeries(this.series1,true) 
                    this.$refs.chart1.updateOptions(this.options1)
            },

            DrillDown({params}){
                console.log(params,'params')
                // console.log(this.pcgMonitoring_data,'Data to')
                this.drillon = true
                this.series1[0].data=[]
                this.options1.xaxis.categories = []
                const countByPcgcode = this.pcgMonitoring_data.reduce((count, obj) => {
                    const pcgCode = obj.code
                    if(obj.pcg_type==params.BarTitle){
                        count[pcgCode] = (count[pcgCode] || 0) + 1;
                    }
                    return count;
                }, []);
                // console.log(countByPcgcode,'countByPcgcode')
                this.series1[0].data=[]
                this.options1.xaxis.categories = []
                let newArr2 = [];
                let newArr = Object.entries(countByPcgcode);
                let test = newArr.sort((a, b) => b[1] - a[1]);
                let obj = Object.entries(test);
                newArr2 = [obj];
                for (let i = 0; i < 20; i++) {
                    // console.log(newArr2[0][i])
                    if (newArr2[0][i]) {
                        this.options1.xaxis.categories.push(newArr2[0][i][1][0])
                        this.series1[0].data.push(newArr2[0][i][1][1]);
                    } 
                }
                this.$refs.chart1.updateSeries(this.series1,true)
                this.$refs.chart1.updateOptions(this.options1);
            },
            
            getCharttype(){
                this.series1[0].data=[]
                this.options1.xaxis.categories = []
                axios({
                    method : "post",
                    url : 'api/getChart',
                    data : {
                        dateFrom : this.dateFrom,
                        dateTo : moment(this.dateTo).format('YYYY-MM-DD 23:59:59'),
                    }
                }).then(res => {
                    this.pcgMonitoring_data = res.data;
                    // console.log(res.data,'data');
                    const countByPcgType = this.pcgMonitoring_data.reduce((count, obj) => {
                        const pcgType = obj.pcg_type;
                        count[pcgType] = (count[pcgType] || 0) + 1;
                        return count;
                    }, []);
                    // console.log(countByPcgType,'countByPcgType')
                    let newArr2 = [];
                    let newArr = Object.entries(countByPcgType);
                    let test = newArr.sort((a, b) => b[1] - a[1]);
                    let obj = Object.entries(test);
                    newArr2 = [obj];
                    for (let i = 0; i < 20; i++) {
                        // console.log(newArr2[0][i])
                        // console.log(newArr2,'newase')
                        // console.log(newArr[0][i],'i')
                        if (newArr2[0][i]) {
                            this.categoryTotal.push({
                                itemName: newArr2[0][i][1][0],
                                itemCount: newArr2[0][i][1][1],
                            });
                            // console.log(newArr2[0][i][1][0],'newArr2[0][i][1][0]')
                            this.options1.xaxis.categories.push(newArr2[0][i][1][0])
                            this.series1[0].data.push(newArr2[0][i][1][1]);
                        } 
                    }
                    this.$refs.chart1.updateSeries(this.series1,true);
                    this.$refs.chart1.updateOptions(this.options1,true);
                })
            },
            
            getChartPlanner(){
                this.series2[0].data=[]
                this.options2.xaxis.categories = []
                axios({
                    method : "post",
                    url : 'api/getChart',
                    data : {
                        dateFrom : this.dateFrom,
                        dateTo : moment(this.dateTo).format('YYYY-MM-DD 23:59:59'),
                    }
                }).then(res => {
                    this.pcgMonitoring_data = res.data;
              
                    // console.log(res.data,'data');
                    const countByPlanner = this.pcgMonitoring_data.reduce((count, obj) => {
                        const pcgplanner = obj.planner_roma;
                        count[pcgplanner] = (count[pcgplanner] || 0) + 1;
                        return count;
                    }, []);
                    // console.log(countByPlanner,'countByPlanner')
                    let newArr2 = [];
                    let newArr = Object.entries(countByPlanner);
                    let test = newArr.sort((a, b) => b[1] - a[1]);
                    let obj = Object.entries(test);
                    newArr2 = [obj];
                    for (let i = 0; i < 20; i++) {
                        // console.log(newArr2[0][i])
                        if (newArr2[0][i] && newArr2[0][i][1][0] != 'null') {
                            this.categoryTotal.push({
                                itemName: newArr2[0][i][1][0],
                                itemCount: newArr2[0][i][1][1],
                            });
                            this.options2.xaxis.categories.push(newArr2[0][i][1][0])
                            this.series2[0].data.push(newArr2[0][i][1][1]);
                        } 
                    }
                    // this.$refs.chart2.updateSeries(this.series2,true);
                    // this.$refs.chart2.updateOptions(this.options2,true);
                })
            },

            getChartSalesman(){
                this.series3[0].data=[]
                this.options3.xaxis.categories = []
                axios({
                    method : "post",
                    url : 'api/getChart',
                    data : {
                        dateFrom : this.dateFrom,
                        dateTo : moment(this.dateTo).format('YYYY-MM-DD 23:59:59'),
                    }
                }).then(res => {
                    this.pcgMonitoring_data = res.data;
                    this.onLoad = false
                    // console.log(res.data,'data');
                    const countBySalesman = this.pcgMonitoring_data.reduce((count, obj) => {
                        const pcgsalesman = obj.salesman_roma;
                        count[pcgsalesman] = (count[pcgsalesman] || 0) + 1;
                        return count;
                        
                    }, []);
                    // console.log(countBySalesman,'countBySalesman')
                    let newArr2 = [];
                    let newArr = Object.entries(countBySalesman);
                    let test = newArr.sort((a, b) => b[1] - a[1]);
                    let obj = Object.entries(test);
                    newArr2 = [obj];
                    for (let i = 0; i < 20; i++) {
                        // console.log(newArr2[0][0][1][0],'salesman')
                        if (newArr2[0][i] && newArr2[0][i][1][0] != 'null') {
                            this.categoryTotal.push({
                                itemName: newArr2[0][i][1][0],
                                itemCount: newArr2[0][i][1][1],
                            });
                            this.options3.xaxis.categories.push(newArr2[0][i][1][0])
                            this.series3[0].data.push(newArr2[0][i][1][1]);
                        } 
                    }
                    // this.$refs.chart3.updateSeries(this.series3,true);
                    // this.$refs.chart3.updateOptions(this.options3,true);
                })
            },
        },

        mounted() {
            this.getCharttype();
            this.getChartPlanner();
            this.getChartSalesman();
        },
    
    }
</script>

<style >

.container {
    text-align: center;
    align-items: center;
}

.titles {
    font-size: 20px;
}

</style>