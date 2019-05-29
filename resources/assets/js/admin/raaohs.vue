<template>
    <div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">RAAO</h4>
                <div class="row">
                    <div class="form-group col-4">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            v-model="tableData.search"
                            @input="getData()"
                            placeholder="Description"
                        >
                    </div>
                </div>
                <p>
                    <a
                        class
                        data-toggle="collapse"
                        href="#multiCollapseExample1"
                        role="button"
                        aria-expanded="false"
                        aria-controls="multiCollapseExample1"
                    >
                        More
                        <span class="fa fa-sort-down"></span>
                    </a>
                </p>
                <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="more">
                        <div class="row">
                            <div class="form-group col-1">
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    v-model="tableData.year"
                                    @input="getData()"
                                    placeholder="Year"
                                >
                            </div>
                            <div class="form-group col-3">
                                <select
                                    v-model="tableData.ffunccod"
                                    @change="getData()"
                                    class="form-control form-control-sm" 
                                >
                                    <option value="">- select -</option>
                                    <option v-for="(item, index) in functions" :value="item.FFUNCCOD">{{ item.FFUNCCOD }} - {{ item.FFUNCTION }}</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <select
                                    v-model="tableData.fund"
                                    @change="getData()"
                                    class="form-control form-control-sm"
                                >
                                    <option value="">- select fund -</option>
                                    <option v-for="(item, index) in funds">{{ item.FFUNDDES }}</option>
                                </select>
                            </div>
                            <div class="form-group col-3">
                                <select
                                    v-model="tableData.appropriation"
                                    @change="getData()"
                                    class="form-control form-control-sm"
                                >
                                    <option value="">- select appropration -</option>
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
                            @click="showRaaods(item.recid)"
                        >
                            <td>{{ item.function.FFUNCCOD }}</td>
                            <td>{{ item.source.FSOURCE }}</td>
                            <td>{{ item.FRAODESC }}</td>
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
    </div>
</template>
<style>
.more {
    /* padding: 0px 0px 1rem 1.5rem; */
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
            { width: "20%", label: "CODE", name: "CODE" },
            { width: "30%", label: "SOURCE", name: "SOURCE" },
            { width: "50%", label: "DESCRIPTION", name: "DESCRIPTION"},
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
        }
    }
};
</script>
