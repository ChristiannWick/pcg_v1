<template>
    <div >
        <!-- <v-card elevation="15" outlined style="border-radius:10px; background-color:lavender; margin:10px;">
            <v-card-title style="font-size:30px; font-family:monospace;">
                PCG Report
            </v-card-title>
            <v-row style="margin:2px; height:60px;">
                <v-col cols="2">
                    <v-text-field
                        dense
                        outlined
                        label="Control Number :"
                    ></v-text-field>
                </v-col>
                <v-col cols="2">
                    <v-text-field
                        dense
                        outlined
                        label="Plan No :"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-card> -->
        <v-card outlined elevation="15" style="border-radius:10px; background-color:lavender; border:1px solid gray; margin:10px;">
            <v-card-title>
                <v-row>
                    <v-col
                    cols="12"
                    sm="6"
                    md="3"
                    >
                    <v-text-field
                        hide-details=""
                        dense
                        outlined
                        label="Control Number :"
                    ></v-text-field>
                    </v-col>

                    <v-col
                    cols="12"
                    sm="6"
                    md="3"
                    >
                    <v-text-field
                        style="width:150px;"
                        hide-details=""
                        dense
                        outlined
                        label="Plan No :"
                    ></v-text-field>
                    </v-col>
                    <v-toolbar flat style="background-color:lavender;">
                    <v-spacer/>
                    <v-col
                    cols="12"
                    md="7"
                    >
                        <v-btn color="blue" dense outlined>Add</v-btn>
                        <v-btn color="blue" dense outlined>Print</v-btn>
                        <v-btn color="green" dense outlined>Excel</v-btn>
                        <v-btn color="green" dense><v-icon color="white">mdi-refresh</v-icon></v-btn> 
                    </v-col>
                    </v-toolbar>
                </v-row>
            </v-card-title>
            <v-simple-table id="report_table" fixed-header height="700px" class="mt-1">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, rgb(42 97 145) 97.1%);font-size:14px;width:100px; color:white;">Category</th>
                        <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, rgb(42 97 145) 97.1%);font-size:14px;width:50px; color:white;">Details</th>
                        <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, rgb(42 97 145) 97.1%);font-size:14px;width:200px; color:white;">English</th>
                        <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, rgb(42 97 145) 97.1%);font-size:14px;width:100px; color:white;">NG Image</th>
                        <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, rgb(42 97 145) 97.1%);font-size:14px;width:135px; color:white;">Action</th>
                        <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, rgb(42 97 145) 97.1%);font-size:14px;width:10px; color:white;">Remarks</th>
                    </tr>
                </thead>
                <tbody style="text-align:center;">
                    <tr 
                    v-for="(item, index) in nextPage" :key="index"
                    >
                        <td style="text-align:left; width:100px;">
                            <span >PCG Code: <b style="text-decoration:underline; display:inline-block;">{{item.code}}</b></span><br>
                            <span >Control Number: <br><b style="text-decoration:underline;">{{item.control_number}}-{{item.revision}}</b></span><br>
                            <span >Date/Time: <br><b style="text-decoration:underline;">{{item.date}}</b></span><br>
                        </td>
                        <td style="text-align:left; width:50px;">
                            <span >Floor: <b style="text-decoration:underline;">{{item.storey}}</b></span><br>
                            <span >Location: <b style="text-decoration:underline;">{{item.location}}</b></span>
                        </td>
                        <td style="vertical-align:middle;">
                            <span >{{item.english}}</span><br>
                            <span >{{item.japanese}}</span>
                        </td>
                        <td align="center">
                            <v-img
                                src="./images/stacked-steps-haikei.png"
                                max-height="170"
                                max-width="170"
                                alt="Logo"
                                aspect-ratio="1"
                            ></v-img>
                        </td>
                        <td>
                            <span >{{item.action_eng}}</span><br>
                            <span >{{item.action_jap}}</span>
                        </td>
                        <td>
                            <span>{{item.remarks}}</span>
                        </td>
                        
                    </tr>
                </tbody>
            </v-simple-table>
            <p style="font-size:14px; color:#424242;" class="ml-2">Plans per page: <b>10</b>
                <b>&nbsp;|</b><span style="font-size:14px;color:#424242;" class="ml-2">Total of <b>{{Number(pcgReport_data.length).toLocaleString()}}</b> record(s)</span>
            </p>
            <div class="text-center" style="margin-top:-35px;">
                <v-pagination
                    v-model="page"
                    color="#5C5CFF"
                    :length="pages"
                    :total-visible="6">
                </v-pagination>
            </div>
        </v-card>
        
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
    export default {
        data() {
            return {
                page : 1,
                search : '',
                items : ['All','Wakugumi','Jikugumi'],
                // date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                date : null,
                menu: false,
                modal: false,
                menu2: false,
                pcgReport_data : []
            }
        },
        methods: {
            getReport(){
                // let date = "";
                // if(this.date == null){
                //     date = this.date
                // }else{
                //     date = moment(this.date).format('YYYY-MM-DD')
                // }
                axios({
                    method : "get",
                    url : 'api/indexReport',
                    // data : {
                    //     method : this.selected_method.value,
                    //     status : this.selected_status.value,
                    //     dateFrom : date,
                    // }
                }).then(res => {
                    console.log(res.data);
                    this.pcgReport_data = res.data
                    // this.pcgReport_data.filter(r => {
                    //     if(r.checked_at != null){
                    //         r.checked_at = r.checked_at
                    //     }else{
                    //         r.checked_at = r.pcg_date
                    //     }
                    // })
                })
            },
        },

        mounted() {
            this.getReport();
        },

        computed: {
            pages(){
                let d = this.pcgReport_data.length,
                    p = 10;
                    return Math.ceil(d / p);
            },
            nextPage(){
                const start = (this.page - 1) * 10,
                end = start + 10;
                let a = this.pcgReport_data.slice(start,end);
                return a;
            },
        },
    }
</script>

<style >
    #report_table {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
    }

    #report_table td, #report_table th {
    border: 1px solid #ddd;
    padding: 5px;
    }

    #report_table tr:nth-child(even){background-color: #f2f2f2;}

    #report_table tr:hover {background-color: #ddd;}

    #report_table th {
    padding-top: 12px;
    padding-bottom: 12px;
    color: white;
    }
</style>