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
                    <a href="#" class="btn btn-md btn-transparent-white font-weight-bold py-3 px-6 mr-2" @click="newVaccineLog">Add New</a>
                    <a href="/" class="btn btn-md btn-transparent-white font-weight-bold py-3 px-6 mr-2">Back</a>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column-fluid">
            <div class="container">
                <div class="card card-custom gutter-b">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-checkable" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <th>Brand</th>
                                        <th>Date Given</th>
                                        <th>Date Expiry</th>
                                        <th>Dose</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, i) in filteredQueues" :key="i" >
                                       <td><small>{{item.brand}}</small></td>
                                       <td><small>{{item.date_given}}</small></td>
                                       <td><small>{{item.date_expiry}}</small></td>
                                       <td><small>{{item.dose}}</small></td>
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
                    <h2 class="col-12 modal-title text-center">ADD VACCINATION DETAILS</h2>
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
                        <div class="col-md-12 mt-2">
                            <label for="">Date Expiry</label>
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="vaccine_log.date_expiry">
                                </div>
                                <span class="text-danger" v-if="errors.date_expiry">{{ errors.date_expiry[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Dose</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-syringe"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Ex. 1st Dose" v-model="vaccine_log.dose">
                            </div>
                            <span class="text-danger" v-if="errors.dose">{{ errors.dose[0] }}</span>
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
                    <h2 class="col-12 modal-title text-center">EDIT VACCINATION DETAILS</h2>
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
                        <div class="col-md-12 mt-2">
                            <label for="">Date Expiry</label>
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="vaccine_log_edit.date_expiry">
                                </div>
                                <span class="text-danger" v-if="errors.date_expiry">{{ errors.date_expiry[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Dose</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-syringe"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Ex. 1st Dose" v-model="vaccine_log_edit.dose">
                            </div>
                            <span class="text-danger" v-if="errors.dose">{{ errors.dose[0] }}</span>
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
            }
        },
        created () {
            this.getVaccineLogs();
        },
        methods: {
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

                     if(v.vaccine_logs.length == 0){
                        this.newVaccineLog();
                    }
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