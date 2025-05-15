
 
 <script>
 import Swal from 'sweetalert2'
 import {mapActions,mapState} from 'vuex'
    export default {
       data: () => ({
         drawer: null,
         items:[
            // {text:'Home', icon:'mdi-home', to:'home'},
            //{text:'Home', icon:'mdi-home-outline', to:'hello'},
            //{text: 'Monitoring', icon: 'mdi-monitor-dashboard', to : 'pcgMonitoring'},
            //{text: 'Report', icon: 'mdi-archive-search-outline', to : 'pcgReport'},
            {text:'Monitoring', icon:'mdi-home', to:'Monitoring'},
            {text:'Report', icon:'mdi-monitor-dashboard', to:'Report'},
            {text: 'Chart', icon: 'mdi-chart-bar', to : 'pcgChart'},
            {text: 'Master', icon: 'mdi-table', to : 'master'},
            // {text: 'Login', icon: 'mdi-account', to : 'login'},
            {text: 'Checked', icon: 'mdi-check-outline', to : 'checked'},
            {text: 'Common Terms', icon: 'mdi-book-open-page-variant', to : 'common_terms'},
            
         ],
         rail: true,
         imageLoadSuccess: true,
       }),
      computed: {
         ...mapState([
            "userInfo",
         ]),
         
         filteredItems() {
            if (this.userInfo.admin === 0) {
               return this.items.filter(item => item.text !== 'Checked' && item.text !== 'Master');
            }else if(this.userInfo.admin === 2){
               return this.items.filter(item => item.text !== 'Checked');
            } else {
            return this.items;
            }
         },
      },
      methods: {
         ...mapActions([
            "storeUserInfo",
         ]),

         handleImageLoad() {
            this.imageLoadSuccess = true; // Image loaded successfully
         },

         handleImageError() {
            this.imageLoadSuccess = false; // Image load failed
         },
         
         Logout() {
            Swal.fire({
               title: "Are you sure you want to logout?",
               showCancelButton: true,
               showConfirmButton: true,
               allowOutsideClick: false,
               button: `Yes`,
            })
            .then((res) => {
               console.log(res);
               if (res.isConfirmed) {
                  // this.drawer = false;
                  this.storeUserInfo(null)
                  this.$router.push("/login");
               }
            });
         },
      },

    }
 </script>
 
 <style>
   .NavD {
      text-align: center;
      color: #ffffff;
      font-size: 15px;
   }

   .NavDr {
      text-align: center;
      color: #ffffff;
      font-size: 11px;
   }

   .footert {
      color: #717879;
      font-size: 12px;
      background-color: white;
   }

   .scrollmenu:hover {
      background-color: #ffffff;
      
   }
   .cmmss {
      max-width: 40px;
   
   }
   .cmmss1 {
      max-width: 32px;
      cursor : pointer;
   
   }
   
   .cmss {
      max-width: 100px;
   }

</style>


<template>
   <v-app id="inspire" class="app">
      <v-app-bar app clipped-left v-if="userInfo != null" class="blue-grey darken-3" >
         <!-- <v-img  @click="drawer = !drawer" 
         class="cmmss mr-5" src="./images/nav1.png"></v-img> -->
         <!-- <v-app-bar-nav-icon dark large style="color:rgb(228,203,163)"  @click="drawer = !drawer"></v-app-bar-nav-icon> -->
         <!-- <v-img class="cmss" @click="drawer = !drawer"  src="./images/pcgapp3.png"></v-img>
         <v-img @dblclick="$router.push({ path: '/master' })" class="cmss mr-1" src="./images/A1.png"></v-img> -->
         <img class="cmss mr-1" src="images/A1.png">
         <v-divider :thickness="4" vertical color="white" ></v-divider>
         <img class="cmss ml-1" src="images/A2.png">
         <!-- <v-img class="cmmss" src="./images/zz.png"></v-img> -->
         <!-- <v-img class="cmms" src="./images/pcg7.png"></v-img> -->
         
            <!-- <v-toolbar-title>
               <span style="display:block; font-family: Arial, Helvetica, sans-serif; color:white;">Planner's Confirmation Guide</span>
               <hr>
               <span  style="font-size:15px; text-align: center; display:block; font-family: Arial, Helvetica, sans-serif; color:white;">Carte Planning Section</span>
            </v-toolbar-title> -->
            <v-spacer></v-spacer>
            <v-subheader>
               <!-- <p class="white--text mr-2 mt-5">Welcome!</p> -->
               <h3 class="white--text mt-1">{{ userInfo.EmployeeName }}</h3>
           </v-subheader>
            <v-avatar style="border: 2px solid #BDBDBD" size="55px">
               <v-img
                  :src="`http://hrd-adminweb/photos/${userInfo.EmployeeCode}.jpg`"
                  @load="handleImageLoad"
                  @error="handleImageError"
                  v-if="imageLoadSuccess"
               ></v-img>
               <v-img v-else src="./images/default-avatar1.png"></v-img>
            </v-avatar>
            <v-icon title="logout" class="ml-2" color="rgb(228,203,163)" @click="Logout()">mdi-logout</v-icon>
      </v-app-bar>
      <v-navigation-drawer
         v-model="drawer"
         v-if="userInfo != null"
         clipped
         app
         mini-variant
         expand-on-hover
         width="180"
         class="blue-grey darken-1"
         permanent
       >
         <v-list nav dense>
            <v-list-item
               v-for="(item, i) in filteredItems"
               :key="i"
               :to="item.to" 
                  
               >
               <v-list-item-icon>
                  <v-icon  dark style="display:block; color: white;" >{{item.icon}}</v-icon>
               </v-list-item-icon>
               <v-list-item-content>
                  <v-list-item-title dark style="display:block; color: white;" >{{item.text}}</v-list-item-title>
               </v-list-item-content>
            </v-list-item>
         </v-list>
      </v-navigation-drawer>
 

      <v-main class="ma-2" >
         <router-view ></router-view>
      </v-main>
      <!-- <v-footer v-if="userInfo != null" style="background-color:white" class="justify-center" height="30px" inset app>
         <p class="footert">
             All Rights Reserved c 2023 | HRD (S) PTE. LTD.
         </p>
      </v-footer> -->

   </v-app>
 </template>