import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import moment from 'moment'


pdfMake.vfs = pdfFonts.pdfMake.vfs;



export default class printData {
	constructor() {
		this.docDefinition = null 
        this.printDate =   moment().format('YYYY-MM-DD')  
          
	}

	createData(arr_data,component,judgement){
        console.log(judgement,'judgement')
        pdfMake.fonts = {
            yourFontName: {
            normal: 'MS-Gothic.tff',
            bold: 'MS-Gothic.tff',
            italics: 'MS-Gothic.tff',
            bolditalics: 'MS-Gothic.tff'
            }
        }

        this.docDefinition = {
            info:{
                title: `${arr_data[0].control_number}-${arr_data[0].revision}-info`,
                author: 'rems',
                subject: 'carte pcg',
                keywords: 'template',
            },
            filename:`${arr_data[0].control_number}-${arr_data[0].revision}-.pdf`,
            pageSize: 'A4',
            pageOrientation: 'landscape',
            content: [],
            pageMargins : [15, 200, 15, 15], //left top right bottom // for header:function()
            
            
            header:function() {
                return [
                    {
                        table:{
                            // layout: 'noBorders',
                            // dontBreakRows: true,
                            // keepWithHeaderRows: 1, 
                            headerRows: 1,
                            widths: ['2%','74%','2%','1%','2%','2%','16%'],
                            // widths: ['2%','75%','2%','1%','2%','2%','16%'],
                            body: [
                                [ //first row of pdf file
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`プランニングカルテ簡易法規検討・壁量検討結果`,style:'headerStyle',bold:true,border: [false, false, false, false],margin: [0, 10, 0, 0] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`${moment().format('LLL')}`,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 10, 0, 0] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`Carte Planning Plan Check Result`,style:'subHeaderStyle',bold:true,border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 10, 0, 10] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    // { text:`${judgement=='OK'?'「検討の結果、問題点が見つかりませんでした。」':'「NG箇所があるため、修正お願い致します。修正後、必要に応じて再度事前検討依頼お願い致します。」'}`,color:'red',style:'subHeaderStyle',bold:false,fontSize:11,border: [false, false, false, false] },
                                    { text:`${judgement.message}`,color:judgement.color,style:'subHeaderStyle',bold:false,fontSize:11,border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false],margin: [0, 0, 0, 20] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`【判定】 NG ：禁止 , NR：推奨しない, ― ：注意喚起`,fontSize:11,bold:false,border: [false, false, false, false],alignment:"left" },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 0, 0, 0] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`【Legend】 NG ：NG , NR: Not recommend,  ― ：Information only`,fontSize:11,bold:false,border: [false, false, false, false],alignment:"left" },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false],margin: [0, 0, 0, 20] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`お客様コード                                 図面番号(Plan No.)`,fontSize:10,bold:false,border: [false, false, false, false],alignment:"left" },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 0, 0, 0] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`Control Number:               ${arr_data[0].control_number}-${arr_data[0].revision}`,style:'subHeaderStyle',bold:true,border: [false, false, false, false],alignment:"left" },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 0, 0, 0] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`下記頂目で不明な点ありましたら、planningsupport@hrd-s.comに間合せお願い致します。`,color:'red',style:'subHeaderStyle',bold:false,fontSize:11,border: [false, false, false, false],margin: [0, 7, 0, 0] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false]},
                                ],
                            ],
                        },
                    },
                ]
            },
            
            styles: {
                headerStyle:{
                    fontSize:20,
                    bold: true,
                    alignment:'left'
                },
                subHeaderStyle:{
                    fontSize:14,
                    bold: true,
                    alignment:'left',

                },
                DateHeader:{
                    fontSize:8,
                    bold: true,

                },
                tableHeader :{
                    fontSize:12,
                    alignment:'center',
                    bold: true,
                    fillColor:'#EEEEEE'
                },
                tableBody :{
                    fontSize:10,
                    alignment:'left',
                    bold: true,
                    margin: [ 0, 5]
                    
                },
                DateStyle:{
                    fontSize:8,
                    bold: true,
                    alignment:'right'
                }
           
            },

            defaultStyle:{
                font:'yourFontName'
            }
        }
        

        let objContent= {}
        let table = {}
        table.widths = ['7%','7%','10%','25%','25%','11%','8%','8%',"6%"],
        table.body = []
            // legend [LEFT, TOP, RIGHT, BOTTOM] 
            table.body.push( 
                [
                    // { text:``,style:'headerStyle',border: [false, false, false, false] },
                    {text: '質問 QUESTION 番号 NUMBER', style: 'tableHeader',border: [true, true, true, true]}, 
                    {text: 'PCG\nCode', style: 'tableHeader',border: [true, true, true, true], margin: [ 0, 6, 0, 0 ]},
                    {text: '検討内容\nSTUDY CONTENT', style: 'tableHeader',border: [true, true, true, true],margin: [ 0, 6, 0, 0 ]}, 
                    {text: '内容\nCONTENTS (ENGLISH)', style: 'tableHeader',border: [true, true, true, true], margin: [ 0, 6, 0, 0 ]}, 
                    {text: '内容\nCONTENTS (JAPANESE)', style: 'tableHeader',border: [true, true, true, true], margin: [ 0, 6, 0, 0 ]}, 
                    {text: "備考\nRemarks", style: 'tableHeader',border: [true, true, true, true],margin: [ 0, 6, 0, 0 ]}, 
                    {text: '各種帳票\nDocument No.', style: 'tableHeader',border: [true, true, true, true],margin: [ 0, 6, 0, 0 ]}, 
                    {text: '判定\nJudgement', style: 'tableHeader',border: [true, true, true, true],margin: [ 0, 6, 0, 0 ]}, 
                    // { text:``,style:'headerStyle',border: [false, false, false, false] }
                ],
                
            )
        objContent.table = table
        this.docDefinition.content.push(objContent)

        console.log(component,'arra')

        let objContent2= {}
        let table2 = {
            layout: 'noBorders',
            dontBreakRows: true,
        }
        table2.widths = ['7%','7%','10%','25%','25%','11%','8%','8%',"6%"],
        table2.body = []
        let prefixPattern = /^.*?-/;
        if(component == 'with_cc_header'){ //for usedialog.vue
            if(!arr_data[0].pcg_english){
                console.log('walang (add to print) data')
            }else{
                console.log('kung UseDialog.vue')
                for (let i = 0; i < arr_data.length; i++) {
                    const reference = arr_data[i].gc_attachment;
                    const cleanedReference = !reference ? reference : reference.replace(prefixPattern, "");
                    
                    const rowData = [
                        { text: `${i + 1}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' },
                        { text: `${arr_data[i].approved_by === null ? '-' : (arr_data[i].pcg_code)}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' }, // approved by = null
                        // { text: `${arr_data[i].pcg_code}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' }, //UseDialog.vue without condition of approved by
                        { text: `${arr_data[i].pcg_type}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' },
                        { text: `${arr_data[i].pcg_english}`, style: 'tableBody', border: [true, false, true, true] },
                        { text: `${arr_data[i].pcg_japanese}`, style: 'tableBody', border: [true, false, true, true] },
                        { text: `${arr_data[i].action_id == 1 ? '' : arr_data[i].action_english + "\n\n" + arr_data[i].action_japanese}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' }
                    ];
    
                    const referencesAndMemos = [
                        !arr_data[i].pcg_reference ? '' : arr_data[i].pcg_reference,
                        !arr_data[i].pcg_reference_2 ? '' : arr_data[i].pcg_reference_2,
                        !arr_data[i].pcg_reference_3 ? '' : arr_data[i].pcg_reference_3,
                        !arr_data[i].pcg_reference_4 ? '' : arr_data[i].pcg_reference_4,
                        !arr_data[i].pcg_hrd_memo ? '' : arr_data[i].pcg_hrd_memo,
                        !arr_data[i].pcg_hrd_memo_2 ? '' : arr_data[i].pcg_hrd_memo_2,
                        !arr_data[i].pcg_shio ? '' : arr_data[i].pcg_shio,
                        !arr_data[i].pcg_hrd_memo_3 ? '' : arr_data[i].pcg_hrd_memo_3,
                        !arr_data[i].pcg_hrd_memo_4 ? '' : arr_data[i].pcg_hrd_memo_4,
                        // !arr_data[i].gc_attachment ? '' : arr_data[i].gc_attachment
                        !arr_data[i].gc_attachment ? '' : cleanedReference

                    ];
    
                    const filteredReferencesAndMemos = referencesAndMemos.filter(item => item !== '');
    
                    // Join references and memos with new lines
                    const referencesAndMemosText = filteredReferencesAndMemos.join('\n');
                    rowData.push({ text: referencesAndMemosText, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
    
                    rowData.push({ text: `${!arr_data[i].judgement ? arr_data[i].remakrs1 : arr_data[i].judgement}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
                    // rowData.push({ text: `${arr_data[i].action_id == 1 ? '' : arr_data[i].action_english + "\n\n" + arr_data[i].action_japanese}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
    
                    // Push the modified row data to the table body
                    table2.body.push(rowData);
                    
                }
                
                objContent2.table = table2
                this.docDefinition.content.push(objContent2)
            }
        }else{ //for troper.vue
            
            console.log('kung PCGTroper.vue',arr_data)
            // if(!arr_data[0].english){
            //     console.log('walang (add to print) data')
            // }else{
                for (let i = 0; i < arr_data.length; i++) {
                    const prefixLength = 13;
                    const reference = arr_data[i].reference;
                    const cleanedReference = !reference ? reference : reference.replace(prefixPattern, "");
                    // const cleanedReference = reference.slice(prefixLength);
                    
                    const rowData = [
                        { text: `${i + 1}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' },
                        { text: `${arr_data[i].approved_by === null ? '-' : (arr_data[i].code)}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' }, // approved by = null
                        // { text: `${arr_data[i].code}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' }, //troper.vue without condition of approved by
                        { text: `${arr_data[i].pcg_type}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' },
                        { text: `${arr_data[i].english }`, style: 'tableBody', border: [true, false, true, true] },
                        { text: `${arr_data[i].japanese }`, style: 'tableBody', border: [true, false, true, true] },
                        { text: `${arr_data[i].action_id  == 1 ? '' : arr_data[i].action_english + "\n\n" + arr_data[i].action_japanese}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' }
                    ];
                
                    // Handling edit references and memos
                    const editReferencesAndMemos = [
                        !arr_data[i].reference ? '' : cleanedReference,
                        // !arr_data[i].reference ? '' : arr_data[i].reference,
                        !arr_data[i].edit_ref1 ? '' : arr_data[i].edit_ref1,
                        !arr_data[i].edit_ref2 ? '' : arr_data[i].edit_ref2,
                        !arr_data[i].edit_ref3 ? '' : arr_data[i].edit_ref3,
                        !arr_data[i].edit_ref4 ? '' : arr_data[i].edit_ref4,
                        !arr_data[i].edit_memo1 ? '' : arr_data[i].edit_memo1,
                        !arr_data[i].edit_memo2 ? '' : arr_data[i].edit_memo2,
                        !arr_data[i].edit_shio ? '' : arr_data[i].edit_shio,
                        !arr_data[i].edit_memo3 ? '' : arr_data[i].edit_memo3,
                        !arr_data[i].edit_memo4 ? '' : arr_data[i].edit_memo4,
                    ];
                
                    // Filter out empty edit references and memos
                    const filteredEditReferencesAndMemos = editReferencesAndMemos.filter(item => item !== '');
                
                    // Join edit references and memos with new lines
                    const editReferencesAndMemosText = filteredEditReferencesAndMemos.join('\n');
                    rowData.push({ text: editReferencesAndMemosText, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
                
                    rowData.push({ text: `${!arr_data[i].judgement ? arr_data[i].remakrs1 : arr_data[i].judgement}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
                    // rowData.push({ text: `${arr_data[i].action_id  == 1? '' : arr_data[i].action_english + "\n\n" + arr_data[i].action_japanese}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
                
                    // Push the modified row data to the table body
                    table2.body.push(rowData);

                }

                objContent2.table = table2
                this.docDefinition.content.push(objContent2)
            // }
        }

    }   

    createData2(arr_data,component,judgement){
        pdfMake.fonts = {
            yourFontName: {
            normal: 'MS-Gothic.tff',
            bold: 'MS-Gothic.tff',
            italics: 'MS-Gothic.tff',
            bolditalics: 'MS-Gothic.tff'
            }
        }

        this.docDefinition = {
            info:{
                title: `${arr_data[0].control_number}-${arr_data[0].revision}-info`,
                author: 'john doe',
                subject: 'subject of document',
                keywords: 'keywords for document',
            },
            filename:`${arr_data[0].control_number}-${arr_data[0].revision}-.pdf`,
            pageSize: 'A4',
            pageOrientation: 'landscape',
            content: [],
            pageMargins : [15, 160, 15, 15],
            
            
            header:function() {
                return [
                    {
                        table:{
                         
                            headerRows: 1,
                            widths: ['2%','74%','2%','1%','2%','2%','16%'],
                            body: [
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`プランニングカルテ簡易法規検討・壁量検討結果`,style:'headerStyle',bold:true,border: [false, false, false, false],margin: [0, 10, 0, 0] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`${moment().format('LLL')}`,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 10, 0, 0] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`Carte Planning PLan Check Result`,style:'subHeaderStyle',bold:true,border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 10, 0, 10] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                    { text:``,fillColor:'#ECEFF1',border: [false, true, false, false]},
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`${judgement=='非推奨'?'「検討の結果、問題点が見つかりませんでした。」':'「NG箇所があるため、修正お願い致します。修正後、必要に応じて再度事前検討依頼お願い致します。」'}`,color:'red',style:'subHeaderStyle',bold:false,fontSize:11,border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false],margin: [0, 0, 0, 30] },
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`【判定】 NG ：禁止 , NR：推奨しない, ― ：注意喚起`,fontSize:11,bold:false,border: [false, false, false, false],alignment:"left" },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false]},
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`【Legend】 NG ：NG , NR: Not recommend,  ― ：Information only`,fontSize:11,bold:false,border: [false, false, false, false],alignment:"left" },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,alignment:'right',fontSize:11,bold:true, border: [false, false, false, false]},
                                ],
                                [
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:`下記頂目で不明な点ありましたら、planningsupport@hrd-s.comに間合せお願い致します。`,color:'red',style:'subHeaderStyle',bold:false,fontSize:11,border: [false, false, false, false],margin: [0, 10, 0, 0] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                    { text:``,style:'headerStyle',border: [false, false, false, false] },
                                ],
                               
                            ],
                        },
                     
                    },
                ]
            },
            
            styles: {
                headerStyle:{
                    fontSize:20,
                    bold: true,
                    alignment:'left'
                },
                subHeaderStyle:{
                    fontSize:14,
                    bold: true,
                    alignment:'left',

                },
                DateHeader:{
                    fontSize:8,
                    bold: true,

                },
                tableHeader :{
                    fontSize:12,
                    alignment:'center',
                    bold: true,
                    fillColor:'#EEEEEE'
                },
                tableBody :{
                    fontSize:10,
                    alignment:'left',
                    bold: true,
                    margin: [ 0, 5]
                    
                },
                DateStyle:{
                    fontSize:8,
                    bold: true,
                    alignment:'right'
                }
           
            },

            defaultStyle:{
                font:'yourFontName'
            }
        }
        

        
        
        let objContent= {}
        let table = {}
        table.widths = ['7%','7%','10%','29%','29%','13%','8%','5%',"5%"],
        table.body = []
            table.body.push( 
                [
                    // { text:``,style:'headerStyle',border: [false, false, false, false] },
                    {text: '質問 QUESTION 番号 NUMBER', style: 'tableHeader',border: [true, true, true, true]}, 
                    {text: 'PCG \nCode', style: 'tableHeader',border: [true, true, true, true], margin: [ 0, 10, 0, 0 ]},
                    {text: '検討内容 STUDY CONTENT', style: 'tableHeader',border: [true, true, true, true],margin: [ 0, 6, 0, 0 ]}, 
                    {text: '内容 \nCONTENTS (ENGLISH)', style: 'tableHeader',border: [true, true, true, true], margin: [ 0, 6, 0, 5 ]}, 
                    {text: '内容 \nCONTENTS (JAPANESE)', style: 'tableHeader',border: [true, true, true, true], margin: [ 0, 6, 0, 5 ]}, 
                    {text: '各種帳票 Document No.', style: 'tableHeader',border: [true, true, true, true],margin: [ 0, 6, 0, 0 ]}, 
                    {text: '判定 Judgement', style: 'tableHeader',border: [true, true, true, true],margin: [ 0, 6, 0, 0 ]}, 
                    // { text:``,style:'headerStyle',border: [false, false, false, false] }
                ],
                
            )
        objContent.table = table
        this.docDefinition.content.push(objContent)

        // console.log(arr_data.length-1,'js')
        let objContent2= {}
        let table2 = {
            layout: 'noBorders',
            dontBreakRows: true,
        }
        table2.widths = ['7%','7%','10%','29%','29%','13%','8%','5%',"5%"],
        table2.body = []
        for (let i = 0; i < arr_data.length; i++) {
            // table2.body.push( 
            //     [
            //         // { text:`${i+1}`,style:'tableBody',border: [true, false, true, true] },
            //         // { text:`${!arr_data[i].pcg_code?arr_data[i].code:arr_data[i].pcg_code}`,style:'tableBody',border: [true, false, true, true] },
            //         // { text:`${!arr_data[i].pcg_english?arr_data[i].english:arr_data[i].pcg_english}`,style:'tableBody',border: [true, false, true, true] },
            //         // { text:`${!arr_data[i].pcg_japanese?arr_data[i].japanese:arr_data[i].pcg_japanese}`,style:'tableBody',border: [true, false, true, true] },
            //         // { text:`${arr_data[i].approver}`,style:'tableBody',border: [true, false, true, true] },
            //         // { text:`${arr_data[i].remarks_status}`,style:'tableBody',border: [true, false, true, true] },
            //         // { text:`${arr_data[i].pcg_code}`,style:'tableBody',border: [true, false, true, true] },

            //         { text:`${i+1}`,style:'tableBody',border: [true, false, true, true], alignment:'center'},
            //         { text:`${arr_data[i].approved_by==null?'-':!arr_data[i].pcg_code?arr_data[i].code:arr_data[i].pcg_code}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
            //         { text:`${arr_data[i].pcg_type}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
            //         { text:`${!arr_data[i].pcg_english?arr_data[i].english:arr_data[i].pcg_english}`,style:'tableBody',border: [true, false, true, true] },
            //         { text:`${!arr_data[i].pcg_japanese?arr_data[i].japanese:arr_data[i].pcg_japanese}`,style:'tableBody',border: [true, false, true, true] },
            //         { text:`${!arr_data[i].pcg_reference?arr_data[i].edit_ref1:arr_data[i].pcg_reference}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
            //         { text:`${!arr_data[i].remarks_status?arr_data[i].remakrs1:arr_data[i].remarks_status}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
            //     ],
            // )

            const rowData = [
                    { text: `${i + 1}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' },
                    { text: `${arr_data[i].approved_by === null ? '-' : (!arr_data[i].pcg_code ? arr_data[i].code : arr_data[i].pcg_code)}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' },
                    { text: `${arr_data[i].pcg_type}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' },
                    { text: `${!arr_data[i].pcg_english ? arr_data[i].english : arr_data[i].pcg_english}`, style: 'tableBody', border: [true, false, true, true] },
                    { text: `${!arr_data[i].pcg_japanese ? arr_data[i].japanese : arr_data[i].pcg_japanese}`, style: 'tableBody', border: [true, false, true, true] },
                ];
            
                // Handling edit references and memos
                const editReferencesAndMemos = [
                    !arr_data[i].reference ? '' : arr_data[i].reference,
                    !arr_data[i].edit_ref1 ? '' : arr_data[i].edit_ref1,
                    !arr_data[i].edit_ref2 ? '' : arr_data[i].edit_ref2,
                    !arr_data[i].edit_ref3 ? '' : arr_data[i].edit_ref3,
                    !arr_data[i].edit_ref4 ? '' : arr_data[i].edit_ref4,
                    !arr_data[i].edit_memo1 ? '' : arr_data[i].edit_memo1,
                    !arr_data[i].edit_memo2 ? '' : arr_data[i].edit_memo2,
                    !arr_data[i].edit_shio ? '' : arr_data[i].edit_shio,
                    !arr_data[i].edit_memo3 ? '' : arr_data[i].edit_memo3,
                    !arr_data[i].edit_memo4 ? '' : arr_data[i].edit_memo4,
                ];
            
                // Filter out empty edit references and memos
                const filteredEditReferencesAndMemos = editReferencesAndMemos.filter(item => item !== '');
            
                // Join edit references and memos with new lines
                const editReferencesAndMemosText = filteredEditReferencesAndMemos.join('\n');
                rowData.push({ text: editReferencesAndMemosText, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
            
                rowData.push({ text: `${!arr_data[i].remarks_status ? arr_data[i].remakrs1 : arr_data[i].remarks_status}`, style: 'tableBody', border: [true, false, true, true], alignment: 'center' });
            
                // Push the modified row data to the table body
                table2.body.push(rowData);
              
        }
        objContent2.table = table2
        this.docDefinition.content.push(objContent2)

        
    }   
    

	print(arr_data,component,judgement){
        
        console.log(arr_data,component,judgement,'eto ay component sa js')
      
        if(component == 'without_cc_header'){
            this.createData2(arr_data,component,judgement); //without header
		    // pdfMake.createPdf(this.docDefinition).open();
            pdfMake.createPdf(this.docDefinition).download(`${arr_data[0].control_number}-${arr_data[0].revision}-info.pdf`);
        }else{
            //UseDialog.vue

            // Create a mapping for colors and messages
            const statusDetails = {
                'NG': { color: 'red', message: '「NG箇所があるため、修正お願い致します。修正後、必要に応じて再度事前検討依頼お願い致します。」' },
                '非推奨': { color: 'black', message: '「検討の結果、問題点が見つかりませんでした。」' },
                '注意喚起': { color: 'blue', message: '「検討の結果、間題が見つかりませんでした。ただし、以下の情報をご確認いただきますようお願いいたします。」' }
            };
            
            // Initialize the judgement variable as an object
            let final_judgement = { status: '', color: '', message: '' };

            // Check if arr_data is empty
            if (arr_data.length === 0) {
                // Set judgement to default values for '注意喚起'
                final_judgement = { status: '注意喚起', color: statusDetails['注意喚起'].color, message: statusDetails['注意喚起'].message };
            } else {
                // Check for 'NG' or '注意喚起' in judgement or remakrs1
                if (arr_data.some(item => item.judgement === 'NG')) {
                    final_judgement = { status: 'NG', ...statusDetails['NG'] };
                } else if (arr_data.some(item => item.judgement === '注意喚起' || item.judgement === '非推奨')) {
                    // Combined condition for '注意喚起' and '非推奨'
                    final_judgement = { status: '注意喚起', ...statusDetails['注意喚起'] }; // Prioritize '注意喚起'
                } else {
                    final_judgement = { status: '非推奨', ...statusDetails['非推奨'] };
                }
            }

            // Output the judgement with color
            console.log(`%c${final_judgement.status}`, `color: ${final_judgement.color}`);
            console.log(final_judgement.message);

            return new Promise(resolve=>{ 
                this.createData(arr_data,component,final_judgement); //with header
                const pdfDocGenerator = pdfMake.createPdf(this.docDefinition);
                pdfDocGenerator.getBase64((buffer) => {
                    resolve(buffer)
                })
            })

            if(arr_data.length > 0){
                return new Promise(resolve=>{ 
                    this.createData(arr_data,component,judgement); //with header
                    const pdfDocGenerator = pdfMake.createPdf(this.docDefinition);
                    pdfDocGenerator.getBase64((buffer) => {
                        resolve(buffer)
                    })
                })
    
                this.createData(arr_data,component,judgement); //with header
                pdfMake.createPdf(this.docDefinition).open();
                // pdfMake.createPdf(this.docDefinition).download(`${arr_data[0].control_number}-${arr_data[0].revision}-info.pdf`);
            }
            
        }

		
	}


    // if(component == 'wew'){
    //     table2.body.push( 
    //                         [
    //                             { text:`${i+1}`,style:'tableBody',border: [true, false, true, true], alignment:'center'},
    //                             { text:`${arr_data[i].approved_by==null?'-':!arr_data[i].pcg_code?arr_data[i].code:arr_data[i].pcg_code}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                             { text:`${arr_data[i].pcg_type}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                             { text:`${!arr_data[i].pcg_english?arr_data[i].english:arr_data[i].pcg_english}`,style:'tableBody',border: [true, false, true, true] },
    //                             { text:`${!arr_data[i].pcg_japanese?arr_data[i].japanese:arr_data[i].pcg_japanese}`,style:'tableBody',border: [true, false, true, true] },
    //                             { 
    //                             text:`
    //                                 ${!arr_data[i].edit_ref1?'':arr_data[i].edit_ref1}
    //                                 ${!arr_data[i].edit_ref2?'':arr_data[i].edit_ref2}
    //                                 ${!arr_data[i].edit_ref3?'':arr_data[i].edit_ref3}
    //                                 ${!arr_data[i].edit_ref4?'':arr_data[i].edit_ref4}
    //                                 ${!arr_data[i].edit_memo1?'':arr_data[i].edit_memo1}
    //                                 ${!arr_data[i].edit_memo2?'':arr_data[i].edit_memo2}
    //                                 ${!arr_data[i].edit_shio?'':arr_data[i].edit_shio}
    //                                 ${!arr_data[i].edit_memo3?'':arr_data[i].edit_memo3}
    //                                 ${!arr_data[i].edit_memo4?'':arr_data[i].edit_memo4}
    //                             `,
    //                             style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                             { text:`${!arr_data[i].remarks_status?arr_data[i].remakrs1:arr_data[i].remarks_status}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                         ],
    //                     )
    // }else{
    //  table2.body.push( 
    //                         [
    //                             { text:`${i+1}`,style:'tableBody',border: [true, false, true, true], alignment:'center'},
    //                             { text:`${arr_data[i].approved_by==null?'-':!arr_data[i].pcg_code?arr_data[i].code:arr_data[i].pcg_code}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                             { text:`${arr_data[i].pcg_type}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                             { text:`${!arr_data[i].pcg_english?arr_data[i].english:arr_data[i].pcg_english}`,style:'tableBody',border: [true, false, true, true] },
    //                             { text:`${!arr_data[i].pcg_japanese?arr_data[i].japanese:arr_data[i].pcg_japanese}`,style:'tableBody',border: [true, false, true, true] },
    //                             { 
    //                             text:`
    //                                 ${!arr_data[i].pcg_reference?'':arr_data[i].pcg_reference}
    //                                 ${!arr_data[i].pcg_reference_2?'':arr_data[i].pcg_reference_2}
    //                                 ${!arr_data[i].pcg_reference_3?'':arr_data[i].pcg_reference_3}
    //                                 ${!arr_data[i].pcg_reference_4?'':arr_data[i].pcg_reference_4}
    //                                 ${!arr_data[i].pcg_hrd_memo?'':arr_data[i].pcg_hrd_memo}
    //                                 ${!arr_data[i].pcg_hrd_memo_2?'':arr_data[i].pcg_hrd_memo_2}
    //                                 ${!arr_data[i].pcg_shio?'':arr_data[i].pcg_shio}
    //                                 ${!arr_data[i].pcg_hrd_memo_3?'':arr_data[i].pcg_hrd_memo_3}
    //                                 ${!arr_data[i].pcg_hrd_memo_4?'':arr_data[i].pcg_hrd_memo_4}
    //                             `,
    //                             style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                             { text:`${!arr_data[i].remarks_status?arr_data[i].remakrs1:arr_data[i].remarks_status}`,style:'tableBody',border: [true, false, true, true], alignment:'center' },
    //                         ],
    //                     )
    // }
    
}


