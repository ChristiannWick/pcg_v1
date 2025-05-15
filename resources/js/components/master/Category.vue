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
                        <!-- <th>
                            Code
                        </th> -->
                        <th>
                            Category
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                    v-for="item in carte_categories"
                    :key="item.id"
                    style="text-align:center; "
                    >
                    <!-- <td style="border: 1px solid #dddddd; " class="text-center">{{ item.code }}</td> -->
                    <td >{{ item.category }}</td>
                    <td >
                        <!-- <v-tooltip bottom>
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
                        </v-tooltip> -->
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
                                <v-icon dense small>mdi-delete</v-icon>
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
                                    :disabled="obj.id == 1"
                                    dense
                                    filled
                                    v-model="obj.category"
                                    label="Category:"
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
                            @click="saveUpdateCategory()"
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
                    <v-btn color="blue darken-1" text @click="deleteCategory">Yes</v-btn>
                    <v-spacer></v-spacer>
                    </v-card-actions>
                </div>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import {mapState,mapActions} from 'vuex'
export default{
    data() {
        return {
            dialog_title: 'Add',
            dialog: false,
            obj:{},
        }
    },

    computed: {
        ...mapState([
            "carte_categories"
        ]),
    },

    methods: {
        ...mapActions([
            "getCategories"
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

        saveUpdateCategory(){
            if(this.dialog_title == 'Add'){
                axios.post('api/save_category',{
                    category : this.obj.category,
                }).then(res => {
                    // console.log(res.data)
                    this.getCategories();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error saving')
                    alert('save_category error contact ihs')
                })
            }
            if(this.dialog_title == 'Edit'){
                axios.post('api/update_category',{
                    id: this.obj.id,
                    category : this.obj.category,
                }).then(res => {
                    // console.log(res.data)
                    this.getCategories();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error updating')
                    alert('update_category error contact ihs')
                })
            }
            
        },

        deleteCategory(){
            axios.post('api/delete_category',{
                id: this.obj.id,
            }).then(res => {
                // console.log(res.data)
                this.getCategories();
                this.dialog = false
            }).catch(err=>{
                console.log(err,'error deleting')
                alert('delete_category error contact ihs')
            })
        }

    }
}
</script>