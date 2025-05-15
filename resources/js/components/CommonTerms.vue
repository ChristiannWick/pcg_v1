<template>
    <div>
        <v-card elevation="0" style="margin:6px; ">
            <v-card-title 
            style="background:rgb(84,110,122); color:white; display:flex;justify-content:space-between;">
                <span class="text-h6">Common Terms</span> 
                <div style="display: flex;  align-items: center;">
                    <v-text-field
                        clearable
                        v-model="search"
                        prepend-inner-icon="mdi-magnify"
                        hide-details
                        dense
                        outlined
                        placeholder="Search here..."
                        dark
                        class="mr-1"
                    >
                    </v-text-field>
                    <v-btn
                        v-if="userInfo.admin > 0"
                        fab
                        dark
                        x-small
                        title="add"
                        color="#989898"
                        @click="openDialog('add',{})"
                    >
                        <v-icon small dark>
                            mdi-plus-thick
                        </v-icon>
                    </v-btn>
                </div>
            </v-card-title>
            <v-card-text >   
                <v-simple-table fixed-header height="650">
                    <template v-slot:default>
                        <thead>
                            <tr >
                                <th class="grey lighten-5">
                                    Terminologies
                                </th>
                                <th class="grey lighten-5">
                                    Common Terminologies
                                </th>
                                <th class="grey lighten-5">
                                    Japanese Term
                                </th>
                                <th v-if="userInfo.admin > 0" class="text-center">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                            v-for="item in searchRecords"
                            :key="item.id"
                            >
                            <td>{{ item.search_term }}</td>
                            <td>{{ item.english_term }}</td>
                            <td>{{ item.japanese_term }}</td>
                            <td v-if="userInfo.admin > 0" class="text-center">
                                <v-btn
                                    title="edit"
                                    fab
                                    x-small
                                    color="green"
                                    dark
                                    @click="openDialog('edit',item)"
                                    >
                                    <v-icon dense small>mdi-pencil</v-icon>
                                </v-btn>
                                <v-btn
                                    title="delete"
                                    fab
                                    x-small
                                    color="red"
                                    dark
                                    @click="deleteCommonTerm(item)"
                                    >
                                    <v-icon dense small>mdi-delete</v-icon>
                                </v-btn>
                            </td>
                            </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-card-text>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="380">
            <v-card >
                <v-card-title class="text-h6" style="background:rgb(84,110,122); ">
                    <span class="text-h6 white--text">{{dialog_title}}</span>
                    <v-spacer/>
                    <v-btn
                        fab
                        dark
                        x-small
                        title="close"
                        color="#989898"
                        @click="dialog = false,obj={}"
                    >
                        <v-icon small color="#ff1100">
                            mdi-close-thick
                        </v-icon>
                    </v-btn>
                </v-card-title>
                <v-card-text>
                    <v-row dense>
                        <v-col
                        cols="12"
                        md="12"
                        >
                            <v-text-field
                                v-model="obj.search_term"
                                label="Search Item"
                            ></v-text-field>
                        </v-col>
                        <v-col
                        cols="12"
                        md="12"
                        >
                            <v-text-field
                                v-model="obj.english_term"
                                label="Common Terms (English)"
                            ></v-text-field>
                        </v-col>
                        <v-col
                        cols="12"
                        md="12"
                        >
                            <v-text-field
                                v-model="obj.japanese_term"
                                label="Common Terms (Japanese)"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer/>
                    <v-btn @click="saveUpdateCommonTerm" outlined text>
                        {{dialog_title == 'Add' ? "save" : "update"}}
                    </v-btn>
                </v-card-actions>
            </v-card>

        </v-dialog>
    </div>
</template>
<script>
import { mapState } from 'vuex';
export default {
    data(){
        return {
            common_terms: [
                // {
                //     search_term: 'Frozen Yogurt',
                //     english_term: 159,
                //     japanese_term: 159,
                // },
                // {
                //     search_term: 'Ice cream sandwich',
                //     english_term: 237,
                //     japanese_term: 237,
                // },
            ],
            search: '',
            dialog: false,
            dialog_title: '',
            obj: {
                search_term: "",
                english_term: "",
                japanese_term: "",
            }
        }
    },

    computed: {
        ...mapState([
            'userInfo'
        ]),

        searchRecords(){
            return this.common_terms.filter((data) => {
                if(!this.search){
                    return [];
                }
                let searchData = new RegExp(this.search.toUpperCase(), "g");
                return JSON.stringify(Object.values(data)).toUpperCase().match(searchData);
            });

        },
    },

    mounted(){
        this.getCommonTerms();
    },

    methods : {

        openDialog(title,item){
            console.log(title,item,'testing')
            this.dialog = true;
            this.obj = {...item};
            if(title == 'add'){
                this.dialog_title = 'Add'
            }else if(title == 'edit'){
                this.dialog_title = 'Edit'
            }
        },

        async getCommonTerms(){
            try {
                const response = await axios.get('api/get_common_terms')
                // const responseData = response.data;
                this.common_terms = response.data
                console.log(this.common_terms,'this.common_terms');
            } catch (error) {
                console.error('Error getCommonTerms data:', error);
            }
        },

        saveUpdateCommonTerm(){
            if(!this.obj.search_term || !this.obj.english_term || !this.obj.japanese_term){
                alert('no item')
                return false;
            }
            if(this.dialog_title == 'Add'){
                axios.post('api/save_common_term',this.obj)
                .then(res => {
                    console.log(res.data)
                    this.getCommonTerms();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error saving')
                    alert('save_common_term error contact ihs')
                })
            }
            if(this.dialog_title == 'Edit'){
                axios.post('api/update_common_term',this.obj)
                .then(res => {
                    console.log(res.data)
                    this.getCommonTerms();
                    this.dialog = false
                }).catch(err=>{
                    console.log(err,'error saving')
                    alert('save_common_term error contact ihs')
                })
            }
            
            // alert('with item')
        },

        deleteCommonTerm(item){
            console.log(item,'delete item')
            let text = "Delete this item?\n";
            if (confirm(text) == true) {
                text = "You pressed OK!";
                axios.post('api/delete_common_term',item)
                .then(res => {
                    console.log(res.data)
                    this.getCommonTerms();
                }).catch(err=>{
                    console.log(err,'error saving')
                    alert('save_common_term error contact ihs')
                })
            } else {
                text = "You canceled!";
            }
        }
    }
}
</script>