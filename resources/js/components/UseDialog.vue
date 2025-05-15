<template>
    <div>
        <v-dialog v-model="use_dialog" transition="dialog-transition" persistent >
            <v-card>
                <v-card-title style="background:rgb(84,110,122); color:white; ">
                    <b>Planner's Confirmation Guides</b>&nbsp;
                    <v-spacer/>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn 
                                icon
                                v-bind="attrs"
                                v-on="on" 
                                dark
                                >
                                <v-icon @click="printPCG('view')" >mdi-eye-check-outline</v-icon>
                            </v-btn> 
                        </template>
                        <span>Preview PDF</span>
                    </v-tooltip>
                    <div class="text-center">
                        <v-menu
                            v-model="menu"
                            :close-on-content-click="false"
                            offset-x
                            bottom
                            origin="center center"
                            transition="scale-transition"
                            >
                            <template v-slot:activator="{ on: menu, attrs }">
                                <v-tooltip bottom>
                                <template v-slot:activator="{ on: tooltip }">
                                    <v-btn
                                    icon
                                    v-bind="attrs"
                                    dark
                                    v-on="{ ...tooltip, ...menu }"
                                    >
                                    <v-icon color="#ff9e9e">mdi-delete-forever</v-icon>
                                    </v-btn>
                                </template>
                                <span>Delete templates</span>
                                </v-tooltip>
                            </template>
                            <v-card width="200">
                                <v-data-table
                                    height="200"
                                    dense
                                    v-model="for_delete_templates"
                                    :headers="headers"
                                    :items="items"
                                    item-key="id"
                                    show-select
                                    class="elevation-1"
                                    :items-per-page="-1"
                                    hide-default-footer
                                >
                                </v-data-table>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        small
                                        color="error"
                                        text
                                        @click="deleteAllTemplates"
                                    >
                                        Delete
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-menu>
                    </div>
                    <v-btn 
                        :disabled="!pcg_obj.control_code || !pcg_obj.revision_no || !pcg_obj.selected_house || pcg_obj.judgement != 'OK'" 
                        @click="printPCG('download')" color="success"
                        v-show="print_pcg_btn == 0"
                        title="export pdf"
                        small
                        >
                        Print<v-icon>mdi-file-pdf-box</v-icon>
                    </v-btn>
                    <v-btn title="close" color="white" icon @click="closeDialog"><v-icon dark>mdi-close</v-icon></v-btn>
                </v-card-title>
                <v-card-text>   
                    <v-container fluid>
                        <v-row dense >
                            <v-col md="2" sm="2">
                                <v-autocomplete
                                    hide-details=""
                                    dense
                                    label="PCG Type:"
                                    outlined
                                    :items="pcgType_items"
                                    item-text="pcg_type"
                                    item-value="pcg_type"
                                    v-model="selected_pcgType"
                                >
                                </v-autocomplete>
                            </v-col>
                            <v-col md="3" sm="3">
                                 <v-autocomplete
                                    hide-details=""
                                    dense
                                    label="Category:"
                                    outlined
                                    :items="carte_categories"
                                    item-text="category"
                                    item-value="category"
                                    v-model="selected_category"
                                >
                                </v-autocomplete>
                            </v-col>
                            <v-col md="3" sm="3">
                                <!-- @keyup.enter="$emit('getPrintPcg')" -->
                                <v-text-field
                                    dense
                                    v-model="search"
                                    clearable
                                    flat
                                    solo-inverted
                                    hide-details
                                    prepend-inner-icon="mdi-magnify"
                                    label="Search"
                                ></v-text-field>
                            </v-col>
                            <v-col class="d-flex align-center" md="2" sm="2">
                                <v-btn @click="resetFilter" dark color="red" small>reset</v-btn>
                            </v-col>
                        </v-row>
                        <v-row dense>
                            <!-- <v-col cols="2">
                                <v-text-field
                                    label="Employee No:"
                                    hide-details
                                    dense
                                    outlined
                                    v-model="employee_id"
                                    readonly
                                >
                                </v-text-field>
                            </v-col> -->
                            <v-col cols="3">
                                <v-text-field
                                    label="Construction Code & Revision:"
                                    hide-details
                                    dense
                                    outlined
                                    placeholder="ex.(1234567-2012-0100)"
                                    v-model="pcg_obj.fullControlCode"
                                    :readonly="print_pcg_btn == 1"
                                    @input="updateCcAndRev"
                                    >
                                </v-text-field>
                            </v-col>
                            <!-- <v-col cols="2">
                                <v-text-field
                                    label="Construction Code:"
                                    hide-details
                                    dense
                                    outlined
                                    placeholder="ex.(1234568-2017)"
                                    v-model="pcg_obj.control_code"
                                    maxlength="12"
                                    :readonly="print_pcg_btn == 1"
                                >
                                </v-text-field>
                            </v-col>
                            <v-col cols="2">
                                <v-text-field
                                    label="Revision No. :"
                                    hide-details
                                    dense
                                    outlined
                                    placeholder="ex.(0101)"
                                    v-model="pcg_obj.revision_no"
                                    maxlength="8"
                                    :readonly="print_pcg_btn == 1"
                                >
                                </v-text-field>
                            </v-col> -->
                            <v-col cols="2">
                                <v-select
                                    hide-details
                                    dense
                                    label="House Type :"
                                    outlined
                                    :items="houses"
                                    item-text="name"
                                    item-value="type"
                                    v-model="pcg_obj.selected_house"
                                >
                                </v-select>
                            </v-col>
                            <!-- <v-col cols="3" style=" margin:0px; padding:0px;">
                                    <v-radio-group
                                    :readonly="print_pcg_btn == 1"
                                    v-model="pcg_obj.judgement"
                                    row
                                    hide-details=""
                                    >
                                    <template v-slot:label>
                                        <div><strong>Judgement:</strong></div>
                                    </template>
                                        <v-radio
                                            :disabled="print_arr.length == 0"
                                            label="NG"
                                            value="NG"
                                        ></v-radio>
                                        <v-radio
                                            label="OK"
                                            value="OK"
                                        ></v-radio>
                                    </v-radio-group>
                            </v-col> -->
                        
                            <!-- <v-col cols="2" >
                                <v-text-field
                                    label="Revision No. :"
                                    hide-details
                                    dense
                                    outlined
                                    placeholder="ex.(0101)"
                                    v-model="pcg_obj.doc1"
                                    :readonly="print_pcg_btn == 1"
                                >
                                </v-text-field>
                            </v-col> -->
                        </v-row>
                    </v-container>
                    <v-simple-table dense fixed-header height="450px" >
                        <thead>
                            <tr>
                                <th style="background:rgb(84,110,122); font-size:14px; color:white;">
                                    <v-checkbox color="white" v-model="main_checkbox" ></v-checkbox>
                                </th>
                                <!-- <th style="text-align:center; background:rgb(84,110,122); font-size:14px; width:8%; color:white;">Question No.</th> -->
                                <!-- <th style="text-align:center; background:rgb(84,110,122); font-size:14px; width:120px; color:white;">PCG CODE</th> -->
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white;">Template ID</th>
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white;">Study Content</th>
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white; ">Content (English)</th>
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white;">Content (Japanese)</th>
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white;">Document No.</th>
                                <!-- <th style="text-align:center; background:rgb(84,110,122); font-size:14px; width:120px; color:white;">Pages (Ex:1,2,6-8)</th> -->
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white;">Judgement</th>
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white;">Remarks</th>
                                <th style="text-align:center; background:rgb(84,110,122); font-size:14px; color:white;">Action</th>
                                <!-- <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, font-size:14px; width:120px; color:white;">階</th>
                                <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, font-size:14px; width:200px; color:white;">指摘箇所</th> -->
                                <!-- <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, font-size:14px; width:20px; color:white;" colspan="2">指摘内容</th> -->
                                <!-- <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, font-size:14px; width:100px; color:white;">備考</th>
                                <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, font-size:14px; width:100px; color:white;">各種帳票</th> -->
                                <!-- <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, font-size:14px; width:100px; color:white;">判定</th>
                                <th rowspan="2" class="text-center " style="background:linear-gradient(179.7deg, rgb(41 142 221) 2.9%, font-size:14px; width:100px; color:white;">設計確認</th> -->
                            </tr>
                        </thead>
                        <tbody style="text-align:center;" >
                            <tr 
                            
                            v-for="(item, index) in searchRecords" :key="index" >
                                <!-- :value="item.print" -->
                                <td>
                                    <v-checkbox v-model="item.print_val" @click="updatePrintPcg(item)"></v-checkbox>
                                </td>
                                <!-- <td>
                                    {{index + 1}}
                                </td> -->
                                <!-- <td>
                                    <span>{{item.pcg_code}}</span>
                                </td> -->
                                <td>
                                    <span>{{item.id}}</span>
                                </td>
                                <td>
                                    <span>{{item.pcg_type}}</span>
                                </td>
                                <td>
                                    <span>{{item.pcg_english}}</span>
                                </td>
                                <td>
                                    <span>{{item.pcg_japanese}}</span>
                                </td>
                                <!-- <td>
                                    <div style="display:block;">{{item.pcg_reference}}</div>
                                    <div style="display:block;" :contenteditable="print_pcg_btn == 0 ? true : false" @blur="updateReferences(item, 'pcg_reference_2', $event)" >{{item.pcg_reference_2}}</div>
                                    <div style="display:block;" :contenteditable="print_pcg_btn == 0 ? true : false" @blur="updateReferences(item, 'pcg_reference_3', $event)" >{{item.pcg_reference_3}}</div>
                                    <div style="display:block;">{{item.pcg_reference_4}}</div>
                                    <div style="display:block;">{{item.pcg_hrd_memo}}</div>
                                    <div style="display:block;">{{item.pcg_hrd_memo_2}}</div>
                                    <div style="display:block;">{{item.pcg_hrd_memo_3}}</div>
                                    <div style="display:block;">{{item.pcg_hrd_memo_4}}</div>
                                    <div style="display:block;">{{item.pcg_shio}}</div>
                                    <span style="display:block;">{{removeDynamicPrefix(item.gc_attachment)}}</span>
                                </td> -->
                                <!-- <td style="padding: 0 4px; vertical-align: top;">
                                    <v-list dense tile class="pa-0">
                                        <template v-for="(field, index) in referenceFields">
                                        <v-list-item 
                                            v-if="item[field.field]" 
                                            :key="index" 
                                            class="px-0"
                                            style="min-height: 40px;"
                                        >
                                            <v-list-item-content class="py-1">
                                                <div class="d-flex align-center">
                                                    <span class="caption grey--text text--darken-1 mr-2">
                                                    {{ field.label }}:
                                                    </span>
                                                    <div v-if="field.editable"
                                                        :contenteditable="print_pcg_btn === 0"
                                                        @blur="updateReferences(item, field.field, $event)"
                                                        class="editable-field pa-1 flex-grow-1">
                                                    {{ item[field.field] }}
                                                    </div>
                                                    <div v-else class="text-caption">
                                                    {{ item[field.field] }}
                                                    </div>
                                                </div>
                                            </v-list-item-content>
                                        </v-list-item>
                                        </template>
                                    </v-list>
                                </td>
                                <td style="padding: 0 4px; vertical-align: top;">
                                    <v-list dense class="pa-0">
                                        <template v-for="(field, index) in referenceFields">
                                        <v-list-item 
                                            v-if="item[field.field]" 
                                            :key="index" 
                                            class="px-0"
                                            style="min-height: 40px;"
                                        >
                                            <v-list-item-content class="py-1">
                                                <div class="d-flex align-center">
                                                    <span class="caption grey--text text--darken-1 mr-2">
                                                    {{field.label}}
                                                    </span>
                                                    <div
                                                        contenteditable
                                                        @focusout="updatePage($event, item, field.pageField)"
                                                        class="editable-field pa-1"
                                                        style="min-width: 80px;"
                                                    >
                                                        {{ item[field.pageField] || '' }}
                                                    </div>
                                                </div>
                                            </v-list-item-content>
                                        </v-list-item>
                                        </template>
                                    </v-list>
                                </td> -->
                                <td class="pa-2" style="width:25%; ">
                                    <v-list dense class="transparent py-0">
                                        <!-- Reference Fields -->
                                        <template v-for="(refField, index) in referenceFields">
                                        <v-list-item 
                                            v-if="item[refField.field]"
                                            :key="index"
                                            class="px-0"
                                        >
                                            <v-list-item-content class="d-flex align-center pa-0">
                                                <!-- Left Side - Reference -->
                                                <v-row dense>

                                                <v-col cols="8" class="d-flex align-center pr-2">
                                                    <span class="caption grey--text text--darken-1 mr-2">
                                                    {{ refField.label }}
                                                    </span>
                                                    <v-tooltip top>
                                                    <template v-slot:activator="{ on }">
                                                        <span 
                                                        class="text-caption text-truncate"
                                                        style="max-width: 200px;"
                                                        v-on="on"
                                                        >
                                                        {{ item[refField.field] }}
                                                        </span>
                                                    </template>
                                                    <span>{{ item[refField.field] }}</span>
                                                    </v-tooltip>
                                                </v-col>

                                                <!-- Right Side - Page Input -->
                                                <v-col cols="4" class="d-flex align-center justify-end pt-8">
                                                    <v-text-field
                                                        :value="item[refField.pageField]"
                                                        @change="updatePage($event, item, refField.pageField)"
                                                        dense
                                                        solo
                                                        hint="Ex: 1,3,7-8"
                                                        style="max-width: 220px;"
                                                        >
                                                        <template v-slot:append>
                                                            <v-tooltip top>
                                                            <template v-slot:activator="{ on }">
                                                                <v-icon 
                                                                small 
                                                                color="grey" 
                                                                v-on="on"
                                                                >
                                                                mdi-book-open-page-variant-outline
                                                                </v-icon>
                                                                
                                                            </template>
                                                            <span>{{'Pages for ' + refField.label}}</span>
                                                            </v-tooltip>
                                                        </template>
                                                    </v-text-field>
                                                </v-col>
                                                </v-row>

                                            </v-list-item-content>
                                        </v-list-item>
                                        </template>
                                    </v-list>
                                </td>
                                <td>
                                    <!-- <span>{{item.remarks_status}}</span> -->
                                    <v-autocomplete
                                        dense
                                        filled
                                        outlined
                                        label="Judgement:"
                                        hide-details 
                                        :items="judgements"
                                        v-model="item.judgement"
                                        @change="updateJudgement(item)"
                                    ></v-autocomplete>
                                </td>
                                <td>
                                    <v-autocomplete
                                        dense
                                        filled
                                        outlined
                                        label="Actions:"
                                        hide-details 
                                        :items="action_items"
                                        item-text="new_english"
                                        item-value="action_id"
                                        v-model="item.action_id"
                                        @change="updateAction(item)"
                                        :filter="filterAutoCompleteItems"
                                    ></v-autocomplete>
                                </td>
                                <td>
                                    <v-btn
                                        @click="deletePrintPcg(item)"
                                        x-small
                                        fab
                                        color="error"
                                        title="Delete Template"
                                        >
                                        <v-icon>mdi-delete</v-icon>
                                    </v-btn>
                                </td>
                                <!-- <td >
                                    <v-select
                                        style=" white-space: normal; "
                                        hide-details
                                        dense
                                        v-model="item.remakrs1"
                                        :items="pcg_action"
                                        item-text="name"
                                        item-value="remakrs1"
                                        @change="update_pcg_report_arr(item)"
                                        label="Remarks:"
                                    ></v-select>
                                </td> -->
                                
                                <!-- <td>
                                    <v-select
                                        hide-details
                                        dense
                                        :items="pcg_storey"
                                        v-model="selected_storey"
                                        @change="update_pcg_report_arr()"
                                    
                                    ></v-select>
                                </td>
                                <td>
                                    <v-select
                                        hide-details
                                        dense
                                        :items="location_list"
                                        v-model="selected_location"
                                        @change="update_pcg_report_arr()"

                                    ></v-select>
                                </td>
                                <td>
                                    <span style="display:block;">{{item.english}}</span>
                                    <span style="display:block;">{{item.japanese}}</span>
                                </td>
                                <td>
                                    <v-file-input></v-file-input>
                                </td>
                                <td>
                                    <v-select
                                        hide-details
                                        dense
                                        :items="pcg_action"
                                        item-text="name"
                                        item-value="value"
                                        v-model="selected_action.value"
                                        @change="update_pcg_report_arr()"
                                        label="Action:"
                                    ></v-select>
                                    <v-text-field
                                        style="margin-top:15px;"
                                        hide-details
                                        dense
                                        readonly
                                        label="Japanese Terms:"
                                        v-model="selected_action.value"
                                    ></v-text-field>
                                </td>
                                <td>
                                    <center><div style="border: 1px solid #555;width: 60%;height: 25px;"></div></center>
                                    <span>{{item.pcg_reference}}</span>
                                    <span>{{item.pcg_reference_2}}</span>
                                    <span>{{item.pcg_reference_3}}</span>
                                    <span>{{item.pcg_reference_4}}</span>
                                    <span>{{item.pcg_hrd_memo}}</span>
                                    <span>{{item.pcg_hrd_memo_2}}</span>
                                    <span>{{item.pcg_hrd_memo_3}}</span>
                                    <span>{{item.pcg_hrd_memo_4}}</span>
                                    <span>{{item.pcg_shio}}</span>
                                </td> -->
                            </tr>
                        </tbody>
                    </v-simple-table>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <!-- <v-btn :loading="loading" :disabled="loading" @click="saveReport()" color="blue">SAVE</v-btn>
                    <v-btn @click="printPCG()" color="success">PRINT PCG</v-btn> -->
                    <v-btn 
                        :disabled="!pcg_obj.control_code || !pcg_obj.revision_no || !pcg_obj.selected_house || !pcg_obj.judgement || pcg_obj.judgement == 'OK' || temp_print_arr.length == 0 || isSaving"
                        v-if="print_pcg_btn == 0" 
                        @click="saveReport()" 
                        color="blue">
                        SAVE
                    </v-btn>
                    <v-btn 
                        :disabled="!pcg_obj.control_code || !pcg_obj.revision_no || !pcg_obj.selected_house || !pcg_obj.judgement" 
                        @click="printPCG('download')" color="success"
                        v-else
                        >
                        PRINT PCG
                    </v-btn>
                    <v-spacer/>
                    <!-- <v-text-field 
                        style="width:20px;"
                        hide-details=""
                        readonly 
                        dense 
                        outlined 
                        label="Selected templates" 
                        :value="temp_print_arr.length">
                        
                    </v-text-field> -->
                    <v-chip :color="temp_print_arr.length > 0 ? 'primary' : ''">Selected template: {{temp_print_arr.length}}</v-chip>
                    <!-- <v-btn 
                        :disabled="!pcg_obj.control_code || !pcg_obj.revision_no || !pcg_obj.selected_house || !pcg_obj.judgement" 
                        @click="printPCG('download')" color="success"
                        v-show="print_arr == 0"
                        >
                        PRINT PCG
                    </v-btn> -->
                    
                </v-card-actions>
            </v-card>
        </v-dialog>

        <div class="text-center">
            <v-dialog v-model="dialogVisible" max-width="400">
                <v-card>
                    <v-overlay :value="gen_overlay">
                        <v-progress-circular
                            indeterminate
                            size="64"
                        ></v-progress-circular>
                    </v-overlay>
                </v-card>
            </v-dialog>
        </div>
        
    </div>
