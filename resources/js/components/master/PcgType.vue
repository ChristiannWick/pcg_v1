<template>
    <div style="padding:4px; ">
        <div style="padding:4px; display: flex; justify-content: flex-end; ">
            <v-btn
                fab
                dark
                small
                color="indigo"
                title="add"
                @click="openDialog('add')"
                >
                <v-icon >
                    mdi-plus
                </v-icon>
            </v-btn>
        </div>
        <v-simple-table
            fixed-header
            height="720"
            >
            <template v-slot:default>
                <thead class="master-header">
                    <tr>
                        <th>
                            PCG Type
                        </th>
                        <th>
                            PCG Code
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                    v-for="item in pcgType_items"
                    :key="item.id"
                    style="text-align:center;"
                    >
                        <td>{{ item.pcg_type }}</td>
                        <td>{{ item.pcg_code }}</td>
                        <td>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                    icon
                                    color="green"
                                    dark
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="openDialog('edit',item)"
                                    >
                                    <v-icon dense small>mdi-pencil</v-icon>
                                    </v-btn>
                                </template>
                                <span>Edit</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                    <v-btn
                                    icon
                                    color="red"
                                    dark
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="openDialog('delete',item)"
                                    >
                                    <v-icon dense small >mdi-delete</v-icon>
                                    </v-btn>
                                </template>
                                <span>Delete</span>
                            </v-tooltip>
                        </td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>

        <v-dialog
            v-model="dialog"
            persistent
            max-width="380"
            >
            <v-card>
                <!-- <div v-if="dialog_title.match(/^(Add|Edit)$/)"> -->
                <div v-if="dialog_title != ''">
                    <v-card-title class="blue-grey darken-1">
                        <span class="text-h6 white--text">{{dialog_title}}</span>
                    </v-card-title>
                    <v-container fluid>
                        <v-row dense>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                    :disabled="dialog_title == 'Edit'"
                                    dense
                                    filled
                                    v-model="obj.pcg_type"
                                    label="PCG Type:"
                                    hide-details=""
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                    dense
                                    filled
                                    v-model="obj.pcg_code"
                                    label="PCG Code:"
                                    hide-details=""
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            dark
                            small
                            color="blue"
                            rounded
                            @click="saveUpdatePcgtype()"
                        >
                            {{dialog_title == 'Add' ? 'save' : 'update'}}
                        </v-btn>
                        <v-btn
                            dark
                            small
                            color="blue"
                            rounded
                            @click="dialog = false"
                        >
                            cancel
                        </v-btn>
                    </v-card-actions>
                </div>
                <div v-else>
                    <v-card-title class="blue-grey darken-1">
                        <span class="subtitle-1 white--text">Are you sure you want to delete this item?</span>
                    </v-card-title>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="dialog = false">Cancel</v-btn>
                    <v-btn color="blue darken-1" text @click="deletePcgtype">Yes</v-btn>
                    <v-spacer></v-spacer>
                    </v-card-actions>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mapState,mapActions} from 'vuex'
export default {
    data(){
        return {
            dialog_title: '',
            dialog: false,
            obj:{},
        }
    },

    computed: {
        ...mapState([
            'pcgType_items',
        ]),
    },

    methods: {
        ...mapActions([
            'getPcgtypes',
        ]),

        openDialog(title,item){
            console.log(title,item,'test item')
            this.obj = {...item}
            this.dialog = true;
            if(title == "add"){
                this.dialog_title = "Add";
            }else if(title == "edit"){
                this.dialog_title = "Edit";
            }else{
                this.dialog_title = ''
            }
            
        },

        saveUpdatePcgtype(){
            if(!this.obj.pcg_type || !this.obj.pcg_code){
                alert('fields must be filled out');
                return false;
            }
            if(this.dialog_title == 'Add'){
                axios.post('api/save_pcgtype',{
                    pcg_type : this.obj.pcg_type,
                    pcg_code : this.obj.pcg_code,
                }).then(res => {
                    // console.log(res.data)
                    this.getPcgtypes();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error saving contact ihs')
                    alert('save_pcgtype existing contact ihs')
                })
            }
            if(this.dialog_title == 'Edit'){
                axios.post('api/update_pcgtype',{
                    id: this.obj.id,
                    pcg_type : this.obj.pcg_type,
                    pcg_code : this.obj.pcg_code,
                }).then(res => {
                    // console.log(res.data)
                    this.getPcgtypes();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error updating contact ihs')
                    alert('pcgtype existing contact ihs')
                })
            }
            
        },

        deletePcgtype(){
            axios.post('api/delete_pcgtype',{
                id: this.obj.id,
            }).then(res => {
                console.log(res.data)
                this.getPcgtypes();
                this.dialog = false
            }).catch(err=>{
                console.log(err,'error deleting contact ihs')
                alert('delete_pcgtype() contact ihs')
            })
        }
    },
}
</script>