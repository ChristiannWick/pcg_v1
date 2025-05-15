import pdfMake from "pdfmake/build/pdfmake";
import pdfFonts from "pdfmake/build/vfs_fonts";
import moment from 'moment'


pdfMake.vfs = pdfFonts.pdfMake.vfs;

export default class printData {
	constructor() {
		this.docDefinition = null 
        this.printDate =   moment().format('YYYY-MM-DD')    
	}
	createData(data){
        pdfMake.fonts = {
            yourFontName: {
                normal: 'MS-Gothic.tff',
                bold: 'MS-Gothic.tff',
                italics: 'MS-Gothic.tff',
                bolditalics: 'MS-Gothic.tff'
            },
        }

        this.docDefinition = {
            pageSize: 'A4',
            pageOrientation: 'landscape',
            // watermark: { text: 'WITH INFO', color: 'black', fontSize:80, opacity: 0.3, bold: true,angle:0 },
            content: [],
            pageMargins : [13, 12, 13, 12],
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
                    fontSize:11,
                    alignment:'center',
                    bold: true,
                    margin: [ 0, 15, 0, 15 ]
                    
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

        console.log(data,'this data')
        let array_status = [
            "「NG箇所があるため、修正お願い致します。修正後、必要に応じて再度事前検討依頼お願い致します。」",
            "「検討の結果、問題点が見つかりませんでした。」"
        ];
        let objContent= {}
        let table = {}
        table.widths = [ '85%','30%','30%','30%','30%','30%','30%','30%','30%','30%','30%','30%','30%','30%'],
        table.body = []
            table.body.push( 
                [
                   
                    { text:`プランニングカルテ簡易法規検討・壁量検討結果`,style:'headerStyle',bold:true,border: [false, false, false, false], },
                    { text:`2023/05/10 9:54`,style:'DateStyle',fontSize:11,bold:false, border: [false, false, false, false] },
                ],
                [
                   
                    { text:`Carte Planning PLan Check Result`,style:'subHeaderStyle',bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                    
                    { text:``,style:'DateStyle',border: [false, false, false, false]},
                    { text:``,style:'DateStyle',border: [false, false, false, false]},
                    
                ],
                [
                    
                    { text:``,style:'DateStyle',border: [false, false, false, false]},
                    { text:``,style:'DateStyle',border: [false, false, false, false]},
                    
                ],
                [
                    
                    { text:``,style:'DateStyle',border: [false, false, false, false]},
                    { text:``,style:'DateStyle',border: [false, false, false, false]},
                    
                ],
                [
                  
                    { text:``,fillColor:'#EEEEEE',border: [false, true, false, true]},
                    { text:``,fillColor:'#EEEEEE',border: [false, true, false, true]},
                    
                ],
                [
                   
                    { text:``,style:'subHeaderStyle',fontSize:13,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                   
                    { text:``,style:'subHeaderStyle',fontSize:13,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                   
                    { text:`「NG箇所があるため、修正お願い致します。修正後、必要に応じて再度事前検討依頼お願い致します。」`,color:'red',style:'subHeaderStyle',bold:false,fontSize:11,border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                  
                    { text:``,style:'subHeaderStyle',fontSize:13,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                   
                    { text:``,style:'subHeaderStyle',fontSize:13,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                
                [
                   
                    { text:`【判定】 NG ：禁止 , NR：推奨しない, ― ：注意喚起`,fontSize:11,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                   
                    { text:`【Legend】 NG ：NG , NR: Not recommend,  ― ：Information only`,fontSize:11,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                   
                    { text:``,style:'subHeaderStyle',fontSize:13,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                    
                    { text:``,style:'subHeaderStyle',fontSize:13,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                   
                    { text:``,style:'subHeaderStyle',fontSize:13,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
                [
                    
                    { text:`お客様コード                                 図面番号(Plan No.)`,fontSize:10,bold:false,border: [false, false, false, false],alignment:"left" },
                    { text:``,border: [false, false, false, false],alignment:"left" },
                    
                ],
                [
                   
                    { text:`Control Number:`,style:'subHeaderStyle',bold:true,border: [false, false, false, false],alignment:"left" },
                    { text:``,style:'DateStyle',border: [false, false, false, false], },
                    
                ],
            )
            for (let i = 0; i < 3; i++) {
                table.body.push( 
                    [
                        
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                    ],
                )
            }
        objContent.table = table
        this.docDefinition.content.push(objContent)
        // console.log(this.docDefinition.content[0].table.body[1],'content')
        // this.docDefinition.content[0].table.body[1].push([{
        //     table: {
        //             widths: ['*'],
        //             body: [[" "], [" "]]
        //     },
        //     layout: {
        //         hLineWidth: function(i, node) {
        //             return (i === 0 || i === node.table.body.length) ? 0 : 2;
        //         },
        //         vLineWidth: function(i, node) {
        //             return 0;
        //         },
        //     }
        // }])
       
        let objContent1= {}
        let table1 = {}
        table1.widths = [ '5%','5%','40%','40%','10','5%','5%'],
        table1.body = []
        table1 = {
            widths: [50,70,'auto','auto',70,70,40],
            body: [
                [
                    {text: '質問 QUESTION 番号 NUMBER', style: 'tableHeader', margin: [ 1, 5, 1, 5 ]}, 
                    {text: '検討内容 STUDY CONTENT', style: 'tableHeader', margin: [ 5, 10, 5, 5 ]}, 
                    {text: '内容 \nCONTENTS (ENGLISH)', style: 'tableHeader', margin: [ 5, 15, 5, 5 ]}, 
                    {text: '内容 \nCONTENTS (JAPANESE)', style: 'tableHeader', margin: [ 5, 15, 5, 5 ]}, 
                    {text: '各種帳票 Document No.', style: 'tableHeader', margin: [ 5, 11, 5, 5 ]}, 
                    {text: '判定 Judgement', style: 'tableHeader', margin: [ 5, 15, 5, 5 ]}, 
                    {text: 'PCG Code', style: 'tableHeader', margin: [ 5, 14, 5, 5 ]}
                ],
                // [
                //     {text: '1', style: 'tableBody',},
                //     {text: '構造(Structural)', style: 'tableBody',},
                //     {text: 'There are restrictions on the minimum frontage of the building.We will send you a modified version of a possible plan. A review of the current plan is required', style: 'tableBody',},
                //     {text: '建物の最小間口に問題があります。可能なプランに修正したものを送信いたします。', style: 'tableBody',},
                //     {text: '(WAKUGUMI)枠組ルールブック140715-07\n\n(JIKUGUMI)軸組ルールブック140715-07', style: 'tableBody',},
                //     {text: 'NG', style: 'tableBody',},
                //     {text: 'SC-008', style: 'tableBody',},
                // ],
                
              
            ]
        }
        for (let i = 0; i < data.length-1; i++) {
            table1.body.push( 
                [
                    { text:`${i+1}`,style:'tableBody',border: [true, true, true, true], margin: [ 1, 5, 1, 5 ] },
                    { text:`${data[i].pcg_type}`,style:'tableBody',border: [true, true, true, true], margin: [ 5, 10, 5, 5 ] },
                    { text:`${data[i].pcg_english}`.replace("'",""),style:'tableBody',border: [true, true, true, true], margin: [ 5, 15, 5, 5 ] },
                    { text:`${data[i].pcg_japanese}`,style:'tableBody',border: [true, true, true, true], margin: [ 5, 15, 5, 5 ] },
                    { text:`${data[i].approver}`,style:'tableBody',border: [true, true, true, true], margin: [ 5, 11, 5, 5 ] },
                    { text:`${data[i].checked_at}`,style:'tableBody',border: [true, true, true, true], margin: [ 5, 15, 5, 5 ] },
                    { text:`${data[i].pcg_code}`,style:'tableBody',border: [true, true, true, true], margin: [ 5, 14, 5, 5 ] },
                ],
            )
        }
        // for (let i = 0; i < data.length; i++) {
        //     table1.body.push( 
        //         [
        //             { text:``,style:'DateStyle',border: [false, false, false, false] },
        //             { text:``,style:'DateStyle',border: [false, false, false, false] },
        //             { text:``,style:'DateStyle',border: [false, false, false, false] },
        //             { text:``,style:'DateStyle',border: [false, false, false, false] },
        //         ],
        //     )
        // }
        objContent1.table = table1
        this.docDefinition.content.push(objContent1)

        let objContent2= {}
        let table2 = {}
        table2.widths = [ '10%','17%','70%','3%'],
        table2.body = []
        // console.log('this_invoice_no',invoice_no);
        // let arr = [
        //     'SERVICE CHARGES FOR HOUSE PLAN CAD DRAWINGS',
        //     'SERVICE CHARGES FOR COLOR PERSPECTIVE DRAWINGS',
        //     'SERVICE CHARGES FOR VARIOUS APPLICATION FORMS'
        // ];
        
        // let itemsOfClaim = "";
        // if(invoice_no.includes("P") || invoice_no.includes("F")){
        //     itemsOfClaim = arr[1]
        // }else if(invoice_no.includes("C")){
        //     itemsOfClaim = arr[0]
        // }else{
        //     itemsOfClaim = arr[2]
        // }
            table2.body.push( 
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    // { text:`${ioc_invoiceDate}`,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    // { text:`JPY ${total_amount}`,style:'subHeaderStyle',border: [false, false, false, false] },
                    // { text:`JPY 1,123,000`,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
            )
            for (let i = 0; i < 15; i++) {
                table2.body.push( 
                    [
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                    ],
                )
            }


        objContent2.table = table2
        this.docDefinition.content.push(objContent2)


        let objContent3= {}
        let table3 = {}
        table3.widths = [ '5%','10%','68%','7%'],
        table3.body = []
            table3.body.push( 
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'subHeaderStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
            )
            for (let i = 0; i < 25; i++) {
                table3.body.push( 
                    [
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                        { text:``,style:'DateStyle',border: [false, false, false, false] },
                    ],
                )
            }
        objContent3.table = table3
        this.docDefinition.content.push(objContent3)

        let objContent4= {}
        let table4 = {}
        table4.widths = [ '3%','50%','44%','3%'],
        table4.body = []
            table4.body.push( 
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,align:"left", bold: true, border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ],
            )
        objContent4.table = table4
        this.docDefinition.content.push(objContent4)

        let objContent5= {}
        let table5 = {}
        table5.widths = [ '3%','50%','44%','3%'],
        table5.body = []
        for (let i =0; i<40; i++){
            table5.body.push( 
                [
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    // { text:``,align:"left",border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                    { text:``,style:'DateStyle',border: [false, false, false, false] },
                ]
            )
        }
            
        objContent5.table = table5
        this.docDefinition.content.push(objContent5)

    }   
        

	print(data){
        // console.log(data,'data')
        this.createData(data)
		pdfMake.createPdf(this.docDefinition).open();
	}
}
