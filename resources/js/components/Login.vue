<template>
    <div>
        <v-app >
            <v-container  fluid class="fill-height">
                <v-layout align-center justify-center>
                    <v-card 
                        elevation="5"
                        width="330px"
                        height="370px"
                        tile
                        class="blue-grey darken-3 pa-2"
                        > 
                        <div >
                            <v-row class="justify-center mt-14 ml-5 mr-5">
                                <v-img  src="./images/A1.png"></v-img>  
                            </v-row>
                            <v-row class="justify-center mt-2 ml-5 mr-5">
                                <v-img  src="./images/22.png"></v-img>  
                            </v-row>
                            
                        </div>
                        <div style="margin-top:100px;">
                            <span style="color:white; font-size:10px; margin-top:8px; float:left;">&nbsp;ver. 1.0</span>
                            <v-tooltip bottom>
                                <template 
                                    v-slot:activator="{on, attrs}">
                                    <v-icon style="float:right;"
                                    v-on="on" color="white" 
                                    v-bind="attrs" @click="manual()">
                                    mdi-book-information-variant
                                    </v-icon>
                                </template>
                                    <span>System Manual</span>
                            </v-tooltip>     
                        </div>
                         
                    </v-card>
                    <v-card 
                        elevation="5"
                        width="280px"
                        height="370px"
                        tile
                        class="pa-2"
                        >
                        <v-img
                            :class="{
                                'mb-8 mt-11 mr-3 ml-3' : user != 'new',
                                'mb-8 mt-3 mr-3 ml-3' : user == 'new'
                            }"
                            src="./images/pcg6.png"
                        ></v-img>
                        <!-- <a class="mb-0 mt-7" style="display:block;zoom: 1.42; font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif; color:#1a3d7d;">CARTE PLANNING SECTION</a>
                        <a class="mb-4 mt-0" style="display:block;zoom: 1.10; font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;color:#020a1a;">INQUIRY TEMPLATES</a> -->
                        <v-text-field class="ml-5 mr-5"
                            outlined
                            dense
                            append-icon="mdi-account"
                            label="Employee ID :"
                            color="#2196F3"
                            hint="5 digits Employee ID no."
                            maxlength="6"
                            v-model="employee_id"
                            :rules="rules"
                            @keyup.enter="login()"
                            />
                            <!-- @keyup.enter="user == 'new'?getUserInfos():login()" -->

                        <v-text-field  class="ml-5 mr-5 mb-5"
                            outlined   
                            dense
                            label="Password :"
                            hide-details=""
                            color="#2196F3"
                            maxlength="6"
                            v-model="employee_password"
                            :type="show ? 'text' : 'password'"
                            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
                            @click:append="show = !show"
                            @keyup.enter="login()"
                            />     
                            <!-- @keyup.enter="user == 'new'?getUserInfos():login()" -->
                        <v-select class="ml-5 mr-5 mb-5"
                            v-show="user == 'new'"
                            style="margin:2px;"
                            hide-details
                            dense
                            label="Teams :"
                            outlined
                            :items="new_team_items"
                            item-text="name"
                            item-value="pcg_team_code"
                            v-model="obj.pcg_team_code"
                        >
                        </v-select>
                        <v-btn
                            style="margin-top: 15px;"
                            :class="statusClass"
                            block
                            dark 
                            @click="login()"
                            ><b>{{card_tile}}</b>
                        </v-btn>
                        <div style="margin-top:15px;">
                            <span style="font-size:14px;">{{message}} </span>
                            <v-btn @click="signup()" color="primary" small text >
                                {{login_btn}}
                            </v-btn>
                        </div>
                    </v-card>
                </v-layout>
            </v-container> 
        </v-app>
        
        <v-overlay :value="gen_overlay">
            <v-progress-circular
                indeterminate
                size="64"
            ></v-progress-circular>
        </v-overlay>
    </div>
</template>


