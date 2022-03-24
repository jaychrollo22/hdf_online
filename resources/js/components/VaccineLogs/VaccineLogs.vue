<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">VACCINATION DETAILS</h2>
                        <div class="d-flex align-items-center font-weight-bold my-2">
                            <a href="#" class="opacity-75 hover-opacity-100">
                                <i class="flaticon2-shelter text-white icon-1x"></i>
                            </a>
                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Logs</a>
                            <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                            <a href="" class="text-white text-hover-white opacity-75 hover-opacity-100">Lists</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <a href="/" class="btn btn-md btn-transparent-white font-weight-bold py-3 px-6 mr-2">Back</a>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column-fluid">
            <div class="container">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="checkbox" v-model="user_vaccination_detail.is_johnson" true-value="true" false-value="false" @change="isTick">
                                <span>Please tick if your vaccine brand is Johnson & Johnson</span>
                            </div>
                            <div class="col-md-12" v-if="user_vaccination_detail.is_approved">
                                <span class="label label-lg label-light-primary label-inline font-weight-bold py-4 float-right" v-if="user_vaccination_detail.is_approved == 'For Verification'">
                                    For Verification
                                </span>
                                <span class="label label-lg label-light-primary label-inline font-weight-bold py-4 float-right" v-else>
                                    {{user_vaccination_detail.is_approved}}
                                </span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="">1st Dose Brand</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="1st Dose Brand" v-model="user_vaccination_detail.first_dose_brand" >
                                </div>
                                <span class="text-danger" v-if="errors.first_dose_brand">{{ errors.first_dose_brand[0] }}</span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="">1st Dose Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="user_vaccination_detail.first_dose_date" >
                                </div>
                                <span class="text-danger" v-if="errors.first_dose_date">{{ errors.first_dose_date[0] }}</span>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label for="">1st Dose Location</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-map"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Location" v-model="user_vaccination_detail.first_dose_city" >
                                </div>
                                <span class="text-danger" v-if="errors.first_dose_city">{{ errors.first_dose_city[0] }}</span>
                            </div>
                            <div class="col-md-4 mt-3" v-if="user_vaccination_detail.is_johnson == 'false'">
                                <label for="">2nd Dose Brand</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-hashtag"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="2nd Dose Brand" v-model="user_vaccination_detail.second_dose_brand" >
                                </div>
                                <span class="text-danger" v-if="errors.first_dose_brand">{{ errors.first_dose_brand[0] }}</span>
                            </div>
                            <div class="col-md-4 mt-3" v-if="user_vaccination_detail.is_johnson == 'false'">
                                <label for="">2nd Dose Date</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="user_vaccination_detail.second_dose_date" >
                                </div>
                                <span class="text-danger" v-if="errors.second_dose_date">{{ errors.second_dose_date[0] }}</span>
                            </div>
                            <div class="col-md-4 mt-3" v-if="user_vaccination_detail.is_johnson  == 'false'">
                                <label for="">2nd Dose Location</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-map"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Location" v-model="user_vaccination_detail.second_dose_city" >
                                </div>
                                <span class="text-danger" v-if="errors.second_dose_city">{{ errors.second_dose_city[0] }}</span>
                            </div>

                            <div class="col-md-4 mt-2">
                                <label for="">Attachment</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-file"></i>
                                        </span>
                                    </div>
                                    <input type="file" id="attachment" class="form-control" ref="file" accept="*" v-on:change="vaccineFileHandleFileUpload()"/>
                                    <a v-if="user_vaccination_detail.attachment" class="btn btn-outline-primary ml-1" :href="'storage/vaccine_files/'+user_vaccination_detail.attachment" target="_blank">View File</a>
                                </div>
                                <span class="text-danger" v-if="errors.attachment">{{ errors.attachment[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary float-right" @click="updateUserVaccineDetails">Update</button>
                    </div>
                </div>

                <div class="card card-custom gutter-b mt-2">
                    <div class="card-body">
                        <a href="#" class="btn btn-md btn-primary float-right" @click="newVaccineLog">Add New</a>
                        Booster Logs
                        <div class="table-responsive">
                            <table class="table table-checkable" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <th>Brand</th>
                                        <th>Date Given</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, i) in filteredQueues" :key="i" >
                                       <td><small>{{item.brand}}</small></td>
                                       <td><small>{{item.date_given}}</small></td>
                                       <td align="center">
                                           <i class="fas fa-pen text-primary" style="cursor:pointer;" @click="editVaccineLog(item)"></i>
                                       </td>
                                    </tr>
                                    <tr v-if="loading">
                                        <td colspan="6">
                                            <div class="col-md-3 pt-5 pb-5">
                                                <div class="spinner spinner-left spinner-primary spinner-lg">
                                                    <span class="ml-15">Loading.. Please wait..</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="add-vaccine-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">ADD BOOSTER DETAILS</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label for="">Brand</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Brand (Pfizer)" v-model="vaccine_log.brand" >
                            </div>
                            <span class="text-danger" v-if="errors.brand">{{ errors.brand[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Date Given</label>
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="vaccine_log.date_given">
                                </div>
                                <span class="text-danger" v-if="errors.date_given">{{ errors.date_given[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="saveVaccineLog" :disabled="saveDisable">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-vaccine-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">EDIT BOOSTER DETAILS</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label for="">Brand</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Brand (Pfizer)" v-model="vaccine_log_edit.brand" >
                            </div>
                            <span class="text-danger" v-if="errors.brand">{{ errors.brand[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Date Given</label>
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="vaccine_log_edit.date_given">
                                </div>
                                <span class="text-danger" v-if="errors.date_given">{{ errors.date_given[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="updateVaccineLog" :disabled="saveDisable">Save</button>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script>
    export default {
        data() {
            return {
                keywords : '',
                vaccine_logs: [],
                errors: [],
                vaccine_log: {
                    brand : '',
                    date_given : '',
                    date_expiry : '',
                    dose : '',
                },
                vaccine_log_edit: {
                    id : '',
                    brand : '',
                    date_given : '',
                    date_expiry : '',
                    dose : '',
                },
                loading : false,
                saveDisable: false,

                currentPage: 0,
                itemsPerPage: 10, 

                user_vaccination_detail : {
                    'id':'',
                    'is_johnson':'false',
                    'first_dose_brand':'',
                    'first_dose_date':'',
                    'first_dose_city':'',
                    'second_dose_brand':'',
                    'second_dose_date':'',
                    'second_dose_city':'',
                    'attachment':'',
                },
            }
        },
        created () {
            this.getVaccineLogs();
            this.getUserVaccinationDetails();
        },
        methods: {
            vaccineFileHandleFileUpload(){
                var file = document.getElementById("attachment");
                this.user_vaccination_detail.attachment = file.files[0];
            },
            updateUserVaccineDetails(){
                let v = this;
                Swal.fire({
                title: 'Are you sure you want to update this vaccine log?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('id', v.user_vaccination_detail.id ? v.user_vaccination_detail.id : "");    
                        formData.append('is_johnson', v.user_vaccination_detail.is_johnson ? v.user_vaccination_detail.is_johnson : "false");    
                        formData.append('first_dose_brand', v.user_vaccination_detail.first_dose_brand ? v.user_vaccination_detail.first_dose_brand : "");    
                        formData.append('first_dose_date', v.user_vaccination_detail.first_dose_date ? v.user_vaccination_detail.first_dose_date : "");    
                        formData.append('first_dose_city', v.user_vaccination_detail.first_dose_city ? v.user_vaccination_detail.first_dose_city : "");  
                        formData.append('attachment', v.user_vaccination_detail.attachment ? v.user_vaccination_detail.attachment : "");  
                        
                        if(v.user_vaccination_detail.is_johnson == 'true'){
                            formData.append('second_dose_brand', "");    
                            formData.append('second_dose_date', "");    
                            formData.append('second_dose_city', "");    
                        }else{
                            formData.append('second_dose_brand', v.user_vaccination_detail.second_dose_brand ? v.user_vaccination_detail.second_dose_brand : "");    
                            formData.append('second_dose_date', v.user_vaccination_detail.second_dose_date ? v.user_vaccination_detail.second_dose_date : "");    
                            formData.append('second_dose_city', v.user_vaccination_detail.second_dose_city ? v.user_vaccination_detail.second_dose_city : "");    
                        }
                        

                        axios.post(`/update-user-vaccination-details`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'Vaccination Details',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                    // timer : 5000
                                }).then(okay => {
                                    if (okay) {
                                        v.user_vaccination_detail = response.data.user_vaccination_details;
                                    }
                                })
                            }
                        })
                    }
                })
                
            },
            isTick : function(e) {
                let v = this;
                if(v.user_vaccination_detail.is_johnson == 'true'){
                    v.user_vaccination_detail.first_dose_brand = "Johnson & Johnson";
                    v.user_vaccination_detail.first_dose_date = "";
                    v.user_vaccination_detail.first_dose_city = "";
                }else{
                    v.user_vaccination_detail.first_dose_brand = "";
                    v.user_vaccination_detail.first_dose_date = "";
                    v.user_vaccination_detail.first_dose_city = "";
                }
            },
            getUserVaccinationDetails(){
                let v = this;
                axios.get('/get-user-vaccination-details')
                .then(response => { 
                    if(response.data){
                        v.user_vaccination_detail = response.data;
                    }
                   
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
            },
            //Vaccine Logs
            updateVaccineLog(){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to update this vaccine log?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('id', v.vaccine_log_edit.id ? v.vaccine_log_edit.id : "");    
                        formData.append('brand', v.vaccine_log_edit.brand ? v.vaccine_log_edit.brand : "");    
                        formData.append('date_given', v.vaccine_log_edit.date_given ? v.vaccine_log_edit.date_given : "");    
                        formData.append('date_expiry', v.vaccine_log_edit.date_expiry ? v.vaccine_log_edit.date_expiry : "");    
                        formData.append('dose', v.vaccine_log_edit.dose ? v.vaccine_log_edit.dose : "");
                        axios.post(`/update-vaccine-logs`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'Vaccine Log.',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                    // timer : 5000
                                }).then(okay => {
                                    if (okay) {
                                        $('#edit-vaccine-log-modal').modal('hide');
                                        v.clearAddEditFields();
                                        var index = v.vaccine_logs.findIndex(item => item.id == v.vaccine_log_edit.id);
                                        v.vaccine_logs.splice(index,1,response.data.user_vaccine_log);
                                    }
                                });
                            }
                        })
                    }else{

                    }
                });
            },
            editVaccineLog(vaccine_log){
                this.vaccine_log_edit.id = vaccine_log.id;
                this.vaccine_log_edit.brand = vaccine_log.brand;
                this.vaccine_log_edit.date_given = vaccine_log.date_given;
                this.vaccine_log_edit.date_expiry = vaccine_log.date_expiry;
                this.vaccine_log_edit.dose = vaccine_log.dose;
                $('#edit-vaccine-log-modal').modal('show');
            },
            getVaccineLogs() {
                let v = this;
                v.loading = true;
                v.vaccine_logs = [];
                axios.get('/get-all-vaccine-logs')
                .then(response => { 
                    v.vaccine_logs = response.data;
                    v.loading = false;

                    //  if(v.vaccine_logs.length == 0){
                    //     this.newVaccineLog();
                    // }
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
            },
            newVaccineLog(){
                this.clearAddEditFields();
                $('#add-vaccine-log-modal').modal('show');
            },
            clearAddEditFields(){
                this.vaccine_log.brand = '';
                this.vaccine_log.date_given = '';
                this.vaccine_log.date_expiry = '';
                this.vaccine_log.dose = '';

                this.vaccine_log_edit.id = '';
                this.vaccine_log_edit.brand = '';
                this.vaccine_log_edit.date_given = '';
                this.vaccine_log_edit.date_expiry = '';
                this.vaccine_log_edit.dose = '';
            },
            saveVaccineLog() {
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to save this vaccine log?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('brand', v.vaccine_log.brand ? v.vaccine_log.brand : "");    
                        formData.append('date_given', v.vaccine_log.date_given ? v.vaccine_log.date_given : "");    
                        formData.append('date_expiry', v.vaccine_log.date_expiry ? v.vaccine_log.date_expiry : "");    
                        formData.append('dose', v.vaccine_log.dose ? v.vaccine_log.dose : "");
                        axios.post(`/save-vaccine-logs`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'Vaccine Log.',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                    // timer : 5000
                                }).then(okay => {
                                    if (okay) {
                                        $('#add-vaccine-log-modal').modal('hide');
                                        v.clearAddEditFields();
                                        v.vaccine_logs.unshift(response.data.user_vaccine_log);   
                                    }
                                });
                            }
                        })
                    }else{

                    }
                });
            },
            setPage(pageNumber) {
                this.currentPage = pageNumber;
            },
            resetStartRow() {
                this.currentPage = 0;
            },
            showPreviousLink() {
                return this.currentPage == 0 ? false : true;
            },
            showNextLink() {
                return this.currentPage == (this.totalPages - 1) ? false : true;
            },
        },
        computed: {
            filteredVaccineLogs(){
                let v = this;
                if(v.vaccine_logs){
                    return Object.values(v.vaccine_logs).filter(vaccine_log => {
                        return vaccine_log.brand.toLowerCase().includes(this.keywords.toLowerCase()) 
                    });
                }else{
                    return [];
                }
            },
            totalPages() {
                return Math.ceil(Object.values(this.filteredVaccineLogs).length / this.itemsPerPageDoor)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredVaccineLogs.slice(index, index + this.itemsPerPage);

                if(this.currentPage >= this.totalPages) {
                    this.currentPage = this.totalPages - 1
                }

                if(this.currentPage == -1) {
                    this.currentPage = 0;
                }

                return queues_array;
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>