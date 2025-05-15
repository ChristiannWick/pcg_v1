<template>
    <div >
        <v-card outlined elevation="3" style="margin:6px; ">
            <v-row dense style="padding:8px;" >
                <v-col cols="2">
                    <v-select
                        hide-details=""
                        dense
                        label="Method:"
                        outlined
                        :items="method_items"
                        item-text="pcg_method"
                        item-value="pcg_method"
                        v-model="selected_method.pcg_method"
                        @change="getTemplates()"
                    >
                    </v-select>
                </v-col>
                <v-col cols="2">
                    <v-select
                        hide-details=""
                        dense
                        label="PCG Type:"
                        outlined
                        :items="pcgType_items"
                        item-text="pcg_type"
                        item-value="pcg_type"
                        v-model="selected_pcgType.pcg_type"
                        @change="getTemplates()"
                    >
                    </v-select>
                </v-col>
                <v-col cols="3">
                    <v-select
                        hide-details=""
                        dense
                        label="Category:"
                        outlined
                        :items="carte_categories"
                        item-text="category"
                        item-value="category"
                        v-model="selected_category.category"
                        @change="getTemplates()"
                    >
                    </v-select>
                </v-col>
                <v-col cols="3">
                    <v-select
                        hide-details=""
                        dense
                        label="Team :"
                        outlined
                        :items="team_items"
                        item-text="name"
                        item-value="pcg_team_code"
                        v-model="selected_team.pcg_team_code"
                        @change="getTemplates()"
                    >
                    </v-select>
                </v-col>
                <v-col cols="2" >
                    <v-select
                        hide-details=""
                        dense
                        label="Status :"
                        outlined
                        :items="status_items"
                        item-text="name"
                        item-value="status"
                        v-model="selected_status.status"
                        @change="getTemplates()"
                    >
                    </v-select>
                </v-col>
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
                            @change="getTemplates()"
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
                            @change="getTemplates()"
                        ></v-date-picker>
                    </v-menu>
                </v-col>
                <v-col cols="3">
                    <!-- <div class="search-container">
                        <v-text-field
                            ref="searchField"
                            clearable
                            v-model="search"
                            prepend-inner-icon="mdi-magnify"
                            hide-details=""
                            dense
                            outlined
                            placeholder="Search here..."
                            @click:clear="resetSearch"
                            @input="handleTyping"
                            @focus="showHistory = true"
                            @blur="hideHistory"
                            @keydown.esc.stop="hideHistory"
                        ></v-text-field>

                        <v-card 
                            v-if="showHistory && searchHistory.length" 
                            class="history-dropdown">
                            <v-list dense>
                                <v-subheader>Recent Searches</v-subheader>
                                <v-list-item
                                    v-for="(item, index) in searchHistory"
                                    :key="index"
                                    @click="applyHistoryItem(item)"
                                    >
                                    <v-list-item-avatar>
                                        <v-icon>mdi-history</v-icon>
                                    </v-list-item-avatar>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ item }}</v-list-item-title>
                                    </v-list-item-content>
                                    <v-list-item-action>
                                        <v-btn 
                                            @mousedown.prevent
                                            icon 
                                            @click.stop="removeHistoryItem($event, index)">
                                            <v-icon small>mdi-close</v-icon>
                                        </v-btn>
                                    </v-list-item-action>
                                </v-list-item>
                            </v-list>
                        </v-card>
                    </div> -->
                    <search-with-history
                        v-model="search"
                        storage-key="componentASearchHistory"
                        @search="handleSearch1"
                    ></search-with-history>
                </v-col>
                <v-col cols="4">
                    <div style="margin:2px; float:right; display: flex; justify-content: space-between; align-items: center; height:100%; " >
                        <!-- <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn 
                                    icon
                                    v-bind="attrs"
                                    v-on="on" 
                                    color="#1565C0" 
                                    dark
                                    dense>
                                    <v-icon @click="$refs.childRef.printPCG('view')" >mdi-eye-check-outline</v-icon>
                                </v-btn> 
                            </template>
                            <span>Preview PDF</span>
                        </v-tooltip> -->
                        <v-btn 
                            class="mx-1"
                            title="common terms"
                            outlined
                            small 
                            @click="common_terms_dialog = !common_terms_dialog"
                            color="#0096fa" 
                            >
                            <v-icon>mdi-book-open-page-variant</v-icon>
                        </v-btn>
                        <v-dialog
                            v-model="add_pcg_dialog"
                            persistent
                            max-width="900"
                            >
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                class="mx-1"
                                v-if="userInfo.admin > 0"
                                @click="openDialog('addDialog')"
                                color="primary"
                                outlined
                                small
                                v-bind="attrs"
                                v-on="on"
                                >
                                Add
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title style="margin-bottom:8px; background:rgb(84,110,122);">
                                    <span class="text-h5" style="color:white;">{{add_pcg_title}}</span>
                                </v-card-title>
                                <v-card-text>
                                    <v-row dense >
                                        <v-col cols="12" md="3" >
                                            <v-autocomplete
                                                v-model="edit_pcg_obj.pcg_method"
                                                :items= "add_method_arr"
                                                item-text="pcg_method"
                                                item-value="pcg_method"
                                                dense
                                                filled
                                                outlined
                                                label="Method :"
                                                hide-details 
                                            ></v-autocomplete>
                                        </v-col>
                                        <v-col cols="12" md="3" >
                                            <v-autocomplete
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
                                            ></v-autocomplete>
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
                                        <v-btn outlined @click="reference_dropdown = !reference_dropdown;" block class="mb-1">
                                            References 
                                            <v-icon v-if="reference_dropdown==true" small>mdi-arrow-down-drop-circle-outline</v-icon>
                                            <v-icon v-else small>mdi-arrow-right-drop-circle-outline</v-icon>
                                        </v-btn>
                                        <div v-if="reference_dropdown==true" >
                                            <v-container fluid>
                                                <v-row dense>
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
                                                <v-row dense>
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
                                                <v-row dense>
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
                                                :rules="nameRulesEnglish"
                                                v-model="edit_pcg_obj.pcg_english"
                                                rows="5"
                                                outlined
                                                background-color="grey lighten-2"
                                                name="input-7-1"
                                                label="English:"
                                                @keydown="restrictQuotes"
                                            ></v-textarea>
                                        </v-col>
                                        <v-col cols="12" sm="6" >
                                            <v-textarea
                                                :rules="nameRulesJapanese"
                                                v-model="edit_pcg_obj.pcg_japanese"
                                                rows="5"
                                                outlined
                                                background-color="grey lighten-2"
                                                name="input-7-1"
                                                label="Japanese:"
                                                @keydown="restrictQuotes"
                                            ></v-textarea>
                                        </v-col>
                                        <!-- <v-col cols="12" md="4" >
                                            <v-autocomplete
                                                dense
                                                filled
                                                outlined
                                                label="Actions :"
                                                hide-details 
                                                :items="action_items"
                                                item-text="english"
                                                item-value="action_id"
                                                v-model="edit_pcg_obj.action_id"
                                            ></v-autocomplete>
                                        </v-col> -->
                                        <!-- <v-col cols="12" md="12" >
                                            <v-autocomplete
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
                                            ></v-autocomplete>
                                        </v-col> -->
                                        <v-col cols="12" sm="6" >
                                            <v-autocomplete
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
                                            ></v-autocomplete>
                                        </v-col>
                                        <!-- <v-col cols="12" sm="2">
                                            <v-checkbox
                                                @click="add_checkbox == false?add_textfield='':add_textfield"
                                                v-model="add_checkbox"
                                                style="margin-top: 5px;"
                                                label="Stop"
                                                hide-details
                                            >{{registerPCG}}</v-checkbox>
                                        </v-col>   
                                        <v-col cols="12" sm="4">
                                            <v-text-field
                                                v-model="add_textfield"
                                                :disabled="add_checkbox == true?false:true"
                                                dense
                                                outlined
                                                hide-details                                         
                                            ></v-text-field>
                                        </v-col>   -->
                                        <!-- <v-col v-show="add_pcg_title == 'Edit PCG'" cols="12" sm="4">
                                            <v-autocomplete
                                                v-model="edit_pcg_obj.checked_by"
                                                :items= "pcgMonitoring_data"
                                                item-text="checked_by"
                                                item-value="checked_by"
                                                style="width:300px;"
                                                dense
                                                filled
                                                outlined
                                                label="Checked By :"
                                                hide-details 
                                            ></v-autocomplete>
                                        </v-col>  -->
                                        <!-- <div v-show="add_pcg_title == 'Add PCG' && remarks_btn == false"> -->
                                        <!-- <div >
                                            <v-btn @click="remarks_btn = true, edit_pcg_obj.remarks = 'With Reference'" color="green" outlined>With Reference</v-btn>
                                            <v-btn @click="remarks_btn = true, edit_pcg_obj.remarks = 'Without Reference'" color="green" outlined>Without Reference</v-btn>
                                            <v-btn @click="remarks_btn = true, edit_pcg_obj.remarks = 'Asked to Technical'" color="green" outlined>Asked to Technical</v-btn>
                                        </div> -->
                                    </v-row>
                                <!-- <small>*indicates required field</small> -->
                                </v-card-text>
                                <v-divider></v-divider>
                                <v-card-actions>
                                    <!-- <div v-if="add_pcg_title == 'Edit PCG'">
                                        <v-btn small color="blue lighten-1" @click="savePrintPcg(edit_pcg_obj)">
                                            Use
                                        </v-btn>
                                    </div> -->
                                    <div v-if="(userInfo.admin == 1 && add_pcg_title == 'Edit PCG') || userInfo.EmployeeCode==edit_pcg_obj.pcg_incharge_code">
                                        <v-btn small color="teal lighten-3 ml-2" @click="updateTemplate()">
                                            Save
                                        </v-btn>
                                        <v-btn dark small color="green darken-1" @click="checkPcg()">
                                            Check
                                        </v-btn>
                                    </div>
                                    <div v-if="userInfo.admin == 1 && add_pcg_title == 'Edit PCG'">
                                        <v-btn small color="teal lighten-3 ml-5" @click="resetPcgApproveStatus()">
                                            Reset
                                        </v-btn>
                                        <v-btn dark small color="rgb(0,128,0)" @click="approvePcg()">
                                            Approve
                                        </v-btn>
                                        <v-btn dark small color="orange darken-1" @click="disapprove_dialog = true">
                                            Disapprove
                                        </v-btn>
                                    </div>
                                    <v-btn small v-if="(userInfo.admin == 1 && add_pcg_title == 'Edit PCG') || userInfo.EmployeeCode==edit_pcg_obj.pcg_incharge_code"
                                        color="red lighten-1 ml-2"
                                        @click="delete_pcg()">
                                        Delete
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn small v-if="add_pcg_title == 'Add PCG'"
                                        color="blue darken-1"
                                        class="white--text"
                                        @click="registerPCG()"
                                    >
                                        Register PCG
                                    </v-btn>
                                    <v-btn small
                                        color="red darken-1"
                                        class="white--text"
                                        @click="closeAddDialog()"
                                    >
                                        Close
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-btn 
                            class="mx-1"
                            title="cart"
                            outlined
                            small 
                            @click="openDialog('useDialog')" 
                            color="#E32636" 
                            >
                            <v-icon>mdi-cart</v-icon>
                        </v-btn>
                        <v-btn 
                            class="mx-1"
                            title="export excel"
                            outlined
                            small
                            @click="exportExcel()" 
                            color="green"  
                            >
                            <v-icon>mdi-file-excel</v-icon>
                        </v-btn>
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn 
                                    class="mx-1"
                                    dark
                                    small
                                    color="#1565C0" 
                                    @click="refreshData()" 
                                    v-bind="attrs"
                                    v-on="on" 
                                    >
                                    <v-icon>mdi-refresh</v-icon>
                                </v-btn> 
                            </template>
                            <span>Refresh Data</span>
                        </v-tooltip>
                    </div>
                </v-col>
                <v-col cols="1">
                  
                    <div class="blink_me">
                        <v-chip x-small color="red" style="font-size:13px; color:white;">{{getnotif_val}}</v-chip>
                        <span style="font-size:10px; display:block; color:red;">For Checking</span>
                    </div>
               
                </v-col>
            </v-row>
        </v-card>
        
        <v-card outlined elevation="3" style="margin:6px;">
            <v-simple-table ref="pcgTable" fixed-header height="650" >
                <thead class="monitoring-header">
                    <tr> 
                        <th class="text-left ">PCG Details</th>
                        <th class="text-center ">English</th>
                        <th class="text-center ">Japanese</th>
                        <th class="text-center ">Attachment</th>
                        <th class="text-center ">In-Charge</th>
                        <th class="text-center ">Reason</th>
                        <th class="text-center ">to print</th> 
                        <th class="text-center ">Freq Used</th>
                    </tr>
                </thead>
                <tbody v-if="pcgMonitoring_data.length > 0" style="text-align:center;" >
                    <tr 
                    @dblclick="editDialog(item)"
                    v-for="(item, index) in nextPage" :key="index" 
                    :style="item.approve_status != '3' || userInfo.admin == 1 ? 'cursor:pointer':'cursor:not-allowed; background-color:#d9d7d7; '"
                    >
                        <td style="text-align:left !important; padding:2px 10px; width:200px;">
                            <span style="display:block; color:rgb(128,0,0); font-size:16px; font-weight:bold;">{{item.pcg_category}}</span>
                            <span style="display:block;">
                                <v-chip v-if="item.approve_status === '2'" rounded x-small color="primary">{{item.pcg_code}}
                                    <!-- <v-icon x-small>mdi-content-copy</v-icon> -->
                                </v-chip> 
                                <v-tooltip color="primary" right>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn outlined icon v-bind="attrs" v-on="on" @click="copyClipboard(item,'language')" x-small color="primary">
                                            <v-icon x-small>mdi-content-copy</v-icon>
                                        </v-btn>
                                    </template>
                                    <span>Copy to Clipboard</span>
                                </v-tooltip>
                            </span>
                            <!-- <span style="display:block;">Date: {{item.checked_at != null ? item.checked_at : item.pcg_date}}</span> -->
                            <span style="display:block;">Date: {{item.pcg_date}}</span>
                            <!-- <span style="display:block;">Date: {{item.checked_at}}</span> -->
                            <span style="display:block; ">Method: <span style="color:red;">{{item.pcg_method}}</span></span>
                            <v-chip small :color= "item.approve_status == '1' ? 'red' : item.approve_status == '3' ? 'orange':'rgb(0,128,0)'">
                                <span style="color:white;">{{item.approve_status=='1'?'Waiting for Approval...':item.approve_status == '3' ? 'Disapproved By: ' + item.disapproved_by : 'Approved By: ' + item.approved_by}}</span>
                            </v-chip>
                            <div v-if="item.checked_by != null && item.checked_by != '' ">
                                <v-chip small color="#32CD32" style="margin-top:2px;">
                                    <span style="color:white;">Checked By: {{item.checked_by}}</span> 
                                </v-chip>
                            </div>
                        </td>
                        <td >
                            <span >{{ item.pcg_english }}</span>
                        </td>
                        <td >
                            <span style="display:block;">{{item.pcg_japanese}}</span>
                            <span style="font-style:italic; font-size:12px; color:rgb(0,128,0);">-Translated by: </span>
                            <span style="color:rgb(0,128,81); ">
                                {{item.pcg_translated_by}}
                            </span>
                        </td>
                        <td style="text-align:left !important; overflow:overlay; ">
                            <div style="width: 110px;">
                                <div v-if="item.pcg_reference != null && item.pcg_reference != ''">
                                    <i style="text-decoration:underline;">Reference (CN/IAR):</i><br>
                                    <v-tooltip bottom color="primary">
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn @click="openDialog('serverDialog',item,'pcg_reference')" v-bind="attrs" v-on="on" outlined text small style="color:blue;">
                                            <span style="display: block; width: 100px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">{{item.pcg_reference}}</span>
                                            <v-icon
                                                right
                                                small
                                                dark
                                                @click.stop="copyClipboard(item,'pcg_reference')"
                                                >
                                                mdi-content-copy
                                            </v-icon>
                                        </v-btn>
                                    </template>
                                    <span>{{item.pcg_reference}}</span>
                                    </v-tooltip>
                                </div>
                                <div v-if="item.pcg_reference_2 != null || item.pcg_reference_3 != null ">
                                    <i style="text-decoration:underline;">Rulebook:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'pcg_reference_2')" v-if="item.pcg_reference_2 != null" outlined text small style="color:blue;" >{{item.pcg_reference_2}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_reference_2')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                    <v-btn class="my-1" @click="openDialog('serverDialog',item,'pcg_reference_3')" v-if="item.pcg_reference_3 != null" outlined text small style="color:blue;" >{{item.pcg_reference_3}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_reference_3')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div v-if="item.pcg_reference_4 != null && item.pcg_reference_4 != '' ">
                                    <i style="text-decoration:underline;">GC Tsutatsu:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'pcg_reference_4')" outlined text small style="color:blue;">{{item.pcg_reference_4}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_reference_4')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div v-if="item.pcg_hrd_memo != null && item.pcg_hrd_memo != '' ">
                                    <i style="text-decoration:underline;">Tei Betsu Manual:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'pcg_hrd_memo')" outlined text small style="color:blue;">{{item.pcg_hrd_memo}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_hrd_memo')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div v-if="item.pcg_hrd_memo_2 != null && item.pcg_hrd_memo_2 != '' ">
                                    <i style="text-decoration:underline;">Shio Manual:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'pcg_hrd_memo_2')" outlined text small style="color:blue;">{{item.pcg_hrd_memo_2}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_hrd_memo_2')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div v-if="item.pcg_hrd_memo_3 != null && item.pcg_hrd_memo_3 != '' ">
                                    <i style="text-decoration:underline;">Renraku Hyou:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'pcg_hrd_memo_3')" outlined text small style="color:blue;">{{item.pcg_hrd_memo_3}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_hrd_memo_3')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div v-if="item.pcg_hrd_memo_4 != null && item.pcg_hrd_memo_4 != '' ">
                                    <i style="text-decoration:underline;">Tsutatsu:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'pcg_hrd_memo_4')" outlined text small style="color:blue;">{{item.pcg_hrd_memo_4}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_hrd_memo_4')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div v-if="item.pcg_shio != null && item.pcg_shio != ''  ">
                                    <i style="text-decoration:underline;">Shio Manual:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'pcg_shio')" outlined text small style="color:blue;">{{item.pcg_shio}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'pcg_shio')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                <div v-if="item.gc_attachment != null && item.gc_attachment != ''  ">
                                    <i style="text-decoration:underline;">GC Attachments:</i><br>
                                    <v-btn @click="openDialog('serverDialog',item,'gc_attachment')" outlined text small style="color:blue;">{{removeDynamicPrefix(item.gc_attachment)}}
                                        <v-icon
                                            right
                                            small
                                            dark
                                            @click.stop="copyClipboard(item,'gc_attachment')"
                                            >
                                            mdi-content-copy
                                        </v-icon>
                                    </v-btn>
                                </div>
                                
                            </div>
                        </td>
                        <!-- <td>
                            <span>{{item.remarks_status}}</span>
                        </td> -->
                        <td>
                            <div style="vertical-align:middle; ">
                                <div style="color:rgb(128,0,0); font-size:12px;">{{item.pcg_incharge}}</div>
                                <div style="color:orange; font-size:12px;">{{item.team_name}}</div>
                                <v-chip small dark :style="{'background-color': item.remarks === 'With Reference' ? 'rgb(94, 152, 131)' : 'grey'}"   >
                                    {{item.remarks}}
                                </v-chip>
                            </div>
                            
                        </td>
                        <td>
                            <span >{{ item.approve_reason }}</span>
                        </td>
                        <td class="text-center">
                            <v-btn
                                :disabled="item.approve_status === '3' || !item.checked_by"
                                v-if="!print_arr.find(printedItem => printedItem.pcg_id === item.id)"
                                @click="savePrintPcg(item)"
                                icon
                                color="primary"
                                title="Insert Template"
                                >
                                <v-icon>mdi-plus-circle</v-icon>
                            </v-btn>
                        </td>
                        <td>
                            <v-chip small color="green" dark>
                                <v-icon x-small>mdi-account</v-icon>{{item.pcg_counter}}
                            </v-chip>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <td colspan="8" style="text-align:center; font-size:16px;font-weight:bold;">
                        <v-icon color="red">mdi-alert-circle</v-icon> No Records found!
                    </td>
                </tbody>
            </v-simple-table>
            <!-- <div class="d-flex align-center">
                <div class="mr-2 ml-2" style="font-size: 14px; color: #424242; margin-top:8px; ">
                Plans per page:
                </div>
                <div>
                    <v-select
                        class="test"
                        v-model="selected_no_of_page"
                        style="width: 65px; border:1px solid #2196f3; border-radius: 4px; "
                        hide-details
                        dense
                        :items="no_of_pages"
                    ></v-select>
                </div>
                <div class="ml-2 ">
                    <b>|</b>
                </div>
                <div class="ml-2 mr-2" style="font-size: 14px; color: #424242; margin-top:8px;">
                Total of <b>{{ Number(searchRecords.length).toLocaleString() }}</b> record(s)
                </div>
            </div>
            <div class="text-center" >
                <v-pagination
                    v-model="page"
                    color="#5C5CFF"
                    :length="pages"
                    :total-visible="6">
                </v-pagination>
            </div> -->
            <div class="d-flex justify-between align-center">
                <div class="d-flex align-center"> <!-- Container for "Plans per page" and select -->
                    <div class="mr-2 ml-2" style="font-size: 14px; color: #424242; margin-top: 8px;">
                        Templates per page:
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
            v-model="server_dialog"
            width="700px"
            >
            <v-card >
                <v-card-title class="indigo accent-2" >
                    <span class="text-h5" style="color:white;">Server</span>
                </v-card-title>
                <v-simple-table fixed-header height="300px">
                    <thead>
                        <tr>
                            <th class="text-center" style="font-size:16px; width:200px; color:#1976d2;">Filename</th>
                            <th class="text-center" style="font-size:16px; width:350px; color:#1976d2;">Server Path</th>
                            <th class="text-center" style="font-size:16px; color:#1976d2;">Action</th>
                        </tr>
                    </thead>
                    <tbody v-if="loading" style="text-align:center;">
                        <tr>
                            <td colspan="3" rowspan="8" style="text-align:center; font-size:16px;font-weight:bold;">
                                <v-progress-circular indeterminate color="red"></v-progress-circular>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else-if="attachment_data.length > 0" style="text-align:center;" >
                        <tr v-for="(item,index) in attachment_data" :key="index">
                            <td style="text-align:center !important; padding:2px 10px; width:200px;">
                                <span style="display:block; color:rgb(128,0,0); color:blue">{{item.filename}}</span>
                            </td>
                            <td style="width:350px;">
                                <span >{{item.directory}}</span>
                            </td>
                            <td style="display: flex; justify-content:center; align-items: center;">
                                <v-btn tile x-small color="success" @click="openPDF(item)">View</v-btn>
                                <v-checkbox
                                    v-if="attachment_data.length > 1 && userInfo.EmployeeCode == item.pcg_incharge_code"
                                    class="mb-2 ml-2"
                                    v-model="attachment_checkbox"
                                    :value="item"
                                    dense
                                    hide-details=""
                                    @click="UpdateTemplateAttachment()"
                                ></v-checkbox>
                                <!-- <v-btn href="php/download.php?path=%5C%5Chrdinfsv%5Cinformation%5Cmanual%5C%E3%83%AB%E3%83%BC%E3%83%AB%E3%83%96%E3%83%83%E3%82%AF&item=220713-21.pdf" target="_blank" tile x-small color="success">View</v-btn> -->
                            </td>
                            
                        </tr>
                    </tbody>
                    <tbody style="text-align:center;" v-else>
                        <tr>
                            <td colspan="3" style="text-align:center; font-size:16px;font-weight:bold;">
                                <v-icon color="red">mdi-alert-circle</v-icon> No Records found!
                            </td>
                        </tr>
                    </tbody>
                </v-simple-table>
            </v-card>
        </v-dialog>
        <use-dialog 
            ref="childRef"
            @closeDialog = "closeDialog" 
            :use_dialog = "use_dialog" 
            :employee_id = "userInfo.EmployeeCode"
            :edit_pcg_obj = "edit_pcg_obj"
            :print_arr = "print_arr"
            :team_code = "userInfo.TeamCode"
            @getPrintPcg ="getPrintPcg"
        ></use-dialog>

        <v-dialog 
            v-model="disapprove_dialog"
            persistent
            max-width="400"
            >
            <v-card>
                <v-card-title style="padding:5px;">
                    {{disapprove_title}}
                    <v-spacer></v-spacer>
                    <v-btn small @click="disapprove_dialog = false, approve_reason =''" color="red">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text style="padding:5px;">
                    <v-textarea
                        filled
                        auto-grow
                        v-model="approve_reason"
                        hide-details
                        background-color="grey lighten-2"
                        name="input-7-1"
                    ></v-textarea>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn small @click="disapprovePcg()" color="primary">
                        save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="common_terms_dialog">
            <CommonTerms v-if="common_terms_dialog == true" ref="child" />
        </v-dialog>


        <v-overlay :value="add_print_overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
        <v-overlay :value="gen_overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
        <v-overlay opacity="0" :value="add_print_overlay2" absolute>
            <v-alert    
                border="left"
                type="success"
                color="green"
                prominent
                dense
                >
                template inserted!
            </v-alert>
        </v-overlay>
        
        
    </div>