<script>
import { mapState, mapActions } from 'vuex';
import Swal from 'sweetalert2'
    export default {
        data() {
            return {
                status: 'blue', // This value will change dynamically
                gen_overlay: false,
                version: '1.0', // * for version Updates
                show: false, // * for showing and hiding passcode
                employee_id: "",  // * user
                employee_password: "", // * passcode
                rules: [v => v.length <= 5 || 'Already Exceed in 5 digits!'],
                user : "old",
                card_tile : "LOGIN",
                login_btn : "SIGN UP",
                message : "Don't have an account?",
                obj : {EmployeeName : "",DepartmentName : "",SectionName : "",},
                new_data : []
            }
        },
        computed: {
            ...mapState([
                "userInfo",
                "team_items",
            ]),

            new_team_items(){
                return this.$store.getters.team_items
            },

            statusClass() {
                // Map different status values to corresponding Vuetify color classes
                if(this.card_tile == "LOGIN"){
                    this.status = 'blue'
                }else{
                    this.status = 'green'
                }
                return {
                    'blue': 'primary', // CSS class for green color
                    'green': 'green', // CSS class for yellow color
                }[this.status];
            },

        },

        mounted() {
            this.$store.dispatch('getTeams');
            this.deleteKeyItems([]);
        },

        methods: {
            ...mapActions([
                "storeUserInfo",
                "deleteKeyItems"
                // "action_monitoring",
            ]),

            manual() {
                window.open(
                    "http://hrdapps65:5050/rems/carte-pcg-v1/-/wikis/System-Manual",
                    "_blank"
                );
            },

            signup(){
                this.gen_overlay = true;
                if(this.user == "old"){
                    this.user = "new";
                    this.card_tile = "REGISTER";
                    this.message = "Have an account?"
                    this.login_btn = "LOGIN";
                    this.employee_id = ""
                    this.employee_password = ""
                    this.obj = {EmployeeName : "",DepartmentName : "",SectionName : ""}
                    setTimeout(() => {
                        this.gen_overlay = false
                    }, 200)
                }else{
                    this.user = "old";
                    this.card_tile = "LOGIN";
                    this.message = "Don't have an account?"
                    this.login_btn = "SIGN UP";
                    this.employee_id = ""
                    this.employee_password = ""
                    setTimeout(() => {
                        this.gen_overlay = false
                    }, 200)
                }
            },

            async getUserInfos(){
                await axios({
                    method : 'post',
                    url : 'api/getUserData',
                    data : {
                        employee_id : this.employee_id
                    }
                }).then(res => {
                    console.log(res.data,'result')
                    this.new_data = res.data
                }).catch(err => {
                    Swal.fire("Employee id doesn't exist.", '', '');
                    // alert("employee id doesn't exist")
                    console.log('eerror',err);
                    this.gen_overlay = false;
                })
            },

            async login(){
                if(!this.employee_id.trim()){
                    Swal.fire({
                        title: 'Insert Employee Id!',
                        icon: 'error',
                        showConfirmButton:false,
                        timer: 1400,
                    });
                    return false
                }//end of if

                if(!this.employee_password.trim()){
                    Swal.fire({
                        title: 'Insert password!',
                        icon: 'error',
                        showConfirmButton:false,
                        timer: 1400,
                    });
                    return false
                }//end of if
                await this.getUserInfos();
                if(this.user == "new"){
                    if(!this.obj.pcg_team_code){
                        Swal.fire({
                            title: 'Insert Team!',
                            icon: 'error',
                            showConfirmButton:false,
                            timer: 1400,
                        });
                        return false
                    }//end of if

                    this.gen_overlay = true;
                    if(this.new_data.length > 0){
                        this.new_data[0].pcg_team_code = this.obj.pcg_team_code
                        axios({
                            method : "POST",
                            url : 'api/insertUserData',
                            data : {
                                user_data : this.new_data,
                                user_password : this.employee_password,

                            }
                        }).then(res => {
                            console.log('insertUserData',res.data)
                            if(res.data == 1){
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Saved!',
                                    showConfirmButton: false,
                                    timer: 1000
                                });
                                this.gen_overlay = false;
                                return false;
                            }

                            if(res.data == 'existing'){
                                Swal.fire('Employee already Exist!', '', '');
                                this.gen_overlay = false;
                                
                            }
                        }).catch(err => {
                            console.log('error insertUserData',err)
                        })
                    }else{
                        Swal.fire("Employee not possible to register.", '', '');
                        this.gen_overlay = false;
                    }
                    
                }else{
                    await axios({
                        method : "post",
                        url : 'api/updateUserData',
                        data : {
                            user_id : this.employee_id,
                            user_password : this.employee_password,
                            update_table : this.new_data
                        }
                    }).then(res => {
                        console.log('updateUserData',res.data);
                        
                        if(res.data.length == 1){
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            })
                            Toast.fire({
                                icon: 'success',
                                title: 'Signed in successfully'
                            })
                            delete res.data[0].password
                            this.storeUserInfo(res.data[0])
                            // this.action_monitoring()
                            this.$router.push("/Monitoring")

                        }else{
                            Swal.fire('Error', 'The username or password you entered is incorrect.', 'info')
                        }
                    }).catch(err => {
                        console.log(err,'error login fetching data')
                        alert('error in ip address');
                    })
                }
            }, 
        },
    }
</script>

<style >
#app{
    background-size: cover;
    /* background-color: coral; */
    background-image:
    linear-gradient(to bottom, rgba(248, 248, 252, 0.52), rgba(135, 135, 135, 0.73));
    /*,
    url("https://images.pexels.com/photos/255379/pexels-photo-255379.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1") ; */
    /* background-repeat: no-repeat;   */
    background-position: center;
    /* background-attachment: fixed; */
}
</style>