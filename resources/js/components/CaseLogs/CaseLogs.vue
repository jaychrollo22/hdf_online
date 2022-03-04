<template>
<div>
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <div class="d-flex flex-column">
                        <h2 class="text-white font-weight-bold my-2 mr-5">Case</h2>
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
                                        <th>CASE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, i) in filteredQueues" :key="i" >
                                       <td>
                                           <small><b>Date :</b>  {{item.case_date}}</small><br>
                                           <small><b>Initial Findings :</b>  {{item.initial_findings}}</small><br>
                                           <small v-if="item.final_findings && item.final_findings !='OTHERS'"><b>Final Findings :</b>  {{item.final_findings}}</small>
                                           <small v-else><b>Final Findings :</b>  {{item.final_finding_others}}</small><br>
                                        </td>
                                       <td><small>{{item.status}}</small></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
            }
        },
        created () {
            this.getCaseLogs();
        },
        methods: {
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