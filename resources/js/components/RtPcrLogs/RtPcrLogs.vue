<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">TEST RESULTS</h2>
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
                    <a href="#" class="btn btn-transparent-white font-weight-bold py-3 px-6 mr-2" @click="newRtPcrLog">Add New</a>
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
                                        <th>Date</th>
                                        <th>File</th>
                                        <th>Results</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                     <tr v-for="(item, i) in filteredQueues" :key="i" >
                                       <td><small>{{item.rt_pcr_date}}</small></td>
                                       <td>
                                           <a :href="'storage/rt_pcr_file/'+item.rt_pcr_file" target="_blank">
                                                <small>View File</small> 
                                            </a>
                                        </td>
                                       <td>
                                           <div v-if="item.results">
                                               <small v-if="item.results == '1'">Positive</small>
                                               <small v-if="item.results == '0'">Negative</small>
                                           </div>
                                           
                                        </td>
                                       <td align="center">
                                           <i class="fas fa-pen text-primary" style="cursor:pointer;" @click="editRtPcrLog(item)"></i>
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

    <div class="modal fade" id="add-rt-pcr-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Add RT-PCR / ANTIGEN</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label for="">Testing Date</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" placeholder="Date" v-model="rt_pcr_log.rt_pcr_date" >
                            </div>
                            <span class="text-danger" v-if="errors.rt_pcr_date">{{ errors.rt_pcr_date[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Attachment</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="file" id="rt_pcr_file_add" class="form-control" ref="file" accept="*" v-on:change="rtPcrFileHandleFileUploadAdd()"/>
                            </div>
                            <span class="text-danger" v-if="errors.rt_pcr_date">{{ errors.rt_pcr_date[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Results</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <select class="form-control" v-model="rt_pcr_log.results">
                                    <option value="">Choose</option>
                                    <option value="0">Negative</option>
                                    <option value="1">Positive</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors.results">{{ errors.results[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="saveRtPcrLog" :disabled="saveDisable">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-rt-pcr-log-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-md modal-fixed" role="document">
            <div class="modal-content">
                <div>
                    <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div> 
                <div class="modal-header">
                    <h2 class="col-12 modal-title text-center">Edit RT-PCR / ANTIGEN</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <label for="">Testing Date</label>
                           <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                </div>
                                <input type="date" class="form-control" placeholder="Date" v-model="rt_pcr_log_edit.rt_pcr_date" >
                            </div>
                            <span class="text-danger" v-if="errors.rt_pcr_date">{{ errors.rt_pcr_date[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Attachment</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <input type="file" id="rt_pcr_file_edit" class="form-control" ref="file" accept="*" v-on:change="rtPcrFileHandleFileUploadEdit()"/>
                            </div>
                            <span class="text-danger" v-if="errors.rt_pcr_file">{{ errors.rt_pcr_file[0] }}</span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for="">Results</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style="cursor:pointer;width:40px;">
                                        <i class="fas fa-file"></i>
                                    </span>
                                </div>
                                <select class="form-control" v-model="rt_pcr_log_edit.results">
                                    <option value="">Choose</option>
                                    <option value="0">Negative</option>
                                    <option value="1">Positive</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors.results">{{ errors.results[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md" @click="updateRtPcrLog" :disabled="saveDisable">Save</button>
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
                rt_pcr_logs: [],
                errors: [],
                rt_pcr_log : {
                    'rt_pcr_date' : '',
                    'rt_pcr_file' : '',
                    'results' : '',
                },
                rt_pcr_log_edit : {
                    'id' : '',
                    'rt_pcr_date' : '',
                    'rt_pcr_file' : '',
                    'results' : '',
                },

                loading : false,
                saveDisable: false,

                currentPage: 0,
                itemsPerPage: 10, 
            }
        },
        created () {
            this.getRtPcrLogs();
        },
        methods: {
            rtPcrFileHandleFileUploadAdd(){
                var file = document.getElementById("rt_pcr_file_add");
                this.rt_pcr_log.rt_pcr_file = file.files[0];
            },
            rtPcrFileHandleFileUploadEdit(){
                var file = document.getElementById("rt_pcr_file_edit");
                this.rt_pcr_log_edit.rt_pcr_file = file.files[0];
            },
            updateRtPcrLog(){
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to update this RT PCR log?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('id', v.rt_pcr_log_edit.id ? v.rt_pcr_log_edit.id : "");    
                        formData.append('rt_pcr_date', v.rt_pcr_log_edit.rt_pcr_date ? v.rt_pcr_log_edit.rt_pcr_date : "");    
                        formData.append('rt_pcr_file', v.rt_pcr_log_edit.rt_pcr_file ? v.rt_pcr_log_edit.rt_pcr_file : "");    
                        formData.append('results', v.rt_pcr_log_edit.results ? v.rt_pcr_log_edit.results : "");  
                        
                        axios.post(`/update-rt-pcr-logs`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'RT PCR Log.',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                    // timer : 5000
                                }).then(okay => {
                                    if (okay) {
                                        $('#edit-rt-pcr-log-modal').modal('hide');
                                        v.clearAddEditFields();
                                        var index = v.rt_pcr_logs.findIndex(item => item.id == v.rt_pcr_log_edit.id);
                                        v.rt_pcr_logs.splice(index,1,response.data.user_rt_pcr_log);
                                    }
                                });
                            }
                        })
                    }else{

                    }
                });
            },
            editRtPcrLog(rt_pcr_log){
                this.rt_pcr_log_edit.id = rt_pcr_log.id;
                this.rt_pcr_log_edit.rt_pcr_date = rt_pcr_log.rt_pcr_date;
                this.rt_pcr_log_edit.rt_pcr_file = rt_pcr_log.rt_pcr_file;
                this.rt_pcr_log_edit.results = rt_pcr_log.results;
                document.getElementById("rt_pcr_file_edit").value = '';
                $('#edit-rt-pcr-log-modal').modal('show');
            },
            getRtPcrLogs() {
                let v = this;
                v.loading = true;
                v.rt_pcr_logs = [];
                axios.get('/get-all-rt-pcr-logs')
                .then(response => { 
                    v.rt_pcr_logs = response.data;
                    v.loading = false;
                })
                .catch(error => { 
                    v.errors = error.response.data.error;
                })
            },
            newRtPcrLog(){
                this.clearAddEditFields();
                $('#add-rt-pcr-log-modal').modal('show');
            },
            clearAddEditFields(){
                this.rt_pcr_log.rt_pcr_date = '';
                this.rt_pcr_log.rt_pcr_file = '';
                this.rt_pcr_log.results = '';

                this.rt_pcr_log_edit.id = '';
                this.rt_pcr_log_edit.rt_pcr_date = '';
                this.rt_pcr_log_edit.rt_pcr_file = '';
                this.rt_pcr_log_edit.results = '';

                document.getElementById("rt_pcr_file_edit").value = '';
                document.getElementById("rt_pcr_file_add").value = '';
            },
            saveRtPcrLog() {
                let v = this;
                Swal.fire({
                    title: 'Are you sure you want to save this RT PCR log?',
                    icon: 'question',
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: `No`,
                    }).then((result) => {
                    if (result.isConfirmed) {
                        let formData = new FormData();
                        formData.append('rt_pcr_date', v.rt_pcr_log.rt_pcr_date ? v.rt_pcr_log.rt_pcr_date : "");    
                        formData.append('rt_pcr_file', v.rt_pcr_log.rt_pcr_file ? v.rt_pcr_log.rt_pcr_file : "");    
                        formData.append('results', v.rt_pcr_log.results ? v.rt_pcr_log.results : "");    
                        axios.post(`/save-rt-pcr-logs`, formData)
                        .then(response => {
                            if(response.data.status == 'saved'){
                                Swal.fire({
                                    title: 'RT PCR Log.',
                                    text: 'Successfully saved.',
                                    icon: 'success',
                                    confirmButtonText: 'Okay',
                                }).then(okay => {
                                    if (okay) {
                                        $('#add-rt-pcr-log-modal').modal('hide');
                                        v.clearAddEditFields();
                                        v.rt_pcr_logs.unshift(response.data.user_rt_pcr_log);   
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
            filteredRtPcrLogs(){
                let v = this;
                if(v.rt_pcr_logs){
                    return Object.values(v.rt_pcr_logs).filter(rt_pcr_log => {
                        return rt_pcr_log.results.toLowerCase().includes(this.keywords.toLowerCase()) 
                    });
                }else{
                    return [];
                }
            },
            totalPages() {
                return Math.ceil(Object.values(this.filteredRtPcrLogs).length / this.itemsPerPageDoor)
            },
            filteredQueues() {
                var index = this.currentPage * this.itemsPerPage;
                var queues_array = this.filteredRtPcrLogs.slice(index, index + this.itemsPerPage);

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