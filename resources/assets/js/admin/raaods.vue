<template>
    <div>
        <div class="modal fade" id="exampleModalCenter">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
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
                                            @click="showRaaodsLedger(item.recid, item.ooe.recid, item.idraao)"
                                        >
                                            <td>{{ item.ooe.FACTCODE }}</td>
                                            <td>{{ item.FOOEDESC }}</td>
                                            <td>{{ formatPrice(item.tapprop) }}</td>
                                            <td>{{ formatPrice(item.tallot) }}</td>
                                            <td>{{ formatPrice(item.toblig) }}</td>
                                            <td>{{ formatPrice(item.tapprop - item.tallot) }}</td>
                                            <td>{{ formatPrice(item.tapprop - item.toblig) }}</td>
                                        </tr>
                                    </tbody>
                                </datatable>
                                <pagination
                                    :pagination="pagination"
                                    @prev="getData(pagination.prevPageUrl)"
                                    @next="getData(pagination.nextPageUrl)"
                                    v-if="pagination.total > tableData.length"
                                ></pagination>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <raaods-ledger :recid="raaodId"></raaods-ledger>
    </div>
</template>

<script>
import Datatable from "../helpers/datatable.vue";
import Pagination from "../helpers/pagination";
import RaaodsLedger from "../admin/raaodsLedger";

export default {
    props: ["recid"],
    components: {
        datatable: Datatable,
        pagination: Pagination,
        RaaodsLedger
    },
    data() {
        let sortOrders = {};

        let columns = [
            { width: "10%", label: "Account Code", name: "Acct. Code" },
            { width: "50%", label: "Account Description", name: "Acct. Description" },
            { width: "10%", label: "Appropration", name: "Appropration" },
            { width: "10%", label: "Allotment", name: "Allotment" },
            { width: "10%", label: "Obligation", name: "Obligation" },
            { width: "10%", label: "Allot Bal", name: "Balance1" },
            { width: "10%", label: "Approp Bal", name: "Balance2" }
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
                length: 10,
                search: "",
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
            raaodId: ""
        };
    },
    watch: {
        recid: function() {
            var self = this;
            self.tableData.recid = self.recid;

            return this.getData();
        }
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

        getData(url = "raaods") {
            axios.get(url, { params: this.tableData }).then(response => {
                let data = response.data;

                if (this.tableData.draw == data.draw) {
                    this.data = data.data.data;
                    this.configPagination(data.data);
                }

            });
        },
        //formating number to currency
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace(",", ".");
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        entryType(item) {
            if (item == 0) {
                return "RAAO DET";
            } else if (item == 1) {
                return "Approp";
            } else if (item == 2) {
                return "Allot";
            } else if (item == 3) {
                return "Oblig";
            }
        },
        showRaaodsLedger(recId, ooeId, raaoId) {
            this.raaodId = {raaod:recId, ooe: ooeId, raao: raaoId};

            $("#modalRaaodsLedger").modal("show");
        }
    }
};
</script>
