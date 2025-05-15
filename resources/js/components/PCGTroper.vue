<template>
    <div >
        <v-card outlined elevation="3" style="margin:6px;">
            <v-card-title style="margin:2px; padding:0px;">
                <v-text-field
                    style="margin:5px; width:55px"
                    v-model="control_number"
                    hide-details=""
                    dense
                    outlined
                    label="Control Number:"
                    placeholder="ex.(1234568-2017)"
                    maxlength="12"
                    clearable
                ></v-text-field>
                <v-text-field
                    style="margin:5px; width:10px"
                    v-model="plan_no"
                    hide-details=""
                    dense
                    outlined
                    label="Plan No:"
                    placeholder="ex.(0101)"
                    maxlength="6"
                    clearable
                ></v-text-field>
                <v-autocomplete
                    hide-details=""
                    style="margin:5px; width:50px"
                    dense
                    label="PCG Type:"
                    outlined
                    :items="filtered_pcgType"
                    item-text="pcg_type"
                    item-value="pcg_type"
                    v-model="search4"
                    clearable
                ></v-autocomplete>
                <v-autocomplete
                    hide-details=""
                    v-model="search5"
                    :items="filtered_category"
                    item-text="category"
                    item-value="category"
                    style="margin:5px; width:50px"
                    dense
                    label="Category:"
                    outlined
                    clearable
                ></v-autocomplete>
                <v-spacer/>
                <v-btn 
                    class="mx-1"
                    title="export excel"
                    dark
                    small
                    @click="exportExcel()" 
                    color="green"  
                    >
                    <v-icon>mdi-file-excel</v-icon>
                </v-btn>
                <v-btn 
                    class="mx-1"
                    title="print"
                    dark
                    small
                    @click="printPDF()" 
                    color="#0f8bc4" 
                    v-if="control_number.length > 0 && plan_no.length > 0 && searchRecords.length > 0"
                    >
                    <v-icon>mdi-printer</v-icon>
                </v-btn>
                <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn 
                            class="mx-1"
                            dark
                            small
                            color="#1565C0" 
                            @click="refresh()" 
                            v-bind="attrs"
                            v-on="on" 
                            >
                            <v-icon color="white">mdi-refresh</v-icon>
                        </v-btn> 
                    </template>
                    <span>Refresh Data</span>
                </v-tooltip>
            </v-card-title>
            <v-row no-gutters style="margin:2px; padding:5px;">
                <v-col cols="2">
                    <v-menu
                        v-model="menu1"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        ref="menu1"
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            hide-details=""
                            style="margin:2px;"
                            dense
                            outlined
                            v-model="dateFrom"
                            label="Date From:"
                            prepend-inner-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                        </template>
                        <v-date-picker
                            color="rgb(84,110,122)"
                            v-model="dateFrom"
                            @input="menu1 = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
                <v-col cols="2">
                    <v-menu
                        v-model="menu2"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            hide-details=""
                            style="margin:2px;"
                            dense
                            outlined
                            v-model="dateTo"
                            label="Date To:"
                            prepend-inner-icon="mdi-calendar"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                        </template>
                        <v-date-picker
                            color="rgb(84,110,122)"
                            v-model="dateTo"
                            @input="menu2 = false"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
                <v-col cols="2">
                    <v-btn class="mt-2" small dark @click="getReport">Search</v-btn>
                </v-col>
            </v-row>
        </v-card>
        <v-card outlined elevation="3" style="margin:6px;">
            <v-simple-table id="report_table" ref="pcgTable" height="620px" fixed-header>
                <thead class="monitoring-header">
                    <tr>
                        <th  class="text-center " >No.</th>
                        <th  class="text-center " >Category</th>
                        <th  class="text-center " >PCG Type</th>
                        <th  class="text-center " >English / Japanese</th>
                        <th  class="text-center " >Remarks</th>
                        <th  class="text-center " >Judgement</th>
                        <th  class="text-center " >Actions</th>
                        <!-- <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, rgb(42 97 145) 97.1%);font-size:14px; width:100px; color:white;">NG Image</th> -->
                        <!-- <th  class="text-center " style="background:linear-gradient(#ffffff,#dddddd); border-top:1px #ddd; font-size:14px;width:110px; color:#555;">Remarks</th> -->
                    </tr>
                </thead>
                <tbody style="text-align:center;">
                    <tr 
                    v-for="(item, index) in nextPage" :key="index"
                    >   
                        <td>
                            {{item.id}}
                        </td>
                        <td style="text-align:left; width:100px;">
                            <span style="font-size:12px; ">PCG Code: <b style="font-size:13px; text-decoration:underline; display:inline-block;">{{item.code}}</b></span><br>
                            <span style="font-size:12px; ">Control Number: <br><b style="font-size:13px; text-decoration:underline;">{{item.control_number}}-{{item.revision}}</b></span><br>
                            <span style="font-size:12px; ">Date/Time: <br><b style="font-size:13px; text-decoration:underline;">{{item.date}}</b></span><br>
                        </td>
                        <td style="text-align:center; width:50px;">
                            <span ><b style="text-decoration:underline;">{{item.pcg_type}}</b></span>
                            <!-- <span >Floor: <b style="text-decoration:underline;">{{item.storey}}</b></span><br>
                            <span >Location: <b style="text-decoration:underline;">{{item.location}}</b></span> -->
                        </td>
                        <td style="vertical-align:middle;">
                            <span >{{item.english}}</span><br>
                            <span >{{item.japanese}}</span>
                        </td>
                        <td>
                            <span >{{item.action_english}}</span><br>
                            <span >{{item.action_japanese}}</span>
                        </td>
                        <td>
                            <span >{{item.judgement}}</span>
                        </td>
                        <td>
                            <v-icon 
                                @click="deleteReportPcg(item)" 
                                v-if="userInfo.EmployeeCode == item.Employee_No" 
                                color="red">mdi-delete
                            </v-icon>
                        </td>
                        <!-- <td align="center">
                            <v-img
                                src="./images/stacked-steps-haikei.png"
                                max-height="170"
                                max-width="170"
                                alt="Logo"
                                aspect-ratio="1"
                            ></v-img>
                        </td> -->
                        <!-- <td>
                            <span >{{item.action_eng}}</span><br>
                            <span >{{item.action_jap}}</span>
                        </td> -->
                        <!-- <td>
                            <span>{{item.remarks}}</span>
                        </td> -->
                        
                    </tr>
                </tbody>
            </v-simple-table>
            <div class="d-flex justify-between align-center">
                <div class="d-flex align-center"> <!-- Container for "Plans per page" and select -->
                    <div class="mr-2 ml-2" style="font-size: 14px; color: #424242; margin-top: 8px;">
                        Plans per page:
                    </div>
                    <div>
                        <v-select
                            class="test"
                            v-model="selected_no_of_page"
                            style="width: 65px; border: 1px solid #2196f3; border-radius: 4px;"
                            hide-details
                            dense
                            :items="no_of_pages"
                        ></v-select>
                    </div>
                </div>
                <div class="text-center flex-grow-1"> <!-- Container for pagination -->
                    <v-pagination
                        @input="handlePageChange"
                        v-model="page"
                        color="blue-grey darken-1"
                        :length="pages"
                        :total-visible="6">
                    </v-pagination>
                </div>
                <div class="ml-2 mr-2" style="font-size: 14px; color: #424242; margin-top: 8px;">
                    Total of <b>{{ Number(searchRecords.length).toLocaleString() }}</b> record(s)
                </div>
            </div>
        </v-card>
        <v-dialog 
            v-model="radio_dialog"
            max-width="240"
            >
            <v-card outlined>
                <v-container fluid>
                    <v-radio-group v-model="radios" >
                        <template v-slot:label>
                            <div><strong>Judgement:</strong></div>
                        </template>
                        <v-radio @click="printPDF()" value="NG" >
                            <template v-slot:label >
                            <div><strong class="red--text">NG</strong></div>
                            </template>
                        </v-radio>
                        <v-radio @click="printPDF()" value="非推奨">
                            <template v-slot:label>
                            <div><strong class="success--text">非推奨</strong></div>
                            </template>
                        </v-radio>
                    </v-radio-group>
                </v-container>
            </v-card>
            
        </v-dialog>

        <v-overlay :value="gen_overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
        
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import print1 from './print1';
import { PDFDocument, PDFPage } from 'pdf-lib';
import { mapState, mapActions } from "vuex";

    export default {
        data() {
            const now = new Date();
            const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDayOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            return {
                gen_overlay : false,
                radio_dialog : false,
                radios : null,
                filter_obj : {},
                control_number : '',
                plan_no : '',
                search3 : '',
                search4 : '',
                search5 : '',
                page : 1,
                items : ['All','Wakugumi','Jikugumi'],
                // date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                date : null,
                menu: false,
                modal: false,
                menu2: false,
                pcgReport_data : [],
                filteredObjects : [],
                currentDate : new Date(),
                no_of_pages: [50, 100, 150, 200],
                selected_no_of_page : 50,
                // dateFrom : this.formatDate(startOfMonth),
                // dateTo : this.formatDate(lastDayOfMonth),
                dateFrom : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                dateTo : (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                menu1: false,
                menu2: false,

                
            }
        },

        methods: {
            ...mapActions([
                // "getPcgtypes"
                "resetTableScroll"
            ]),

            async handlePageChange(currPage) {
                console.log(currPage,'current page')                
                // Call the Vuex action
                await this.resetTableScroll(this.$refs.pcgTable);
                
                // Any other logic after scroll reset
            },

            deleteReportPcg(item){
                let text = "Delete this item?";
                if (confirm(text) == true) {
                    console.log(item,'item test')
                    axios.post('api/delete_report_pcg',{
                        id: item.id
                    }).then(res => {
                        console.log(res.data,'deleteReportPcg')
                        this.getReport()
                    }).catch(err => {
                        console.log(err,'error delete_report_pcg')
                        alert('error delete_report_pcg')
                    })
                }
            },

            formatDate(date) {
                const year = date.getFullYear();
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                return `${year}-${month}-${day}`;
            },

            exportExcel(){
                // console.log(this.searchRecords,'searchrecords')
                this.gen_overlay = true;
                // Get the components of the current date
                var year = this.currentDate.getFullYear();
                var month = String(this.currentDate.getMonth() + 1).padStart(2, '0');
                var day = String(this.currentDate.getDate()).padStart(2, '0');

                // Format the date as "yyyy-mm-dd"
                var formattedDate = `${year}-${month}-${day}`;

                let form = new FormData();
                form.append('data', JSON.stringify(this.searchRecords))

                if(this.searchRecords.length > 0){
                    axios({
                        method : "post",
                        url : 'report_excel',
                        // data : this.searchRecords,
                        data : form,
                        responseType: 'blob'
                    }).then(res => {
                        console.log(res.data,'ressesw')
                        const url = window.URL.createObjectURL(new Blob([res.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `PCG_REPORT-${formattedDate}.xls`); 
                        document.body.appendChild(link);
                        link.click();
                        this.gen_overlay = false;
                    }).catch(err => {
                        this.gen_overlay = false;
                        alert('export excel error due to data rows number')
                    })
                }else{
                    alert('no data');
                }
                
            },

            async refresh(){
                this.control_number = ''
                this.plan_no = ''
                this.search3 = ''
                this.search4 = ''
                this.search5 = ''
                // const now = new Date();
                // const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                // const lastDayOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);

                // this.dateFrom = this.formatDate(startOfMonth);
                // this.dateTo = this.formatDate(lastDayOfMonth);
                await this.getReport();
            },
            
            async getReport(){
                this.gen_overlay = true;
                axios.get(`api/getReport`,{
                    params : {
                        dateFrom : this.dateFrom,
                        dateTo : this.dateTo
                    }
                }).then(res => {
                    console.log(res.data,'result of report')
                    this.pcgReport_data = res.data
                    this.gen_overlay = false;
                }).catch(err =>{
                    console.log(err,'error')
                    this.gen_overlay = false
                    alert('error fetching report data')
                })
            },

            openDialog(){
                this.radio_dialog = true;
                this.radios = null;
            },

            async printPDF(){
                // if(this.radios != null){
                    // if(!this.control_number.length && !this.plan_no && !this.search3 && !this.search4 && !this.search5){
                    //     return alert('NG')
                    // }
                    
                    if(this.control_number.length > 0 && this.plan_no.length > 0 && this.searchRecords.length > 0){
                        this.gen_overlay = true;
                        // console.log(typeof(this.control_number.length),'control_number')
                        console.log(this.searchRecords,'psrint');
                        const keysToCheck = [
                            "reference", "edit_ref1", "edit_ref2", "edit_ref3", "edit_ref4",
                            "edit_memo1", "edit_memo2", "edit_shio", "edit_memo3", "edit_memo4"
                        ];
                        // const nonNullValues = this.searchRecords.flatMap(item =>
                        //     keysToCheck
                        //         .filter(key => item[key] !== null)
                        //         .map(key => ({ [key]: item[key] }))
                        // );
                        // console.log(nonNullValues,'nonNullValues')

                        const nonNullValues = this.searchRecords.flatMap(item =>
                            keysToCheck
                                .filter(key => item[key] !== null)
                                .map(key => ({ 
                                    [key]: item[key],
                                    [`${key}_pages`]: item[`${key}_pages`]
                                }))
                        );
                        console.log(nonNullValues,'nonNullValues')
                        
                        this.radio_dialog = false;
                        let FirstPage = new print1()
                        let newFirstPage = await FirstPage.print(this.searchRecords,'wew',this.radios)

                        // console.log(newFirstPage,'testttto')

                        // return this.generate1(this.searchRecords,'wew',this.radios);
                        // return

                        // const groupedData = {};
                        // nonNullValues.forEach(item => {
                        //     for (const key in item) {
                        //         if (item.hasOwnProperty(key)) {
                        //             if (!groupedData[key]) {
                        //                 groupedData[key] = [];
                        //             }
                        //             const values = item[key].split(" / ");
                        //             groupedData[key].push(...values);
                        //         }
                        //     }
                        // });

                        // const newData = [];
                        // for (const key in groupedData) {
                        //     if (groupedData.hasOwnProperty(key)) {
                        //         const values = groupedData[key];
                        //         processValues(key, values);
                        //     }
                        // }
                        // console.log(newData,'test2');
                        
                        // Rest of your code...
                        // async function processValues(key, values) {
                        //     if (values.length > 1) {
                        //         const firstValue = values[0];
                        //         const allValuesSame = values.every(value => value === firstValue);

                        //         if (!allValuesSame) {
                        //             values.forEach(value => {
                        //                 const newItem = {};
                        //                 newItem[key] = value;
                        //                 newData.push(newItem);
                        //             });
                        //         } else {
                        //             const newItem = {};
                        //             newItem[key] = values[0];
                        //             newData.push(newItem);
                        //         }
                        //     } else {
                        //         const newItem = {};
                        //         newItem[key] = values[0];
                        //         newData.push(newItem);
                        //     }
                        // }
                        axios({
                            method : "post",
                            url : 'api/getFilename2',
                            data : nonNullValues,
                            // data : newData,
                            // responseType : 'arraybuffer'
                        }).then(({data}) => {
                            console.log(data.fileBuffer,'data.fileBuffer')
                            data.fileBuffer.unshift(newFirstPage);
                            data.fileBufferPages.unshift(null);
                            console.log(data,'newFirstPage data')
                            let downloadUrl = null;
                            return new Promise(async(resolve)=>{
                                // create a new PDF document
                                const mergedPdf = await PDFDocument.create();
                                // Set document properties
                                mergedPdf.setTitle(`${this.control_number}-${this.plan_no}`);
                                mergedPdf.setAuthor('remil');
                                mergedPdf.setSubject('pdf');
                                mergedPdf.setKeywords(['carte pcg system', 'extraction']);

                                // loop through the PDFs and add each one to the merged document
                                // for (const pdf of data.fileBuffer) {
                                    
                                // // decode the base64-encoded PDF
                                //     if(pdf){
                                //     const pdfBytes = Buffer.from(pdf, 'base64');
                                //     // create a PDFDocument object from the bytes
                                //     const pdfDoc = await PDFDocument.load(pdfBytes);
                                //     // loop through each page in the PDF and add it to the merged document
                                //     const pages = await mergedPdf.copyPages(pdfDoc, pdfDoc.getPageIndices());
                                //         pages.forEach((page) => {
                                //             mergedPdf.addPage(page);
                                //             // this.countPageUpdate += 1;
                                //         });
                                //     }
                                
                                //     // save the merged PDF to a file
                                //     const mergedPdfBytes = await mergedPdf.save();
                                //     // Create a Blob object with the merged PDF data
                                //     const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
                                        
                                //     // Create a download URL for the Blob
                                //     downloadUrl = URL.createObjectURL(blob);
                                //     resolve(mergedPdfBytes)
                                // }


                                // loop through the PDFs and add each one to the merged document
                                for (let index = 0; index <= data.fileBuffer.length-1; index++) {
                                    const pdf = data.fileBuffer[index];

                                    // decode the base64-encoded PDF
                                    if (pdf) {
                                        const pdfBytes = Buffer.from(pdf, 'base64');
                                        // create a PDFDocument object from the bytes
                                        const pdfDoc = await PDFDocument.load(pdfBytes);
                                        console.log(`Loaded PDF with ${pdfDoc.getPageCount()} pages.`);
                                        // Assume `input` is the dynamic input string (e.g., "1", "5", "1-5", "2, 4, 6", "2, 4, 6-8")
                                        // const input = index === 1 ? "" : ""; // Replace this with your dynamic input for index 1
                                        console.log( data.fileBufferPages[index],'testing of index')
                                        const input = index >= 1 ? ( data.fileBufferPages[index] || "") : ""; // Replace this with your dynamic input for index 1
                                        console.log(input, 'input val')
                                        const totalPages = pdfDoc.getPageCount();
                                        const pagesToCopy = index >= 1 ? this.parsePageInput(input, totalPages) : Array.from({ length: totalPages }, (_, i) => i); // Copy all pages for other PDFs
                                        console.log(`Valid pages to copy for index ${index}: ${pagesToCopy}`);
                                        
                                        // loop through the specified pages and add them to the merged document
                                        const pages = await mergedPdf.copyPages(pdfDoc, pagesToCopy);
                                        pages.forEach((page) => {
                                            mergedPdf.addPage(page);
                                        });
                                    }
                                }


                                // save the merged PDF to a file
                                const mergedPdfBytes = await mergedPdf.save();
                                // Create a Blob object with the merged PDF data
                                const blob = new Blob([mergedPdfBytes], { type: 'application/pdf' });
                                    
                                // Create a download URL for the Blob
                                downloadUrl = URL.createObjectURL(blob);
                                resolve(mergedPdfBytes)

                                // window.open(downloadUrl, '_blank');
                                const downloadLink = document.createElement('a');
                                downloadLink.href = downloadUrl;
                                downloadLink.target = '_blank'; // Open in new tab if needed
                                downloadLink.download = `${this.control_number}-${this.plan_no}-info.pdf`; // Set the filename for download

                                // Simulate a click on the link to trigger the download
                                downloadLink.click();

                                // Clean up: remove the temporary link
                                URL.revokeObjectURL(downloadUrl);
                                this.gen_overlay = false
                            })
                        }).catch(err => {
                            alert('contact ihs ')
                            console.log(err,'error in exporting pdf file')
                        })

                        
                        
                        
                    }
                    // console.log(this.search5.length ,'lenerheu')
                    // else if((this.searchRecords.length > 0) && (this.search3 || this.search4 || this.search5) ){
                    //     console.log(this.search3)
                    //     // console.log(this.searchRecords,'psrint');
                    //     // console.log(this.radios,'radios')
                    //     this.radio_dialog = false;
                    //     return this.generate1(this.searchRecords,'without_cc_header',this.radios);
                        
                    // }
                // }
                
                // if(!this.search3 && !this.search4 && !this.search5){
                //     console.log(this.searchRecords,'data');
                //     this.generate1(this.searchRecords,'without_cc_header',this.radios);
                // }else if(this.control_number.length == 12 && this.plan_no.length == 4){
                //     console.log(this.searchRecords,'data');
                //     this.generate1(this.searchRecords,'wew');
                // }
            },

            parsePageInput(input, totalPages) { //for printing of pdf with specific page of a pdf
                const pages = new Set(); // Use a Set to avoid duplicates

                // If input is null or blank, return all pages
                if (input === null || input === "") {
                    return Array.from({ length: totalPages }, (_, i) => i); // Return all page indices
                }

                // Split the input by commas to handle multiple inputs
                const inputs = input.split(',').map(item => item.trim());

                inputs.forEach(item => {
                    if (item.includes('-')) {
                        // Handle range (e.g., "1-5")
                        const [start, end] = item.split('-').map(Number);
                        for (let i = start; i <= end; i++) {
                            if (i > 0 && i <= totalPages) {
                                pages.add(i - 1); // Convert to zero-based index
                            }
                        }
                    } else {
                        // Handle single page (e.g., "1" or "5")
                        const page = Number(item);
                        if (page > 0 && page <= totalPages) {
                            pages.add(page - 1); // Convert to zero-based index
                        }
                    }
                });

                return Array.from(pages); // Convert Set back to Array
            },

            generate1(arr_data,value,judgement){
                var testPrintOutput = new print1();
                testPrintOutput.print(arr_data,value,judgement);
            },
        },

        mounted() {
            this.getReport();
        },

        watch: {
            radios(val){
                console.log(val,'radio_value')
            }
        },

        computed: {
            ...mapState([
                "carte_categories",
                "pcgType_items",
                "userInfo"
            ]),

            filtered_pcgType(){
                let data = this.pcgType_items.filter(r => {
                    return r.pcg_type != "All";
                })
                return data;
            },

            filtered_category(){
                let data = this.carte_categories.filter(r => {
                    return r.category != "All";
                })
                return data;
            },

            pages(){
                let d = this.searchRecords.length,
                p = this.selected_no_of_page;
                return Math.ceil(d / p);
            },
            nextPage(){
                const start = (this.page - 1) * this.selected_no_of_page,
                end = start + this.selected_no_of_page;
                let a = this.searchRecords.slice(start,end);
                return a;
            },

            searchRecords(){
                // console.log(this.search5,'category')
                // if(this.control_number.length == 12 && this.plan_no.length == 4 ){
                //     return data.control_number === this.control_number && data.revision === this.plan_no;
                // }else{
                //     return this.pcgReport_data;
                // }
                return this.pcgReport_data.filter(data => {
                    if(!this.control_number){
                        return data;
                    }else{
                        if(data.control_number === this.control_number){
                            // console.log(data,'data')
                            return data;
                        }  
                    }
                }).filter(data=>{
                    if(!this.plan_no){
                        return data;
                    }else{
                        if(data.revision === this.plan_no){                          
                            return data;
                        }  
                       
                    }
                }).filter(data=>{
                    if(!this.search3){
                        return data;
                    }else{
                        if( data.code !== null &&
                            data.code.toUpperCase().includes(this.search3.toUpperCase()) ){                          
                            return data;
                        } 
                        if(data.house_type.toUpperCase().includes(this.search3.toUpperCase()) ){                          
                            return data;
                        } 
                        if(data.pcg_type.toUpperCase().includes(this.search3.toUpperCase()) ){                          
                            return data;
                        } 
                        if(data.date.toUpperCase().includes(this.search3.toUpperCase()) ){                          
                            return data;
                        } 
                    }
                }).filter(data=>{
                    if(!this.search4){
                        return data;
                    }else{
                        if(data.pcg_type.toUpperCase().includes(this.search4.toUpperCase()) ){                          
                            return data;
                        } 
                    }
                }).filter(data=>{
                    if(!this.search5){
                        return data;
                    }else{
                        if(data.pcg_category == this.search5){                          
                            return data;
                        } 
                    }
                })
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

    #report_table tr:nth-child(even){
        background-color: #f2f2f2;
    }

    #report_table tr:hover {
        background-color: #ddd;
    }

    #report_table th {
        padding-top: 12px;
        padding-bottom: 12px;
        color: white;
    }
    
</style>