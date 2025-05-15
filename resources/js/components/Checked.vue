<template>
    <div>
        <v-card outlined elevation="5" style="margin:6px;">
            <v-card-title class="text-h6 " style="background:rgb(84,110,122); color:white; ">
                Checked Templates
                <v-spacer/>
                <div class="text-center">
                    <v-chip
                        small
                        color="green"
                        text-color="white"
                        label
                        >
                        <v-badge
                            inline
                            color="rgb(146, 188, 134)"
                            :content="pcg_templates.length == 0 ? '0':pcg_templates.length"
                            >
                            For Approval:&nbsp;
                        </v-badge>
                    </v-chip>
                    <v-chip
                        small
                        color="red"
                        text-color="white"
                        label
                        >
                        <v-badge
                            inline
                            color="rgb(228, 132, 134)"
                            :content="for_approval_templates.length == 0 ? '0':for_approval_templates.length"
                            >
                            For Checking:&nbsp;
                        </v-badge>
                    </v-chip>
                    <v-chip
                        small
                        color="rgb(0,128,0)"
                        text-color="white"
                        label
                        >
                        <v-badge
                            inline
                            color="rgb(62, 153, 103)"
                            :content="approved_templates.length == 0 ? '0':approved_templates.length"
                            >
                            Approved: &nbsp;
                        </v-badge>
                    </v-chip>
                    <v-btn color="#1976D2" title="refresh data" @click="refreshData()" small >
                        <v-icon color="white">mdi-refresh</v-icon>
                    </v-btn>
                    <v-btn @click="approveAll()" class="white--text" color="#43A047" small>approve all</v-btn>
                </div>
            </v-card-title>
            <v-simple-table
                fixed-header
                height="650"
                ref="pcgTable"
            >
                <template v-slot:default>
                    <thead>
                        <tr>
                            <th class="grey lighten-5 text-center " style="font-size:15px; height: 30px;">
                                <v-checkbox class="my-3" hide-details="" color="blue" :indeterminate="isIndeterminate" @click.native="toggleCheckbox" v-model="main_checkbox" ></v-checkbox>
                            </th>
                            <th class="grey lighten-5 text-center " style="font-size:15px; height: 30px;">
                                PCG Details
                            </th>
                            <th class="grey lighten-5 text-center " style="font-size:15px; height: 30px;">
                                English
                            </th>
                            <th class="grey lighten-5 text-center " style="font-size:15px; height: 30px;">
                                Japanese
                            </th>
                            <th class="grey lighten-5 text-center " style="font-size:15px; height: 30px;">
                                Attachment
                            </th>
                            <th class="grey lighten-5 text-center " style="font-size:15px; height: 30px;">
                                In-Charge
                            </th>
                            <th class="grey lighten-5 text-center " style="font-size:15px; width: 100px;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody style="text-align:center;">
                        <tr
                        @dblclick="editDialog(item)"
                        v-for="(item, index) in nextPage"
                        :key="index"
                        :style="item.approve_status != '3' || userInfo.admin == 1 ? 'cursor:pointer':'cursor:not-allowed; background-color:#d9d7d7; '"
                        >
                            <td>
                                <v-checkbox v-model="item.print"  @click="updatePrintPcg(item)"></v-checkbox>
                            </td>
                            <td class="text-center ">
                                <span style="display:block; color:rgb(128,0,0); font-size:16px; font-weight:bold;">{{item.pcg_category}}
                                    <v-tooltip color="primary" right>
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn outlined icon v-bind="attrs" v-on="on" @click="copyClipboard(item)" x-small color="primary">
                                                <v-icon x-small>mdi-content-copy</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Copy to Clipboard</span>
                                    </v-tooltip>
                                </span>
                                <span style="display:block;">Date: {{item.pcg_date}}</span>
                                <span style="display:block; ">Method: <span style="color:red;">{{item.pcg_method}}</span></span>
                                <v-chip small :color= "item.approve_status == '1' ? 'red' : item.approve_status == '3' ? 'orange':'rgb(0,128,0)'">
                                    <span style="color:white;">{{item.approve_status=='1'?'Waiting for Approval...':item.approve_status == '3' ? 'Disapproved By: ' + item.disapproved_by : 'Approved By: ' + item.approved_by}}</span>
                                </v-chip>
                                <div v-show="item.checked_by != null && item.checked_by != '' ">
                                    <v-chip small color="#32CD32" style="margin: 2px 16px;">
                                        <span style="color:white;">Checked By: {{item.checked_by}}</span> 
                                    </v-chip>
                                </div>
                            </td>
                            <td class="text-center">
                                <span >{{ item.pcg_english }}</span>
                            </td>
                            <td class="text-center">
                                <span style="display:block;">{{item.pcg_japanese}}</span>
                                <span style="font-style:italic; font-size:12px; color:rgb(0,128,0);">-Translated by: </span>
                                <span style="color:rgb(0,128,81); ">
                                    {{item.pcg_translated_by}}
                                </span>
                            </td>
                            <td class="text-center" style="overflow:overlay; ">
                                <div style="width: 110px;">
                                    <div v-show="item.pcg_reference != null && item.pcg_reference != ''">
                                        <i style="text-decoration:underline;">Reference (CN/IAR):</i><br>
                                        <v-tooltip bottom color="primary">
                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn @click="openDialog('serverDialog',item.pcg_reference)" v-bind="attrs" v-on="on" outlined text small style="color:blue;">
                                                <span style="display: block; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{item.pcg_reference}}</span>
                                            </v-btn>
                                        </template>
                                        <span>{{item.pcg_reference}}</span>
                                        </v-tooltip>
                                    </div>
                                    <div v-show="item.pcg_reference_2 != null && item.pcg_reference_2 != '' || item.pcg_reference_3 != null && item.pcg_reference_3 != ''">
                                        <i style="text-decoration:underline;">Rulebook:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_reference_2)" v-show="item.pcg_reference_2 != null && item.pcg_reference_2 != ''" outlined text small style="color:blue;" >{{item.pcg_reference_2}}</v-btn><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_reference_3)" v-show="item.pcg_reference_3 != null && item.pcg_reference_3 != ''" outlined text small style="color:blue;" >{{item.pcg_reference_3}}</v-btn><br>
                                    </div>
                                    <div v-show="item.pcg_reference_4 != null && item.pcg_reference_4 != '' ">
                                        <i style="text-decoration:underline;">GC Tsutatsu:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_reference_4)" outlined text small style="color:blue;">{{item.pcg_reference_4}}</v-btn><br>
                                    </div>
                                    <div v-show="item.pcg_hrd_memo != null && item.pcg_hrd_memo != '' ">
                                        <i style="text-decoration:underline;">Tei Betsu Manual:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_hrd_memo)" outlined text small style="color:blue;">{{item.pcg_hrd_memo}}</v-btn><br>
                                    </div>
                                    <div v-show="item.pcg_hrd_memo_2 != null && item.pcg_hrd_memo_2 != '' ">
                                        <i style="text-decoration:underline;">Shio Manual:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_hrd_memo_2)" outlined text small style="color:blue;">{{item.pcg_hrd_memo_2}}</v-btn><br>
                                    </div>
                                    <div v-show="item.pcg_hrd_memo_3 != null && item.pcg_hrd_memo_3 != '' ">
                                        <i style="text-decoration:underline;">Renraku Hyou:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_hrd_memo_3)" outlined text small style="color:blue;">{{item.pcg_hrd_memo_3}}</v-btn><br>
                                    </div>
                                    <div v-show="item.pcg_hrd_memo_4 != null && item.pcg_hrd_memo_4 != '' ">
                                        <i style="text-decoration:underline;">Tsutatsu:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_hrd_memo_4)" outlined text small style="color:blue;">{{item.pcg_hrd_memo_4}}</v-btn><br>
                                    </div>
                                    <div v-show="item.pcg_shio != null && item.pcg_shio != ''  ">
                                        <i style="text-decoration:underline;">Shio Manual:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.pcg_shio)" outlined text small style="color:blue;">{{item.pcg_shio}}</v-btn><br>
                                    </div>
                                    <div v-show="item.gc_attachment != null && item.gc_attachment != ''  ">
                                        <i style="text-decoration:underline;">GC Attachments:</i><br>
                                        <v-btn @click="openDialog('serverDialog',item.gc_attachment)" outlined text small style="color:blue;">{{removeDynamicPrefix(item.gc_attachment)}}</v-btn><br>
                                    </div>
                                    
                                </div>
                            </td>
                            <td>
                                <div style="vertical-align:middle; ">
                                    <div style="color:rgb(128,0,0); font-size:12px;">{{item.pcg_incharge}}</div>
                                    <div style="color:orange; font-size:12px;">{{item.team_name}}</div>
                                    <v-chip small dark :style="{'background-color': item.remarks === 'With Reference' ? 'rgb(94, 152, 131)' : 'grey'}"   >
                                        {{item.remarks}}
                                    </v-chip>
                                </div>
                                
                            </td>
                            <td class="text-center pa-0">
                                <v-btn @click="approve_pcg(item.id)" color="primary" title="approve" icon small outlined >
                                    <v-icon small color="#73b9ff">mdi-thumb-up</v-icon>
                                </v-btn>
                                <v-btn v-show="checked_templates.length == 0" @click="disapprove_pcg(item.id)" color="red" title="disapprove" icon small outlined >
                                    <v-icon small color="#e77575">mdi-thumb-down</v-icon>
                                </v-btn>
                                <v-btn @click="delete_pcg(item.id)" color="red" title="delete" icon small outlined >
                                    <v-icon small color="red">mdi-delete</v-icon>
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </template>
            </v-simple-table>
            <v-pagination
                @input="handlePageChange"
                color="rgb(84,110,122)"
                v-model="currentPage"
                :length="pages"
                :total-visible="5"
            ></v-pagination>
            <div>{{ startIndex }} - {{ endIndex }} of <b>{{ pcg_templates.length }}</b> record(s)</div>
        </v-card>


        <v-dialog
            v-model="server_dialog"
            width="700px"
            >
            <v-card >
                <v-card-title class="indigo accent-2" >
                    <span class="text-h5 white--text" >Server</span>
                </v-card-title>
                <v-simple-table fixed-header height="300px">
                    <thead>
                        <tr>
                            <th class="text-center" style="font-size:16px; width:200px; color:#1976d2;">Filename</th>
                            <th class="text-center" style="font-size:16px; width:350px; color:#1976d2;">Server Path</th>
                            <th class="text-center" style="font-size:16px; color:#1976d2;">Action</th>
                        </tr>
                    </thead>
                    <!-- <tbody v-if="attachment_data.length == 0" style="text-align:center;">
                        <tr>
                            <td colspan="3" rowspan="8" style="text-align:center; font-size:16px;font-weight:bold;">
                                <v-progress-circular indeterminate color="red"></v-progress-circular>
                            </td>
                        </tr>
                    </tbody> -->
                    <tbody v-if="attachment_data.length > 0" style="text-align:center;" >
                        <tr v-for="(item,index) in attachment_data" :key="index">
                            <td style="text-align:center !important; padding:2px 10px; width:200px;">
                                <span style="display:block; color:rgb(128,0,0); color:blue">{{item.filename}}</span>
                            </td>
                            <td style="width:350px;">
                                <span >{{item.directory}}</span>
                            </td>
                            <td >
                                <v-btn tile x-small color="success" @click="openPDF(item)">View</v-btn>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else style="text-align:center;" >
                        <tr>
                            <td colspan="3" style="text-align:center; font-size:16px;font-weight:bold;">
                                <v-icon color="red">mdi-alert-circle</v-icon> No Records found!
                            </td>
                        </tr>
                    </tbody>
                </v-simple-table>
            </v-card>
        </v-dialog>

        <v-snackbar
            v-model="snackbar"
            timeout="1000"
            >
            Text copied to clipboard

            <template v-slot:action="{ attrs }">
                <v-btn
                color="pink"
                text
                v-bind="attrs"
                @click="snackbar = false"
                >
                Close
                </v-btn>
            </template>
        </v-snackbar>

        <v-overlay :value="overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>

        <v-dialog
            v-model="add_pcg_dialog"
            persistent
            max-width="900px"
            >
            <v-card>
                <v-card-title style="margin-bottom:8px; background:rgb(84,110,122);">
                    <span class="text-h5" style="color:white;">{{add_pcg_title}}</span>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="3" >
                            <v-select
                                v-model="edit_pcg_obj.pcg_method"
                                :items= "add_method_arr"
                                item-text="pcg_method"
                                item-value="pcg_method"
                                dense
                                filled
                                outlined
                                label="Method :"
                                hide-details 
                            ></v-select>
                        </v-col>
                        <v-col cols="12" md="3" >
                            <v-select
                                @change="getPcgCode()"
                                v-model="edit_pcg_obj.pcg_type"
                                :items="add_pcg_arr"
                                item-text="pcg_type"
                                item-value="pcg_type"
                                dense
                                filled
                                label="PCG Type :"
                                outlined
                                hide-details=""
                            ></v-select>
                        </v-col>
                        <v-col cols="12" md="4" >
                            <v-autocomplete
                                v-model="edit_pcg_obj.pcg_category"
                                :items="add_category_arr"
                                item-text="category"
                                item-value="category"
                                dense
                                filled
                                outlined
                                label="Category :"
                                hide-details 
                            ></v-autocomplete>
                        </v-col>
                        <v-col cols="12" md="2" >
                            <v-text-field
                                v-model="edit_pcg_obj.pcg_code"
                                dense
                                outlined
                                readonly      
                                label="Code :" 
                                hide-details                                         
                            ></v-text-field>
                        </v-col>
                        
                            <v-btn outlined @click="reference_dropdown = !reference_dropdown;" block>
                                References 
                                <v-icon v-if="reference_dropdown==true" small>mdi-arrow-down-drop-circle-outline</v-icon>
                                <v-icon v-else small>mdi-arrow-right-drop-circle-outline</v-icon>
                            </v-btn>
                            
                            <div v-show="reference_dropdown==true" >
                                <!-- <v-container style="border:1px solid rgb(51, 49, 49);" class="grey lighten-2" > -->
                                <v-container>
                                    <v-row >
                                        <v-col cols="4">
                                            <v-text-field 
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="参照 （CN / IAR） - Reference (CN/IAR)"
                                            v-model="edit_pcg_obj.pcg_reference"
                                            item-value="pcg_reference"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col  cols="4">
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="邸別マニュアル - Tei Betsu Manual"
                                            v-model="edit_pcg_obj.pcg_hrd_memo"
                                            item-value="pcg_hrd_memo"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="4">
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="ルールブック - Rulebook"
                                            v-model="edit_pcg_obj.pcg_reference_2"
                                            item-value="pcg_reference_2"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row >
                                        <v-col cols="4" >
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="仕様マヌアル - Shio Manual"
                                            v-model="edit_pcg_obj.pcg_hrd_memo_2"
                                            item-value="pcg_hrd_memo_2"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="4" >
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="仕様マヌアル - Shio Manual"
                                            v-model="edit_pcg_obj.pcg_shio"
                                            item-value="pcg_shio"

                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="4" >
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="ルールブック - Rulebook"
                                            v-model="edit_pcg_obj.pcg_reference_3"
                                            item-value="pcg_reference_3"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                    <v-row >
                                        <v-col cols="4" >
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="連絡票 - Renraku Hyou"
                                            v-model="edit_pcg_obj.pcg_hrd_memo_3"
                                            item-value="pcg_hrd_memo_3"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="4" >
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="通達 - Tsutatsu"
                                            v-model="edit_pcg_obj.pcg_reference_4"
                                            item-value="pcg_reference_4"
                                            ></v-text-field>
                                        </v-col>
                                        <v-col cols="4" >
                                            <v-text-field
                                            hide-details=""
                                            prepend-inner-icon="mdi-file-upload"
                                            outlined
                                            dense
                                            clearable
                                            label="通達 - Tsutatsu"
                                            v-model="edit_pcg_obj.pcg_hrd_memo_4"
                                            item-value="pcg_hrd_memo_4"
                                            ></v-text-field>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </div>
                            <!-- v-show="add_pcg_title == 'Add PCG'" -->
                        <!-- <v-col   cols="12" >
                            <v-file-input
                                disabled
                                accept=".pdf"
                                hide-details=""
                                dense
                                outlined
                                multiple
                                density="compact"
                                label="Upload Reference File(GC)"
                                v-model="fileAttachments.attachmentFile10"
                                @change="fileChange(fileAttachments.attachmentFile10, 'GC ATTACHMENTS', 'files')"
                            ></v-file-input>
                        </v-col> -->
                        <v-col cols="12" sm="6" >
                            <v-textarea
                                v-model="edit_pcg_obj.pcg_english"
                                rows="5"
                                hide-details
                                outlined
                                background-color="grey lighten-2"
                                name="input-7-1"
                                label="English"
                                @keydown="restrictQuotes"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12" sm="6" >
                            <v-textarea
                                v-model="edit_pcg_obj.pcg_japanese"
                                rows="5"
                                hide-details
                                outlined
                                background-color="grey lighten-2"
                                name="input-7-1"
                                label="Japanese"
                                @keydown="restrictQuotes"
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12" md="12" >
                            <v-select
                                style="width:300px;"
                                dense
                                filled
                                outlined
                                label="Judgement :"
                                hide-details 
                                :items="remark_items"
                                item-text="name"
                                item-value="remarks_status"
                                v-model="edit_pcg_obj.remarks_status"
                            ></v-select>
                        </v-col>
                        <v-col cols="12" sm="6" >
                            <v-select
                                v-model="edit_pcg_obj.pcg_translated_by"
                                :items= "translatedBy_items"
                                item-text="translatedBy"
                                item-value="pcg_translated_by"
                                style="width:300px;"
                                dense
                                filled
                                outlined
                                label="Translated By :"
                                hide-details 
                            ></v-select>
                        </v-col>
                        
                    </v-row>
                
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>

                    <div v-show="(userInfo.admin == 1 && add_pcg_title == 'Edit PCG') || userInfo.EmployeeCode==edit_pcg_obj.pcg_incharge_code">
                        <v-btn color="teal lighten-3 ml-2" @click="updateTemplate()">
                            Save
                        </v-btn>
                    </div>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red darken-1"
                        class="white--text"
                        @click="closeAddDialog()"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import Swal from 'sweetalert2';