</template>

<script>
import print_pdf from './printPDF'
import moment from 'moment';
import print1 from './print1'
import { PDFDocument, PDFPage } from 'pdf-lib';
import Swal from 'sweetalert2';
import { mapActions, mapState, mapMutations } from 'vuex';

export default {
    props : ['use_dialog','employee_id','edit_pcg_obj','print_arr','team_code'],
    data() {
        return {
            search:'',
            selected_pcgType: 'All',
            selected_category: 'All',
            dialogVisible: false,
            overlay: false,
            gen_overlay : false,
            loading : false,
            print_pcg_btn : 0,
            selectedOption : [],
            pcg_obj : {
                fullControlCode: ''
            },
            selected_house : "",
            // houses : [
            //     { name: 'Jikugumi', type: 'Jikugumi' },
            //     { name: 'Wakugumi', type: 'Wakugumi' },
            // ],
            selected_storey : "",
            pcg_storey : [
                "1階","2階","3階","R階","PH","立面図"
            ],
            selected_location : "",
            location_list : [
                "和室", "広縁", "L/D/K" ,"シューズクローク" ,"玄関ホール" ,"ホール" ,"洗面所",
		        "脱衣室", "洗面脱衣室", "吹抜", "トイレ", "主寝室", "洋室",
				"納戸","WIC","書斎" ,"ファミリールーム" ,"ユーティリティ", "シャワールーム",
                "システムバス" ,"バルコニー" ,"ロフト" ,"中庭" ,"車庫" ,"外部"		
            ],
            selected_action : {},
            // pcg_action : [
            //     {remarks_status: "NG", name: "NG"},
            //     {remarks_status: "NR", name: "NR"},
            // ],
            checkbox1 : false,
            // main_checkbox : false,
            // temp_print_arr : [],
            menu: false,
            for_delete_templates : [],
            isSaving: false, // New property to track saving state
            judgements : [
                'NG','非推奨','注意喚起'
            ],
        }
    },
    
    methods: {
        ...mapActions([
            // 'getActions'
            "updateCartTerm"
        ]),

        updateCcAndRev() {
            const parts = this.pcg_obj.fullControlCode.split('-');
            if (parts.length >= 2) {
                this.pcg_obj.control_code = `${parts[0]}-${parts[1]}`;
                this.pcg_obj.revision_no = parts.length > 2 ? parts[2] : '';
            } else {
                this.pcg_obj.control_code = this.pcg_obj.fullControlCode; // If input is invalid, just set control_code
                this.pcg_obj.revision_no = ''; // Clear revision_no if not valid
            }
        },

        resetFilter(){
            // this.updateCartTerm('');
            this.search = ''
            this.selected_pcgType = "All"
            this.selected_category = "All"
        },

        filterAutoCompleteItems(item, queryText, itemText) {
            // Filter based on item-value (action_id)
            // console.log(item,queryText,itemText)
            // console.log(item.new_english.toLowerCase())
            return item.new_english.toLowerCase().includes(queryText.toLowerCase());
        },

        updateJudgement(item){
            console.log(item,'judgement item')
            axios.post('api/update_cart_judgement',{
                id : item.cart_id,
                judgement : item.judgement
            }).then(res => {
                console.log(res.data,'updateAction')
                this.$emit('getPrintPcg')
            }).catch(err => {
                console.log(err,'ereror updateAction')
            })
        },

        updatePage(event,item,column_name) {
            console.log(item,column_name, 'item update page');
            // item[column_name]  = event.target.innerText; 
            item[column_name]  = event.replace(/\s+/g, ''); // Remove spaces if needed
            

            axios.post('api/update_cart_pages',{
                id : item.cart_id,
                column_name: column_name,
                pages : item[column_name],
            })
            .then(res => {
                console.log(res.data,'updateAction')
                this.$emit('getPrintPcg')
            }).catch(err => {
                console.log(err,'ereror updateAction')
            })
        },

        updateAction(item){
            console.log(item,'update action')
            // this.temp_print_arr = []
            axios.post('api/update_cart_action',{
                id : item.cart_id,
                action_id : !item.action_id ? 1 : item.action_id 
            })
            .then(res => {
                console.log(res.data,'updateAction')
                this.$emit('getPrintPcg')
            }).catch(err => {
                console.log(err,'ereror updateAction')
            })
            
            // if (item && item.action_id) {  
            //     const filtered = this.action_items.filter(res => res.action_id == item.action_id)  
            //     item.action_english = filtered.length > 0 ? filtered[0].english : null  
            //     item.action_japanese = filtered.length > 0 ? filtered[0].japanese : null  
            // } else {  
            //     item.action_english = null  
            //     item.action_japanese = null  

            //     console.log(item,'test item')
            // }  
        },

        deleteAllTemplates(){
            const ids = this.for_delete_templates.map(item => item.id);
            console.log(ids,'asdasdadsad')
            let text = "Delete Selected Templates?\n";
            if (confirm(text) == true) {
                axios({
                    method : "post",
                    url : 'api/delete_multiple_print',
                    data : {
                        pcg_ids : ids,
                        print_by : this.userInfo.EmployeeCode,
                    }
                }).then(res => {
                    console.log(res.data,'deleted success');
                    this.main_checkbox = false;
                    this.$emit('getPrintPcg')
                    // this.temp_print_arr = [];
                }).catch(err => {
                    console.log(err,'error multiple delete of cart items')
                })
            }
        },

        deletePrintPcg(item){
            console.log(item,'delete personal template?')
            let text = "Remove Template?\n";
            if (confirm(text) == true) {
                axios({
                    method : "post",
                    url : 'api/delete_print',
                    data : item
                }).then(res => {
                    console.log(res.data,'deleted success');
                    this.$emit('getPrintPcg')
                    // Swal.fire({
                    //     position: "bottom-end",
                    //     icon: "success",
                    //     title: "Pcg has been removed!",
                    //     showConfirmButton: false,
                    //     timer: 1000
                    // });
                }).catch(err => {
                    console.log(err,'error delete of cart items')
                })
            }
        },

        updateReferences(item, key, event){
            console.log(item, 'test function item');
            // console.log(this.print_arr, 'print arr data');

            // Update the specified property of the item
            item[key] = event.target.innerText;

            // Find the index of the item in this.print_arr and update it
            const index = this.print_arr.findIndex(printItem => printItem.id === item.id);
            if (index !== -1) {
                this.print_arr[index] = item;
            }

            console.log("Updated item:", item);
            console.log("Updated this.print_arr:", this.print_arr);
        },

        removeDynamicPrefix(attachment) {
            // Use a regular expression to remove the dynamic prefix
            if (attachment !== null && attachment !== '') {
                // Use a regular expression to remove the dynamic prefix
                // const dynamicPrefix = this.extractDynamicPrefix(attachment);
                // const regex = new RegExp(`^${dynamicPrefix}`);
                const regex = /^.*?-/;
                return attachment.replace(regex, "");
            } else {
                // Handle the case where attachment is null or an empty string
                return '';
            }
        },

        openOverlayInDialog() {
            // this.dialogVisible = true;
            // this.gen_overlay = true;
            setTimeout(() => {// Simulate loading for a few seconds (you can replace this with actual loading logic)
                this.gen_overlay = !this.gen_overlay;
                this.dialogVisible = !this.dialogVisible;
            }, 500);
        },

        saveReport(){
            if (this.isSaving) return; // Prevent further clicks if already saving
            this.isSaving = true; // Set saving state to true
            console.log(this.print_arr,'print_arr value')
            axios({
                method : 'post',
                url : 'api/getJapanEmployees',
                data : {
                    ConstructionCode : this.pcg_obj.control_code
                }
            }).then(async res => {
                console.log(res.data,'ressssssss')
                if(this.print_arr.length > 0 ){
                    await this.print_arr.forEach(element => {
                        element.Employee_No =  this.employee_id
                        element.control_number = this.pcg_obj.control_code
                        element.revision = this.pcg_obj.revision_no
                        element.house_type = this.pcg_obj.selected_house
                        element.planner_kanji = !res.data[0].plannerName?null:res.data[0].plannerName
                        element.planner_roma = !res.data[0].plannerNameRomanji?null:res.data[0].plannerNameRomanji
                        element.planner_code = !res.data[0].PlannerCode?null:res.data[0].PlannerCode
                        element.planner_branch_code = !res.data[0].plannerBranchCode?null:res.data[0].plannerBranchCode
                        element.planner_branch_name = !res.data[0].plannerBranchName?null:res.data[0].plannerBranchName
                        element.salesman_kanji = !res.data[0].salesmanName?null:res.data[0].salesmanName
                        element.salesman_roma = !res.data[0].salesmanNameRomanji?null:res.data[0].salesmanNameRomanji
                        element.salesman_code = !res.data[0].SalesmanCode?null:res.data[0].SalesmanCode
                        element.salesman_branch_code = !res.data[0].salesmanBranchCode?null:res.data[0].salesmanBranchCode
                        element.salesman_branch_name = !res.data[0].salesmanBranchName?null:res.data[0].salesmanBranchName
                    });

                    // this.temp_print_arr = this.print_arr.filter(result => {
                    //     return result.print == 1
                    // })

                    console.log(this.temp_print_arr,'asxdaxsdasdxasd')
                    if(this.temp_print_arr.length > 0){
                        axios({
                            method : 'post',
                            url : 'api/insertReport',
                            // data : this.print_arr
                            data : this.temp_print_arr
                        }).then(res => {
                            console.log('res',res.data)
                            this.print_pcg_btn = 1;
                        }).catch(err => {
                            alert('error insertReport')
                            console.log(err,'errror')
                        }).finally(() => {
                            this.isSaving = false; // Reset saving state
                        });

                    }else{
                        alert('select template')
                    }
                }else{
                    alert('no data');
                }
            }).catch(err => {
                alert('ConstructionCode does not exist or wrong input of ConstructionCode')
                console.log(err,'errror')
            })

            
        },

        closeDialog(){
            // this.$emit.print_arr = [];
            this.$emit('closeDialog');
            this.print_pcg_btn = 0;
            this.pcg_obj = {};
            // this.main_checkbox = false
            // this.temp_print_arr = [];
            // this.print_arr.forEach(el => {
            //     el.print = 0; // Set el.print to 1
            // });
            
            
        },
        
        async generate_test(arr_data){
            var testPrintOutput = new print_pdf();
            testPrintOutput.print(arr_data);
        },

        generate1(arr_data,value,judgement){
            var testPrintOutput = new print1();
            testPrintOutput.print(arr_data,value,judgement);
        },
        
        async printPCG(item){
            console.log(item,'print value')
            console.log(this.pcg_obj.judgement,'judgement val')
            // this.generate1(this.print_arr,'with_cc_header',this.pcg_obj.judgement);
            this.openOverlayInDialog();
            // this.dialogVisible = true;
            // this.gen_overlay = true;
            const arr = [];
            // if(this.temp_print_arr.length == 0 || this.pcg_obj.judgement == "OK"){
            if(this.temp_print_arr.length == 0){
                let obj1 = {
                    Employee_No : this.employee_id,
                    control_number : this.pcg_obj.control_code,
                    revision : this.pcg_obj.revision_no,
                    house_type : this.pcg_obj.selected_house,
                    judgement : this.pcg_obj.judgement
                }
                arr.push(obj1);
                console.log(arr,'sample ar nga')
                let FirstPage = new print1()
                let newFirstPage = await FirstPage.print(arr,'with_cc_header',this.pcg_obj.judgement)

                const pdfBytes = Buffer.from(newFirstPage, 'base64');

                const pdfBlob = new Blob([pdfBytes], { type: 'application/pdf' });

                try {
                    console.log('Before createObjectURL:', pdfBlob);

                    // Create a URL from the Blob
                    const blobUrl = URL.createObjectURL(pdfBlob);
                    console.log('After createObjectURL:', blobUrl);

                    if(item === 'view'){
                        // Open the URL in a new tab to display the PDF
                        window.open(blobUrl, '_blank');

                        // Clean up the Blob URL after opening the PDF
                        URL.revokeObjectURL(blobUrl);
                    }else{ //download
                        // Create a temporary <a> element
                        try {
                            let new_arr = [];
                            const response = await axios.post('api/getJapanEmployees',{
                                ConstructionCode : this.pcg_obj.control_code
                            })
                            const responseData = response.data;
                            // new_arr = arr.map(element => ({
                            //     ...element,
                            //     planner_kanji : !responseData[0].plannerName?null:responseData[0].plannerName,
                            //     planner_roma : !responseData[0].plannerNameRomanji?null:responseData[0].plannerNameRomanji,
                            //     planner_code : !responseData[0].PlannerCode?null:responseData[0].PlannerCode,
                            //     planner_branch_code : !responseData[0].plannerBranchCode?null:responseData[0].plannerBranchCode,
                            //     planner_branch_name : !responseData[0].plannerBranchName?null:responseData[0].plannerBranchName,
                            //     salesman_kanji : !responseData[0].salesmanName?null:responseData[0].salesmanName,
                            //     salesman_roma : !responseData[0].salesmanNameRomanji?null:responseData[0].salesmanNameRomanji,
                            //     salesman_code : !responseData[0].SalesmanCode?null:responseData[0].SalesmanCode,
                            //     salesman_branch_code : !responseData[0].salesmanBranchCode?null:responseData[0].salesmanBranchCode,
                            //     salesman_branch_name : !responseData[0].salesmanBranchName?null:responseData[0].salesmanBranchName,
                            // }))
                            // Check if responseData is empty
                            const plannerData = responseData.length > 0 ? responseData[0] : {};

                            new_arr = arr.map(element => ({
                                ...element,
                                planner_kanji: plannerData.plannerName || null,
                                planner_roma: plannerData.plannerNameRomanji || null,
                                planner_code: plannerData.PlannerCode || null,
                                planner_branch_code: plannerData.plannerBranchCode || null,
                                planner_branch_name: plannerData.plannerBranchName || null,
                                salesman_kanji: plannerData.salesmanName || null,
                                salesman_roma: plannerData.salesmanNameRomanji || null,
                                salesman_code: plannerData.SalesmanCode || null,
                                salesman_branch_code: plannerData.salesmanBranchCode || null,
                                salesman_branch_name: plannerData.salesmanBranchName || null,
                            }));

                            await axios({
                                method : 'post',
                                url : 'api/insertReport',
                                data : new_arr
                            }).then(res => {
                                console.log('res',res.data)
                                const link = document.createElement('a');
                                link.href = blobUrl;
                                link.download = `${this.pcg_obj.control_code}-${this.pcg_obj.revision_no}-info.pdf`; // Set the desired filename

                                // Trigger a click event on the <a> element
                                link.click();

                                // Clean up the Blob URL after the download
                                URL.revokeObjectURL(blobUrl);
                    
                            })

                            console.log(new_arr,'test ne_arr')
                            
                        } catch (error) {
                            console.error('Error posting data:', error);
                        }

                      
                        this.closeDialog()

                    }
                    this.openOverlayInDialog();
                    
                } catch (error) {
                    console.error('Error occurred:', error);
                }
                
            
                // this.generate1(this.arr,'with_cc_header',this.pcg_obj.judgement);
            }else{
                console.log(this.print_arr,'psrint');
                const keysToCheck = [
                    "pcg_reference", "pcg_reference_2", "pcg_reference_3", "pcg_reference_4", "pcg_hrd_memo",
                    "pcg_hrd_memo_2", "pcg_shio", "pcg_hrd_memo_3", "pcg_hrd_memo_4", "gc_attachment"
                ];
                const nonNullValues = this.temp_print_arr.flatMap(item =>
                    keysToCheck
                        .filter(key => item[key] !== null)
                        .map(key => ({ 
                            [key]: item[key],
                            [`${key}_page_cart`]: item[`${key}_page_cart`]
                        }))
                );
                console.log(nonNullValues,'nonNullValues')

                // const uniqueNonNullableValues = [];
                // const seenValues = {};
                // nonNullValues.forEach(item => {
                //     const newItem = {};
                //     for (const key in item) {
                //         const value = item[key];
                //         if (!(key in seenValues) || seenValues[key] !== value) {
                //         newItem[key] = value;
                //         seenValues[key] = value;
                //         }
                //     }
                //     if (Object.keys(newItem).length > 0) {
                //         uniqueNonNullableValues.push(newItem);
                //     }
                // });
                // console.log(uniqueNonNullableValues,'test');
                
                let FirstPage = new print1()
                let newFirstPage = await FirstPage.print(this.temp_print_arr,'with_cc_header',this.pcg_obj.judgement)
                
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

                // console.log(groupedData, 'groupedData');
                // if(this.pcg_obj.doc1 != ""){
                //     groupedData.push({'pcg_reference_2' : this.pcg_obj.doc1})
                // }

                // const newData = [];
                // for (const key in groupedData) {
                //     if (groupedData.hasOwnProperty(key)) {
                //         const values = groupedData[key];
                //         processValues(key, values);
                //     }
                // }
                // console.log(newData,'test2');
                
                // // Rest of your code...
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
                    // data : uniqueNonNullableValues,
                    // data : newData,
                    data : nonNullValues,
                    // responseType : 'arraybuffer'
                }).then(({data}) => {
                    console.log(data,'dasdata')
                    // return false
                    console.log(data.fileBuffer,'data.fileBuffer')
                    data.fileBuffer.unshift(newFirstPage);
                    data.fileBufferPages.unshift(null);
                    console.log(data,'newFirstPage data')
                    let downloadUrl = null;
                    return new Promise(async(resolve)=>{
                        // create a new PDF document
                        const mergedPdf = await PDFDocument.create();
                        // Set document properties
                        mergedPdf.setTitle('My Document Title');
                        mergedPdf.setAuthor('Author Name');
                        mergedPdf.setSubject('Document Subject');
                        mergedPdf.setKeywords(['keyword1', 'keyword2']);

                        // // loop through the PDFs and add each one to the merged document
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

                        if(item == 'view'){
                            window.open(downloadUrl, '_blank'); //with attachments and front page
                            URL.revokeObjectURL(downloadUrl);
                            // const pdfBytes = Buffer.from(newFirstPage, 'base64');
                            // const pdfBlob = new Blob([pdfBytes], { type: 'application/pdf' });
                            // const blobUrl = URL.createObjectURL(pdfBlob);
                            // window.open(blobUrl, '_blank'); //with front_page only of pdf
                        }else{
                            // window.open(downloadUrl, '_blank');
                            const downloadLink = document.createElement('a');
                            downloadLink.href = downloadUrl;
                            downloadLink.target = '_blank'; // Open in new tab if needed
                            downloadLink.download = `${this.temp_print_arr[0].control_number}-${this.temp_print_arr[0].revision}-info.pdf`; // Set the filename for download

                            // Simulate a click on the link to trigger the download
                            downloadLink.click();

                            // Clean up: remove the temporary link
                            URL.revokeObjectURL(downloadUrl);
                            this.multipleUpdatePrint(0,true);
                            this.closeDialog()
                            // this.$emit('refresh')
                        }
                        this.openOverlayInDialog();
                    })
                }).catch(err => {
                    alert('contact ihs ')
                    console.log(err,'error in exporting pdf file')
                })
                // this.generate1(this.print_arr,'with_cc_header',this.pcg_obj.judgement);
            }
           
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
         
        update_pcg_report_arr(item){
            this.pcg_obj.remarks_status = item.remarks_status
            item.remarks_status = this.pcg_obj.remarks_status
            // this.print_arr.forEach(element => {
            //     element.Employee_No =  this.employee_id,
            //     element.control_number = this.pcg_obj.control_code,
            //     element.revision = this.pcg_obj.revision_no,
            //     element.house_type = this.pcg_obj.selected_house
            // });
            console.log(this.print_arr,'rint')


            // this.print_arr.forEach(element => {

            //     let obj = {
            //         newData : element.map(r => ({
            //             Employee_No : this.employee_id,
            //             remarks : this.selected_action.remarks,
            //             control_number : this.pcg_obj.control_code,
            //             revision : this.pcg_obj.revision_no,
            //             house_type : this.pcg_obj.selected_house
            //         }))
            //     }
            //     console.log(obj,'obj')
            // });
            // console.log(this.print_arr[i],'tsit');
            // console.log(',',this.selectedOption);
            // if(this.print_arr.length > 0){
            //     this.print_arr.map(r => {
            //         if(r.id == id){
            //             r.Employee_No = this.employee_id
            //             r.remarks = this.selected_action.remarks
            //             r.control_number = this.pcg_obj.control_code
            //             r.revision = this.pcg_obj.revision_no
            //             r.house_type = this.pcg_obj.selected_house
            //         }
            //     })
            //     console.log(this.print_arr,'arrar');
            // }
            // console.log(this.selected_action.remarks)
            // console.log(this.selected_house)
            // console.log(this.selected_location)
            // console.log(this.selected_storey)
        },

        // updatePrintPcg(item){ //old insert temp_print_arr function
        //     console.log(item,'update item')
        //     // if(!item.print){
        //     //     item.print = 0
        //     //     console.log(item)
        //     // }else{
        //     //     item.print = 1
        //     //     console.log(item)
        //     // }
        //     const existingItemIndex = this.temp_print_arr.findIndex(res => res.id === item.id);
        //     if(existingItemIndex !== -1){
        //         this.temp_print_arr.splice(existingItemIndex, 1);
        //     }else{
        //         this.temp_print_arr.push(item);
        //     }
        //     console.log(this.temp_print_arr,'temp_print_arr')
        // },

        async updatePrintPcg(item){
            console.log(item,'item')
            try {
                const response = await axios.post('api/update_print',{
                    ids: [item.cart_id],
                    print_val : item.print_val
                })
                const responseData = response.data;
                console.log(responseData,'updatePrintPcg');
                // this.$emit('getPrintPcg')

            } catch (error) {
                console.error('Error posting data:', error);
            }
        },
        
        multipleUpdatePrint(main_checkbox_val,reset_val){
            console.log(main_checkbox_val, 'set main_checkbox_val')  
            const ids = this.print_arr.map(res => res.cart_id)  
            console.log(ids, 'ids')  
            axios.post('api/update_print', {  
                ids: ids,  
                print_val: main_checkbox_val,
                reset_val: reset_val
            }).then(response => {  
                const responseData = response.data
                console.log(responseData,'responseData')
                this.$emit('getPrintPcg')  
            }).catch(error => {  
                console.error('Error posting data:', error)  
            }) 
        },
    },

    computed: {
        ...mapState([
            "action_items",
            "userInfo",
            "pcgType_items",
            "cart_search",
            "carte_categories"
        ]),

        cart_search: {
            get() {
                return this.$store.state.cart_search;
            },
            set(value) {
                this.updateCartTerm(value);
            }
        },

        referenceFields() {
            return [
                { 
                    field: 'pcg_reference',
                    pageField: 'pcg_reference_page_cart',
                    label: 'Reference (CN/IAR):',
                    editable: false,
                },
                { 
                    field: 'pcg_reference_2',
                    pageField: 'pcg_reference_2_page_cart',
                    label: 'Rulebook:',
                    editable: false
                },
                { 
                    field: 'pcg_reference_3',
                    pageField: 'pcg_reference_3_page_cart',
                    label: 'Rulebook:',
                    editable: false
                },
                { 
                    field: 'pcg_reference_4',
                    pageField: 'pcg_reference_4_page_cart',
                    label: 'GC Tsutatsu:',
                    editable: false
                },
                { 
                    field: 'pcg_hrd_memo',
                    pageField: 'pcg_hrd_memo_page_cart',
                    label: 'Tei Betsu Manual:',
                    editable: false
                },
                { 
                    field: 'pcg_hrd_memo_2',
                    pageField: 'pcg_hrd_memo_2_page_cart',
                    label: 'Shio Manual:',
                    editable: false
                },
                { 
                    field: 'pcg_hrd_memo_3',
                    pageField: 'pcg_hrd_memo_3_page_cart',
                    label: 'Renraku Hyou:',
                    editable: false
                },
                { 
                    field: 'pcg_hrd_memo_4',
                    pageField: 'pcg_hrd_memo_4_page_cart',
                    label: 'Tsutatsu:',
                    editable: false
                },
                { 
                    field: 'pcg_shio',
                    pageField: 'pcg_shio_page_cart',
                    label: 'Shio Manual',
                    editable: false
                },
            ]
        },

        temp_print_arr() {
            return this.print_arr.filter(res => res.print_val == true)
        },
        headers() {
            return [{ text: 'TemplateID', align: 'center', sortable: false, value: 'id' }];
        },
        items() {
            return this.print_arr.map((item, index) => ({ ...item, index: index + 1 })); // Add 1 for human-readable indexing
        },

        houses(){
            let houses = [
                { name: 'Jikugumi', type: 'Jikugumi' },
                { name: 'Wakugumi', type: 'Wakugumi' },
            ]

            let data = houses.filter(r => {
                if(this.team_code == 4){
                    return this.pcg_obj.selected_house = "Jikugumi"
                }else{
                    return this.pcg_obj.selected_house = "Wakugumi"
                }
            })
            // console.log(data,'data computed')
            return data;
        },

        // searchRecords(){ // for search text field
        //     return this.print_arr.filter((data) => {
        //         if(!this.cart_search){
        //             return [];
        //         }
        //         let searchData = new RegExp(this.cart_search.toUpperCase(), "g");
        //         console.log(searchData,'searchdata')
        //         return JSON.stringify(Object.values(data)).toUpperCase().match(searchData);
        //     });
        // },

        searchRecords(){ // for search text field
            return this.print_arr.filter((data) => {
                if(!this.search){
                    // return [];
                    return data;
                }else{
                    let searchData = new RegExp(this.search.toUpperCase(), "g");
                    // console.log(searchData,'searchdata')
                    // Check if the search text matches
                    const matchesSearch = JSON.stringify(Object.values(data)).toUpperCase().match(searchData);
                    return matchesSearch;
                }
            }).filter(data=>{
                if(this.selected_pcgType == "All"){
                    return data;
                }else{
                    if(data.pcg_type == this.selected_pcgType){                          
                        return data;
                    } 
                }
            })
            .filter(data=>{
                if(this.selected_category == "All"){
                    return data;
                }else{
                    if(data.pcg_category == this.selected_category){                          
                        return data;
                    } 
                }
            });
        },

        main_checkbox: {  
            get() {  
                // console.log(this.temp_print_arr.length)  
                // console.log(this.print_arr.length)  
                if (this.temp_print_arr.length == this.print_arr.length) {  
                    return true;  
                }  
                return false;  
            },  
            set(value) {  
                this.multipleUpdatePrint(value,false)
            }  
        }  

    },

    watch: {
        temp_print_arr(value){ //SSD25-0414921-298
            console.log(value,'temp_print_arr value test')
            if(value.length > 0){
                this.pcg_obj.judgement = "NG"
            }else{
                this.pcg_obj.judgement = "OK"
            }
        },

        // pcg_obj: {
        //     handler(newVal) {
        //         console.log(newVal,'newvallellee')
        //         this.pcg_obj.fullControlCode = `${newVal.control_code}${newVal.revision_no ? '-' + newVal.revision_no : ''}`;
        //     },
        //     deep: true
        // }

        // use_dialog(val){
        //     if(val){
        //         this.pcg_obj.judgement = "NG"
        //     }
        // },

        // main_checkbox(value) {//old insert temp_print_arr function
        //     if(value){
        //         this.temp_print_arr = []
        //         this.print_arr.forEach(el => {
        //             el.print = 1; // Set el.print to 1
        //             this.temp_print_arr.push(el);
        //         });
        //     }else{
        //         this.temp_print_arr.forEach(el => {
        //             el.print = 0; // Set el.print to 0
        //         });
        //         this.temp_print_arr = [];
        //     }
        // },
        // pcg_obj(val){
        //     console.log(val,'pcgval')
        // },

    },

    mounted() {
        // this.getActions();
        // this.getEmployees();
    },
}
</script>
<style scoped>
    table,th,td {
        border : 1px solid black;
        border-collapse: collapse;
    }
    .editable-field {
        border: 1px solid #ddd;
        border-radius: 4px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.5, 1);
        min-height: 32px;
        display: flex;
        align-items: center;
    }

    .editable-field:focus {
        border-color: var(--v-primary-base);
        background-color: #f5f5f5;
        outline: none;
    }
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* th, td {
        background-color: #96D4D4;
        border-color: #96D4D4;
    } */
</style>

