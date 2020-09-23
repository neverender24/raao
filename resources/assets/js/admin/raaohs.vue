<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">RAAO</h4>
                <div class="row">
                    <div class="form-group col-md-1">
                        <p>
                            <a
                                class
                                data-toggle="collapse"
                                href="#multiCollapseExample1"
                                role="button"
                                aria-expanded="false"
                                aria-controls="multiCollapseExample1"
                            >
                                <span class="fa fa-filter"></span>
                                Filter
                            </a>
                        </p>
                    </div>
                    <div class="form-group col-md-11">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model="tableData.search"
                            @input="getData()"
                            placeholder="Search description"
                        />
                    </div>
                </div>
                
                <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="more">
                        <div class="row">
                            <div class="form-group col-sm-12 col-md-3">
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    v-model="tableData.year"
                                    @input="getData()"
                                    placeholder="Year"
                                />
                            </div>
                            <div class="form-group col-sm-12 col-md-3">
                                <select
                                    v-model="tableData.ffunccod"
                                    @change="getData()"
                                    class="form-control form-control-sm"
                                >
                                    <option value>- select -</option>
                                    <option
                                        v-for="(item, index) in functions"
                                        :value="item.FFUNCCOD"
                                    >{{ item.FFUNCCOD }} - {{ item.FFUNCTION }}</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-3">
                                <select
                                    v-model="tableData.fund"
                                    @change="getData()"
                                    class="form-control form-control-sm"
                                >
                                    <option value>- select fund -</option>
                                    <option v-for="(item, index) in funds">{{ item.FFUNDDES }}</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 col-md-3">
                                <select
                                    v-model="tableData.appropriation"
                                    @change="getData()"
                                    class="form-control form-control-sm"
                                >
                                    <option value>- select appropration -</option>
                                    <option
                                        v-for="(item, index) in appropriations"
                                    >{{ item.FAPPTYPE }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <datatable
                    :columns="columns"
                    :sortKey="sortKey"
                    :sortOrders="sortOrders"
                    @sort="sortBy"
                >
                    <tbody>
                        <tr
                            v-for="(item, index) in data"
                            :key="item.recid"
                        >
                            <td>
                                <!-- <button class="btn btn-sm" @click="addSchedule(item.recid, item.FRAODESC)"><span class="fa fa-plus"></span></button> -->
                                <button class="btn btn-sm" @click="showRaaods(item.recid)"><span class="fa fa-list"></span></button>
                            </td>
                            <td>{{ item.function.FFUNCCOD }}</td>
                            <td>{{ item.source.FSOURCE }}</td>
                            <td>{{ item.FRAODESC }}</td>
                            <td style="text-align: right;">{{ formatPrice(sumApprop(item.approp)) }}</td>
                            <td style="text-align: right;">{{ formatPrice(sumAllot(item.allot)) }}</td>
                            <td style="text-align: right;">{{ formatPrice(sumOblig(item.oblig)) }}</td>
                            <td
                                style="text-align: right;"
                            >{{ formatPrice(allotBalance(sumApprop(item.approp), sumAllot(item.allot))) }}</td>
                            <td
                                style="text-align: right;"
                            >{{ formatPrice(appropBalance(sumAllot(item.allot), sumOblig(item.oblig))) }}</td>
                        </tr>
                    </tbody>
                </datatable>
                <pagination
                    :pagination="pagination"
                    @prev="getData(pagination.prevPageUrl)"
                    @next="getData(pagination.nextPageUrl)"
                ></pagination>
            </div>
        </div>
        <raaods :recid="recid"></raaods>

        <!-- Schedule -->
        <div class="modal fade" id="schedule_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Advertisement</label>
                                    <input type="date" class="form-control" v-model="schedule.adverts">
                                </div>
                                <div class="form-group">
                                    <label>Submission</label>
                                    <input type="date" class="form-control" v-model="schedule.submission">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Notice</label>
                                    <input type="date" class="form-control" v-model="schedule.notice">
                                </div>
                                <div class="form-group">
                                    <label>Contract</label>
                                    <input type="date" class="form-control" v-model="schedule.contract">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="saveSchedule()">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Schedule -->
    </div>
</template>
<style>
.more {
    /* padding: 0px 0px 1rem 1.5rem; */
}

@media
	  only screen 
    and (max-width: 760px), (min-device-width: 768px) 
    and (max-device-width: 1024px)  {

	td:nth-of-type(1):before { content: "Action: "; }
	td:nth-of-type(2):before { content: "Code: "; }
	td:nth-of-type(3):before { content: "Source: "; }
	td:nth-of-type(4):before { content: "Description: "; }
	td:nth-of-type(5):before { content: "Appropriation: "; }
	td:nth-of-type(6):before { content: "Allotment: "; }
	td:nth-of-type(7):before { content: "Obligation: "; }
	td:nth-of-type(8):before { content: "Approp Balance: "; }
	td:nth-of-type(9):before { content: "Allot Balance: "; }
}
</style>
<script>
import Datatable from "../helpers/datatable.vue";
import Pagination from "../helpers/pagination";
import Raaods from "../admin/raaods";

export default {
    components: {
        datatable: Datatable,
        pagination: Pagination,
        Raaods
    },
    data() {
        let sortOrders = {};

        let columns = [
            { width: "10%", label: "Action", name: "Action" },
            { width: "10%", label: "Code", name: "CODE" },
            { width: "10%", label: "Source", name: "SOURCE" },
            { width: "50%", label: "Description", name: "DESCRIPTION" },
            { width: "10%", label: "Appropriation", name: "APPROP" },
            { width: "10%", label: "Allotment", name: "ALLOT" },
            { width: "10%", label: "Obligation", name: "OBLIG" },
            {
                width: "10%",
                label: "Appropriation Balance",
                name: "Approp Bal"
            },
            { width: "10%", label: "Allotment Balance", name: "Allot Bal" }
        ];

        columns.forEach(column => {
            sortOrders[column.name] = -1;
        });

        return {
            columns: columns,
            sortKey: "recid",
            sortOrders: sortOrders,
            tableData: {
                draw: 0,
                length: 15,
                search: "",
                year: "",
                ffunccod: "",
                fund: "",
                appropriation: "",
                column: 0,
                dir: "desc"
            },
            pagination: {
                lastPage: "",
                currentPage: "",
                total: "",
                lastPageUrl: "",
                nextPageUrl: "",
                prevPageUrl: "",
                from: "",
                to: ""
            },
            data: [],
            recid: "",
            appropriations: [],
            funds: [],
            functions: [],
            schedule: {
                adverts: '',
                submission: '',
                notice: '',
                contract: '',
                raoh_id: 0
            }
        };
    },
    mounted() {
        this.getData();

        axios.get("funds").then(response => {
            this.funds = response.data;
        });

        axios.get("appropriation-types").then(response => {
            this.appropriations = response.data;
        });

        axios.get("functions").then(response => {
            this.functions = response.data;
        });
    },
    methods: {
        configPagination(data) {
            this.pagination.lastPage = data.last_page;
            this.pagination.currentPage = data.current_page;
            this.pagination.total = data.total;
            this.pagination.lastPageUrl = data.last_page_url;
            this.pagination.prevPageUrl = data.prev_page_url;
            this.pagination.nextPageUrl = data.next_page_url;
            this.pagination.from = data.from;
            this.pagination.to = data.to;
        },
        sortBy(key) {
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
            this.tableData.column = this.getIndex(this.columns, "name", key);
            this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
            this.getData();
        },

        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value);
        },

        getData(url = "raaohs") {
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }
            });
        },

        showRaaods(recid) {
            this.recid = recid;

            $("#exampleModalCenter").modal("show");
        },

        addSchedule(recid, desc) {
            this.schedule.raoh_id = recid;
            $("#schedule_modal").find("#exampleModalLabel").text(desc)
            $("#schedule_modal").modal("show");
        },

        saveSchedule() {
            axios.post("save_schedule", this.schedule).then(response => {
                this.funds = response.data;
            });
        },

        sumApprop(data) {
            var sum = 0;
            _.forEach(data, function(e) {
                sum = e.famount + sum;
            });

            return sum;
        },

        sumAllot(data) {
            var sum = 0;
            _.forEach(data, function(e) {
                sum = e.famount + sum;
            });

            return sum;
        },

        sumOblig(data) {
            var sum = 0;
            _.forEach(data, function(e) {
                sum = e.famount + sum;
            });

            return sum;
        },

        allotBalance(allot, oblig) {
            return allot - oblig;
        },

        appropBalance(approp, oblig) {
            return approp - oblig;
        },

        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    }
};
</script>