</template>

<script>

import axios from 'axios';
import { mapState, mapActions } from "vuex";
import moment from 'moment';
import UseDialog from './UseDialog.vue'
import Swal from 'sweetalert2';
import CommonTerms from './CommonTerms.vue';
import SearchWithHistory from './SearchHistory.vue';

    export default {
        components: {
            UseDialog,
            CommonTerms,
            SearchWithHistory
        },
        data() {
            return {
                 searchHistory: [],
                showHistory: false,
                typingTimer: null,
                common_terms_dialog:false,
                add_print_overlay2: false,
                loading: false,
                use_dialog : false,
                server_dialog : false,
                page : 1,
                search : '',
                selected_method: { pcg_method: 'All' },
                selected_pcgType: { pcg_type: 'All' },
                selected_category: { category: 'All', code: 'All' },
                selected_team: {  pcg_team_code: 1 },
                selected_status: { name: 'All', status: 'All' },
                status_items : [
                    { name: 'All', status: 'All' },
                    { name: 'For Checking', status: 'FC' },
                    { name: 'Checked', status: 'C' },
                    { name: 'Approved', status: 'A' },
                    { name: 'Disapproved', status: 'D' },
                    { name: 'Freq Used', status: 'FU' },
                ],
                pcg_category_add_items :[],
                remark_items : [
                    // { remarks_status: '', name: '' },
                    // { remarks_status: 'NG(Structural NG, Cause TH)', name: 'NG' },
                    // { remarks_status: '非推奨(Possible but not Recommended)', name: '非推奨' },
                    // { remarks_status: '注意喚起(Information Only)', name: '注意喚起' },
                    // { remarks_status: '>HRDミス(HRD Mistake)', name: 'HRDミス' },
                    // { remarks_status: 'Special Case Only', name: 'Special Case Only' },

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
                date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
                dateFrom : null,
                dateTo : null,
                modal: false,
                menu1: false,
                menu2: false,
                pcgMonitoring_data : [],
                getnotif_val : null,
                attachment_data : [],
                add_pcg_dialog: false,
                add_pcg_title: "Add PCG",
                edit_pcg_obj : {},
                add_checkbox : false,
                add_textfield : "",
                reference_dropdown : false,
                remarks_btn : false,
                add_pcg_remarks : null,
                add_pcg_incharge  : null,
                checkbox : null,
                print_arr : [],
                disapprove_title : 'Disapprove Reason:',
                approve_reason : "",
                disapprove_dialog : false,

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

                add_print_overlay: false,
                gen_overlay: false,
                currentDate : new Date(),
                no_of_pages: [50, 100, 150, 200],
                selected_no_of_page : 50,
                nameRulesEnglish: [
                    v => {
                        const japaneseChars = /[\u3040-\u309F\u30A0-\u30FF\uFF00-\uFF9F\u4E00-\u9FFF]/;
                        if (japaneseChars.test(v)) {
                            return 'This field requires characters in English.';
                        }
                        return true;
                    },
                ],
                nameRulesJapanese: [
                    v => {
                        const englishChars = /^[a-zA-Z0-9]+$/;
                        // const japaneseChars = /[\u3000-\u303f\u3040-\u309f\u30a0-\u30ff\uff00-\uff9f\u4e00-\u9faf\u3400-\u4dbf]/;
                        // if (!japaneseChars.test(v)) {
                        //     return 'This field requires characters in Japanese.';
                        // }
                        // return true;
                        const japaneseChars = /[\u3000-\u303f\u3040-\u309f\u30a0-\u30ff\uff00-\uff9f\u4e00-\u9faf\u3400-\u4dbf\,\u3001\u3002\u30fb\u201c\u201d\s]/;
                        if (!v) {
                            return true;
                        }
                        for (let i = 0; i < v.length; i++) {
                            if (!japaneseChars.test(v[i])) {
                                return 'This field requires characters in Japanese.';
                            }
                        }
                        return true;
                    },
                ],
                attachment_checkbox: []
                
            }
        },

        created() {
            const history = localStorage.getItem('searchHistory');
            this.searchHistory = history ? JSON.parse(history) : [];
        },

        beforeDestroy() {
            clearTimeout(this.typingTimer);
        },

        methods: {
            ...mapActions([
                "storeUserInfo",
                "getMethods",
                "getPcgtypes",
                "getCategories",
                "getTeams",
                "getUsers",
                "getActions",
                'getVersions',
                "resetTableScroll"
            ]),

            handleSearch1(query) {
            // Your search implementation for Component A
                console.log('Component A searching:', query);
            },

            handleTyping() {
                clearTimeout(this.typingTimer);
                if (this.search) {
                    this.typingTimer = setTimeout(() => {
                    this.saveSearch(this.search.trim());
                    }, 2000); // Save after 2 seconds of inactivity
                }
            },

            saveSearch(query) {
                if (!query) return;
                
                // Update history without duplicates
                this.searchHistory = [
                    query,
                    ...this.searchHistory.filter(item => item !== query)
                ].slice(0, 10); // Keep only 10 most recent

                localStorage.setItem('searchHistory', JSON.stringify(this.searchHistory));
                this.executeSearch(query);
            },

            resetSearch() {
                this.search = '';
                clearTimeout(this.typingTimer);
                this.showHistory = true;
            },

            applyHistoryItem(item) {
                this.search = item;
                this.executeSearch(item);
                this.showHistory = false;
            },

            // removeHistoryItem(index) {
            //     this.searchHistory.splice(index, 1);
            //     localStorage.setItem('searchHistory', JSON.stringify(this.searchHistory));
            // },
            removeHistoryItem(event, index) {
                event?.preventDefault(); // Safe check for event
                event?.stopPropagation();
                
                // Create new array to trigger reactivity
                this.searchHistory = this.searchHistory.filter((_, i) => i !== index);
                localStorage.setItem('searchHistory', JSON.stringify(this.searchHistory));
                
                // Keep dropdown open
                this.showHistory = true;
                
                // Maintain focus
                this.$nextTick(() => {
                    if (this.$refs.searchField) {
                        this.$refs.searchField.focus();
                    }
                    // this.$refs.searchField?.focus();
                });
            },

            executeSearch(query) {
                console.log('Searching:', query);
                // Your search implementation here
            },

            hideHistory() {
                setTimeout(() => {
                    this.showHistory = false;
                }, 200);
            },

            async handlePageChange(currPage) {
                console.log(currPage,'current page')                
                // Call the Vuex action
                await this.resetTableScroll(this.$refs.pcgTable);
                
                // Any other logic after scroll reset
            },

            // updateAdminValue(){
            //     let obj = {...this.userInfo};

            //     const user = this.carte_users.filter(item => {
            //         return item.EmployeeCode == this.userInfo.EmployeeCode
            //     })
            //     if(user.length > 0){
            //         obj.admin = user[0].admin
            //     }
            //     this.storeUserInfo(obj)
            //     console.log(user,'user')
            // },

            UpdateTemplateAttachment(){
                console.log(this.attachment_checkbox,'attachment_checkbox')
                if(this.attachment_checkbox){
                    const filenameWithoutExtension = this.attachment_checkbox[0].filename.replace(/\.pdf$/, '');
                    axios.post('api/edit_pcg_attachment',{
                        id : this.attachment_checkbox[0].id,
                        column_name : this.attachment_checkbox[0].column_name,
                        filename : filenameWithoutExtension,
                    }).then(res => {
                        console.log(res.data,'result of update')
                        this.attachment_checkbox = []
                        this.server_dialog = false
                        this.getTemplates();
                        this.getPrintPcg();
                    }).catch(error => {
                        console.error('Error updating template:', error);
                    });
                }
                
            },

            savePrintPcg(item){
                item.user = this.userInfo.EmployeeCode
                console.log(item,'insert personal template')
               
                axios({
                    method : "post",
                    url : 'api/insert_print',
                    data : item
                }).then(res => {
                    console.log(res.data,'insert success');
                    // this.print_arr = res.data;
                    this.getPrintPcg();
                    this.add_pcg_dialog = false;
                    this.add_print_overlay2 = true;
                    // Swal.fire({
                    //     position: "bottom-end",
                    //     icon: "success",
                    //     title: "template inserted!",
                    //     showConfirmButton: false,
                    //     timer: 1000
                    // });
                }).catch(err => {
                    console.log(err,'savePrintPcg() error contact ihs')
                })
               
            },

            getPrintPcg(){
                console.log(this.cart_search,'cart search')
                axios({
                    method: "post",
                    url: 'api/get_print',
                    data: {
                        EmployeeCode : this.userInfo.EmployeeCode,
                        cart_search : this.cart_search,
                    }
                }).then(res =>{
                    // console.log(res.data,'getPrintPcg')
                    this.print_arr = res.data
                }).catch(err => {
                    console.log(err,'error');
                })
            },

            copyClipboard(item,value){
                console.log(item,'item to copy')
                let textToCopy = ''
                if(value === 'language'){
                    textToCopy = item.pcg_english + '\n' + item.pcg_japanese;
                }else{
                    textToCopy = item[value]
                }
                
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

                // You can also show a message indicating the text has been copied if needed
                Swal.fire({
                    toast:true,
                    position: "center",
                    icon: "success",
                    title: "Text copied to clipboard",
                    showConfirmButton: false,
                    timer: 1500
                });
            },

            removeDynamicPrefix(attachment) {
                // Use a regular expression to remove the dynamic prefix
                // const dynamicPrefix = this.extractDynamicPrefix(attachment);
                // const regex = new RegExp(`^${dynamicPrefix}`);
                // return attachment.replace(regex, "");
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

            closeAddDialog(){
                this.add_pcg_dialog = false;
                this.fileAttachments.attachmentFile10 = [];
                // this.remarks_btn = false;
                this.allFiles =  []; 
                this.files =  []; 
                // this.print_arr = [];
                console.log(this.print_arr,'test all file')

            },

            restrictQuotes(event) {
                const key = event.key;
                
                // Check if the pressed key is a single quote or double quote
                if (key === "'" || key === '"') {
                    event.preventDefault();
                }
            },
        
            filterQuotes() {
                // Filter out single quotes and double quotes
                this.edit_pcg_obj.pcg_english = this.edit_pcg_obj.pcg_english.replace(/['"]/g, '');
                this.edit_pcg_obj.pcg_japanese = this.edit_pcg_obj.pcg_japanese.replace(/['"]/g, '');
            },

            getRowClass(item) {
                return item.approve_status === "3" ? "disapproved-row" : "";
            },

            exportExcel(){
                console.log(this.searchRecords,'searchrecords')
                this.gen_overlay = true
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
                        url : 'monitoring_excel',
                        // data : this.searchRecords,
                        data : form,
                        responseType: 'blob'
                    }).then(res => {
                        console.log(res.data,'ressesw')
                        const url = window.URL.createObjectURL(new Blob([res.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `PCG-${formattedDate}.xls`); 
                        document.body.appendChild(link);
                        link.click();
                        this.gen_overlay = false
                    })
                }else{
                    // alert('no data');
                    this.gen_overlay = false;
                }
                
            },

            fileChange(tempVariable, category, vmod){
                console.log(vmod,'vmod')
                this[vmod] = [];
                console.log(this[vmod],'filechange')
                    const readFile = (file) => {
                        return new Promise((resolve, reject) => {
                            const reader = new FileReader();
                            reader.onload = () => resolve(reader.result);
                            reader.onerror = error => reject(error);
                            reader.readAsArrayBuffer(file);
                        });
                    }
                tempVariable.forEach(async (item) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(item);
                    reader.onloadend = () => {
                        this[vmod].push({
                            data: reader.result,
                            name: item.name,
                            column_name: category,
                            size : item.size
                        });
                    };
                })
                        
            },

            savingAttachments(){
                this.allFiles = [...this.files, ...this.files1, ...this.files2, ...this.files3, ...this.files4, ...this.files5, ...this.files6, ...this.files7, ...this.files8, ...this.files9];
                console.log(this.allFiles,'allfile')
                // console.log(lastFile, 'lastFile')
            
            },

            insert_print_data(item){
                item.user = this.userInfo.EmployeeCode
                this.add_print_overlay = !this.add_print_overlay
                const existingItemIndex = this.print_arr.findIndex(res => res.id === item.id);
                if(existingItemIndex !== -1){
                    this.print_arr.splice(existingItemIndex, 1);
                }else{
                    this.print_arr.push(item);
                }

                // console.log(this.print_arr,'add to cart array');
                
                // axios({
                //     method : "post",
                //     url : 'api/insert_print',
                //     data : item
                // }).then(res => {
                //     console.log(res.data,'rere');
                //     this.print_arr = [...res.data];
                // })
            },

            closeDialog(){
                // this.print_arr = [];
                // this.getTemplates();
                // this.print_arr = [];
                // this.use_dialog = false;
                this.refreshData();
                
            },

            async refreshData(){
                // this.gen_overlay = true;
                this.selected_method.pcg_method = "All"
                this.selected_pcgType.pcg_type = "All"
                this.selected_team.pcg_team_code = 1
                this.selected_status.status = "All"
                this.selected_category.category = "All"
                this.dateFrom = null
                this.dateTo = null
                this.fileAttachments.attachmentFile10 =[]
                this.allFiles =  []; 
                this.files =  []; 
                // this.print_arr = [];
                this.use_dialog = false;
                await this.getTemplates();
                // this.gen_overlay = true;
            },

            async getTemplates(){
                this.gen_overlay = true;
                axios({
                    method : "post",
                    url : 'api/indexMonitoring',
                    data : {
                        method : this.selected_method.pcg_method,
                        pcg : this.selected_pcgType.pcg_type,
                        team : this.selected_team.pcg_team_code,
                        status : this.selected_status.status,
                        category : this.selected_category.category,
                        // dateFrom : moment(this.dateFrom).format('YYYY-MM-DD 00:00:00'),
                        // dateTo : moment(this.dateTo).format('YYYY-MM-DD 23:59:59'),
                        dateFrom : this.dateFrom,
                        dateTo : this.dateTo,
                        selected_no_of_page: this.selected_no_of_page,
                        startRecord: this.startRecord,
                        
                    }
                }).then(async res => {
                    this.gen_overlay = false;
                    console.log(res.data,'indexMonitoring data');
                    this.fileAttachments.attachmentFile10 =[]
                    this.pcgMonitoring_data = res.data.data
                    this.getPrintPcg()
                    const payload = {
                        user_id: this.userInfo.EmployeeCode
                    }
                    await this.getUsers(payload);
                    await this.getVersions(this.userInfo);
                    this.checkUser()
                }).catch(err => {
                    console.log(err,'getTemplates() error')
                    // alert('contact ihs')
                    this.gen_overlay = false
                })
            },

            
            checkUser(){
                console.log(this.carte_users,'carte users')
                let result = this.carte_users.filter(res => {
                    return res.EmployeeCode == this.userInfo.EmployeeCode
                })
                console.log(result,'reserl')
                const condition1 = result[0].admin != this.userInfo.admin || result[0].pcg_team_code != this.userInfo.TeamCode
                const condition2 = result[0].version_id != this.versions.id
                // const test1 = this.userInfo.version_id != this.$store.state.versions.id
                if(result.length > 0 && ( condition1 || condition2)){
                    console.log('true')
                    alert('new version is available, signing out...')
                    this.storeUserInfo(null)
                    this.$router.push("/login");
                }
            },

            getFilename(itemVal,column_name){
                console.log(itemVal,'isemse')
                console.log(column_name,'column_name')
                this.loading = true;
                axios({
                    method : "post",
                    url : 'api/getFilename',
                    data : {
                        filename : itemVal[column_name]
                    }
                }).then(res => {
                    // const directory = encodeURIComponent(res.data[0].directory.replace(/\//g, '-'));
                    // const filename = encodeURIComponent(res.data[0].filename);
                    // const url = `php/download.php?path=${directory}&item=${filename}`;
                    // console.log(url,'url')
                    this.attachment_data = res.data.map(element => ({
                        ...element,
                        pcg_incharge_code: itemVal.pcg_incharge_code,
                        id: itemVal.id,
                        column_name: column_name
                    }));
                    console.log('rer',this.attachment_data);
                    this.loading = false;

                }).catch(err => {
                    console.log(err,'error in fetching file')
                })
            },

            openDialog(DialogName,itemVal,column_name){
                // console.log(DialogName,'DialogName')
                // console.log(itemVal,'itemval')
                if(DialogName == "addDialog"){
                    this.edit_pcg_obj = {};
                    this.add_pcg_title = "Add PCG";
                    this.add_pcg_dialog = true;
                }
                if(DialogName == "serverDialog"){
                    this.server_dialog = true;
                    this.getFilename(itemVal,column_name);
                }
                if(DialogName == "useDialog"){
                    this.add_pcg_dialog = false;
                    this.use_dialog = true;
                }
                
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

            editDialog(item){
                if(item.approve_status != 3 || this.userInfo.admin == 1){
                    console.log(item,'Edit item');
                    this.edit_pcg_obj = {...item};
                    this.add_pcg_title = "Edit PCG";
                    this.add_pcg_dialog = true;
                    
                    // item.checked_by == null?item.checked_by = "":item.checked_by
                    // item.disapproved_by == null?item.disapproved_by = "":item.disapproved_by
                   
                }
            },

            async getStatusForChecking(){
                axios({
                    method : "GET",
                    url : 'api/getNotification'
                }).then(res => {
                    // console.log('re',res.data.length);
                    this.getnotif_val = res.data.count;
                })
            },

            registerPCG(){
                this.savingAttachments();
                this.validateTemplate()

                // return;
                // if(this.add_checkbox == true){
                //     if(this.add_textfield){
                //         this.validateTemplate()
                //     }else{
                //         alert('Please provide stop code')
                //     }
                // }else{
                //     this.add_textfield = "";
                //     this.validateTemplate();
                // }
            },

            validateTemplate(){
                // console.log(this.fileAttachments.attachmentFile1[0],'filestse')
                // this.insertTemplate();
                // return;
                
                const japaneseChars = /[\u3040-\u309F\u30A0-\u30FF\uFF00-\uFF9F\u4E00-\u9FFF]/;
                const japaneseChars2 = /[\u3000-\u303f\u3040-\u309f\u30a0-\u30ff\uff00-\uff9f\u4e00-\u9faf\u3400-\u4dbf\,\u3001\u3002\u30fb\u201c\u201d\s]/;
                if(!this.edit_pcg_obj.pcg_method){
                    alert('Please select Method')
                }else if(!this.edit_pcg_obj.pcg_type){
                    alert('Please select PCG Type')
                }else if(!this.edit_pcg_obj.pcg_category){
                    alert('Please select Category')
                }else if(!this.edit_pcg_obj.pcg_code){
                    alert('error fetching code reselect pcg type')
                }
                // else if(
                //     !this.fileAttachments.attachmentFile1 ||
                //     !this.fileAttachments.attachmentFile2 ||
                //     !this.fileAttachments.attachmentFile3 ||
                //     !this.fileAttachments.attachmentFile4 ||
                //     !this.fileAttachments.attachmentFile5 ||
                //     !this.fileAttachments.attachmentFile6 ||
                //     !this.fileAttachments.attachmentFile7 ||
                //     !this.fileAttachments.attachmentFile8 ||
                //     !this.fileAttachments.attachmentFile9
                // ){
                //     alert('Please Add References')
                // }
                else if(!this.edit_pcg_obj.pcg_english){
                    alert('Please input English');
                }else if(!this.edit_pcg_obj.pcg_japanese){
                    alert('Please input Japanese');
                }
                // else if(!this.edit_pcg_obj.remarks_status){
                //     alert('Please input Judgement')
                // }
                else if(!this.edit_pcg_obj.pcg_translated_by){
                    alert('Please input Tranlated by')
                }
                // else if(!this.edit_pcg_obj.remarks){
                //     alert('Please choose remarks')
                //     // this.remarks_btn = false;
                // }
                else{
                    let eng_val = null;
                    let jap_val = null;

                    if (japaneseChars.test(this.edit_pcg_obj.pcg_english)) {
                        eng_val = 1
                        // alert("English field contains Japanese characters. Please enter only English characters.");
                        // return false;
                    }
                    for (let i = 0; i < this.edit_pcg_obj.pcg_japanese.length; i++) {
                        if (!japaneseChars2.test(this.edit_pcg_obj.pcg_japanese[i])) {
                            jap_val = 1
                            // alert("Japanese field English characters. Please enter only Japanese characters.");
                            // return false;
                        }
                    }
                    let text = "Are you sure you want to insert this template?\n" +
                    "  * OK to proceed.\n" +
                    "  * Cancel to exit.";
                    console.log(jap_val,eng_val)
                    if((eng_val && jap_val) || (eng_val && !jap_val) || (!eng_val && jap_val) ){
                        if (confirm(text) == true ) {
                            // text = "You pressed OK!";
                            this.insertTemplate();
                            console.log('insert sucess')
                            
                        } else {
                            console.log('insert cancel')
                            // text = "You canceled!";
                        }
                    }else{
                        this.insertTemplate();
                        console.log('insert sucess without asking')
                    }
                    
                    
                }
            },
            
            insertTemplate(){
                axios({
                    method : "post",
                    url : 'api/add_pcg',
                    data : {
                        pcg_method : this.edit_pcg_obj.pcg_method,
                        pcg_type : this.edit_pcg_obj.pcg_type,
                        pcg_category : this.edit_pcg_obj.pcg_category,
                        pcg_code : this.edit_pcg_obj.pcg_code,
                        // pcg_action_id : this.edit_pcg_obj.action_id,
                        // pcg_reference : this.fileAttachments.attachmentFile1,
                        // pcg_hrd_memo : this.fileAttachments.attachmentFile2,
                        // pcg_reference_2 : this.fileAttachments.attachmentFile3,
                        // pcg_hrd_memo_2 : this.fileAttachments.attachmentFile4,
                        // pcg_shio : this.fileAttachments.attachmentFile5,
                        // pcg_reference_3 : this.fileAttachments.attachmentFile6,
                        // pcg_hrd_memo_3 : this.fileAttachments.attachmentFile7,
                        // pcg_reference_4 : this.fileAttachments.attachmentFile8,
                        // pcg_hrd_memo_4 : this.fileAttachments.attachmentFile9,

                        pcg_reference : this.edit_pcg_obj.pcg_reference,
                        pcg_hrd_memo : this.edit_pcg_obj.pcg_hrd_memo,
                        pcg_reference_2 : this.edit_pcg_obj.pcg_reference_2,
                        pcg_hrd_memo_2 : this.edit_pcg_obj.pcg_hrd_memo_2,
                        pcg_shio : this.edit_pcg_obj.pcg_shio,
                        pcg_reference_3 : this.edit_pcg_obj.pcg_reference_3,
                        pcg_hrd_memo_3 : this.edit_pcg_obj.pcg_hrd_memo_3,
                        pcg_reference_4 : this.edit_pcg_obj.pcg_reference_4,
                        pcg_hrd_memo_4 : this.edit_pcg_obj.pcg_hrd_memo_4,

                        // remarks_status : this.edit_pcg_obj.remarks_status,
                        pcg_english : this.edit_pcg_obj.pcg_english,
                        pcg_japanese : this.edit_pcg_obj.pcg_japanese,
                        pcg_translated_by : this.edit_pcg_obj.pcg_translated_by,
                        pcg_incharge_code : this.userInfo.EmployeeCode,
                        pcg_incharge : this.userInfo.EmployeeName,
                        pcg_team_code : this.userInfo.TeamCode,
                        remarks : this.edit_pcg_obj.remarks,
                        files : this.allFiles,
                    }
                // }).then(({data}) => {
                }).then((res) => {
                    console.log(res.data, 'register pcg ')
                    this.getTemplates();
                    this.fileAttachments.attachmentFile10 = [];
                    this.files = [];
                    this.allFiles = [];
                    this.reference_dropdown = false;
                    this.add_pcg_dialog = false;
                    this.getPrintPcg();

                    // this.remarks_btn = false;
                    // this.add_pcg_remarks = null;
                    
                }).catch(err => {
                    console.error('Error:', err); // Log the entire error object
                    if (err.response && err.response.status === 500) {
                        // Check for specific error message within 500 response
                        if (err.response.data && err.response.data.message && 
                            err.response.data.message.includes('Integrity constraint violation: 1062 Duplicate entry ')) {
                            alert('Error: Duplicate entry detected. Please enter a unique value.');
                        } else {
                        // Handle other 500 errors
                        console.error('Unexpected 500 error:', err.response.data);
                        alert('An internal server error occurred. Please try again later.'); // Customize the alert message
                        }
                    } else if (err.request) {
                        // Network error or request timeout
                        console.error('Error:', err.request);
                    } else {
                        // Other errors
                        console.error('Error:', err.message);
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
                        // pcg_action_id : this.edit_pcg_obj.action_id,
                        // pcg_reference : this.fileAttachments.attachmentFile1,
                        // pcg_hrd_memo : this.fileAttachments.attachmentFile2,
                        // pcg_reference_2 : this.fileAttachments.attachmentFile3,
                        // pcg_hrd_memo_2 : this.fileAttachments.attachmentFile4,
                        // pcg_shio : this.fileAttachments.attachmentFile5,
                        // pcg_reference_3 : this.fileAttachments.attachmentFile6,
                        // pcg_hrd_memo_3 : this.fileAttachments.attachmentFile7,
                        // pcg_reference_4 : this.fileAttachments.attachmentFile8,
                        // pcg_hrd_memo_4 : this.fileAttachments.attachmentFile9,

                        pcg_reference : this.edit_pcg_obj.pcg_reference,
                        pcg_hrd_memo : this.edit_pcg_obj.pcg_hrd_memo,
                        pcg_reference_2 : this.edit_pcg_obj.pcg_reference_2,
                        pcg_hrd_memo_2 : this.edit_pcg_obj.pcg_hrd_memo_2,
                        pcg_shio : this.edit_pcg_obj.pcg_shio,
                        pcg_reference_3 : this.edit_pcg_obj.pcg_reference_3,
                        pcg_hrd_memo_3 : this.edit_pcg_obj.pcg_hrd_memo_3,
                        pcg_reference_4 : this.edit_pcg_obj.pcg_reference_4,
                        pcg_hrd_memo_4 : this.edit_pcg_obj.pcg_hrd_memo_4,

                        // remarks_status : this.edit_pcg_obj.remarks_status,
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
                    this.getTemplates();
                    this.add_pcg_dialog = false
                    this.fileAttachments.attachmentFile10 = [];
                    this.files = [];
                    this.allFiles = [];
                    this.getPrintPcg();
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
                                id : this.edit_pcg_obj.id
                            }
                        }).then(res => {
                            console.log('this id',res.data)
                            this.getTemplates();
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

            resetPcgApproveStatus(){
                let text = "Reset Template?";
                if (confirm(text) == true) {
                    axios.post('api/reset_template_status',{
                        id : this.edit_pcg_obj.id,
                    }).then(res => {
                        console.log(res.data)
                        this.getTemplates();
                    }).catch(err => {
                        console.log(err,'error resetPcgApproveStatus()')
                        alert('resetPcgApproveStatus() error')
                    })
                }
            },

            checkPcg(){
                const toUpperName = this.userInfo.EmployeeName.toLowerCase().split(' ').map(word => {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');
                
                axios({
                    method : "post",
                    url : 'api/check_pcg',
                    data : {
                        id : this.edit_pcg_obj.id,
                        checked_by : toUpperName
                    }
                }).then(res => {
                    console.log('this id',res.data)
                    this.getTemplates();
                    this.add_pcg_dialog = false;
                }).catch(err => {
                    console.log(err,'error checkPcg()')
                    alert('checkPcg() error')
                })
            },

            approvePcg(){
                const toUpperName = this.userInfo.EmployeeName.toLowerCase().split(' ').map(word => {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');
                axios({
                    method : "post",
                    url : 'api/approve_pcg',
                    data : {
                        id : this.edit_pcg_obj.id,
                        approved_by : toUpperName,
                    }
                }).then(res => {
                    console.log('this id',res.data)
                    this.getTemplates();
                    this.add_pcg_dialog = false;
                }).catch(err => {
                    console.log(err,'error approvePcg()')
                    alert('approvePcg() error')
                })
            },

            disapprovePcg(){
                const toUpperName = this.userInfo.EmployeeName.toLowerCase().split(' ').map(word => {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');
                this.disapprove_dialog = false
                axios({
                    method : "post",
                    url : 'api/disapprove_pcg',
                    data : {
                        id : this.edit_pcg_obj.id,
                        disapproved_by : toUpperName,
                        reason : this.approve_reason
                    }
                }).then(res => {
                    this.approve_reason = ''
                    console.log('this id',res.data)
                    this.getTemplates();
                    this.add_pcg_dialog = false;
                }).catch(err => {
                    console.log(err,'error disapprovePcg()')
                    alert('disapprovePcg() error')
                })
            },
          
            category_add(){
                axios({
                    method: "POST",
                    url: "api/category",
                    data: {
                        method : this.edit_pcg_obj.category
                    }
                }).then(res => {
                    console.log('res',res.data);
                    this.pcg_category_add_items = res.data
                })
            },

            getPcgCode(){
                let data = this.pcgType_items.filter(r => {
                    if(this.edit_pcg_obj.pcg_type == r.pcg_type){
                        return r.pcg_code;
                    }
                });
                console.log(data,'data')
                axios({
                    method : "POST",
                    url : "api/code",
                    data : {
                        method : this.edit_pcg_obj.pcg_method,
                        pcg_code : data[0].pcg_code
                    }
                }).then(res => {
                    console.log(res.data);
                    this.edit_pcg_obj.pcg_code = res.data;
                    this.edit_pcg_obj = {...this.edit_pcg_obj}
                
                })
            },

            code_edit(){
                axios({
                    method : "POST",
                    url : "api/code",
                    data : {
                        category : this.edit_pcg_obj.pcg_category,
                        // category : this.edit_pcg_obj.category
                    }
                }).then(res => {
                    console.log('res',res.data);
                })
            },
            
        },

        async mounted() {
            await this.getTemplates();
            await this.getMethods();
            await this.getPcgtypes();
            await this.getCategories();
            await this.getTeams();
            await this.getStatusForChecking();
            await this.getActions();
            // this.getVersions(this.userInfo);
            // this.getPrintPcg();
            // this.updateAdminValue()
            // this.category_add();
            // this.edit_pcg_obj.pcg_method = "All"
            // this.category_add();
        },
       
        computed: {
            ...mapState([
                "userInfo",
                "method_items",
                "pcgType_items",
                "carte_categories",
                "team_items",
                "carte_users",
                'versions',
                "cart_search"
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

            startRecord() {
                const start = (this.page - 1) * this.selected_no_of_page;
                console.log(start,'start value')
                return start;
            },
           
            pages(){
                let d = this.searchRecords.length,
                // p = 10;
                p = this.selected_no_of_page;
                return Math.ceil(d / p);
            },

            nextPage(){
                // const start = (this.page - 1) * 10,
                // end = start + 10;
                const start = (this.page - 1) * this.selected_no_of_page,
                end = start + this.selected_no_of_page;
                let a = this.searchRecords.slice(start,end);
                return a;
            },
            
            searchRecords(){ // for search text field
                
                return this.pcgMonitoring_data.filter((data) => {
                    if(!this.search){
                        return [];
                    }
                    let searchData = new RegExp(this.search.toUpperCase(), "g");
                    return JSON.stringify(Object.values(data)).toUpperCase().match(searchData);
                });
            },

        },
        watch: {
            add_print_overlay2 (val) {
                val && setTimeout(() => {
                this.add_print_overlay2 = false
                }, 500)
            },
            server_dialog(val){
                // console.log(val,'server dialog')
                if(val == false){
                    this.attachment_data = [];
                }
            },
            // edit_pcg_obj(val){
            //     console.log(val,'edit_pcg_obj_value');
            //     // console.log(this.userInfo.admin);
            // },
            add_print_overlay (val) {
                val && setTimeout(() => {
                    this.add_print_overlay = false
                }, 200)
            },
        },
        
        
    }
</script>

<style >
/* .red_status .v-input__control .v-text-field__details .theme--light.v-messages .v-messages__wrapper .v-messages-message{
    color:red !important;
} */

/* .theme--light.v-messages {
    color: red;
} */
/* table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 8px;
      border: 1px solid #ccc;
      transition: background-color 0.2s ease;
    } */
.blink_me {
    animation: blinker 1s linear infinite;
    text-align: center;
}

@keyframes blinker {
    50% {
        opacity: 0;
    }
}

.disapproved-row {
    background-color: #d9d7d7; /* Change to gray or any other desired color */
    color: #888888;  
    pointer-events: none; /* Disable hover effects */
  }


.ele {
  animation: 1s fadeIn;
  animation-fill-mode: forwards;
  visibility: hidden;
}

.ele:hover {
  background-color: #123;
}

@keyframes fadeIn {
  0% {
    opacity: 0;
  }
  100% {
    visibility: visible;
    opacity: 1;
  }
}
</style>