export default {
    data() {
        return {
            pcg_templates: [],
            for_approval_templates: [],
            approved_templates: [],
            main_checkbox : false,
            isIndeterminate: false,
            selectedTable: null,
            server_dialog : false,
            attachment_data : [],
            currentPage: 1,
            itemsPerPage: 50,
            snackbar: false,
            checked_templates : [],
            overlay: false,
            edit_pcg_obj : {},
            add_pcg_title: "Add PCG",
            add_pcg_dialog : false,
            reference_dropdown : false,
            fileAttachments: {
                attachmentFile1: [], //参照 （CN / IAR） - Reference (CN/IAR)
                attachmentFile2: [], //邸別マニュアル - Tei Betsu Manual
                attachmentFile3: [], //ルールブック - Rulebook1
                attachmentFile4: [], //仕様マヌアル - Shio Manual1
                attachmentFile5: [], //仕様マヌアル - Shio Manual2
                attachmentFile6: [], //ルールブック - Rulebook2
                attachmentFile7: [], //連絡票 - Renraku Hyou
                attachmentFile8: [], //GC通達 - GC Tsutatsu
                attachmentFile9: [], //通達 - Tsutatsu
                attachmentFile10: [], //GC ATTACHMENT

            },

            files : [],
            files1 : [],
            files2 : [],
            files3 : [],
            files4 : [],
            files5 : [],
            files6 : [],
            files7 : [],
            files8 : [],
            files9 : [],
            allFiles : [],
            remark_items : [
                {remarks_status: "-", name: "-"},
                {remarks_status: "NG", name: "NG"},
                {remarks_status: "NR", name: "NR"},
            ],
            translatedBy_items : [
                // { translatedBy: '', pcg_translated_by: '' },
                { translatedBy: 'Google Translator', pcg_translated_by: 'Google Translator' },
                { translatedBy: 'Sadamitsu Fukaya', pcg_translated_by: 'Sadamitsu Fukaya' },
                { translatedBy: 'Fabro, Ingrid S.', pcg_translated_by: 'Fabro, Ingrid S.' },
                { translatedBy: 'Ilagan, Amielene V.', pcg_translated_by: 'Ilagan, Amielene V.' },
            ],
        }
    },

    watch: {
        server_dialog(val){
            // console.log(val,'server dialog')
            if(val == false){
                this.attachment_data = [];
            }
        },

        
    },

    computed: {
        ...mapState([
            "userInfo",
            "method_items",
            "pcgType_items",
            "carte_categories",
        ]),

        add_method_arr(){
            let data = this.method_items.filter(r => {
                return r.id != 1;
            })
            // console.log('add metohd',data)
            return data;
        },

        add_pcg_arr(){
            let data = this.pcgType_items.filter(r => {
                return r.id != 1;
            })
            return data;
        },

        add_category_arr(){
            let data = this.carte_categories.filter(r => {
                return r.id != 1;
            })
            return data;
        },

        pages() {
            return Math.ceil(this.pcg_templates.length / this.itemsPerPage);
        },
        startIndex() {
            return (this.currentPage - 1) * this.itemsPerPage + 1;
        },
        endIndex() {
            const endIndex = this.currentPage * this.itemsPerPage;
            return endIndex > this.pcg_templates.length ? this.pcg_templates.length : endIndex;
        },
        nextPage(){
            const start = (this.currentPage - 1) * this.itemsPerPage,
            end = start + this.itemsPerPage;
            let a = this.pcg_templates.slice(start,end);
            return a;
        },
        
        
    },

    mounted() {
        this.getCheckedTemplates();
        this.getForApprovalTemplates();
        this.getApprovedTemplates();
    },

    methods: {
        ...mapActions([
            "resetTableScroll"
        ]),

        async handlePageChange(currPage) {
            console.log(currPage,'current page')                
            // Call the Vuex action
            await this.resetTableScroll(this.$refs.pcgTable);
            
            // Any other logic after scroll reset
        },

        refreshData(){
            this.getCheckedTemplates();
            this.getForApprovalTemplates();
            this.getApprovedTemplates();
        },

        openPDF(item){
            console.log(item,'teteteteass')
            axios({
                method : 'post',
                url : 'api/openPDF',
                data : {
                    pdf_id : item.pdf_id,
                    filename : item.filename,
                    directory : item.directory
                },
                responseType : 'blob'
            }).then(res => {
                console.log(res.data,'eto arase ');

                // Create a Blob object from the response
                const fileBlob = new Blob([res.data], { type: 'application/pdf' });

                // Create a URL for the Blob object
                const fileUrl = URL.createObjectURL(fileBlob);

                // Open the PDF in a new window
                window.open(fileUrl, '_blank');
                
            }).catch(err => {
                console.log(err,'errror open pdf')
            })
        },

        delete_pcg(id){
            Swal.fire({
                title: `Are you sure you want to delete this data?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method : "post",
                        url : 'api/delete_pcg',
                        data : {
                            id : id
                        }
                    }).then(res => {
                        console.log('this id',res.data)
                        this.getCheckedTemplates();
                        this.getForApprovalTemplates();
                        this.getApprovedTemplates();
                        this.add_pcg_dialog = false;
                    })
                }else{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Cancelled!',
                        showConfirmButton: false,
                        timer: 1000
                    })
                }
            })
        },

        updateTemplate(){
            this.savingAttachments();
            // this.print_arr = []
            axios({
                method : "post",
                url : 'api/edit_pcg',
                data : {
                    id : this.edit_pcg_obj.id,
                    pcg_method : this.edit_pcg_obj.pcg_method,
                    pcg_type : this.edit_pcg_obj.pcg_type,
                    pcg_category : this.edit_pcg_obj.pcg_category,
                    pcg_code : this.edit_pcg_obj.pcg_code,
                    pcg_reference : this.edit_pcg_obj.pcg_reference,
                    pcg_hrd_memo : this.edit_pcg_obj.pcg_hrd_memo,
                    pcg_reference_2 : this.edit_pcg_obj.pcg_reference_2,
                    pcg_hrd_memo_2 : this.edit_pcg_obj.pcg_hrd_memo_2,
                    pcg_shio : this.edit_pcg_obj.pcg_shio,
                    pcg_reference_3 : this.edit_pcg_obj.pcg_reference_3,
                    pcg_hrd_memo_3 : this.edit_pcg_obj.pcg_hrd_memo_3,
                    pcg_reference_4 : this.edit_pcg_obj.pcg_reference_4,
                    pcg_hrd_memo_4 : this.edit_pcg_obj.pcg_hrd_memo_4,
                    remarks_status : this.edit_pcg_obj.remarks_status,
                    pcg_english : this.edit_pcg_obj.pcg_english,
                    pcg_japanese : this.edit_pcg_obj.pcg_japanese,
                    pcg_translated_by : this.edit_pcg_obj.pcg_translated_by,
                    pcg_incharge_code : this.userInfo.EmployeeCode,
                    pcg_incharge : this.userInfo.EmployeeName,
                    remarks : this.edit_pcg_obj.remarks,
                    files : this.allFiles,
                    gc_attachment : this.edit_pcg_obj.gc_attachment

                }
            }).then(res => {
                console.log('update pcg',res.data);
                this.getCheckedTemplates();
                this.getForApprovalTemplates();
                this.getApprovedTemplates();
                this.add_pcg_dialog = false
                this.fileAttachments.attachmentFile10 = [];
                this.files = [];
                this.allFiles = [];
            })
        },

        savingAttachments(){
            this.allFiles = [...this.files, ...this.files1, ...this.files2, ...this.files3, ...this.files4, ...this.files5, ...this.files6, ...this.files7, ...this.files8, ...this.files9];
            console.log(this.allFiles,'allfile')
            // console.log(lastFile, 'lastFile')
        
        },

        restrictQuotes(event) {
            const key = event.key;
            
            // Check if the pressed key is a single quote or double quote
            if (key === "'" || key === '"') {
                event.preventDefault();
            }
        },

        closeAddDialog(){
            this.add_pcg_dialog = false;
            this.fileAttachments.attachmentFile10 = [];
            // this.remarks_btn = false;
            this.allFiles =  []; 
            this.files =  []; 
            // this.print_arr = [];
            console.log(this.print_arr,'test all file')

        },

        editDialog(item){
            console.log(item,'te');
            if(this.userInfo.admin == 1){
                this.edit_pcg_obj = {...item};
                this.add_pcg_title = "Edit PCG";
                this.add_pcg_dialog = true;
            }
        },

        toggleCheckbox() {
            console.log(this.main_checkbox,'checkbox vlaue')
            if (this.main_checkbox === false) {
                this.main_checkbox = false;
                this.isIndeterminate = false;
                
                this.pcg_templates.forEach(el => {
                    el.print = 0; // Set el.print to 0
                });
                this.checked_templates = [];
            } else if (this.main_checkbox === true) {
                this.main_checkbox = true; // Set to null for indeterminate state
                this.isIndeterminate = false;

                this.checked_templates = []
                this.pcg_templates.forEach(el => {
                    el.print = 1; // Set el.print to 1
                    this.checked_templates.push(el);
                });
            } else {
                this.main_checkbox = false;
                this.isIndeterminate = false;
            }

            console.log(this.checked_templates,'checked tempaltes data')
        },
        
        updatePrintPcg(item){
            const existingItemIndex = this.checked_templates.findIndex(res => res.id === item.id);
            const count = this.pcg_templates.length
            
            if(existingItemIndex !== -1){
                this.checked_templates.splice(existingItemIndex, 1);
                
                console.log(this.checked_templates,'xadsxads splice')
                if(this.checked_templates.length > 0){
                    this.isIndeterminate = true;
                    this.main_checkbox = false;
                }else{
                    this.isIndeterminate = false;
                }
            }else{
                this.checked_templates.push(item);
                const filtered = this.checked_templates.filter(res => {
                    if(res.print == 1){
                        return res;
                    }
                })
                console.log(count,filtered.length,'xadsxads push')
                if(count === filtered.length){
                    this.main_checkbox = true
                    this.isIndeterminate = false;
                    // this.main_checkbox = true
                    // this.toggleCheckbox()
                }else{
                    this.isIndeterminate = true;
                }
            }

            console.log(this.checked_templates,'checked tempaltes data')
        },

        getCheckedTemplates(){
            this.overlay = true
            axios({
                url : 'api/getCheckedTemplates',
                method : 'get'
            }).then(res => {
                this.checked_templates = [];
                this.isIndeterminate = false;
                this.pcg_templates = res.data
                this.main_checkbox = false
                this.isIndeterminate = false
                this.overlay = false;
                console.log(res.data,'getCheckedTemplates() result')
            }).catch(error => {
                console.error('error',error)
            })
        },

        getForApprovalTemplates(){
            axios({
                url : 'api/getForApprovalTemplates',
                method : 'get'
            }).then(res => {
                this.for_approval_templates = res.data
            }).catch(error => {
                console.error('error',error)
            })
        },

        getApprovedTemplates(){
            axios({
                url : 'api/getApprovedTemplates',
                method : 'get'
            }).then(res => {
                this.approved_templates = res.data
            }).catch(error => {
                console.error('error',error)
            })
        },

        copyClipboard(item){
            this.snackbar = true;
            console.log(item,'item to copy')
            const textToCopy = item.pcg_english + '\n' + item.pcg_japanese;

            // Create a temporary textarea element
            const textarea = document.createElement("textarea");
            textarea.value = textToCopy;

            // Append the textarea to the document
            document.body.appendChild(textarea);

            // Select the text in the textarea
            textarea.select();

            // Copy the selected text to the clipboard
            document.execCommand("copy");

            // Remove the temporary textarea
            document.body.removeChild(textarea);

        },

        openDialog(DialogName,itemVal){
            if(DialogName == "serverDialog"){
                this.server_dialog = true;
                this.getFilename(itemVal);
            }
        },

        getFilename(itemVal){
            console.log(itemVal,'isemse')
            axios({
                method : "post",
                url : 'api/getFilename',
                data : {
                    filename : itemVal
                }
            }).then(res => {
                console.log('rer',res.data);
                this.attachment_data = res.data;

            }).catch(err => {
                console.log(err,'error in fetching file')
            })
        },

        removeDynamicPrefix(attachment) {
            if (attachment !== null && attachment !== '') {
                const regex = /^.*?-/;
                return attachment.replace(regex, "");
            } else {
                return '';
            }
        },

        approve_pcg(id){
            // Swal.fire("SweetAlert2 is working!");
            console.log(id,'id')
            const toUpperName = this.userInfo.EmployeeName.toLowerCase().split(' ').map(word => {
                return word.charAt(0).toUpperCase() + word.slice(1);
            }).join(' ');
            let text = "Approve Template?";
            if (confirm(text) == true) {
               axios({
                    method : "post",
                    url : 'api/approve_pcg',
                    data : {
                        id : id,
                        approved_by : toUpperName,
                        reason : null
                    }
                }).then(res => {
                    console.log('this id',res.data)
                    this.getCheckedTemplates();
                    this.getForApprovalTemplates();
                    this.getApprovedTemplates();
                })
            }
           
        },

        disapprove_pcg(id){
            // console.log(id,'id')
            const toUpperName = this.userInfo.EmployeeName.toLowerCase().split(' ').map(word => {
                return word.charAt(0).toUpperCase() + word.slice(1);
            }).join(' ');

            Swal.fire({
                allowOutsideClick : false,
                allowEscapeKey : false,
                input: "textarea",
                inputLabel: "Disapprove Reason: ",
                inputPlaceholder: "Indicate reason.",
                inputAttributes: {
                    "aria-label": "Indicate reason."
                },
                showCancelButton: true,
                confirmButtonText: `
                <span class="mdi mdi-floppy"></span>&nbsp;Save
                `,
                inputValidator: (value) => {
                    if (!value) {
                        return "Reason is needed!";
                    }else{
                        console.log('success boi')
                        axios({
                            method : "post",
                            url : 'api/disapprove_pcg',
                            data : {
                                id : id,
                                disapproved_by : toUpperName,
                                reason : value
                            }
                        }).then(res => {
                            this.checked_templates = []
                            console.log('this id',res.data)
                            this.getCheckedTemplates();
                            this.getForApprovalTemplates();
                            this.getApprovedTemplates();
                        })
                    }
                }
            });
            
        },
        
        approveAll(){
            const toUpperName = this.userInfo.EmployeeName.toLowerCase().split(' ').map(word => {
                return word.charAt(0).toUpperCase() + word.slice(1);
            }).join(' ');
            if(this.checked_templates.length > 0){
                Swal.fire({
                    title: "Approve all templates?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Approve all!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post('api/approveTemplates',{
                            checked_templates : this.checked_templates,
                            approved_by : toUpperName,
                        })
                        .then(res => {
                            console.log(res.data)
                            Swal.fire({
                                title: "Approved!",
                                text: "templates approved.",
                                icon: "success"
                            });
                            this.getCheckedTemplates();
                            this.getForApprovalTemplates();
                            this.getApprovedTemplates();
                        }).catch(error => {
                            console.error('Error',error);
                        })
                    
                    }
                });
            }else{
                alert("nothing to approve")
            }
           
        },

       
    },
}
</script>