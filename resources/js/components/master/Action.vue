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
                            English
                        </th>
                        <th>
                            Japanese
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                    v-for="item in action_items"
                    :key="item.name"
                    style="text-align:center;"
                    >
                        <td>{{ item.english }}</td>
                        <td>{{ item.japanese }}</td>
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
                <div v-if="dialog_title.match(/^(Add|Edit)$/)">
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
                                    dense
                                    filled
                                    v-model="obj.english"
                                    label="English Action:"
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
                                    v-model="obj.japanese"
                                    label="Japanese Action:"
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
                            @click="saveUpdateAction()"
                        >
                            {{dialog_title == 'Add' ? 'Save' : 'Update'}}
                        </v-btn>
                        <v-btn
                            dark
                            small
                            color="blue"
                            rounded
                            @click="dialog = false"
                        >
                            Close
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
                    <v-btn color="blue darken-1" text @click="deleteAction">Yes</v-btn>
                    <v-spacer></v-spacer>
                    </v-card-actions>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';
export default {
    data(){
        return {
            dialog_title: 'Add',
            dialog: false,
            obj:{},
        }
    },

    computed: {
        ...mapState([
            "action_items"
        ])
    },

    methods:{
        ...mapActions([
            "getActions"
        ]),

        openDialog(title,item){
            console.log(item,'test item')
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

        saveUpdateAction(){
            if(!this.obj.english || !this.obj.japanese){
                alert('fields must be filled out.')
                return false;
            }
            if(this.dialog_title == 'Add'){
                axios.post('api/save_action',{
                    english : this.obj.english,
                    japanese : this.obj.japanese,
                }).then(res => {
                    // console.log(res.data)
                    this.getActions();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error saving')
                    alert('save_action error contact ihs')
                })
            }
            if(this.dialog_title == 'Edit'){
                axios.post('api/update_action',{
                    id: this.obj.action_id,
                    english : this.obj.english,
                    japanese : this.obj.japanese,
                }).then(res => {
                    // console.log(res.data)
                    this.getActions();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error updating')
                    alert('update_action error contact ihs')
                })
            }
            
        },

        deleteAction(){
            axios.post('api/delete_action',{
                id: this.obj.action_id,
            }).then(res => {
                // console.log(res.data)
                this.getActions();
                this.dialog = false
            }).catch(err=>{
                console.log(err,'error deleting')
                alert('delete_action error contact ihs')
            })
        }

        
    },

    

}
</script>
