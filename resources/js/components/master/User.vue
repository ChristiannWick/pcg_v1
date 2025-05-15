<template>
    <div style="padding:4px; ">
        <div style="padding: 4px; display: flex; justify-content: space-between; align-items: center;">
            <v-text-field
                style="max-width: 300px;"
                clearable
                v-model="searchUser"
                prepend-inner-icon="mdi-magnify"
                hide-details
                dense
                outlined
                placeholder="Search here..."
            >
            </v-text-field>

            <v-btn
                fab
                dark
                small
                color="indigo"
                title="add"
                @click="openDialog('add')"
            >
                <v-icon dark>
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
                        <th class="text-left">
                            Name
                        </th>
                        <th>
                            NickName
                        </th>
                        <th>
                            Team Name
                        </th>
                        <!-- <th>
                            Password
                        </th> -->
                        <!-- <th>
                            Admin
                        </th> -->
                        <th>
                            Role
                        </th>
                        <th>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                    v-for="(item,index) in searchRecords"
                    :key="index"
                    style="text-align:center; "
                    >
                    <td>
                        <div style="display:flex; align-items:center;">
                            <img
                            style="border-radius:50%; height:40px; "
                            :src="`http://hrd-adminweb/photos/${item.EmployeeCode}.jpg`" 
                            alt="user">&nbsp;
                            <span>{{ item.EmployeeName }}</span>
                        </div>
                        
                        
                    </td>
                    <td>{{ item.NickName }}</td>
                    <td>{{ item.TeamName }}</td>
                    <!-- <td>{{ item.password }}</td> -->
                    <!-- <td>{{ item.admin }}</td> -->
                    <td>{{ item.role }}</td>
                    <td>
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            v-if="['02045', '09473', '36396'].includes(userInfo.EmployeeCode)"
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
                <div v-if="dialog_title != ''">
                    <v-card-title class="blue-grey darken-1">
                        <span class="text-h7 white--text">{{dialog_title}}</span>
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
                                    v-model="obj.EmployeeCode"
                                    label="User ID :"
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
                                    v-model="obj.EmployeeName"
                                    label="Name :"
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
                                    v-model="obj.NickName"
                                    label="NickName :"
                                    hide-details=""
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-select
                                    dense
                                    filled
                                    label="Team :"
                                    :items="new_team_items"
                                    item-text="name"
                                    item-value="pcg_team_code"
                                    v-model="obj.pcg_team_code"
                                    hide-details=""
                                ></v-select>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-text-field
                                    :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                                    :type="show1 ? 'text' : 'password'"
                                    @click:append="show1 = !show1"
                                    dense
                                    filled
                                    v-model="obj.password"
                                    label="Password :"
                                    hide-details=""
                                ></v-text-field>
                            </v-col>
                            <v-col
                                cols="12"
                                sm="12"
                                md="12"
                            >
                                <v-select
                                    dense
                                    filled
                                    v-model="obj.admin"
                                    item-text="name"
                                    item-value="value"
                                    :items="roles"
                                    label="Role:"
                                ></v-select>                     
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            :disabled="saveUsersDisabled"
                            :dark="!saveUsersDisabled"
                            small
                            color="blue"
                            rounded
                            @click="saveUpdateUser()"
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
                    <v-btn color="blue darken-1" text @click="deleteUser">Yes</v-btn>
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
    data() {
        return {
            admin_items: [0, 1],
            roles: [
                {value:0, name:"Member"},
                {value:2, name:"Support"},
                {value:1, name:"Admin"},
            ],
            dialog_title: 'Add',
            dialog: false,
            obj:{},
            searchUser: '',
            show1: false,
            payload : {
                user_id: ''
            }
        }
    },
    
    computed: {
        ...mapState([
            "carte_users",
            "team_items",
            "userInfo"
        ]),

        new_team_items(){
            return this.$store.getters.team_items
        },
        
        searchRecords(){
            return this.$store.getters.new_carte_users.filter((data) => {
                if(!this.searchUser){
                    return [];
                }
                let searchData = new RegExp(this.searchUser.toUpperCase(), "g");
                return JSON.stringify(Object.values(data)).toUpperCase().match(searchData);
            });

        },

        saveUsersDisabled() {
            return !(this.obj.EmployeeCode && this.obj.EmployeeName && this.obj.pcg_team_code && this.obj.password && this.obj.admin >=0);
        },
    },

    methods: {
        ...mapActions([
            "getUsers"
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

        saveUpdateUser(){
            if(this.dialog_title == 'Add'){
                axios.post('api/addUser',this.obj)
                .then(res => {
                    console.log(res.data)
                    this.getUsers(this.payload);
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error saving')
                    alert('addUser error contact ihs')
                })
            }
            if(this.dialog_title == 'Edit'){
                axios.post('api/updateUsers',this.obj)
                .then(res => {
                    console.log(res.data)
                    this.getUsers(this.payload);
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error updating')
                    alert('updateUsers errorcontact ihs')
                })
            }
            
        },

        deleteUser(){
            axios.post('api/deleteUser',this.obj)
            .then(res => {
                console.log(res.data)
                this.getUsers(this.payload);
                this.dialog = false
            }).catch(err=>{
                console.log(err,'error deleting')
                alert('deleteUser error contact ihs')
            })
        }


    }
}
</script>