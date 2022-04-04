<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">CASE LOGS</h2>
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
                    <a href="#" class="btn btn-transparent-white font-weight-bold py-3 px-6 mr-2" @click="newCase">Add New</a>
                    <a href="/" class="btn btn-md btn-transparent-white font-weight-bold py-3 px-6 mr-2">Generate QR</a>
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
                                        <th>DETAILS</th>
                                        <th>LOGS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, i) in filteredQueues" :key="i" >
                                       <td>
                                           <small><b>Case Date :</b>  {{item.case_date}}</small><br>
                                           <small><b>Location :</b>  {{item.location}}</small><br>
                                           <small><b>Temperature :</b>  {{item.temperature}}</small><br>
                                           <small><b>Initial Findings :</b>  {{item.initial_findings}}</small><br>
                                           <small v-if="item.final_findings && item.final_findings !='OTHERS'"><b>Final Findings :</b>  {{item.final_findings}}</small>
                                           <small v-else><b>Final Findings :</b>  {{item.final_finding_others}}</small><br>
                                           <small><b>Status : </b> {{item.status}}</small>
                                        </td>
                                       <td>
                                           <div v-if="item.case_logs.length > 0">
                                               <small class="text-primary"><a href="#" class="btn btn-sm btn-primary" @click="viewCaseLog(item)">{{item.case_logs.length}} Logs</a></small>  <small><a href="#" class="btn btn-sm btn-success" @click="addNew(item)">Add New</a></small>
                                           </div>
                                           <div v-else>
                                               <small><a href="#" @click="addNew(item)" class="btn btn-sm btn-success">Add New</a></small>
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


    <div class="modal fade" id="add-case-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Add Case Log</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label for="">Log Date</label>
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="case_log.log_date">
                                </div>
                                <span class="text-danger" v-if="errors.log_date">{{ errors.log_date[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Temperature</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Temperature" v-model="case_log.temperature" >
                            </div>
                            <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Oximeter</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Oximeter" v-model="case_log.oximeter" >
                            </div>
                            <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <label for="">Others/Remarks</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Others/Remarks" v-model="case_log.others">
                            </div>
                            <span class="text-danger" v-if="errors.others">{{ errors.others[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="saveCaseLog" :disabled="saveDisable">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-case-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Edit Case Log</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label for="">Log Date</label>
                            <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="cursor:pointer;width:40px;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" v-model="case_log_edit.log_date">
                                </div>
                                <span class="text-danger" v-if="errors.log_date">{{ errors.log_date[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Temperature</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="Temperature" v-model="case_log_edit.temperature" >
                            </div>
                            <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Oximeter</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Oximeter" v-model="case_log_edit.oximeter" >
                            </div>
                            <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                        </div>
                        
                        <div class="col-md-12 mt-2">
                            <label for="">Others/Remarks</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Others/Remarks" v-model="case_log_edit.others">
                            </div>
                            <span class="text-danger" v-if="errors.others">{{ errors.others[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="updateCaseLog" :disabled="saveDisable">Save</button>
                    <button class="btn btn-default btn-md" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="view-case-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-checkable" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>LOG DETAILS</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in selected_case_log.case_logs" :key="i" >
                                    <td>
                                        <small>Log Date : <strong>{{ item.log_date }}</strong> </small>  <br>
                                        <small>Temperature : <strong>{{ item.temperature }}</strong> </small> <br>
                                        <small>Oximeter : <strong>{{ item.oximeter }}</strong> </small> <br>
                                        <small>Others/Remarks : <strong>{{ item.others }}</strong> </small>
                                    </td>
                                    <td align="center">
                                        <button class="btn btn-sm btn-primary mr-2" @click="editCaseLog(item)"><i class="fas fa-pen text-white" style="cursor:pointer;" ></i></button>
                                        <button class="btn btn-sm btn-danger mr-2" @click="removeCaseLog(item)"><i class="fas fa-trash text-white" style="cursor:pointer;" ></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new-case-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-body">
                    <h4>Add New Case</h4>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="">Case Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" v-model="case_add.case_date" >
                            </div>
                            <span class="text-danger" v-if="errors.case_date">{{ errors.case_date[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="">Location</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <select class="form-control" v-model="case_add.location" id="location">
                                    <option value="">Choose Location</option>
                                    <option value="Capiz">Capiz</option>
                                    <option value="Davao">Davao</option>
                                    <option value="Bacolod">Bacolod</option>
                                    <option value="Iloilo">Iloilo</option>
                                    <option value="LFMI/Farms">LFMI/Farms</option>
                                    <option value="Bataan">Bataan</option>
                                    <option value="BGC">BGC</option>
                                    <option value="Manila">Manila</option>
                                    <option value="Isabela">Isabela</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors.location">{{ errors.location[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="">Temperature</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="number" class="form-control" placeholder="00.00" v-model="case_add.temperature" >
                            </div>
                            <span class="text-danger" v-if="errors.temperature">{{ errors.temperature[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="">Oximeter</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Oximeter" v-model="case_add.oximeter" >
                            </div>
                            <span class="text-danger" v-if="errors.oximeter">{{ errors.oximeter[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="">Initial Findings</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="(COUGH/FEVER)" v-model="case_add.initial_findings" >
                            </div>
                            <span class="text-danger" v-if="errors.initial_findings">{{ errors.initial_findings[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label for="">Remarks</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" placeholder="Remarks" v-model="case_add.remarks" >
                            </div>
                            <span class="text-danger" v-if="errors.remarks">{{ errors.remarks[0] }}</span>
                        </div>

                        <div class="col-md-12 mt-3">
                            <span class="mb-2">Have you been in the office for the past 5 days?</span>  <br>
                            <input type="radio" id="YES" v-model="case_add.is_office" value="YES">
                            <label for="YES">YES</label><br>
                            <input type="radio" id="NO" v-model="case_add.is_office" value="NO">
                            <label for="NO">NO</label><br>
                        </div>

                        <div class="col-md-12" v-if="case_add.is_office == 'YES'">
                            <span>Please list down the names, that you have been in close contact.</span>
                            <div v-for="(item,index) in case_close_contacts" v-bind:key="index">
                                <select class="form-control mt-2" v-model="item.contact_user_id">
                                    <option value="">Choose Name</option>
                                    <option v-for="(user,x) in users" v-bind:key="x" :value="user.id">{{user.name}}</option>
                                </select>
                            </div>
                            <button class="btn btn-sm btn-warning float-right mt-2 mr-2" @click="addNewLineCloseContact">New</button>
                            <button class="btn btn-sm btn-danger float-right mt-2 mr-2" @click="removeNewLineCloseContact">Remove</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" @click="saveNewCase" :disabled="disableNewCase">Save</button>
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
                cases: [],
                errors : [],
                currentPage: 0,
                itemsPerPage: 10, 

                loading : false,


                saveDisable : false,
                case_log : {
                    cases_id : '',
                    log_date : '',
                    temperature : '',
                    oximeter : '',
                    others : ''
                },
                case_log_edit : {
                    id : '',
                    cases_id : '',
                    log_date : '',
                    temperature : '',
                    oximeter : '',
                    others : ''
                },
                selected_case_log : [],

                case_add : {
                    case_date : '',
                    temperature : '',
                    oximeter : '',
                    initial_findings : '',
                    location : '',
                    remarks : '',
                    is_office : '',
                },

                disableNewCase : false,

                case_close_contacts : [{
                    contact_user_id : ''
                }],

                users : []
            }
        },
        created () {
            this.getCaseLogs();
            this.getUsers();
        },
        methods: {
            getUsers(){
                let v = this;
                v.users = [];
                axios.get('/get-all-users')
                .then(response => { 
                    v.users = response.data;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
            },
            addNewLineCloseContact(){
                this.case_close_contacts.push({
                    contact_user_id : ''
                })
            },
            removeNewLineCloseContact(){
                this.case_close_contacts.splice(-1);
            },
            saveNewCase(){
                let v = this;
                v.disableNewCase = true;
                Swal.fire({
                title: 'Are you sure you want to save this case log?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: `Yes`,
                denyButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('case_date', v.case_add.case_date ? v.case_add.case_date : "");
                        formData.append('temperature', v.case_add.temperature ? v.case_add.temperature : "");
                        formData.append('oximeter', v.case_add.oximeter ? v.case_add.oximeter : "");
                        formData.append('initial_findings', v.case_add.initial_findings ? v.case_add.initial_findings : "");
                        formData.append('location', v.case_add.location ? v.case_add.location : "");
                        formData.append('remarks', v.case_add.remarks ? v.case_add.remarks : "");
                        formData.append('is_office', v.case_add.is_office ? v.case_add.is_office : "");
                        formData.append('case_close_contacts', v.case_close_contacts ? JSON.stringify(v.case_close_contacts) : "");
                        axios.post(`/save-new-case`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'Case has been saved.',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                }).then(okay => {
                                    if (okay) {
                                        $('#new-case-modal').modal('hide');
                                        v.cases.unshift(response.data.cases);   
                                        v.case_add.case_date = '';
                                        v.case_add.temperature = '';
                                        v.case_add.oximeter = '';
                                        v.case_add.initial_findings = '';
                                        v.case_add.remarks = '';
                                        v.case_add.location = '';
                                        v.case_add.is_office = '';
                                        v.disableNewCase = false;
                                    }
                                });
                            }else{
                                v.disableNewCase = false;
                            }
                        })
                    }else{
                        v.disableNewCase = false;
                    }
                })
            },
            newCase(){
                this.case_add.case_date = '';
                this.case_add.temperature = '';
                this.case_add.initial_findings = '';
                this.case_add.remarks = '';
                this.case_add.location = '';
                this.case_add.is_office = '';
                this.case_close_contacts = [];
                this.case_close_contacts.push({
                    contact_user_id : ''
                })
                $('#new-case-modal').modal('show');
            },
            removeCaseLog(case_log){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to remove this case log?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('id', case_log.id);
                        formData.append('cases_id', case_log.cases_id);
                        axios.post(`/remove-case-logs`, formData)
                        .then(response => {
                            if(response.data.status == 'removed'){
                                Swal.fire({
                                    title: 'Case Log.',
                                    text: 'Successfully removed.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                }).then(okay => {
                                    if (okay) {
                                        var index = v.cases.findIndex(item => item.id == case_log.cases_id);
                                        v.cases.splice(index,1,response.data.cases);  

                                        var index_case_log = v.selected_case_log.case_logs.findIndex(item => item.id == case_log.id);
                                        v.selected_case_log.case_logs.splice(index_case_log,1);  

                                        if(v.selected_case_log.case_logs.length == 0){
                                            $('#view-case-log-modal').modal('hide');
                                        }
                                    }
                                });
                            }
                        })
                    }
                })
            },
            updateCaseLog(){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to save this case log?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('id', v.case_log_edit.id ? v.case_log_edit.id : "");    
                        formData.append('cases_id', v.case_log_edit.cases_id ? v.case_log_edit.cases_id : "");    
                        formData.append('log_date', v.case_log_edit.log_date ? v.case_log_edit.log_date : "");    
                        formData.append('temperature', v.case_log_edit.temperature ? v.case_log_edit.temperature : "");    
                        formData.append('oximeter', v.case_log_edit.oximeter ? v.case_log_edit.oximeter : "");    
                        formData.append('others', v.case_log_edit.others ? v.case_log_edit.others : "");
                        axios.post(`/update-case-logs`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'Case Log.',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                }).then(okay => {
                                    if (okay) {
                                        $('#edit-case-log-modal').modal('hide');
                                        var index = v.cases.findIndex(item => item.id == v.case_log_edit.cases_id);
                                        v.cases.splice(index,1,response.data.cases);  
                                        v.clearAddEditFields();
                                    }
                                });
                            }
                        })
                        .catch(error => { 
                            v.errors = error.response.data.errors;
                        })
                    }
                });
            },
            editCaseLog(case_log_edit){
                this.case_log_edit.id = case_log_edit.id;
                this.case_log_edit.cases_id = case_log_edit.cases_id;
                this.case_log_edit.log_date = case_log_edit.log_date;
                this.case_log_edit.temperature = case_log_edit.temperature;
                this.case_log_edit.oximeter = case_log_edit.oximeter;
                this.case_log_edit.others = case_log_edit.others;
                $('#view-case-log-modal').modal('hide');
                $('#edit-case-log-modal').modal('show');
            },
            viewCaseLog(selected_case_log){
                this.selected_case_log = selected_case_log;
                $('#view-case-log-modal').modal('show');
            },
            saveCaseLog() {
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to save this case log?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('cases_id', v.case_log.cases_id ? v.case_log.cases_id : "");    
                        formData.append('log_date', v.case_log.log_date ? v.case_log.log_date : "");    
                        formData.append('temperature', v.case_log.temperature ? v.case_log.temperature : "");    
                        formData.append('oximeter', v.case_log.oximeter ? v.case_log.oximeter : "");    
                        formData.append('others', v.case_log.others ? v.case_log.others : "");
                        axios.post(`/save-case-logs`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'Case Log.',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                    // timer : 5000
                                }).then(okay => {
                                    if (okay) {
                                        $('#add-case-log-modal').modal('hide');
                                        var index = v.cases.findIndex(item => item.id == v.case_log.cases_id);
                                        v.cases.splice(index,1,response.data.cases);  
                                        v.clearAddEditFields();
                                    }
                                });
                            }
                        })
                        .catch(error => { 
                            v.errors = error.response.data.errors;
                        })
                    }
                });
            },
            addNew(case_log){
                let v = this;
                v.case_log.cases_id = case_log.id; 
                $('#add-case-log-modal').modal('show');
            },
            clearAddEditFields(){
                let v = this;
                v.case_log.cases_id = '';
                v.case_log.log_date = '';
                v.case_log.temperature = '';
                v.case_log.oximeter = '';
                v.case_log.others = '';    

                v.case_log_edit.cases_id = '';
                v.cacase_log_editse_log.log_date = '';
                v.case_log_edit.temperature = '';
                v.case_log_edit.oximeter = '';
                v.case_log_edit.others = '';    
            },
            getCaseLogs() {
                let v = this;
                v.loading = true;
                v.cases = [];
                axios.get('/get-all-case-logs')
                .then(response => { 
                    v.cases = response.data;
                    v.loading = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
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
            filteredCaseLogs(){
                let v = this;
                if(v.cases){
                    return Object.values(v.cases).filter(rt_pcr_log => {
                        return rt_pcr_log; 
                    });
                }else{
                    return [];
                }
            },
            totalPages() {
                return Math.ceil(Object.values(this.filteredCaseLogs).length / this.itemsPerPageDoor)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredCaseLogs.slice(index, index + this.itemsPerPage);

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