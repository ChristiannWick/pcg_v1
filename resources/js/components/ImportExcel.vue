<template>
    <div id="app">
        <h1>Excel File Reader</h1>
        <div>
            <input type="file" ref="fileInput" @change="handleFileUpload" />
            <table v-if="excelData.length > 0">
            <thead>
                <tr>
                <th v-for="(header, index) in excelData[0]" :key="index">{{ header }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, rowIndex) in excelData" :key="rowIndex">
                <td v-for="(cell, cellIndex) in row" :key="cellIndex">{{ cell }}</td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>
</template>

<script>

import readExcelFile from 'read-excel-file';

export default {
  
    data() {
        return {
            excelData: [],
            result : [],
            result2 : []
        }
    },

    methods: {
        async handleFileUpload(event) {
            this.result = []
            const file = event.target.files[0];
            if (file) {
                try {
                this.excelData = await readExcelFile(file);
                console.log(this.excelData,'exceldata');
                const keys = this.excelData[0];

                for (let i = 1; i < this.excelData.length; i++) {
                    const values = this.excelData[i];
                    const obj = {};
                    for (let j = 0; j < keys.length; j++) {
                        obj[keys[j]] = values[j];
                    }
                    this.result.push(obj);
                }
                 this.$refs.fileInput.value = '';
                console.log(this.result,'resulta')

                // let with_data = [];
                // let without_data = [];
                // for (let i = 0; i < this.result.length; i++) {
                //     const e = this.result[i];
                //     const data = await this.getData(e.management_code,e.revision_number )
                //     if(data.WithData === 1){
                //         // console.log(data,'if')
                //         data.plan_code = e.plan_code
                //         data.app_type = e.app_type
                //         // with_data.push(data)
                //         await axios({
                //             method : 'post',
                //             url : 'api/insert_test_carte',
                //             data : {
                //                 with_data : data,
                //             }
                //         }).then(res => {
                //             console.log(res.data,'witdata')
                //         })
                
                //     }else{
                //         // console.log(data,'else')
                //         data.plan_code = e.plan_code
                //         data.app_type = e.app_type
                //         // without_data.push(data)
                //         await axios({
                //             method : 'post',
                //             url : 'api/insert_test_carte',
                //             data : {
                //                 without_data : data,
                //             }
                //         }).then(res => {
                //             console.log(res.data,'withoutdata')

                //         })
                
                //     }
                // }
                
                // console.log(with_data,'with_data')
                // console.log(without_data,'without_data')
                
                // this.result.forEach(element => {
                //     element.pcg_japanese = element.pcg_japanese != null ?element.pcg_japanese.replace(/\n/g, ' '):element.pcg_japanese;
                //     element.pcg_english = element.pcg_english != null ?element.pcg_english.replace(/\n/g, ' '):element.pcg_english;
                //     element.stop_checkpoints = element.stop_checkpoints != null ?element.stop_checkpoints.replace(/\n/g, '. '):null;
                //     element.approve_staus = 1
                //     element.pcg_incharge = "Ingrid S. Fabro"
                //     element.pcg_translated_by = "Ingrid Fabro"
                //     if(element.remarks_status != 'NG' && element.remarks_status != 'NR'){
                //         element.remarks_status = '-'
                //     }
                //     if(element.pcg_method == "JIKU/WAKU"){
                //         element.pcg_method = "Both"
                //     }else if(element.pcg_method == "JIKU"){
                //         element.pcg_method = "Jikugumi"
                //     }else{
                //         element.pcg_method = "Wakugumi"
                //     }

                //     if(element.pcg_reference_2 == null){
                //         element.remarks = "Without Reference"
                //     }else{
                //         element.remarks = "With Reference"
                //     }
                // });
                // console.log(this.result,'result');

                // await axios({
                //     method : 'post',
                //     url : 'api/insert_test_carte',
                //     data : {
                //         // datas : this.result,
                //         with_data : with_data,
                //         without_data : without_data,
                //     }
                // }).then(res => {
                //     // this.combineData(this.result,res.data);
                //     // console.log(this.result,'new result');
                //     // const caseWhenParts = this.result.map(item => {
                //     //     return `\nWHEN ${item.id} THEN '${item.customer_name}'`;
                //     // }).join(' ');
                //     // const idList = this.result.map(item => item.id).join(', ');
                //     // const sql = `UPDATE cad_request_basic_information\n SET customer_name = CASE id ${caseWhenParts} \nELSE customer_name \nEND WHERE id IN (${idList});`;
                //     // console.log(sql,'sql test')

                //     console.log(res.data)

                // })
                


                } catch (error) {
                    console.error('Error reading Excel file:', error);
                }
            }
        },

        async getData(cc,plan_number) {
            const url = `http://10.169.130.113:1432/api/planSearch/${cc}/${plan_number}`
            console.log(url,'url test')
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    // throw new Error(`Response status: ${response.status} ${cc}`);
                }

                const json = await response.json();
                // console.log(json);
                return json;
            } catch (error) {
                console.error(error.message);
            }
        },
        

        combineData(data1, data2) {
            data1.forEach(item1 => {
                const item2 = data2.find(item2 => item2.ConstructionCode === item1.construction_code);
                const parts = item1.customer_name.split(' ');
                if(item2){
                    const result = item2.ConstructionCode.startsWith("P");
                    console.log(result,'result');
                    item1.customer_name = (result ? item2.ConstructionName.split(' ')[0] : item2.ConstructionName.split('　')[0]) + ' ' + parts.slice(1).join(' ');
                }
            });
        }

    },
}
</script>

<style>
    /* table {
    border-collapse: collapse;
    width: 100%;
    }

    th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    }

    th {
    background-color: #f2f2f2;
    } */
</style>